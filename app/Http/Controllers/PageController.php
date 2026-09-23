<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Order;
use App\Models\Page;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function cmsPage(string $slug): \Illuminate\View\View
    {
        $page = Page::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('pages.cms-page', compact('page'));
    }

    public function faq(): \Illuminate\View\View
    {
        $faqs = Faq::active()->get()->groupBy('category');
        return view('pages.faq', compact('faqs'));
    }

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
        $posts = BlogPost::where('is_published', true)
            ->orderByDesc('published_at')
            ->get();
        return view('pages.blog', compact('posts'));
    }

    public function blogPost(string $slug): \Illuminate\View\View
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('pages.blog-post', compact('post'));
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
            $order = Order::with(['items.product', 'statusHistories'])->where('number', $number)->first();
        }

        return view('pages.track', compact('order', 'number'));
    }
}
