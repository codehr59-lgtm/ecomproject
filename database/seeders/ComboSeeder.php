<?php

namespace Database\Seeders;

use App\Models\Combo;
use App\Models\ComboItem;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComboSeeder extends Seeder
{
    public function run(): void
    {
        $combosData = [
            [
                'name'              => 'Breakfast Combo',
                'slug'              => 'breakfast-combo',
                'badge'             => 'Best Seller',
                'short_description' => 'Start your morning with our premium 100% natural Sundarban Honey, soft Ajwa dates, and authentic Gawa Ghee.',
                'description'       => '<p>Experience the pure taste of authentic organic staples. Carefully curated to provide wholesome nutrition, natural antioxidants, and unmatched purity for your entire family.</p><ul><li>100% Pure Sundarban Wild Honey</li><li>Grade A Ajwa Dates direct from Medina</li><li>Traditional Grass-Fed Bilona Gawa Ghee</li></ul>',
                'price'             => 5500,
                'original_price'    => 6250,
                'stock'             => 50,
                'is_active'         => true,
                'is_featured'       => true,
                'sort'              => 1,
                'items'             => [
                    ['product_name' => 'Sundarban Wild Honey', 'quantity' => 1],
                    ['product_name' => 'Ajwa Premium Dates',   'quantity' => 1],
                    ['product_name' => 'Gawa Ghee',            'quantity' => 1],
                ],
            ],
            [
                'name'              => 'Wellness & Immunity Combo',
                'slug'              => 'wellness-combo',
                'badge'             => 'Immunity Boost',
                'short_description' => 'A potent trio of Black Seed Honey, Cold Pressed Black Cumin Oil, and Extra Virgin Coconut Oil for everyday vitality.',
                'description'       => '<p>Boost your immune defense and gut health with this powerful herbal trinity. Known for age-old healing properties, cold-pressed extraction, and unrefined richness.</p>',
                'price'             => 2100,
                'original_price'    => 2380,
                'stock'             => 40,
                'is_active'         => true,
                'is_featured'       => true,
                'sort'              => 2,
                'items'             => [
                    ['product_name' => 'Black Seed Honey',          'quantity' => 1],
                    ['product_name' => 'Black Cumin Seed Oil',      'quantity' => 1],
                    ['product_name' => 'Extra Virgin Coconut Oil',  'quantity' => 1],
                ],
            ],
            [
                'name'              => 'Ramadan Special Dates & Honey Pack',
                'slug'              => 'ramadan-special',
                'badge'             => 'Mega Deal',
                'short_description' => 'Premium dates selection along with unprocessed honey comb, curated for the holy month and fasting energy.',
                'description'       => '<p>Break your fast with the finest hand-picked Ajwa and melt-in-mouth Sukkari dates, paired with chewy raw honeycomb filled with rich liquid gold.</p>',
                'price'             => 5200,
                'original_price'    => 5990,
                'stock'             => 60,
                'is_active'         => true,
                'is_featured'       => true,
                'sort'              => 3,
                'items'             => [
                    ['product_name' => 'Ajwa Premium Dates', 'quantity' => 1],
                    ['product_name' => 'Sukkari Dates',      'quantity' => 1],
                    ['product_name' => 'Natural Honeycomb',  'quantity' => 1],
                ],
            ],
            [
                'name'              => 'Pure Kitchen Cooking Essentials',
                'slug'              => 'cooking-essentials',
                'badge'             => 'Kitchen Pack',
                'short_description' => 'Cold-pressed Ghani Mustard Oil, aromatic Gawa Ghee, and stone-ground Turmeric & Chili powders.',
                'description'       => '<p>Transform your daily cooking with 100% adulteration-free Bengali kitchen treasures. Raw pungent mustard oil, golden fragrant ghee, and vibrant spices.</p>',
                'price'             => 2750,
                'original_price'    => 3145,
                'stock'             => 45,
                'is_active'         => true,
                'is_featured'       => true,
                'sort'              => 4,
                'items'             => [
                    ['product_name' => 'Deshi Mustard Oil', 'quantity' => 1],
                    ['product_name' => 'Gawa Ghee',         'quantity' => 1],
                    ['product_name' => 'Turmeric Powder',   'quantity' => 1],
                    ['product_name' => 'Chili Powder',      'quantity' => 1],
                ],
            ],
            [
                'name'              => 'Royal Gift Hamper',
                'slug'              => 'gift-hamper',
                'badge'             => 'Gift Hamper',
                'short_description' => 'A royal gift box featuring raw wild forest honey, jumbo Medjool dates, and premium bilona ghee.',
                'description'       => '<p>The ultimate present of health and luxury. Ideal for corporate gifts, weddings, festive greetings, and beloved family members.</p>',
                'price'             => 3600,
                'original_price'    => 4100,
                'stock'             => 30,
                'is_active'         => true,
                'is_featured'       => true,
                'sort'              => 5,
                'items'             => [
                    ['product_name' => 'Sundarban Wild Honey', 'quantity' => 1],
                    ['product_name' => 'Medjool Large Dates',  'quantity' => 1],
                    ['product_name' => 'Gawa Ghee',            'quantity' => 1],
                ],
            ],
        ];

        foreach ($combosData as $data) {
            $itemsData = $data['items'];
            unset($data['items']);

            // Pick an image from the first item if available
            $firstProduct = Product::where('name', 'like', "%{$itemsData[0]['product_name']}%")->first();
            if ($firstProduct && $firstProduct->image) {
                $data['image'] = $firstProduct->image;
            }

            $combo = Combo::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            // Re-sync items
            $combo->items()->delete();
            $sort = 1;
            foreach ($itemsData as $itemInfo) {
                $p = Product::where('name', 'like', "%{$itemInfo['product_name']}%")->first();
                if ($p) {
                    ComboItem::create([
                        'combo_id'    => $combo->id,
                        'product_id'  => $p->id,
                        'quantity'    => $itemInfo['quantity'],
                        'custom_name' => $p->name,
                        'sort'        => $sort++,
                    ]);
                }
            }
        }
    }
}
