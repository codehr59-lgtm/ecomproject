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
            if (function_exists('shell_exec')) {
                $gitOutput = @shell_exec('git pull origin main 2>&1');
                $log[] = "<h3>0. Git Pull:</h3><pre>" . e($gitOutput ?: 'No output') . "</pre>";
            } else {
                $log[] = "<h3>0. Git Pull:</h3><p>shell_exec is disabled on this server. Please use Hostinger Git / Deploy.</p>";
            }
        }
        
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $log[] = "<h3>1. Migrations:</h3><pre>" . e(\Illuminate\Support\Facades\Artisan::output()) . "</pre>";

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

Route::get('/cleanup-demo-products', function() {
    $allProducts = \App\Models\Product::with(['category', 'brand'])->orderBy('id')->get();
    
    // Demo images from GroceryDemoSeeder & config/products.php
    $demoImageNames = [
        'ajwa_dates', 'black_cumin', 'black_seed', 'brown_eggs', 'california_almonds',
        'chepa_shutki', 'chhola_boot', 'chia_seeds', 'chili_powder', 'chinigura_rice',
        'coriander_powder', 'cow_milk', 'cream_butter', 'ctc_black_tea', 'fresh_garlic',
        'fresh_ginger', 'gawa_ghee', 'ghani_mustard', 'green_apples', 'himsagar_mango',
        'loitta_shutki', 'lychee_honey', 'medjool_dates', 'moong_dal', 'natural_honeycomb',
        'nazirshail_rice', 'pumpkin_seeds', 'red_onions', 'safawi_dates', 'shahi_garam',
        'sukkari_dates', 'sundarban_honey', 'sylhet_green', 'turmeric_powder', 'virgin_coconut',
        'whole_jeera', 'test_honey'
    ];
    
    $demoConfigSlugs = [
        'sundarban-wild-honey-1-kg', 'black-seed-honey-500-g', 'lychee-flower-honey-500-g',
        'mustard-flower-honey-500-g', 'berry-flower-honey-500-g', 'ajwa-dates-500-g',
        'medjool-dates-500-g', 'amber-dates-500-g', 'sukkari-dates-500-g', 'mabroom-dates-500-g',
        'cold-pressed-mustard-oil-1-l', 'wood-pressed-coconut-oil-500-ml', 'deshi-ghee-bati-500-g',
        'black-seed-oil-100-ml', 'extra-virgin-olive-oil-500-ml', 'turmeric-powder-200-g',
        'chili-powder-200-g', 'coriander-powder-200-g', 'cumin-powder-200-g', 'garam-masala-blend-100-g',
        'walnuts-akhrot-250-g', 'almonds-badam-250-g', 'cashews-kaju-250-g', 'pistachios-pesta-200-g',
        'chia-seeds-250-g', 'kalijira-polao-rice-1-kg', 'nazirshail-rice-5-kg', 'miniket-rice-5-kg',
        'chinigura-aromatic-rice-1-kg', 'red-rice-lal-chal-2-kg', 'himsagar-mango-5-kg',
        'langra-mango-5-kg', 'amrapali-mango-5-kg', 'fazli-mango-5-kg', 'haribhanga-mango-5-kg',
        'sreemangal-ctc-black-tea-400-g', 'panchagarh-green-tea-100-g', 'organic-masala-chai-250-g',
        'roasted-arabica-coffee-200-g'
    ];

    $demoList = [];
    $realList = [];

    foreach ($allProducts as $p) {
        $img = (string) ($p->image ?? '');
        $isDemo = false;

        // Check if image matches demo pattern
        foreach ($demoImageNames as $dName) {
            if (str_contains($img, $dName)) {
                $isDemo = true;
                break;
            }
        }

        // Check if slug is from CatalogSeeder config with no image
        if (empty($img) && in_array($p->slug, $demoConfigSlugs, true)) {
            $isDemo = true;
        }

        if ($isDemo) {
            $demoList[] = $p;
        } else {
            $realList[] = $p;
        }
    }

    $doDelete = request()->has('confirm');
    $deletedCount = 0;

    if ($doDelete) {
        foreach ($demoList as $dp) {
            // Detach / delete variations and reviews if any
            $dp->variations()->delete();
            $dp->productReviews()->delete();
            $dp->images()->delete();
            $dp->delete();
            $deletedCount++;
        }
        \App\Support\Catalog::flushCache();
    }

    return response()->json([
        'status'         => $doDelete ? 'deleted' : 'preview',
        'deleted_count'  => $deletedCount,
        'demo_count'     => count($demoList),
        'demo_products'  => collect($demoList)->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'image' => $p->image, 'slug' => $p->slug]),
        'real_count'     => count($realList),
        'real_products'  => collect($realList)->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'image' => $p->image, 'slug' => $p->slug]),
    ]);
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
