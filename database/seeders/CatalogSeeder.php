<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    /**
     * Seed all catalog data from config/products.php.
     * Idempotent: truncates then re-inserts each run.
     */
    public function run(): void
    {
        // Disable FK constraints for SQLite truncation
        \DB::statement('PRAGMA foreign_keys = OFF;');

        Product::truncate();
        Brand::truncate();
        Category::truncate();

        \DB::statement('PRAGMA foreign_keys = ON;');

        // ── 1. Seed categories ────────────────────────────────────────────
        $categoryData = config('products.categories', []);
        $categoryMap  = []; // slug => Category model id

        foreach ($categoryData as $sort => $cat) {
            $category = Category::create([
                'slug'      => $cat['id'],
                'name'      => $cat['name'],
                'tint'      => $cat['tint'],
                'note'      => $cat['note'],
                'sort'      => $sort,
                'is_active' => true,
            ]);
            $categoryMap[$cat['id']] = $category->id;
        }

        // ── 2. Seed brands ────────────────────────────────────────────────
        $brandNames = config('products.brands', []);
        $brandIds   = []; // index => Brand model id

        foreach ($brandNames as $i => $name) {
            $brand       = Brand::create([
                'slug'      => Str::slug($name),
                'name'      => $name,
                'is_active' => true,
            ]);
            $brandIds[$i] = $brand->id;
        }

        $brandCount = count($brandIds);

        // ── 3. Seed products ──────────────────────────────────────────────
        $productsData = config('products.products', []);

        foreach ($productsData as $index => $p) {
            // Map category slug → DB category id
            $catSlug    = $p['cat'];
            $categoryId = $categoryMap[$catSlug] ?? null;

            if ($categoryId === null) {
                throw new \RuntimeException("Unknown category slug '{$catSlug}' in product id {$p['id']}");
            }

            // Round-robin brand assignment
            $brandId = $brandCount > 0 ? $brandIds[$index % $brandCount] : null;

            // Unique slug: name + weight
            $slug = Str::slug($p['name'] . '-' . $p['weight']);

            // Random stock between 20-200
            $stock = rand(20, 200);

            Product::create([
                'slug'        => $slug,
                'name'        => $p['name'],
                'weight'      => $p['weight'],
                'price'       => (int) $p['price'],
                'old_price'   => isset($p['old_price']) ? (int) $p['old_price'] : null,
                'category_id' => $categoryId,
                'brand_id'    => $brandId,
                'badge'       => $p['badge'] ?? null,
                'rating'      => (float) ($p['rating'] ?? 4.8),
                'reviews'     => (int) ($p['reviews'] ?? 120),
                'blurb'       => $p['blurb'] ?? null,
                'certified'   => ! empty($p['certified']),
                'stock'       => $stock,
                'is_active'   => true,
                'image'       => null,
                'sort'        => $index,
            ]);
        }

        $this->command->info(
            'CatalogSeeder: '
            . Category::count() . ' categories, '
            . Brand::count() . ' brands, '
            . Product::count() . ' products seeded.'
        );
    }
}
