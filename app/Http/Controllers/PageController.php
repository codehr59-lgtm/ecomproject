<?php

namespace App\Http\Controllers;

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

    public function login(): \Illuminate\View\View
    {
        return view('pages.login');
    }

    public function register(): \Illuminate\View\View
    {
        return view('pages.register');
    }

    public function account(): \Illuminate\View\View
    {
        return view('pages.account');
    }

    public function wishlist(): \Illuminate\View\View
    {
        return view('pages.wishlist');
    }

    public function track(): \Illuminate\View\View
    {
        return view('pages.track');
    }
}
