<?php

namespace App\Http\Controllers;

use App\Support\Catalog;

class CatalogController extends Controller
{
    public function home(): \Illuminate\View\View
    {
        $topSelling = Catalog::topSelling();

        return view('pages.home', [
            'banners'    => Catalog::banners(),
            'categories' => Catalog::categories(),
            'topSelling' => $topSelling,
            'featured'   => Catalog::featured(8, array_column($topSelling, 'slug')),
        ]);
    }

    public function category(string $slug): \Illuminate\View\View
    {
        $category = Catalog::category($slug);
        abort_if(! $category, 404);

        return view('pages.category', [
            'category'   => $category,
            'categories' => Catalog::categories(),
            'products'   => Catalog::byCategory($slug),
        ]);
    }

    public function product(string $slug): \Illuminate\View\View
    {
        $product = Catalog::find($slug);
        abort_if(! $product, 404);

        return view('pages.product', [
            'product' => $product,
            'related' => Catalog::related($slug),
        ]);
    }

    public function checkout(): \Illuminate\View\View
    {
        return view('pages.checkout');
    }
}
