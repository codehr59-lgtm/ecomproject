<?php

namespace App\Http\Controllers;

use App\Support\Catalog;

class CatalogController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'banners'    => Catalog::banners(),
            'categories' => Catalog::categories(),
            'topSelling' => Catalog::topSelling(),
            'featured'   => Catalog::featured(),
        ]);
    }

    public function category(string $slug)
    {
        $category = Catalog::category($slug);
        abort_if(! $category, 404);

        return view('pages.category', [
            'category'   => $category,
            'categories' => Catalog::categories(),
            'products'   => Catalog::byCategory($slug),
        ]);
    }

    public function product(string $slug)
    {
        $product = Catalog::find($slug);
        abort_if(! $product, 404);

        return view('pages.product', [
            'product' => $product,
            'related' => Catalog::related($slug),
        ]);
    }

    public function checkout()
    {
        return view('pages.checkout');
    }
}
