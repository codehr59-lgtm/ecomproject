<?php

use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/category/{slug}', [CatalogController::class, 'category'])->name('category');
Route::get('/product/{slug}', [CatalogController::class, 'product'])->name('product');
Route::get('/checkout', [CatalogController::class, 'checkout'])->name('checkout');

Route::fallback(fn () => response()->view('errors.404', [], 404));
