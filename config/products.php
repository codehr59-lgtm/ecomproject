<?php

/**
 * Shuvo Organic Grocery — catalog configuration.
 * Ported verbatim from _design-reference/data.js.
 * BACKEND SWAP POINT: replace this file with Eloquent queries in Catalog.php when moving to a DB.
 */
return [

    'free_gift_threshold' => 3000,
    'free_ship_threshold' => 1500,

    // ── Categories ────────────────────────────────────────────────────────
    'categories' => [
        ['id' => 'honey',   'name' => 'Honey',        'tint' => '#E7B84B', 'note' => 'Raw & wild-sourced'],
        ['id' => 'dates',   'name' => 'Dates',        'tint' => '#A9682F', 'note' => 'Ajwa, Medjool, Sukkari'],
        ['id' => 'oil-ghee','name' => 'Oil & Ghee',   'tint' => '#D7A53C', 'note' => 'Cold-pressed & pure'],
        ['id' => 'spices',  'name' => 'Spices',       'tint' => '#C0432F', 'note' => 'Stone-ground fresh'],
        ['id' => 'nuts',    'name' => 'Nuts & Seeds', 'tint' => '#9C7A4D', 'note' => 'Roasted & raw'],
        ['id' => 'rice',    'name' => 'Rice',         'tint' => '#C9B98E', 'note' => 'Aromatic & aged'],
        ['id' => 'mango',   'name' => 'Mango',        'tint' => '#E59A2B', 'note' => 'Seasonal, pre-order'],
        ['id' => 'tea',     'name' => 'Tea & Coffee', 'tint' => '#6E7F4F', 'note' => 'Garden fresh'],
    ],

    // ── Products ──────────────────────────────────────────────────────────
    // Ids are 1-based sequential, mirroring the P() counter in data.js.
    // badge: 'best' | 'new' | 'preorder' | null
    // certified key present only where data.js sets certified:true
    'products' => [

        // id 1
        [
            'id'       => 1,
            'name'     => 'Sundarban Wild Honey',
            'weight'   => '1 kg',
            'price'    => 2300,
            'old_price'=> 2500,
            'cat'      => 'honey',
            'badge'    => 'best',
            'rating'   => 4.9,
            'reviews'  => 412,
            'blurb'    => 'Single-origin wild honey collected from the Sundarban mangrove forest. Unheated, unprocessed, naturally crystallizing.',
        ],

        // id 2
        [
            'id'       => 2,
            'name'     => 'Black Seed Honey',
            'weight'   => '500 g',
            'price'    => 750,
            'old_price'=> 800,
            'cat'      => 'honey',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 188,
            'blurb'    => 'Infused with cold-pressed black seed (kalonji). Bold, slightly bitter finish prized for daily wellness.',
        ],

        // id 3
        [
            'id'       => 3,
            'name'     => 'Lychee Flower Honey',
            'weight'   => '500 g',
            'price'    => 550,
            'old_price'=> 600,
            'cat'      => 'honey',
            'badge'    => 'new',
            'rating'   => 4.8,
            'reviews'  => 96,
            'blurb'    => 'Light, floral and delicate — harvested during the spring lychee bloom.',
        ],

        // id 4
        [
            'id'       => 4,
            'name'     => 'Natural Honeycomb',
            'weight'   => '1 kg',
            'price'    => 2250,
            'old_price'=> 2500,
            'cat'      => 'honey',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 54,
            'blurb'    => 'Cut straight from the hive. Chewable wax comb, raw enzymes intact.',
        ],

        // id 5
        [
            'id'       => 5,
            'name'     => 'Ajwa Premium Dates',
            'weight'   => '1 kg',
            'price'    => 2250,
            'old_price'=> 2500,
            'cat'      => 'dates',
            'badge'    => 'best',
            'rating'   => 4.9,
            'reviews'  => 320,
            'blurb'    => 'Jumbo-grade Ajwa from Madinah. Soft, rich, low-glycemic and deeply sweet.',
        ],

        // id 6
        [
            'id'       => 6,
            'name'     => 'Medjool Large Dates',
            'weight'   => '1 kg',
            'price'    => 1984,
            'old_price'=> 2200,
            'cat'      => 'dates',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 210,
            'blurb'    => 'Caramel-soft Egyptian Medjool. The classic snacking date.',
        ],

        // id 7
        [
            'id'       => 7,
            'name'     => 'Safawi / Kalmi Dates',
            'weight'   => '1 kg',
            'price'    => 1170,
            'old_price'=> 1300,
            'cat'      => 'dates',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 140,
            'blurb'    => 'A-grade Safawi — firm texture, dark and not too sweet.',
        ],

        // id 8
        [
            'id'       => 8,
            'name'     => 'Sukkari Dates',
            'weight'   => '1 kg',
            'price'    => 1290,
            'old_price'=> 1450,
            'cat'      => 'dates',
            'badge'    => 'new',
            'rating'   => 4.8,
            'reviews'  => 77,
            'blurb'    => 'Golden, melt-in-mouth Sukkari from the Qassim region.',
        ],

        // id 9
        [
            'id'       => 9,
            'name'     => 'Gawa Ghee',
            'weight'   => '1 kg',
            'price'    => 1700,
            'old_price'=> 1800,
            'cat'      => 'oil-ghee',
            'badge'    => 'best',
            'rating'   => 4.9,
            'reviews'  => 530,
            'blurb'    => "Slow-cooked from pure cow's milk butter. Grainy, golden, deeply aromatic.",
        ],

        // id 10
        [
            'id'       => 10,
            'name'     => 'Deshi Mustard Oil',
            'weight'   => '5 L',
            'price'    => 1550,
            'old_price'=> null,
            'cat'      => 'oil-ghee',
            'badge'    => 'best',
            'rating'   => 4.8,
            'reviews'  => 488,
            'blurb'    => 'Cold-pressed Maghi mustard oil. Pungent, sharp, traditional ghani-milled.',
        ],

        // id 11
        [
            'id'       => 11,
            'name'     => 'Extra Virgin Coconut Oil',
            'weight'   => '1 L',
            'price'    => 2030,
            'old_price'=> null,
            'cat'      => 'oil-ghee',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 132,
            'blurb'    => 'Organic cold-pressed coconut oil. Cooking, skin and hair.',
        ],

        // id 12
        [
            'id'       => 12,
            'name'     => 'Black Cumin Seed Oil',
            'weight'   => '250 ml',
            'price'    => 980,
            'old_price'=> 1100,
            'cat'      => 'oil-ghee',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 88,
            'blurb'    => 'Cold-pressed kalonji oil — a wellness staple, taken by the spoon.',
        ],

        // id 13
        [
            'id'       => 13,
            'name'     => 'Kala Bhuna Masala',
            'weight'   => '250 g',
            'price'    => 675,
            'old_price'=> 750,
            'cat'      => 'spices',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 145,
            'blurb'    => 'Hand-blended Chittagong kala bhuna spice mix. Smoky, dark, complex.',
        ],

        // id 14
        [
            'id'       => 14,
            'name'     => 'Turmeric Powder',
            'weight'   => '500 g',
            'price'    => 295,
            'old_price'=> null,
            'cat'      => 'spices',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 99,
            'blurb'    => 'Stone-ground sun-dried turmeric. High curcumin, no additives.',
        ],

        // id 15
        [
            'id'       => 15,
            'name'     => 'Chili Powder',
            'weight'   => '500 g',
            'price'    => 400,
            'old_price'=> null,
            'cat'      => 'spices',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 110,
            'blurb'    => 'Pure red chili, ground fresh. Vivid colour, clean heat.',
        ],

        // id 16
        [
            'id'       => 16,
            'name'     => 'Cumin Powder',
            'weight'   => '500 g',
            'price'    => 880,
            'old_price'=> null,
            'cat'      => 'spices',
            'badge'    => 'new',
            'rating'   => 4.8,
            'reviews'  => 61,
            'blurb'    => 'Roasted then ground whole cumin. Warm, earthy, fragrant.',
        ],

        // id 17
        [
            'id'       => 17,
            'name'     => 'Honey Roasted Nuts Mix',
            'weight'   => '800 g',
            'price'    => 1500,
            'old_price'=> null,
            'cat'      => 'nuts',
            'badge'    => 'best',
            'rating'   => 4.8,
            'reviews'  => 240,
            'blurb'    => 'Almonds, cashews and walnuts tumbled in raw honey.',
        ],

        // id 18
        [
            'id'       => 18,
            'name'     => 'Raw Cashew Nuts',
            'weight'   => '500 g',
            'price'    => 920,
            'old_price'=> 1000,
            'cat'      => 'nuts',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 130,
            'blurb'    => 'Whole W320 cashews, unsalted and unroasted.',
        ],

        // id 19
        [
            'id'       => 19,
            'name'     => 'Chia Seeds',
            'weight'   => '500 g',
            'price'    => 640,
            'old_price'=> null,
            'cat'      => 'nuts',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 85,
            'blurb'    => 'Premium black chia. Omega-rich, perfect for puddings and drinks.',
        ],

        // id 20
        [
            'id'       => 20,
            'name'     => 'Mixed Pumpkin & Sunflower Seeds',
            'weight'   => '400 g',
            'price'    => 560,
            'old_price'=> 620,
            'cat'      => 'nuts',
            'badge'    => 'new',
            'rating'   => 4.8,
            'reviews'  => 47,
            'blurb'    => 'Lightly roasted seed mix for salads and snacking.',
        ],

        // id 21
        [
            'id'       => 21,
            'name'     => 'Aromatic Kalijira Rice',
            'weight'   => '5 kg',
            'price'    => 720,
            'old_price'=> null,
            'cat'      => 'rice',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 158,
            'blurb'    => 'Tiny-grain Bangladeshi kalijira. Fragrant polao and payesh rice.',
        ],

        // id 22
        [
            'id'       => 22,
            'name'     => 'Aged Basmati Rice',
            'weight'   => '5 kg',
            'price'    => 1150,
            'old_price'=> 1250,
            'cat'      => 'rice',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 96,
            'blurb'    => 'Two-year aged long-grain basmati. Fluffy, separate, aromatic.',
        ],

        // id 23
        [
            'id'       => 23,
            'name'     => 'Amrapali Mango',
            'weight'   => '10 kg',
            'price'    => 1600,
            'old_price'=> null,
            'cat'      => 'mango',
            'badge'    => 'preorder',
            'rating'   => 4.8,
            'reviews'  => 70,
            'blurb'    => 'Naturally ripened Rajshahi Amrapali. Pre-order for peak-season delivery.',
        ],

        // id 24
        [
            'id'       => 24,
            'name'     => 'Himsagar Mango',
            'weight'   => '5 kg',
            'price'    => 850,
            'old_price'=> null,
            'cat'      => 'mango',
            'badge'    => 'preorder',
            'rating'   => 4.8,
            'reviews'  => 64,
            'blurb'    => 'The king of mangoes — fibreless, intensely sweet Himsagar.',
        ],

        // id 25
        [
            'id'       => 25,
            'name'     => 'Garden Fresh Green Tea',
            'weight'   => '100 g',
            'price'    => 480,
            'old_price'=> null,
            'cat'      => 'tea',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 58,
            'blurb'    => 'Whole-leaf green tea from Sylhet hill gardens.',
        ],

        // id 26
        [
            'id'       => 26,
            'name'     => 'Single Origin Coffee Beans',
            'weight'   => '250 g',
            'price'    => 720,
            'old_price'=> 800,
            'cat'      => 'tea',
            'badge'    => 'new',
            'rating'   => 4.8,
            'reviews'  => 39,
            'blurb'    => 'Medium-roast arabica, ground or whole bean.',
        ],

        // id 27 — extra mango sizes (seasonal pre-order)
        [
            'id'       => 27,
            'name'     => 'Amrapali Mango',
            'weight'   => '20 kg',
            'price'    => 3000,
            'old_price'=> 3200,
            'cat'      => 'mango',
            'badge'    => 'preorder',
            'rating'   => 4.8,
            'reviews'  => 52,
            'blurb'    => 'Bulk Rajshahi Amrapali, naturally ripened. Reserve for peak season.',
        ],

        // id 28
        [
            'id'       => 28,
            'name'     => 'Amrapali Mango',
            'weight'   => '5 kg',
            'price'    => 850,
            'old_price'=> null,
            'cat'      => 'mango',
            'badge'    => 'preorder',
            'rating'   => 4.8,
            'reviews'  => 41,
            'blurb'    => 'A family-size crate of fibreless, intensely sweet Amrapali.',
        ],

        // id 29
        [
            'id'       => 29,
            'name'     => 'Himsagar Mango',
            'weight'   => '10 kg',
            'price'    => 1600,
            'old_price'=> null,
            'cat'      => 'mango',
            'badge'    => 'preorder',
            'rating'   => 4.8,
            'reviews'  => 48,
            'blurb'    => 'The king of mangoes — sweet, aromatic, naturally ripened Himsagar.',
        ],

        // id 30 — organic certified line
        [
            'id'        => 30,
            'name'      => 'African Organic Wild Honey',
            'weight'    => '500 g',
            'price'     => 1100,
            'old_price' => 1250,
            'cat'       => 'honey',
            'badge'     => null,
            'rating'    => 4.8,
            'reviews'   => 132,
            'blurb'     => 'Wild-foraged organic honey from African highlands. Bold and aromatic.',
            'certified' => true,
        ],

        // id 31
        [
            'id'        => 31,
            'name'      => 'Organic Spirulina Powder',
            'weight'    => '250 g',
            'price'     => 1140,
            'old_price' => 1200,
            'cat'       => 'nuts',
            'badge'     => 'new',
            'rating'    => 4.8,
            'reviews'   => 64,
            'blurb'     => 'Nutrient-dense organic spirulina. A daily green boost.',
            'certified' => true,
        ],

        // id 32
        [
            'id'        => 32,
            'name'      => 'Organic Matcha Green Tea',
            'weight'    => '100 g',
            'price'     => 1500,
            'old_price' => null,
            'cat'       => 'tea',
            'badge'     => null,
            'rating'    => 4.8,
            'reviews'   => 47,
            'blurb'     => 'Stone-ground ceremonial matcha. Smooth, vivid and earthy.',
            'certified' => true,
        ],

        // id 33
        [
            'id'        => 33,
            'name'      => 'Ashwagandha Powder',
            'weight'    => '100 g',
            'price'     => 600,
            'old_price' => null,
            'cat'       => 'spices',
            'badge'     => 'new',
            'rating'    => 4.8,
            'reviews'   => 38,
            'blurb'     => 'USDA-organic ashwagandha root powder for calm and balance.',
            'certified' => true,
        ],

        // id 34
        [
            'id'        => 34,
            'name'      => 'Organic Apple Cider Vinegar',
            'weight'    => '250 ml',
            'price'     => 490,
            'old_price' => null,
            'cat'       => 'oil-ghee',
            'badge'     => null,
            'rating'    => 4.8,
            'reviews'   => 55,
            'blurb'     => 'Raw, unfiltered ACV with the mother. Tangy and bright.',
            'certified' => true,
        ],

        // id 35
        [
            'id'       => 35,
            'name'     => 'Ajwa Premium Dates',
            'weight'   => '500 g',
            'price'    => 1250,
            'old_price'=> null,
            'cat'      => 'dates',
            'badge'    => null,
            'rating'   => 4.8,
            'reviews'  => 73,
            'blurb'    => 'Half-kilo of soft, rich jumbo Ajwa from Madinah.',
        ],

    ],

    // ── Brands ────────────────────────────────────────────────────────────
    'brands' => ['Shuvo Farms', 'Khejuri', 'Honeyraj', 'Glarvest', 'Shosti Food'],

    // ── Testimonials ──────────────────────────────────────────────────────
    'testimonials' => [
        [
            'name' => 'Sultana Yesmin',
            'role' => 'Homemaker',
            'text' => 'Ordered twice now — same great quality and fast delivery both times. Completely satisfied.',
        ],
        [
            'name' => 'Ayesha Khan',
            'role' => 'Banker',
            'text' => 'In a market full of doubt, Shuvo is a name I actually trust. The honey is the real thing.',
        ],
        [
            'name' => 'Shahriar Abir',
            'role' => 'Service Holder',
            'text' => "I don't even like ghee, but my father said this is the best he's ever had.",
        ],
        [
            'name' => 'Fariha Tumpa',
            'role' => 'Entrepreneur',
            'text' => 'Clean packaging, honest sourcing, and the dates are unreal. My monthly order now.',
        ],
    ],

    // ── Combo Deals ───────────────────────────────────────────────────────
    // ~11-14 % savings vs sum of individual item prices
    'combos' => [
        [
            'name'      => 'Breakfast Combo',
            'items'     => 'Sundarban Wild Honey + Ajwa Premium Dates + Gawa Ghee',
            'price'     => 5500,
            'old_price' => 6250,
        ],
        [
            'name'      => 'Wellness Combo',
            'items'     => 'Black Seed Honey + Black Cumin Seed Oil + Ashwagandha Powder',
            'price'     => 2100,
            'old_price' => 2380,
        ],
        [
            'name'      => 'Ramadan Special',
            'items'     => 'Ajwa Premium Dates + Sukkari Dates + Natural Honeycomb',
            'price'     => 5200,
            'old_price' => 5990,
        ],
        [
            'name'      => 'Cooking Essentials',
            'items'     => 'Deshi Mustard Oil + Gawa Ghee + Turmeric Powder + Chili Powder',
            'price'     => 2750,
            'old_price' => 3145,
        ],
        [
            'name'      => 'Gift Hamper',
            'items'     => 'African Organic Wild Honey + Honey Roasted Nuts Mix + Organic Matcha Green Tea',
            'price'     => 3600,
            'old_price' => 4100,
        ],
    ],

];
