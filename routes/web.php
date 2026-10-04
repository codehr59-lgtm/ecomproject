<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/setup-now', function () {
    try {
        $log = [];

        if (request()->has('pull')) {
            $gitOutput = @shell_exec('git pull origin main 2>&1');
            $log[] = "<h3>0. Git Pull:</h3><pre>" . e($gitOutput ?: 'No output or shell_exec disabled') . "</pre>";
        }
        
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $log[] = "<h3>1. Migrations:</h3><pre>" . e(\Illuminate\Support\Facades\Artisan::output()) . "</pre>";
        
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $log[] = "<h3>2. Seeders:</h3><pre>" . e(\Illuminate\Support\Facades\Artisan::output()) . "</pre>";

        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            $log[] = "<h3>3. Storage Link:</h3><pre>" . e(\Illuminate\Support\Facades\Artisan::output()) . "</pre>";
        } catch (\Throwable $e) {
            $log[] = "<h3>3. Storage Link:</h3><pre>" . e($e->getMessage()) . "</pre>";
        }

        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $log[] = "<h3>4. Cache Clear:</h3><pre>" . e(\Illuminate\Support\Facades\Artisan::output()) . "</pre>";

        return response('<html><body style="font-family:sans-serif;padding:30px;background:#0f172a;color:#f8fafc;">'
            . '<h1 style="color:#22c55e;">🎉 Setup Completed Successfully!</h1>'
            . implode('', $log)
            . '<div style="margin-top:20px;"><a href="/" style="background:#22c55e;color:#fff;padding:10px 20px;text-decoration:none;border-radius:6px;font-weight:bold;">Go to Homepage &rarr;</a></div>'
            . '</body></html>');
    } catch (\Throwable $e) {
        return response('<html><body style="font-family:sans-serif;padding:30px;background:#0f172a;color:#f8fafc;">'
            . '<h1 style="color:#ef4444;">❌ Setup Error</h1>'
            . '<p>' . e($e->getMessage()) . '</p>'
            . '<pre style="background:#1e293b;padding:15px;border-radius:6px;overflow:auto;">' . e($e->getTraceAsString()) . '</pre>'
            . '</body></html>', 500);
    }
});

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/shop', [CatalogController::class, 'shop'])->name('shop');
Route::get('/category/{slug}', [CatalogController::class, 'category'])->name('category'); // alias → shop view filtered
Route::get('/product/{id}', [CatalogController::class, 'product'])->whereNumber('id')->name('product');
Route::get('/combos', [CatalogController::class, 'combos'])->name('combos.index');
Route::get('/combo/{slug}', [CatalogController::class, 'combo'])->name('combo.show');
Route::get('/checkout', [CatalogController::class, 'checkout'])->name('checkout');

// Order placement (POST) + confirmation + invoice
Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{number}/confirmation', [OrderController::class, 'confirmation'])->name('order.confirmation');
Route::get('/order/{number}/invoice', [OrderController::class, 'invoice'])->name('order.invoice');
Route::get('/order/{number}/packing-slip', [OrderController::class, 'packingSlip'])->name('order.packing-slip');
Route::get('/order/{number}/shipping-label', [OrderController::class, 'shippingLabel'])->name('order.shipping-label');

// Payment gateway entry point + callbacks
Route::get('/payment/{number}', [PaymentController::class, 'start'])->name('payment.start');

// SSLCommerz callbacks (POST from external — CSRF excluded in bootstrap/app.php)
Route::post('/payment/sslcommerz/success', [PaymentController::class, 'sslSuccess'])->name('payment.sslcommerz.success');
Route::post('/payment/sslcommerz/fail',    [PaymentController::class, 'sslFail'])->name('payment.sslcommerz.fail');
Route::post('/payment/sslcommerz/cancel',  [PaymentController::class, 'sslCancel'])->name('payment.sslcommerz.cancel');
Route::post('/payment/sslcommerz/ipn',     [PaymentController::class, 'sslIpn'])->name('payment.sslcommerz.ipn');

// bKash callback (GET from external)
Route::get('/payment/bkash/callback', [PaymentController::class, 'bkashCallback'])->name('payment.bkash.callback');

// Nagad callback (GET from external)
Route::get('/payment/nagad/callback', [PaymentController::class, 'nagadCallback'])->name('payment.nagad.callback');

// Rocket callbacks (POST from external — CSRF excluded in bootstrap/app.php)
Route::post('/payment/rocket/callback', [PaymentController::class, 'rocketCallback'])->name('payment.rocket.callback');

// marketing / static
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
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

// Admin notifications (JSON)
Route::middleware('auth')->get('/api/admin/notifications', function () {
    if (! auth()->user()->is_admin) {
        return response()->json(['count' => 0, 'orders' => []]);
    }
    $recent = \App\Models\Order::orderByDesc('created_at')
        ->take(8)
        ->get(['id', 'number', 'customer_name', 'total', 'status', 'created_at']);

    $newCount = \App\Models\Order::where('status', 'pending')->count();

    return response()->json([
        'count'  => $newCount,
        'orders' => $recent->map(fn ($o) => [
            'id'     => $o->id,
            'number' => $o->number,
            'name'   => $o->customer_name,
            'total'  => $o->total,
            'status' => $o->status,
            'time'   => $o->created_at->diffForHumans(),
        ]),
    ]);
})->name('admin.notifications');

// CMS dynamic pages (must be after all other routes)
Route::get('/page/{slug}', [PageController::class, 'cmsPage'])->name('page.show');

// Fallback direct storage file server (in case public/storage symlink is missing on production/shared hosting)
Route::get('/storage/{path}', function (string $path) {
    $cleanPath = str_replace(['../', '..\\'], '', $path);
    $filePath = storage_path('app/public/' . $cleanPath);

    if (! file_exists($filePath) || is_dir($filePath)) {
        $altPath = storage_path('app/' . $cleanPath);
        if (file_exists($altPath) && ! is_dir($altPath)) {
            $filePath = $altPath;
        } else {
            abort(404);
        }
    }

    $mime = mime_content_type($filePath) ?: 'application/octet-stream';

    return response()->file($filePath, [
        'Content-Type'  => $mime,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.fallback');

Route::fallback(fn () => response()->view('errors.404', [], 404));
