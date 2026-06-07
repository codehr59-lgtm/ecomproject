/* Shuvo — organic grocery storefront data
   Plain JS, attaches to window.SHUVO so Babel components can read it. */
(function () {
  // --- Categories -------------------------------------------------------
  const categories = [
    { id: "honey",   name: "Honey",        tint: "#E7B84B", note: "Raw & wild-sourced" },
    { id: "dates",   name: "Dates",        tint: "#A9682F", note: "Ajwa, Medjool, Sukkari" },
    { id: "oil-ghee",name: "Oil & Ghee",   tint: "#D7A53C", note: "Cold-pressed & pure" },
    { id: "spices",  name: "Spices",       tint: "#C0432F", note: "Stone-ground fresh" },
    { id: "nuts",    name: "Nuts & Seeds", tint: "#9C7A4D", note: "Roasted & raw" },
    { id: "rice",    name: "Rice",         tint: "#C9B98E", note: "Aromatic & aged" },
    { id: "mango",   name: "Mango",        tint: "#E59A2B", note: "Seasonal, pre-order" },
    { id: "tea",     name: "Tea & Coffee", tint: "#6E7F4F", note: "Garden fresh" },
  ];

  // --- Products ---------------------------------------------------------
  // price/oldPrice in BDT. badge: best | new | preorder | null
  let _id = 0;
  const P = (o) => Object.assign({ id: ++_id, rating: 4.8, reviews: 120 }, o);

  const products = [
    P({ name: "Sundarban Wild Honey", weight: "1 kg", price: 2300, oldPrice: 2500, cat: "honey", badge: "best", reviews: 412, rating: 4.9,
        blurb: "Single-origin wild honey collected from the Sundarban mangrove forest. Unheated, unprocessed, naturally crystallizing." }),
    P({ name: "Black Seed Honey", weight: "500 g", price: 750, oldPrice: 800, cat: "honey", badge: null, reviews: 188,
        blurb: "Infused with cold-pressed black seed (kalonji). Bold, slightly bitter finish prized for daily wellness." }),
    P({ name: "Lychee Flower Honey", weight: "500 g", price: 550, oldPrice: 600, cat: "honey", badge: "new", reviews: 96,
        blurb: "Light, floral and delicate — harvested during the spring lychee bloom." }),
    P({ name: "Natural Honeycomb", weight: "1 kg", price: 2250, oldPrice: 2500, cat: "honey", badge: null, reviews: 54,
        blurb: "Cut straight from the hive. Chewable wax comb, raw enzymes intact." }),

    P({ name: "Ajwa Premium Dates", weight: "1 kg", price: 2250, oldPrice: 2500, cat: "dates", badge: "best", reviews: 320, rating: 4.9,
        blurb: "Jumbo-grade Ajwa from Madinah. Soft, rich, low-glycemic and deeply sweet." }),
    P({ name: "Medjool Large Dates", weight: "1 kg", price: 1984, oldPrice: 2200, cat: "dates", badge: null, reviews: 210,
        blurb: "Caramel-soft Egyptian Medjool. The classic snacking date." }),
    P({ name: "Safawi / Kalmi Dates", weight: "1 kg", price: 1170, oldPrice: 1300, cat: "dates", badge: null, reviews: 140,
        blurb: "A-grade Safawi — firm texture, dark and not too sweet." }),
    P({ name: "Sukkari Dates", weight: "1 kg", price: 1290, oldPrice: 1450, cat: "dates", badge: "new", reviews: 77,
        blurb: "Golden, melt-in-mouth Sukkari from the Qassim region." }),

    P({ name: "Gawa Ghee", weight: "1 kg", price: 1700, oldPrice: 1800, cat: "oil-ghee", badge: "best", reviews: 530, rating: 4.9,
        blurb: "Slow-cooked from pure cow's milk butter. Grainy, golden, deeply aromatic." }),
    P({ name: "Deshi Mustard Oil", weight: "5 L", price: 1550, oldPrice: null, cat: "oil-ghee", badge: "best", reviews: 488,
        blurb: "Cold-pressed Maghi mustard oil. Pungent, sharp, traditional ghani-milled." }),
    P({ name: "Extra Virgin Coconut Oil", weight: "1 L", price: 2030, oldPrice: null, cat: "oil-ghee", badge: null, reviews: 132,
        blurb: "Organic cold-pressed coconut oil. Cooking, skin and hair." }),
    P({ name: "Black Cumin Seed Oil", weight: "250 ml", price: 980, oldPrice: 1100, cat: "oil-ghee", badge: null, reviews: 88,
        blurb: "Cold-pressed kalonji oil — a wellness staple, taken by the spoon." }),

    P({ name: "Kala Bhuna Masala", weight: "250 g", price: 675, oldPrice: 750, cat: "spices", badge: null, reviews: 145,
        blurb: "Hand-blended Chittagong kala bhuna spice mix. Smoky, dark, complex." }),
    P({ name: "Turmeric Powder", weight: "500 g", price: 295, oldPrice: null, cat: "spices", badge: null, reviews: 99,
        blurb: "Stone-ground sun-dried turmeric. High curcumin, no additives." }),
    P({ name: "Chili Powder", weight: "500 g", price: 400, oldPrice: null, cat: "spices", badge: null, reviews: 110,
        blurb: "Pure red chili, ground fresh. Vivid colour, clean heat." }),
    P({ name: "Cumin Powder", weight: "500 g", price: 880, oldPrice: null, cat: "spices", badge: "new", reviews: 61,
        blurb: "Roasted then ground whole cumin. Warm, earthy, fragrant." }),

    P({ name: "Honey Roasted Nuts Mix", weight: "800 g", price: 1500, oldPrice: null, cat: "nuts", badge: "best", reviews: 240,
        blurb: "Almonds, cashews and walnuts tumbled in raw honey." }),
    P({ name: "Raw Cashew Nuts", weight: "500 g", price: 920, oldPrice: 1000, cat: "nuts", badge: null, reviews: 130,
        blurb: "Whole W320 cashews, unsalted and unroasted." }),
    P({ name: "Chia Seeds", weight: "500 g", price: 640, oldPrice: null, cat: "nuts", badge: null, reviews: 85,
        blurb: "Premium black chia. Omega-rich, perfect for puddings and drinks." }),
    P({ name: "Mixed Pumpkin & Sunflower Seeds", weight: "400 g", price: 560, oldPrice: 620, cat: "nuts", badge: "new", reviews: 47,
        blurb: "Lightly roasted seed mix for salads and snacking." }),

    P({ name: "Aromatic Kalijira Rice", weight: "5 kg", price: 720, oldPrice: null, cat: "rice", badge: null, reviews: 158,
        blurb: "Tiny-grain Bangladeshi kalijira. Fragrant polao and payesh rice." }),
    P({ name: "Aged Basmati Rice", weight: "5 kg", price: 1150, oldPrice: 1250, cat: "rice", badge: null, reviews: 96,
        blurb: "Two-year aged long-grain basmati. Fluffy, separate, aromatic." }),

    P({ name: "Amrapali Mango", weight: "10 kg", price: 1600, oldPrice: null, cat: "mango", badge: "preorder", reviews: 70,
        blurb: "Naturally ripened Rajshahi Amrapali. Pre-order for peak-season delivery." }),
    P({ name: "Himsagar Mango", weight: "5 kg", price: 850, oldPrice: null, cat: "mango", badge: "preorder", reviews: 64,
        blurb: "The king of mangoes — fibreless, intensely sweet Himsagar." }),

    P({ name: "Garden Fresh Green Tea", weight: "100 g", price: 480, oldPrice: null, cat: "tea", badge: null, reviews: 58,
        blurb: "Whole-leaf green tea from Sylhet hill gardens." }),
    P({ name: "Single Origin Coffee Beans", weight: "250 g", price: 720, oldPrice: 800, cat: "tea", badge: "new", reviews: 39,
        blurb: "Medium-roast arabica, ground or whole bean." }),

    // extra mango sizes (seasonal pre-order)
    P({ name: "Amrapali Mango", weight: "20 kg", price: 3000, oldPrice: 3200, cat: "mango", badge: "preorder", reviews: 52,
        blurb: "Bulk Rajshahi Amrapali, naturally ripened. Reserve for peak season." }),
    P({ name: "Amrapali Mango", weight: "5 kg", price: 850, oldPrice: null, cat: "mango", badge: "preorder", reviews: 41,
        blurb: "A family-size crate of fibreless, intensely sweet Amrapali." }),
    P({ name: "Himsagar Mango", weight: "10 kg", price: 1600, oldPrice: null, cat: "mango", badge: "preorder", reviews: 48,
        blurb: "The king of mangoes — sweet, aromatic, naturally ripened Himsagar." }),

    // organic certified line
    P({ name: "African Organic Wild Honey", weight: "500 g", price: 1100, oldPrice: 1250, cat: "honey", badge: null, reviews: 132, certified: true,
        blurb: "Wild-foraged organic honey from African highlands. Bold and aromatic." }),
    P({ name: "Organic Spirulina Powder", weight: "250 g", price: 1140, oldPrice: 1200, cat: "nuts", badge: "new", reviews: 64, certified: true,
        blurb: "Nutrient-dense organic spirulina. A daily green boost." }),
    P({ name: "Organic Matcha Green Tea", weight: "100 g", price: 1500, oldPrice: null, cat: "tea", badge: null, reviews: 47, certified: true,
        blurb: "Stone-ground ceremonial matcha. Smooth, vivid and earthy." }),
    P({ name: "Ashwagandha Powder", weight: "100 g", price: 600, oldPrice: null, cat: "spices", badge: "new", reviews: 38, certified: true,
        blurb: "USDA-organic ashwagandha root powder for calm and balance." }),
    P({ name: "Organic Apple Cider Vinegar", weight: "250 ml", price: 490, oldPrice: null, cat: "oil-ghee", badge: null, reviews: 55, certified: true,
        blurb: "Raw, unfiltered ACV with the mother. Tangy and bright." }),
    P({ name: "Ajwa Premium Dates", weight: "500 g", price: 1250, oldPrice: null, cat: "dates", badge: null, reviews: 73,
        blurb: "Half-kilo of soft, rich jumbo Ajwa from Madinah." }),
  ];

  const testimonials = [
    { name: "Sultana Yesmin", role: "Homemaker", text: "Ordered twice now — same great quality and fast delivery both times. Completely satisfied." },
    { name: "Ayesha Khan", role: "Banker", text: "In a market full of doubt, Shuvo is a name I actually trust. The honey is the real thing." },
    { name: "Shahriar Abir", role: "Service Holder", text: "I don't even like ghee, but my father said this is the best he's ever had." },
    { name: "Fariha Tumpa", role: "Entrepreneur", text: "Clean packaging, honest sourcing, and the dates are unreal. My monthly order now." },
  ];

  const brands = ["Shuvo Farms", "Khejuri", "Honeyraj", "Glarvest", "Shosti Food"];

  window.SHUVO = { categories, products, testimonials, brands };
})();
