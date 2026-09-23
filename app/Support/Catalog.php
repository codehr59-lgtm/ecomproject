<?php

namespace App\Support;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

/**
 * Catalog — DB-backed accessor for Shuvo storefront data.
 *
 * Migrated in Phase B1: categories, brands, products now read from the
 * database via Eloquent models. Every public method returns the SAME array
 * shapes as the previous config-backed implementation so no Blade view
 * requires any change.
 *
 * Unchanged (still config-backed, migrated in a later phase):
 *   testimonials(), combos(), giftThreshold(), shipThreshold()
 *
 * Array shape for products (toCardArray):
 *   id, name, weight, price, old_price, cat, badge, rating, reviews, blurb, certified
 *
 * Array shape for categories:
 *   id (= slug), name, tint, note, count
 */
class Catalog
{
    // ── Raw data accessors ────────────────────────────────────────────────

    /**
     * All active products as card arrays (cached for 30 minutes).
     *
     * @return array<int,array>
     */
    public static function products(): array
    {
        return \Illuminate\Support\Facades\Cache::remember('catalog.products', 1800, function () {
            return Product::active()
                ->with(['category', 'productReviews', 'variations'])
                ->orderBy('sort')
                ->get()
                ->map(fn (Product $p) => $p->toCardArray())
                ->all();
        });
    }

    /**
     * Categories, each enriched with a derived `count` of active products.
     * `id` is the category slug to match the old config shape.
     *
     * @return array<int,array>
     */
    public static function categories(): array
    {
        return \Illuminate\Support\Facades\Cache::remember('catalog.categories', 1800, function () {
            return Category::active()
                ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
                ->orderBy('sort')
                ->get()
                ->map(fn (Category $c) => [
                    'id'       => $c->slug,
                    'model_id' => $c->id,
                    'name'     => $c->name,
                    'image'    => $c->image,
                    'tint'     => $c->tint,
                    'note'     => $c->note,
                    'count'    => (int) $c->products_count,
                ])
                ->all();
        });
    }

    /**
     * Brand names as a plain array of strings.
     *
     * @return array<int,string>
     */
    public static function brands(): array
    {
        return \Illuminate\Support\Facades\Cache::remember('catalog.brands', 1800, function () {
            return Brand::active()
                ->orderBy('id')
                ->pluck('name')
                ->all();
        });
    }

    // ── Config-backed (not migrated in B1) ────────────────────────────────

    /** @return array<int,array> */
    public static function testimonials(): array
    {
        return config('products.testimonials', []);
    }

    /** @return array<int,array> */
    public static function combos(): array
    {
        return \Illuminate\Support\Facades\Cache::remember('catalog.combos', 1800, function () {
            $dbCombos = \App\Models\Combo::active()
                ->with(['items.product', 'items.variation'])
                ->orderBy('sort')
                ->get();

            if ($dbCombos->isNotEmpty()) {
                return $dbCombos->map(fn (\App\Models\Combo $c) => $c->toCardArray())->all();
            }

            return config('products.combos', []);
        });
    }

    /**
     * Find a combo by slug or numeric ID.
     */
    public static function combo(string|int $idOrSlug): ?\App\Models\Combo
    {
        $query = \App\Models\Combo::active()->with(['items.product', 'items.variation']);

        if (is_numeric($idOrSlug)) {
            return $query->where('id', (int) $idOrSlug)->first();
        }

        return $query->where('slug', $idOrSlug)->first();
    }

    // ── Threshold helpers ─────────────────────────────────────────────────

    public static function giftThreshold(): int
    {
        return (int) config('products.free_gift_threshold', 0);
    }

    public static function shipThreshold(): int
    {
        return (int) config('products.free_ship_threshold', 0);
    }

    // ── Single-item lookups ───────────────────────────────────────────────

    /**
     * Find a product by numeric DB id.
     * Views call route('product', $id) with the numeric id — DB autoincrement
     * matches 1..35 after a fresh seed.
     */
    public static function find(mixed $id): ?array
    {
        $product = Product::active()
            ->with(['category', 'productReviews', 'variations'])
            ->find((int) $id);

        return $product?->toCardArray();
    }

    /**
     * Single category by slug (the 'id' in the old config shape).
     * Returns the same array shape as categories() entries, or null.
     */
    public static function category(string $slug): ?array
    {
        $c = Category::active()
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->where('slug', $slug)
            ->first();

        if (! $c) {
            return null;
        }

        return [
            'id'    => $c->slug,
            'name'  => $c->name,
            'tint'  => $c->tint,
            'note'  => $c->note,
            'count' => (int) $c->products_count,
        ];
    }

    // ── Collection queries ────────────────────────────────────────────────

