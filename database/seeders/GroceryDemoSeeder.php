<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GroceryDemoSeeder extends Seeder
{
    /**
     * Download or copy helper for high quality images.
     */
    private function saveImage(string $url, string $destPath): ?string
    {
        $fullPath = public_path('storage/' . $destPath);
        $dir = dirname($fullPath);

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true, true);
        }

        // If file already exists and has valid size, keep it
        if (File::exists($fullPath) && filesize($fullPath) > 5000) {
            return $destPath;
        }

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 8,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $contents = @file_get_contents($url, false, $context);
            if ($contents && strlen($contents) > 2000) {
                File::put($fullPath, $contents);
                return $destPath;
            }
        } catch (\Throwable $e) {
            // Ignore failure, will fallback
        }

        return null;
    }

    public function run(): void
    {
        $this->command->info('Starting Grocery Demo Seeding...');

        // Ensure directories exist
        foreach (['products', 'categories', 'sliders', 'banners'] as $dir) {
            $path = public_path('storage/' . $dir);
            if (! File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }
        }

        // ══════════════════════════════════════════════════════════════
        // 1. POPULATE HERO SLIDERS
        // ══════════════════════════════════════════════════════════════
        $this->command->info('1. Seeding Hero Sliders & Banners...');

        $sliderImages = [
            'sliders/grocery_fresh_slider.jpg' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=1200&auto=format&fit=crop&q=80',
            'sliders/honey_ghee_slider.jpg'    => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=1200&auto=format&fit=crop&q=80',
            'sliders/dates_fruits_slider.jpg'  => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1200&auto=format&fit=crop&q=80',
        ];

        foreach ($sliderImages as $path => $url) {
            $this->saveImage($url, $path);
        }

        Slider::truncate();

        Slider::create([
            'title'       => 'Fresh Grocery & Daily Bazar',
            'subtitle'    => '১০০% খাঁটি ও তাজা নিত্যপ্রয়োজনীয় গ্রোসারি পণ্য — সরাসরি আপনার ঘরে ডেলিভারি',
            'image'       => 'sliders/grocery_fresh_slider.jpg',
            'button_text' => 'Shop Grocery',
            'button_url'  => route('shop'),
            'is_active'   => true,
            'sort'        => 1,
            'type'        => 'slider',
        ]);

        Slider::create([
            'title'       => '100% Pure Honey & Gawa Ghee',
            'subtitle'    => 'সুন্দরবনের খাঁটি কাঁচা মধু ও ঐতিহ্যবাহী গাওয়া ঘি — কোনো কেমিক্যাল বা প্রিজারভেটিভ নেই',
            'image'       => 'sliders/honey_ghee_slider.jpg',
            'button_text' => 'Explore Pure Honey',
            'button_url'  => route('category', 'honey'),
            'is_active'   => true,
            'sort'        => 2,
            'type'        => 'slider',
        ]);

        Slider::create([
            'title'       => 'Exclusive Combo Deals & Savings',
            'subtitle'    => 'স্পেশাল ফ্যামিলি কম্বো প্যাকেজে সাশ্রয় করুন সর্বোচ্চ ২০% পর্যন্ত',
            'image'       => 'sliders/dates_fruits_slider.jpg',
            'button_text' => 'View All Combos',
            'button_url'  => route('combos.index'),
            'is_active'   => true,
            'sort'        => 3,
            'type'        => 'slider',
        ]);

        // Right Hero Side Banner
        $bannerImg = $this->saveImage(
            'https://images.unsplash.com/photo-1506617420156-8e4536971650?w=800&auto=format&fit=crop&q=80',
            'banners/daily_grocery_hero_banner.jpg'
        );

        Banner::where('position', 'hero')->delete();
        Banner::create([
            'title'       => 'Cooking Essentials Deal',
            'subtitle'    => 'ঘানি ভাঙা সরিষার তেল ও সুগন্ধি চালের অফার',
            'image'       => $bannerImg ?: 'banners/daily_grocery_hero_banner.jpg',
            'cta'         => 'Order Today',
            'href'        => route('category', 'oil-ghee'),
            'position'    => 'hero',
            'sort'        => 1,
            'is_active'   => true,
        ]);

        // ══════════════════════════════════════════════════════════════
        // 2. CORE GROCERY CATEGORIES
        // ══════════════════════════════════════════════════════════════
        $this->command->info('2. Seeding Core Categories with Photos...');

        $categoryDefinitions = [
            [
                'slug'  => 'honey',
                'name'  => 'Honey (মধু)',
                'tint'  => '#FA8B01',
                'note'  => '100% Pure Raw & Wild Honey',
                'image' => 'categories/cat_honey.jpg',
                'url'   => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'slug'  => 'dates',
                'name'  => 'Dates (খেজুর)',
                'tint'  => '#8B4513',
                'note'  => 'Medina Ajwa, Medjool & Sukkari',
                'image' => 'categories/cat_dates.jpg',
                'url'   => 'https://images.unsplash.com/photo-1596704017254-9b121068fb31?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'slug'  => 'oil-ghee',
                'name'  => 'Oil & Ghee (তেল ও ঘি)',
                'tint'  => '#D97706',
                'note'  => 'Ghani Mustard Oil & Gawa Ghee',
                'image' => 'categories/cat_oil_ghee.jpg',
                'url'   => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'slug'  => 'rice',
                'name'  => 'Rice & Dal (চাল ও ডাল)',
                'tint'  => '#16A34A',
                'note'  => 'Chinigura Polao, Nazirshail & Dal',
                'image' => 'categories/cat_rice_dal.jpg',
                'url'   => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'slug'  => 'spices',
                'name'  => 'Spices (খাঁটি মসলা)',
                'tint'  => '#DC2626',
                'note'  => 'Turmeric, Red Chili & Whole Jeera',
                'image' => 'categories/cat_spices.jpg',
                'url'   => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'slug'  => 'nuts',
                'name'  => 'Nuts & Seeds (বাদাম ও বীজ)',
                'tint'  => '#CA8A04',
                'note'  => 'Almonds, Cashews & Chia Seeds',
                'image' => 'categories/cat_nuts.jpg',
                'url'   => 'https://images.unsplash.com/photo-1508746829417-e6f548d8d6ed?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'slug'  => 'dairy',
                'name'  => 'Dairy & Eggs (দুধ ও ডিম)',
                'tint'  => '#EA580C',
                'note'  => 'Farm Fresh Brown Eggs & Pure Milk',
                'image' => 'categories/cat_dairy_eggs.jpg',
                'url'   => 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'slug'  => 'fruits-veggies',
                'name'  => 'Fruits & Daily Bazar (ফল ও নিত্যবাজার)',
                'tint'  => '#059669',
                'note'  => 'Seasonal Mango, Fresh Onions & Veggies',
                'image' => 'categories/cat_fruits.jpg',
                'url'   => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'slug'  => 'tea',
                'name'  => 'Tea & Coffee (চা ও কফি)',
                'tint'  => '#15803D',
                'note'  => 'Organic Sylhet Green Tea & Coffee',
                'image' => 'categories/cat_tea.jpg',
                'url'   => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'slug'  => 'sutki',
                'name'  => 'Shutki & Fish (শুঁটকি ও মাছ)',
                'tint'  => '#0284C7',
                'note'  => 'Cox\'s Bazar Chemical-Free Dry Fish',
                'image' => 'categories/cat_shutki.jpg',
                'url'   => 'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?w=400&auto=format&fit=crop&q=80',
            ],
        ];

        $categoryMap = []; // slug => Category model

        foreach ($categoryDefinitions as $sort => $cDef) {
            $savedImg = $this->saveImage($cDef['url'], $cDef['image']);

            $cat = Category::updateOrCreate(
                ['slug' => $cDef['slug']],
                [
                    'name'      => $cDef['name'],
                    'tint'      => $cDef['tint'],
                    'note'      => $cDef['note'],
                    'image'     => $savedImg ?: $cDef['image'],
                    'sort'      => $sort + 1,
                    'is_active' => true,
                ]
            );

            $categoryMap[$cDef['slug']] = $cat;
        }

        // ══════════════════════════════════════════════════════════════
        // 3. BRANDS
        // ══════════════════════════════════════════════════════════════
        $brandNames = ['Shuvo Pure', 'Pran', 'Radhuni', 'Teer', 'Aarong Dairy', 'Fresh', 'Kazi & Kazi', 'Ispahani'];
        $brandIds = [];
        foreach ($brandNames as $bName) {
            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($bName)],
                ['name' => $bName, 'is_active' => true]
            );
            $brandIds[] = $brand->id;
        }

        // ══════════════════════════════════════════════════════════════
        // 4. RICH 40+ GROCERY PRODUCTS WITH PHOTOS
        // ══════════════════════════════════════════════════════════════
        $this->command->info('3. Seeding 40+ Grocery Products with Photos...');

        $groceryProducts = [
            // ── Honey ──
            [
                'name'      => 'Sundarban Raw Wild Honey (সুন্দরবনের মধু)',
                'cat'       => 'honey',
                'weight'    => '500g',
                'price'     => 650,
                'old_price' => 750,
                'badge'     => 'Best Seller',
                'blurb'     => '১০০% প্রাকৃতিক সুন্দরবনের খাঁটি বুনো মধু। কোনো চিনি বা রাসায়নিক ভেজাল নেই।',
                'url'       => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/sundarban_honey_500g.jpg',
            ],
            [
                'name'      => 'Sundarban Raw Wild Honey - 1 kg Family Pack',
                'cat'       => 'honey',
                'weight'    => '1 kg',
                'price'     => 1250,
                'old_price' => 1450,
                'badge'     => 'Save ৳200',
                'blurb'     => 'খাঁটি সুন্দরবনের অপরিশোধিত প্রাকৃতিক মধু। স্বাস্থ্য ও রোগ প্রতিরোধে অতুলনীয়।',
                'url'       => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/sundarban_honey_1kg.jpg',
            ],
            [
                'name'      => 'Black Seed Flower Honey (কালোজিরা ফুলের মধু)',
                'cat'       => 'honey',
                'weight'    => '500g',
                'price'     => 750,
                'old_price' => 850,
                'badge'     => 'Organic',
                'blurb'     => 'কালোজিরা ফুলের প্রাকৃতিক নেকটার থেকে সংগৃহীত। সর্বরোগের মহৌষধ হিসেবে সমাদৃত।',
                'url'       => 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/black_seed_honey.jpg',
            ],
            [
                'name'      => 'Natural Raw Honeycomb (খাঁটি মৌচাক মধু)',
                'cat'       => 'honey',
                'weight'    => '450g',
                'price'     => 950,
                'old_price' => 1100,
                'badge'     => 'Hot',
                'blurb'     => 'সরাসরি মৌচাক সহ খাঁটি মধুর টুকরা। তাজা স্বাদ ও প্রাকৃতিক মোমের পুষ্টি।',
                'url'       => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/natural_honeycomb.jpg',
            ],
            [
                'name'      => 'Lychee Flower Honey (লিচু ফুলের মধু)',
                'cat'       => 'honey',
                'weight'    => '500g',
                'price'     => 520,
                'old_price' => 600,
                'badge'     => 'Popular',
                'blurb'     => 'দিনাজপুরের লিচু বাগান থেকে মিষ্টি সুবাসযুক্ত হালকা সোনালি মধু।',
                'url'       => 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/lychee_honey.jpg',
            ],

            // ── Dates ──
            [
                'name'      => 'Grade A Medina Ajwa Dates (মদিনার আযওয়া খেজুর)',
                'cat'       => 'dates',
                'weight'    => '500g',
                'price'     => 1150,
                'old_price' => 1350,
                'badge'     => 'Best Seller',
                'blurb'     => 'মদিনা মুনাওয়ারা থেকে সরাসরি আমদানিকৃত নরম, কালো ও বরকতময় আযওয়া খেজুর।',
                'url'       => 'https://images.unsplash.com/photo-1596704017254-9b121068fb31?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/ajwa_dates_500g.jpg',
            ],
            [
                'name'      => 'Jumbo Medjool Dates (মেডজুল জ্যাম্বো খেজুর)',
                'cat'       => 'dates',
                'weight'    => '500g',
                'price'     => 950,
                'old_price' => 1100,
                'badge'     => 'Premium',
                'blurb'     => 'খেজুরের রাজা মেডজুল — মিষ্টি, নরম ও রসালো স্বাদের আন্তর্জাতিক মানসম্পন্ন খেজুর।',
                'url'       => 'https://images.unsplash.com/photo-1559181567-c3190ca9959b?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/medjool_dates.jpg',
            ],
            [
                'name'      => 'Golden Sukkari Dates (সুক্কারী খেজুর)',
                'cat'       => 'dates',
                'weight'    => '500g',
                'price'     => 650,
                'old_price' => 750,
                'badge'     => 'Sweet',
                'blurb'     => 'মিষ্টি ও ক্রিস্পি স্বাদের খাঁটি সৌদি সুক্কারী খেজুর।',
                'url'       => 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/sukkari_dates.jpg',
            ],
            [
                'name'      => 'Safawi / Kalmi Dates (কলমি / সাফাভি খেজুর)',
                'cat'       => 'dates',
                'weight'    => '500g',
                'price'     => 580,
                'old_price' => 650,
                'badge'     => '10% OFF',
                'blurb'     => 'গাঢ় কালো ও মাঝারি নরম সাফাভি খেজুর। প্রচুর ফাইবার ও আয়রন সমৃদ্ধ।',
                'url'       => 'https://images.unsplash.com/photo-1596704017254-9b121068fb31?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/safawi_dates.jpg',
            ],

            // ── Oil & Ghee ──
            [
                'name'      => 'Traditional Bilona Gawa Ghee (ঘানিভাঙা গাওয়া ঘি)',
                'cat'       => 'oil-ghee',
                'weight'    => '400g',
                'price'     => 1250,
                'old_price' => 1400,
                'badge'     => 'Best Seller',
                'blurb'     => 'ঘাসের গরুর খাঁটি দুধের মাখন জ্বাল দিয়ে তৈরি দানাদার ও সুগন্ধি গাওয়া ঘি।',
                'url'       => 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/gawa_ghee_400g.jpg',
            ],
            [
                'name'      => 'Traditional Bilona Gawa Ghee - 900g Jar',
                'cat'       => 'oil-ghee',
                'weight'    => '900g',
                'price'     => 2650,
                'old_price' => 2950,
                'badge'     => 'Family Pack',
                'blurb'     => 'গ্রামের খাঁটি দুধের ঐতিহ্যবাহী বিলোনা পদ্ধতিতে প্রস্তুত সুবাসিত ঘি।',
                'url'       => 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/gawa_ghee_900g.jpg',
            ],
            [
                'name'      => 'Cold-Pressed Ghani Mustard Oil (কাঠের ঘানির সরিষার তেল)',
                'cat'       => 'oil-ghee',
                'weight'    => '1 L',
                'price'     => 320,
                'old_price' => 360,
                'badge'     => '100% Pure',
                'blurb'     => 'প্রথম চাপের কাঠের ঘানিতে ভাঙা খাঁটি সরিষার তেল। তীব্র ঝাঁঝ ও অতুলনীয় সুবাস।',
                'url'       => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/ghani_mustard_oil.jpg',
            ],
            [
                'name'      => 'Extra Virgin Cold-Pressed Coconut Oil (নারকেল তেল)',
                'cat'       => 'oil-ghee',
                'weight'    => '500 ml',
                'price'     => 450,
                'old_price' => 520,
                'badge'     => 'Cold Pressed',
                'blurb'     => 'কাঁচা নারকেলের দুধ থেকে তৈরি ভোজ্য কোল্ড প্রেসড এক্সট্রা ভার্জিন তেল।',
                'url'       => 'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/virgin_coconut_oil.jpg',
            ],
            [
                'name'      => 'Pure Black Cumin Seed Oil (কালোজিরা তেল)',
                'cat'       => 'oil-ghee',
                'weight'    => '200 ml',
                'price'     => 380,
                'old_price' => 450,
                'badge'     => 'Herbal',
                'blurb'     => '১০০% কোল্ড প্রেসড কালোজিরা তেল। রোগ প্রতিরোধ ক্ষমতা বৃদ্ধিতে সহায়ক।',
                'url'       => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/black_cumin_oil.jpg',
            ],

            // ── Rice & Dal ──
            [
                'name'      => 'Chinigura Aromatic Polao Rice (সুগন্ধি চিনিগুঁড়া চাল)',
                'cat'       => 'rice',
                'weight'    => '1 kg',
                'price'     => 165,
                'old_price' => 185,
                'badge'     => 'Aromatic',
                'blurb'     => 'দিনাজপুরের সেরা ছোট দানার সুগন্ধি চিনিগুঁড়া চাল। পোলাও, বিরিয়ানি ও ক্ষীরের জন্য সেরা।',
                'url'       => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/chinigura_rice_1kg.jpg',
            ],
            [
                'name'      => 'Chinigura Aromatic Polao Rice - 5 kg Bag',
                'cat'       => 'rice',
                'weight'    => '5 kg',
                'price'     => 780,
                'old_price' => 880,
                'badge'     => 'Save ৳100',
                'blurb'     => 'প্রিমিয়াম কোয়ালিটি সুগন্ধি চিনিগুঁড়া চাল ৫ কেজির পারিবারিক ব্যাগ।',
                'url'       => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/chinigura_rice_5kg.jpg',
            ],
            [
                'name'      => 'Premium Nazirshail Rice (প্রিমিয়াম নাজিরশাইল চাল)',
                'cat'       => 'rice',
                'weight'    => '5 kg',
                'price'     => 440,
                'old_price' => 480,
                'badge'     => 'Daily Staple',
                'blurb'     => 'চিকন ও ঝরঝরে নাজিরশাইল চাল। দৈনন্দিন দুপুরের খাবারের জন্য অত্যন্ত উপযোগী।',
                'url'       => 'https://images.unsplash.com/photo-1536304993881-ff6e9eefa2a6?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/nazirshail_rice.jpg',
            ],
            [
                'name'      => 'Deshi Masoor Dal (দেশি চিকন মসুর ডাল)',
                'cat'       => 'rice',
                'weight'    => '1 kg',
                'price'     => 145,
                'old_price' => 160,
                'badge'     => 'Top Quality',
                'blurb'     => 'দেশি ছোট দানার ফ্রেশ মসুর ডাল। দ্রুত সিদ্ধ হয় ও অসাধারণ স্বাদ।',
                'url'       => 'https://images.unsplash.com/photo-1585994192701-f1a505c817ea?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/deshi_masoor_dal.jpg',
            ],
            [
                'name'      => 'Yellow Moong Dal (সোনালি মুগ ডাল)',
                'cat'       => 'rice',
                'weight'    => '1 kg',
                'price'     => 175,
                'old_price' => 195,
                'badge'     => 'Fresh',
                'blurb'     => 'ভাজা ও পরিষ্কার সোনালি মুগ ডাল। সুস্বাদু খিচুড়ি ও ডাল রান্নায় সেরা।',
                'url'       => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/moong_dal.jpg',
            ],
            [
                'name'      => 'Chhola Boot / Chickpeas (দেশি কাঁচা ছোলা)',
                'cat'       => 'rice',
                'weight'    => '1 kg',
                'price'     => 125,
                'old_price' => 140,
                'badge'     => 'Protein',
                'blurb'     => 'উচ্চমাত্রার প্রোটিন ও ফাইবার সমৃদ্ধ পরিষ্কার দেশি ছোলা।',
                'url'       => 'https://images.unsplash.com/photo-1515543237350-b3eea1ec8082?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/chhola_boot.jpg',
            ],

            // ── Spices ──
            [
                'name'      => 'Stone-Ground Turmeric Powder (খাঁটি হলুদ গুঁড়া)',
                'cat'       => 'spices',
                'weight'    => '250g',
                'price'     => 120,
                'old_price' => 140,
                'badge'     => 'No Color',
                'blurb'     => 'কোনো কৃত্রিম রং বা ভেজাল ছাড়া পাহাড়ি কাঁচা হলুদ শুকিয়ে গুঁড়া করা।',
                'url'       => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/turmeric_powder.jpg',
            ],
            [
                'name'      => 'Sun-Dried Red Chili Powder (ঝাল শুকনা মরিচ গুঁড়া)',
                'cat'       => 'spices',
                'weight'    => '250g',
                'price'     => 140,
                'old_price' => 160,
                'badge'     => 'Hot & Spicy',
                'blurb'     => 'রোদে শুকানো খাঁটি লাল মরিচ। প্রাকৃতিক লাল রং ও তীক্ষ্ণ ঝাঁঝালো স্বাদ।',
                'url'       => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/chili_powder.jpg',
            ],
            [
                'name'      => 'Premium Whole Cumin / Jeera (আস্ত মিষ্টি জিরা)',
                'cat'       => 'spices',
                'weight'    => '200g',
                'price'     => 240,
                'old_price' => 280,
                'badge'     => 'Aromatic',
                'blurb'     => 'উচ্চ সুবাসযুক্ত বাছাইকৃত আস্ত জিরা। রান্নায় এনে দেয় অপূর্ব ঘ্রাণ।',
                'url'       => 'https://images.unsplash.com/photo-1599940824399-b87987ceb72a?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/whole_jeera.jpg',
            ],
            [
                'name'      => 'Coriander Powder (ধনিয়া গুঁড়া)',
                'cat'       => 'spices',
                'weight'    => '250g',
                'price'     => 110,
                'old_price' => 130,
                'badge'     => 'Fresh',
                'blurb'     => 'তাজা দেশি ধনিয়া বীজ থেকে পরিষ্কার করে তৈরি খাঁটি ধনিয়া গুঁড়া।',
                'url'       => 'https://images.unsplash.com/photo-1509358271058-acd22cc93898?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/coriander_powder.jpg',
            ],
            [
                'name'      => 'Shahi Garam Masala Mix (শাহী গরম মসলা)',
                'cat'       => 'spices',
                'weight'    => '100g',
                'price'     => 260,
                'old_price' => 310,
                'badge'     => 'Special',
                'blurb'     => 'এলাচ, দারুচিনি, লবঙ্গ, জয়ফল, জয়ত্রী ও তেজপাতার নিখুঁত শাহী মিশ্রণ।',
                'url'       => 'https://images.unsplash.com/photo-1509358271058-acd22cc93898?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/shahi_garam_masala.jpg',
            ],

            // ── Nuts & Seeds ──
            [
                'name'      => 'California Jumbo Almonds (আমেরিকান কাঠবাদাম)',
                'cat'       => 'nuts',
                'weight'    => '250g',
                'price'     => 380,
                'old_price' => 450,
                'badge'     => 'Best Seller',
                'blurb'     => 'ক্যালিফোর্নিয়ার তাজা বড় দানার কাঠবাদাম। ব্রেন ও হার্টের জন্য উপকারী।',
                'url'       => 'https://images.unsplash.com/photo-1508746829417-e6f548d8d6ed?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/california_almonds.jpg',
            ],
            [
                'name'      => 'Roasted Salted Cashew Nuts (কাজুবাদাম)',
                'cat'       => 'nuts',
                'weight'    => '250g',
                'price'     => 420,
                'old_price' => 490,
                'badge'     => 'Crunchy',
                'blurb'     => 'হালকা ভাজা ও মচমচে প্রিমিয়াম কোয়ালিটি কাজুবাদাম। স্বাস্থ্যকর নাস্তা।',
                'url'       => 'https://images.unsplash.com/photo-1536591375315-1b8368903277?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/roasted_cashews.jpg',
            ],
            [
                'name'      => 'Raw Organic Chia Seeds (অর্গানিক চিয়া সিড)',
                'cat'       => 'nuts',
                'weight'    => '250g',
                'price'     => 350,
                'old_price' => 420,
                'badge'     => 'Superfood',
                'blurb'     => 'ওমেগা-৩, ক্যালসিয়াম ও ফাইবারে সমৃদ্ধ সার্টিফাইড অর্গানিক চিয়া সিড।',
                'url'       => 'https://images.unsplash.com/photo-1514733670139-4d87a1941d55?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/chia_seeds.jpg',
            ],
            [
                'name'      => 'Raw Pumpkin Seeds (মিষ্টি কুমড়ার বীজ)',
                'cat'       => 'nuts',
                'weight'    => '200g',
                'price'     => 280,
                'old_price' => 340,
                'badge'     => 'Healthy',
                'blurb'     => 'প্রোটিন, জিঙ্ক ও ম্যাগনেসিয়ামের চমৎকার প্রাকৃতিক উৎস।',
                'url'       => 'https://images.unsplash.com/photo-1508746829417-e6f548d8d6ed?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/pumpkin_seeds.jpg',
            ],

            // ── Dairy & Eggs ──
            [
                'name'      => 'Farm Fresh Layer Brown Eggs (ফার্মের তাজা লাল ডিম)',
                'cat'       => 'dairy',
                'weight'    => '12 pcs',
                'price'     => 155,
                'old_price' => 170,
                'badge'     => 'Daily Fresh',
                'blurb'     => 'প্রতিদিন সকালের তাজা লাল ডিম। উচ্চমানের প্রোটিন সমৃদ্ধ।',
                'url'       => 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/brown_eggs_12.jpg',
            ],
            [
                'name'      => 'Pure Pasteurized Cow Milk (খাঁটি গরুর তরল দুধ)',
                'cat'       => 'dairy',
                'weight'    => '1 L',
                'price'     => 95,
                'old_price' => 105,
                'badge'     => 'Fresh Milk',
                'blurb'     => 'গাভীর খাঁটি পাস্তুরিত তরল দুধ। পুষ্টিকর ও ক্যালসিয়ামে ভরপুর।',
                'url'       => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/cow_milk_1l.jpg',
            ],
            [
                'name'      => 'Sweet Cream Salted Butter (খাঁটি মাখন)',
                'cat'       => 'dairy',
                'weight'    => '200g',
                'price'     => 220,
                'old_price' => 250,
                'badge'     => 'Creamy',
                'blurb'     => 'তাজা দুধের খাঁটি মাখন। সকালের টোস্ট কিংবা রান্নায় অনন্য স্বাদ।',
                'url'       => 'https://images.unsplash.com/photo-1589985270826-4b7bb135bc9d?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/cream_butter.jpg',
            ],

            // ── Fruits & Daily Bazar ──
            [
                'name'      => 'Rajshahi Fresh Himsagar Mango (হিমসাগর আম)',
                'cat'       => 'fruits-veggies',
                'weight'    => '3 kg',
                'price'     => 450,
                'old_price' => 520,
                'badge'     => 'Seasonal',
                'blurb'     => 'রাজশাহীর বাগান থেকে সরাসরি ফরমালিনমুক্ত মিষ্টি ও সুস্বাদু হিমসাগর আম।',
                'url'       => 'https://images.unsplash.com/photo-1553279768-865429fa0078?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/himsagar_mango.jpg',
            ],
            [
                'name'      => 'Fresh Green Apples (সবুজ আপেল)',
                'cat'       => 'fruits-veggies',
                'weight'    => '1 kg',
                'price'     => 340,
                'old_price' => 390,
                'badge'     => 'Crispy',
                'blurb'     => 'খাস্তা ও মিষ্টি-টক স্বাদের রসালো তাজা সবুজ আপেল।',
                'url'       => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/green_apples.jpg',
            ],
            [
                'name'      => 'Deshi Red Onion (দেশি লাল পেঁয়াজ)',
                'cat'       => 'fruits-veggies',
                'weight'    => '1 kg',
                'price'     => 85,
                'old_price' => 95,
                'badge'     => 'Bazar Special',
                'blurb'     => 'রান্নার অপরিহার্য দেশি ঝাঁঝালো শুকনো লাল পেঁয়াজ।',
                'url'       => 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/red_onions.jpg',
            ],
            [
                'name'      => 'Fresh Garlic (দেশি রসুন)',
                'cat'       => 'fruits-veggies',
                'weight'    => '500g',
                'price'     => 120,
                'old_price' => 140,
                'badge'     => 'Fresh',
                'blurb'     => 'রান্নার সুঘ্রাণ ও স্বাস্থ্যের জন্য প্রাকৃতিক শুকনো দেশি রসুন।',
                'url'       => 'https://images.unsplash.com/photo-1540148426945-6cf22a6b2383?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/fresh_garlic.jpg',
            ],
            [
                'name'      => 'Fresh Ginger (দেশি আদা)',
                'cat'       => 'fruits-veggies',
                'weight'    => '500g',
                'price'     => 130,
                'old_price' => 150,
                'badge'     => 'Fresh',
                'blurb'     => 'রসালো ও ঝাঁঝালো দেশি তাজা আদা। হজম ও রান্নায় উপকারী।',
                'url'       => 'https://images.unsplash.com/photo-1615485500704-8e990f9900f7?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/fresh_ginger.jpg',
            ],

            // ── Tea & Coffee ──
            [
                'name'      => 'Organic Sylhet Green Tea (শ্রীমঙ্গলের গ্রিন টি)',
                'cat'       => 'tea',
                'weight'    => '100g',
                'price'     => 220,
                'old_price' => 260,
                'badge'     => 'Organic',
                'blurb'     => 'শ্রীমঙ্গলের বাগান থেকে হস্তনির্মিত খাঁটি অ্যান্টিঅক্সিডেন্ট সমৃদ্ধ গ্রিন টি পাতা।',
                'url'       => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/sylhet_green_tea.jpg',
            ],
            [
                'name'      => 'Premium CTC Black Tea (স্পেশাল কড়া ব্ল্যাক টি)',
                'cat'       => 'tea',
                'weight'    => '400g',
                'price'     => 260,
                'old_price' => 300,
                'badge'     => 'Strong',
                'blurb'     => 'কড়া লিকার ও চমৎকার ঘ্রাণের স্পেশাল ব্লেন্ড ব্ল্যাক চা।',
                'url'       => 'https://images.unsplash.com/photo-1594631252845-29fc4cc8cde9?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/ctc_black_tea.jpg',
            ],

            // ── Shutki ──
            [
                'name'      => 'Cox\'s Bazar Loitta Shutki (লইট্টা শুঁটকি)',
                'cat'       => 'sutki',
                'weight'    => '250g',
                'price'     => 280,
                'old_price' => 320,
                'badge'     => 'Cleaned',
                'blurb'     => 'কক্সবাজারের সমুদ্রের বালুমুক্ত ও কেমিক্যালবিহীন প্রাকৃতিকভাবে রোদে শুকানো লইট্টা শুঁটকি।',
                'url'       => 'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/loitta_shutki.jpg',
            ],
            [
                'name'      => 'Chapa / Chepa Shutki (চেপা শুঁটকি)',
                'cat'       => 'sutki',
                'weight'    => '250g',
                'price'     => 350,
                'old_price' => 400,
                'badge'     => 'Traditional',
                'blurb'     => 'ঐতিহ্যবাহী পদ্ধতিতে মটকায় গাঁজানো খাঁটি চেপা শুঁটকি। অতুলনীয় দেশি স্বাদ।',
                'url'       => 'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?w=600&auto=format&fit=crop&q=80',
                'file'      => 'products/chepa_shutki.jpg',
            ],
        ];

        // Seed or update products
        foreach ($groceryProducts as $index => $item) {
            $cat = $categoryMap[$item['cat']] ?? null;
            if (! $cat) {
                continue;
            }

            $savedImg = $this->saveImage($item['url'], $item['file']);
            $brandId = ! empty($brandIds) ? $brandIds[$index % count($brandIds)] : null;
            $slug = Str::slug($item['name'] . '-' . $item['weight']);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'name'        => $item['name'],
                    'weight'      => $item['weight'],
                    'price'       => $item['price'],
                    'old_price'   => $item['old_price'],
                    'category_id' => $cat->id,
                    'brand_id'    => $brandId,
                    'badge'       => $item['badge'],
                    'rating'      => 4.8 + (rand(0, 2) / 10),
                    'reviews'     => rand(35, 240),
                    'blurb'       => $item['blurb'],
                    'description' => "<p>{$item['blurb']}</p><p>আমাদের প্রতিটি পণ্য শতভাগ বিশুদ্ধ ও ভেজালমুক্ত। আমরা মান ও সতেজতা নিশ্চিত করতে বিশ্বস্ত উৎস থেকে সরাসরি সংগ্রহ করি।</p>",
                    'stock'       => rand(30, 150),
                    'is_active'   => true,
                    'image'       => $savedImg ?: $item['file'],
                    'sort'        => $index + 1,
                ]
            );
        }

        // ══════════════════════════════════════════════════════════════
        // 5. UPDATE HOMEPAGE CATEGORY RAILS
        // ══════════════════════════════════════════════════════════════
        $this->command->info('4. Setting up Homepage Rails...');

        $homepageRails = [];
        $railSlugs = ['honey', 'oil-ghee', 'rice', 'dates', 'spices', 'nuts', 'dairy'];

        foreach ($railSlugs as $rSlug) {
            if (isset($categoryMap[$rSlug])) {
                $c = $categoryMap[$rSlug];
                $homepageRails[] = [
                    'category_id' => (string) $c->id,
                    'title'       => $c->name,
                    'limit'       => 10,
                    'sort_by'     => 'sort_order',
                    'is_active'   => true,
                ];
            }
        }

        Setting::set('homepage_category_rails', $homepageRails);
        Setting::set('homepage_featured_categories', true);
        Setting::set('homepage_hero_slider', true);
        Setting::set('homepage_combos', true);

        $this->command->info('Grocery Demo Seeding completed successfully!');
    }
}
