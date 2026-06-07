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
     * All active products as card arrays.
     *
     * @return array<int,array>
     */
    public static function products(): array
    {
        return Product::active()
            ->with('category')
            ->orderBy('sort')
            ->get()
            ->map(fn (Product $p) => $p->toCardArray())
            ->all();
    }

    /**
     * Categories, each enriched with a derived `count` of active products.
     * `id` is the category slug to match the old config shape.
     *
     * @return array<int,array>
     */
    public static function categories(): array
    {
        return Category::active()
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('sort')
            ->get()
            ->map(fn (Category $c) => [
                'id'    => $c->slug,
                'name'  => $c->name,
                'tint'  => $c->tint,
                'note'  => $c->note,
                'count' => (int) $c->products_count,
            ])
            ->all();
    }

    /**
     * Brand names as a plain array of strings.
     *
     * @return array<int,string>
     */
    public static function brands(): array
    {
        return Brand::active()
            ->orderBy('id')
            ->pluck('name')
            ->all();
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
        return config('products.combos', []);
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
            ->with('category')
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
     */
    public static function byCategory(string $catSlug): array
    {
        $category = Category::where('slug', $catSlug)->first();

        if (! $category) {
            return [];
        }

        return Product::active()
            ->with('category')
            ->where('category_id', $category->id)
            ->orderBy('sort')
            ->get()
            ->map(fn (Product $p) => $p->toCardArray())
            ->all();
    }

    /**
     * Top-selling products: badge='best', padded to $limit by highest reviews.
     */
    public static function topSelling(int $limit = 8): array
    {
        $best = Product::active()
            ->with('category')
            ->where('badge', 'best')
            ->orderByDesc('reviews')
            ->get();

        if ($best->count() >= $limit) {
            return $best->take($limit)
                ->map(fn (Product $p) => $p->toCardArray())
                ->all();
        }

        $bestIds = $best->pluck('id')->all();

        $pad = Product::active()
            ->with('category')
            ->whereNotIn('id', $bestIds)
            ->orderByDesc('reviews')
            ->take($limit - $best->count())
            ->get();

        return $best->concat($pad)
            ->map(fn (Product $p) => $p->toCardArray())
            ->all();
    }

    /**
     * Products with badge='new'.
     */
    public static function newArrivals(int $limit = 10): array
    {
        return Product::active()
            ->with('category')
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
            ->with('category')
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
        return Product::active()
            ->with('category')
            ->where('certified', true)
            ->orderBy('sort')
            ->take($limit)
            ->get()
            ->map(fn (Product $p) => $p->toCardArray())
            ->all();
    }

    /**
     * Products in the same category as the given numeric id, excluding that product.
     * Uses numeric DB id (not slug).
     */
    public static function related(mixed $id, int $limit = 5): array
    {
        $product = Product::active()->with('category')->find((int) $id);

        if (! $product) {
            return [];
        }

        return Product::active()
            ->with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', (int) $id)
            ->orderBy('sort')
            ->take($limit)
            ->get()
            ->map(fn (Product $p) => $p->toCardArray())
            ->all();
    }

    /**
     * Case-insensitive name search. Empty query returns all products.
     */
    public static function search(string $q): array
    {
        if ($q === '') {
            return self::products();
        }

        return Product::active()
            ->with('category')
            ->where('name', 'like', '%' . $q . '%')
            ->orderBy('sort')
            ->get()
            ->map(fn (Product $p) => $p->toCardArray())
            ->all();
    }

    /**
     * First $limit active products — used for "Just For You" rail.
     */
    public static function featured(int $limit = 10): array
    {
        return Product::active()
            ->with('category')
            ->orderBy('sort')
            ->take($limit)
            ->get()
            ->map(fn (Product $p) => $p->toCardArray())
            ->all();
    }
}