    /**
     * Products belonging to a category (identified by slug).
     * Uses cached products collection in memory for instantaneous sub-millisecond response.
     */
    public static function byCategory(string $catSlug): array
    {
        return array_values(array_filter(self::products(), fn ($p) => ($p['cat'] ?? '') === $catSlug));
    }

    /**
     * Products belonging to a category with customizable limit and sort.
     */
    public static function categoryProducts(int $categoryId, int $limit = 5, string $sortBy = 'sort_order'): array
    {
        // Resolve slug from cached categories in memory
        $allCats = self::categories();
        $catSlug = null;
        foreach ($allCats as $c) {
            if (($c['model_id'] ?? null) == $categoryId || ($c['id'] ?? null) == $categoryId) {
                $catSlug = $c['id'];
                break;
            }
        }

        if ($catSlug) {
            $filtered = self::byCategory($catSlug);
            if (! empty($filtered)) {
                return array_slice($filtered, 0, $limit);
            }
        }

        return \Illuminate\Support\Facades\Cache::remember("catalog.cat_products.{$categoryId}.{$limit}.{$sortBy}", 1800, function () use ($categoryId, $limit, $sortBy) {
            $query = Product::active()
                ->with(['category', 'productReviews', 'variations'])
                ->where('category_id', $categoryId);

            switch ($sortBy) {
                case 'latest':
                    $query->latest('id');
                    break;
                case 'popular':
                    $query->orderByDesc('reviews');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'sort_order':
                default:
                    $query->orderBy('sort')->orderBy('id');
                    break;
            }

            return $query->take($limit)
                ->get()
                ->map(fn (Product $p) => $p->toCardArray())
                ->all();
        });
    }

    /**
     * Top-selling products: badge='best', padded to $limit.
     */
    public static function topSelling(int $limit = 8): array
    {
        $all = self::products();
        $best = array_values(array_filter($all, fn ($p) => ($p['badge'] ?? '') === 'best'));
        if (count($best) >= $limit) {
            return array_slice($best, 0, $limit);
        }

        $rest = array_values(array_filter($all, fn ($p) => ($p['badge'] ?? '') !== 'best'));
        return array_slice(array_merge($best, $rest), 0, $limit);
    }

    /**
     * Products with badge='new'.
     */
    public static function newArrivals(int $limit = 10): array
    {
        return Product::active()
            ->with(['category', 'productReviews', 'variations'])
            ->where('badge', 'new')
            ->orderBy('sort')
            ->take($limit)
            ->get()
            ->map(fn (Product $p) => $p->toCardArray())
            ->all();
    }

    /**
     * Products with badge='preorder'.
     */
    public static function preorder(int $limit = 10): array
    {
        return Product::active()
            ->with(['category', 'productReviews', 'variations'])
            ->where('badge', 'preorder')
            ->orderBy('sort')
            ->take($limit)
            ->get()
            ->map(fn (Product $p) => $p->toCardArray())
            ->all();
    }

    /**
     * Products with certified=true.
     */
    public static function certified(int $limit = 10): array
    {
        $cert = array_values(array_filter(self::products(), fn ($p) => ! empty($p['certified'])));
        return array_slice($cert, 0, $limit);
    }

    /**
     * Products in the same category as the given numeric id, excluding that product.
     * Uses numeric DB id (not slug).
     */
    public static function related(mixed $id, int $limit = 5): array
    {
        $all = self::products();
        $target = null;
        foreach ($all as $p) {
            if (($p['id'] ?? null) == $id) {
                $target = $p;
                break;
            }
        }

        if (! $target) {
            return [];
        }

        $sameCat = array_values(array_filter($all, fn ($p) => ($p['cat'] ?? '') === ($target['cat'] ?? '') && ($p['id'] ?? null) != $id));
        return array_slice($sameCat, 0, $limit);
    }

    /**
     * Case-insensitive name search. Empty query returns all products.
     */
    public static function search(string $q): array
    {
        if ($q === '') {
            return self::products();
        }

        $qLower = mb_strtolower(trim($q));
        return array_values(array_filter(self::products(), fn ($p) => str_contains(mb_strtolower($p['name'] ?? ''), $qLower)));
    }

    /**
     * First $limit active products — used for "Just For You" rail.
     */
    public static function featured(int $limit = 10): array
    {
        return array_slice(self::products(), 0, $limit);
    }

    /**
     * Invalidate catalog caches when admin edits products/categories.
     */
    public static function flushCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('catalog.products');
        \Illuminate\Support\Facades\Cache::forget('catalog.categories');
        \Illuminate\Support\Facades\Cache::forget('catalog.brands');
        \Illuminate\Support\Facades\Cache::forget('catalog.combos');
        \Illuminate\Support\Facades\Cache::forget('storefront.home_view_data');
    }
}
