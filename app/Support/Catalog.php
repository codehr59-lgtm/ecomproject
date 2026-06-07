<?php

namespace App\Support;

/**
 * Static accessor over config/products.php.
 * BACKEND SWAP POINT: replace these method bodies with Eloquent queries when
 * migrating to a database. No Blade view or controller reads config('products.*') directly.
 */
class Catalog
{
    // ── Raw data accessors ────────────────────────────────────────────────

    /** @return array<int,array> */
    public static function products(): array
    {
        return config('products.products', []);
    }

    /**
     * Categories, each enriched with a derived `count` of products in that category.
     *
     * @return array<int,array>
     */
    public static function categories(): array
    {
        $products = self::products();
        return array_map(function (array $cat) use ($products): array {
            $cat['count'] = count(array_filter($products, fn ($p) => $p['cat'] === $cat['id']));
            return $cat;
        }, config('products.categories', []));
    }

    /** @return array<int,string> */
    public static function brands(): array
    {
        return config('products.brands', []);
    }

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

    /** Find a product by numeric id. */
    public static function find(mixed $id): ?array
    {
        $id = (int) $id;
        foreach (self::products() as $p) {
            if ((int) $p['id'] === $id) {
                return $p;
            }
        }
        return null;
    }

    /** Single category by id-slug (with derived count). */
    public static function category(string $id): ?array
    {
        foreach (self::categories() as $c) {
            if ($c['id'] === $id) {
                return $c;
            }
        }
        return null;
    }

    // ── Collection queries ────────────────────────────────────────────────

    /** Products belonging to a category. */
    public static function byCategory(string $catId): array
    {
        return array_values(array_filter(self::products(), fn ($p) => $p['cat'] === $catId));
    }

    /**
     * Top-selling products: badge === 'best'.
     * If fewer than $limit, pads with the highest-review products not already included.
     */
    public static function topSelling(int $limit = 8): array
    {
        $best = array_values(array_filter(self::products(), fn ($p) => ($p['badge'] ?? null) === 'best'));

        if (count($best) >= $limit) {
            return array_slice($best, 0, $limit);
        }

        $bestIds = array_column($best, 'id');
        $rest    = array_filter(self::products(), fn ($p) => ! in_array($p['id'], $bestIds, true));
        usort($rest, fn ($a, $b) => ($b['reviews'] ?? 0) <=> ($a['reviews'] ?? 0));
        $pad = array_slice(array_values($rest), 0, $limit - count($best));

        return array_values(array_merge($best, $pad));
    }

    /** Products with badge === 'new'. */
    public static function newArrivals(int $limit = 10): array
    {
        return array_slice(
            array_values(array_filter(self::products(), fn ($p) => ($p['badge'] ?? null) === 'new')),
            0,
            $limit
        );
    }

    /** Products with badge === 'preorder'. */
    public static function preorder(int $limit = 10): array
    {
        return array_slice(
            array_values(array_filter(self::products(), fn ($p) => ($p['badge'] ?? null) === 'preorder')),
            0,
            $limit
        );
    }

    /** Products with certified === true. */
    public static function certified(int $limit = 10): array
    {
        return array_slice(
            array_values(array_filter(self::products(), fn ($p) => ! empty($p['certified']))),
            0,
            $limit
        );
    }

    /**
     * Products in the same category as the given id, excluding that product itself.
     */
    public static function related(mixed $id, int $limit = 5): array
    {
        $product = self::find($id);
        if (! $product) {
            return [];
        }
        $rel = array_filter(
            self::products(),
            fn ($p) => $p['cat'] === $product['cat'] && (int) $p['id'] !== (int) $id
        );
        return array_slice(array_values($rel), 0, $limit);
    }

    /** Case-insensitive name search. Empty query returns all products. */
    public static function search(string $q): array
    {
        if ($q === '') {
            return self::products();
        }
        $lower = mb_strtolower($q);
        return array_values(array_filter(
            self::products(),
            fn ($p) => str_contains(mb_strtolower($p['name']), $lower)
        ));
    }

    /** First $limit products — used for "Just For You" rail. */
    public static function featured(int $limit = 10): array
    {
        return array_slice(self::products(), 0, $limit);
    }
}
