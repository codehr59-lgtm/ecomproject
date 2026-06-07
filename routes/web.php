<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WishlistController;
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

// public auth pages (served by Fortify view callbacks; GET routes handled by Fortify)
Route::get('/track', [PageController::class, 'track'])->name('track');

// auth-required pages
Route::middleware('auth')->group(function () {
    Route::get('/account', [PageController::class, 'account'])->name('account');
    Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');

    // Addresses CRUD
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');

    // Wishlist toggle (JSON)
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

Route::fallback(fn () => response()->view('errors.404', [], 404));
