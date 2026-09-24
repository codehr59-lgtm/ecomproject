<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

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

Route::get('/debug-auth', function () {
    try {
        $algos = password_algos();
        $hash = password_hash('password', PASSWORD_BCRYPT);
        $user = \App\Models\User::where('email', 'admin@shuvo.com')->first();
        $check = password_verify('password', $user->password);
        $hasherCheck = \Illuminate\Support\Facades\Hash::check('password', $user->password);
        $needsRehash = \Illuminate\Support\Facades\Hash::needsRehash($user->password);
        $make = \Illuminate\Support\Facades\Hash::make('password');
        return response()->json([
            'algos' => $algos,
            'hash_sample' => $hash,
            'verify' => $check,
            'hasherCheck' => $hasherCheck,
            'needsRehash' => $needsRehash,
            'make' => $make,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'error' => get_class($e),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

Route::fallback(fn () => response()->view('errors.404', [], 404));
