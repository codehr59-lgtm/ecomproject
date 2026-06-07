<?php

namespace App\Http\Controllers;

use App\Support\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home(): \Illuminate\View\View
    {
        return view('pages.home', [
            'categories'  => Catalog::categories(),
            'topSelling'  => Catalog::topSelling(),
            'brands'      => Catalog::brands(),
            'combos'      => Catalog::combos(),
            'testimonials'=> Catalog::testimonials(),
            'mango'       => Catalog::byCategory('mango'),
            'honey'       => Catalog::byCategory('honey'),
            'dates'       => Catalog::byCategory('dates'),
            'oilGhee'     => Catalog::byCategory('oil-ghee'),
            'certified'   => Catalog::certified(),
            'justForYou'  => Catalog::featured(10),
        ]);
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

        return view('pages.product', [
            'product' => $product,
            'related' => Catalog::related($id),
        ]);
    }

    public function checkout(): \Illuminate\View\View
    {
        return view('pages.checkout');
    }
}
