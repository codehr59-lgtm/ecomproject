<?php

namespace App\Support;

class Catalog
{
    /** @return array<int,array> */
    public static function all(): array
    {
        return config('products.products', []);
    }

    public static function categories(): array
    {
        return config('products.categories', []);
    }

    public static function banners(): array
    {
        return config('products.banners', []);
    }

    public static function giftThreshold(): int
    {
        return (int) config('products.free_gift_threshold', 0);
    }

    public static function find(string $slug): ?array
    {
        foreach (self::all() as $p) {
            if ($p['slug'] === $slug) {
                return $p;
            }
        }
        return null;
    }

    public static function category(string $slug): ?array
    {
        foreach (self::categories() as $c) {
            if ($c['slug'] === $slug) {
                return $c;
            }
        }
        return null;
    }

    /** @return array<int,array> */
    public static function byCategory(string $slug): array
    {
        return array_values(array_filter(self::all(), fn ($p) => $p['category'] === $slug));
    }

    /** @return array<int,array> */
    public static function featured(int $limit = 8): array
    {
        return array_slice(self::all(), 0, $limit);
    }

    /** @return array<int,array> */
    public static function topSelling(int $limit = 8): array
    {
        return array_slice(array_reverse(self::all()), 0, $limit);
    }

    /** @return array<int,array> */
    public static function related(string $slug, int $limit = 6): array
    {
        $product = self::find($slug);
        if (! $product) {
            return [];
        }
        $rel = array_filter(
            self::all(),
            fn ($p) => $p['category'] === $product['category'] && $p['slug'] !== $slug
        );
        return array_slice(array_values($rel), 0, $limit);
    }
}
