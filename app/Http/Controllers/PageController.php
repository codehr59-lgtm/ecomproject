<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PageController extends Controller
{
    public function about(): \Illuminate\View\View
    {
        return view('pages.about');
    }

    public function contact(): \Illuminate\View\View
    {
        return view('pages.contact');
    }

    public function blog(): \Illuminate\View\View
    {
        return view('pages.blog');
    }

    public function blogPost(string $slug): \Illuminate\View\View
    {
        return view('pages.blog-post', ['slug' => $slug]);
    }

    public function privacy(): \Illuminate\View\View
    {
        return view('pages.privacy');
    }

    public function terms(): \Illuminate\View\View
    {
        return view('pages.terms');
    }

    public function account(): \Illuminate\View\View
    {
        $user = auth()->user();
        $addresses = $user->addresses()->orderByDesc('is_default')->orderBy('created_at')->get();
        $orders = $user->orders()->with('items')->latest()->get();
        return view('pages.account', compact('user', 'addresses', 'orders'));
    }

    public function wishlist(): \Illuminate\View\View
    {
        $user = auth()->user();
        $products = $user->wishlists()
            ->with('product.category')
            ->get()
            ->pluck('product')
            ->filter()
            ->map(fn ($p) => $p->toCardArray())
            ->values();

        return view('pages.wishlist', ['products' => $products, 'serverWishlist' => true]);
    }

    public function track(\Illuminate\Http\Request $request): \Illuminate\View\View
    {
        $number = $request->query('number');
        $order  = null;

        if ($number) {
            $order = Order::with('items')->where('number', $number)->first();
        }

        return view('pages.track', compact('order', 'number'));
    }
}
