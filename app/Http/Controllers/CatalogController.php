<?php

namespace App\Http\Controllers;

use App\Support\Catalog;
use App\Support\PaymentConfig;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home(): \Illuminate\View\View
    {
        $viewData = \Illuminate\Support\Facades\Cache::remember('storefront.home_view_data_v2', 300, function () {
            // 1. Featured categories slider
            $showFeaturedCategories = (bool) \App\Models\Setting::get('homepage_featured_categories', true);
            $featuredCatIds = (array) \App\Models\Setting::get('homepage_featured_category_ids', []);
            $allCategories = Catalog::categories();

            if (! empty($featuredCatIds)) {
                $categories = array_values(array_filter($allCategories, function ($c) use ($featuredCatIds) {
                    return in_array((string) ($c['model_id'] ?? ''), array_map('strval', $featuredCatIds), true);
                }));
            } else {
                $categories = $allCategories;
            }

            // 2. Dynamic Category Rails
            $savedRails = \App\Models\Setting::get('homepage_category_rails');
            $categoryRails = [];

            if (is_array($savedRails) && ! empty($savedRails)) {
                foreach ($savedRails as $railConfig) {
                    if (empty($railConfig['is_active'])) {
                        continue;
                    }

                    $categoryId = (int) ($railConfig['category_id'] ?? 0);
                    if (! $categoryId) {
                        continue;
                    }

                    $category = \App\Models\Category::find($categoryId);
                    if (! $category || ! $category->is_active) {
                        continue;
                    }

                    $title = ! empty(trim($railConfig['title'] ?? '')) ? trim($railConfig['title']) : $category->name;
                    $limit = max(1, min(30, (int) ($railConfig['limit'] ?? 10)));
                    $sortBy = $railConfig['sort_by'] ?? 'sort_order';

                    $products = Catalog::categoryProducts($category->id, $limit, $sortBy);

                    $categoryRails[] = [
                        'category'   => $category,
                        'title'      => $title,
                        'viewAllUrl' => route('category', $category->slug),
                        'products'   => $products ?? [],
                    ];
                }
            } else {
                // Default 4 rails if settings have not been customized yet
                $defaultDefs = [
                    ['slug' => 'mango', 'title' => 'Mango'],
                    ['slug' => 'honey', 'title' => 'All Natural Honey'],
                    ['slug' => 'dates', 'title' => 'Premium Dates'],
                    ['slug' => 'oil-ghee', 'title' => 'Cooking Essentials'],
                ];

                foreach ($defaultDefs as $def) {
                    $products = Catalog::byCategory($def['slug']);
                    if (! empty($products)) {
                        $categoryRails[] = [
                            'category'   => (object) ['slug' => $def['slug'], 'name' => $def['title']],
                            'title'      => $def['title'],
                            'viewAllUrl' => route('category', $def['slug']),
                            'products'   => array_slice($products, 0, 10),
                        ];
                    }
                }
            }

            // 3. Other sections settings
            $topSellingLimit = (int) \App\Models\Setting::get('homepage_top_selling_limit', 4);
            $justForYouLimit = (int) \App\Models\Setting::get('homepage_just_for_you_limit', 10);

            return [
                'sliders'            => (bool) \App\Models\Setting::get('homepage_hero_slider', true) ? \App\Models\Slider::active()->get() : collect(),
                'banner'             => (bool) \App\Models\Setting::get('homepage_hero_slider', true) ? \App\Models\Banner::where('is_active', true)->where('position', 'hero')->orderBy('sort')->first() : null,
                'showHero'           => (bool) \App\Models\Setting::get('homepage_hero_slider', true),
                'showFeaturedCats'   => $showFeaturedCategories,
                'categories'         => $categories,
                'categoryRails'      => $categoryRails,
                'showTopSelling'     => (bool) \App\Models\Setting::get('homepage_top_selling', true),
                'topSellingTitle'    => \App\Models\Setting::get('homepage_top_selling_title', 'Top Selling Products'),
                'topSelling'         => Catalog::topSelling($topSellingLimit),
                'showBrands'         => (bool) \App\Models\Setting::get('homepage_brands', true),
                'brands'             => Catalog::brands(),
                'showCombos'         => (bool) \App\Models\Setting::get('homepage_combos', true),
                'combos'             => Catalog::combos(),
                'showCertified'      => (bool) \App\Models\Setting::get('homepage_certified', true),
                'certifiedTitle'     => \App\Models\Setting::get('homepage_certified_title', 'Organic Certified'),
                'certified'          => Catalog::certified(),
                'showJustForYou'     => (bool) \App\Models\Setting::get('homepage_just_for_you', true),
                'justForYouTitle'    => \App\Models\Setting::get('homepage_just_for_you_title', 'Just For You'),
                'justForYou'         => Catalog::featured($justForYouLimit),
                'showTestimonials'   => (bool) \App\Models\Setting::get('homepage_testimonials', true),
                'testimonials'       => \App\Models\Testimonial::active()->get(),
                // Keep legacy keys for backwards compatibility if any subview references them
                'mango'              => Catalog::byCategory('mango'),
                'honey'              => Catalog::byCategory('honey'),
                'dates'              => Catalog::byCategory('dates'),
                'oilGhee'            => Catalog::byCategory('oil-ghee'),
            ];
        });

        return view('pages.home', $viewData);
    }


    public function shop(Request $request): \Illuminate\View\View
    {
        $cat      = $request->query('cat');
        $q        = (string) $request->query('q', $request->query('search', ''));

        $category = $cat ? Catalog::category($cat) : null;

        if ($cat) {
            $products = Catalog::byCategory($cat);
        } elseif ($q !== '') {
            $products = Catalog::search($q);
        } else {
            $products = Catalog::products();
        }

        return view('pages.shop', [
            'categories' => Catalog::categories(),
            'products'   => $products,
            'category'   => $category,
            'activeCat'  => $cat,
            'q'          => $q,
        ]);
    }

    public function category(string $slug): \Illuminate\View\View
    {
        $category = Catalog::category($slug);
        abort_if(! $category, 404);

        return view('pages.shop', [
            'categories' => Catalog::categories(),
            'products'   => Catalog::byCategory($slug),
            'category'   => $category,
            'activeCat'  => $slug,
            'q'          => null,
        ]);
    }

    public function product(mixed $id): \Illuminate\View\View
    {
        $product = Catalog::find($id);
        abort_if(! $product, 404);

        $model = \App\Models\Product::with(['category', 'specifications', 'faqs', 'variations', 'relatedProducts.category', 'productReviews'])
            ->find((int) $id);

        return view('pages.product', [
            'product' => $product,
            'model'   => $model,
            'related' => Catalog::related($id),
        ]);
    }

    public function combo(string $slug): \Illuminate\View\View
    {
        $combo = \App\Models\Combo::active()
            ->with(['items.product.category', 'items.variation'])
            ->where('slug', $slug)
            ->first();

        abort_if(! $combo, 404);

        $otherCombos = \App\Models\Combo::active()
            ->with('items.product')
            ->where('id', '!=', $combo->id)
            ->take(3)
            ->get();

        return view('pages.combo-detail', [
            'combo'       => $combo,
            'otherCombos' => $otherCombos,
        ]);
    }

    public function combos(): \Illuminate\View\View
    {
        $combos = \App\Models\Combo::active()
            ->with(['items.product'])
            ->orderBy('sort')
            ->get();

        return view('pages.combos', [
            'combos' => $combos,
        ]);
    }

    public function checkout(): \Illuminate\View\View
    {
        $paymentMethods = PaymentConfig::enabledMethods();

        // Ensure at least COD is available as ultimate fallback
        if (empty($paymentMethods)) {
            $paymentMethods = ['cod'];
        }

        $deliveryConfig = [
            'inside'       => (int) \App\Models\Setting::get('delivery_inside_dhaka', 60),
            'outside'      => (int) \App\Models\Setting::get('delivery_outside_dhaka', 120),
            'freeMin'      => (int) \App\Models\Setting::get('free_shipping_min', 1500),
            'zone1Label'   => \App\Models\Setting::get('delivery_zone_1_label', 'Inside Dhaka'),
            'zone2Label'   => \App\Models\Setting::get('delivery_zone_2_label', 'Outside Dhaka'),
        ];

        return view('pages.checkout', compact('paymentMethods', 'deliveryConfig'));
    }
}
