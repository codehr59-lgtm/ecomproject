<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/shop', [CatalogController::class, 'shop'])->name('shop');
Route::get('/category/{slug}', [CatalogController::class, 'category'])->name('category'); // alias → shop view filtered
Route::get('/product/{id}', [CatalogController::class, 'product'])->whereNumber('id')->name('product');
Route::get('/checkout', [CatalogController::class, 'checkout'])->name('checkout');

// marketing / static
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'blogPost'])->name('blog.post');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// account UI shells (frontend only this phase)
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::get('/account', [PageController::class, 'account'])->name('account');
Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');
Route::get('/track', [PageController::class, 'track'])->name('track');

Route::fallback(fn () => response()->view('errors.404', [], 404));
