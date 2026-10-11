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
    $gitHead = 'unknown';
    if (file_exists(base_path('.git/HEAD'))) {
        $headRef = trim(file_get_contents(base_path('.git/HEAD')));
        if (str_starts_with($headRef, 'ref: ')) {
            $refPath = base_path('.git/' . substr($headRef, 5));
            if (file_exists($refPath)) {
                $gitHead = trim(file_get_contents($refPath));
            } else {
                $gitHead = $headRef;
            }
        } else {
            $gitHead = $headRef;
        }
    }

    $disabledFunctions = ini_get('disable_functions');

    $products = \App\Models\Product::with('category')->orderBy('id')->get();
    $categories = \App\Models\Category::withCount('products')->orderBy('id')->get();
    $combos = \App\Models\Combo::with('items')->orderBy('id')->get();

    $doDelete = request()->get('action') === 'delete_demo' && request()->get('token') === 'mv_clean_2026';
    $deletedCount = 0;
    if ($doDelete) {
        $idsToDelete = request()->get('ids');
        if ($idsToDelete === 'all_seeders') {
            // Delete demo products created by seeders (CatalogSeeder IDs 1-35 and GroceryDemoSeeder IDs 36-75)
            // But let's only delete if confirmed
            $deletedCount = \App\Models\Product::where(function($q) {
                // Products from GroceryDemoSeeder have image starting with products/ or null with seeder names
                $q->where('image', 'like', 'products/%')
                  ->orWhereNull('image');
            })->where('id', '<=', 75)->delete();

            \App\Services\Catalog::flushCache();
        } elseif (is_array($idsToDelete)) {
            $deletedCount = \App\Models\Product::whereIn('id', $idsToDelete)->delete();
            \App\Services\Catalog::flushCache();
        }
    }

    $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Catalog Manager</title>'
          . '<style>body{font-family:sans-serif;background:#0f172a;color:#f8fafc;padding:24px;font-size:13px;}'
          . 'table{width:100%;border-collapse:collapse;margin:15px 0;background:#1e293b;border-radius:8px;overflow:hidden;}'
          . 'th,td{padding:8px 12px;border:1px solid #334155;text-align:left;}th{background:#334155;color:#38bdf8;}'
          . '.demo{color:#f87171;font-weight:bold;}.user{color:#4ade80;font-weight:bold;}'
          . 'a.btn{display:inline-block;padding:8px 16px;background:#ef4444;color:#fff;text-decoration:none;border-radius:6px;font-weight:bold;margin:10px 0;}'
          . '</style></head><body>';
    $html .= "<h2>Git HEAD: <span style='color:#38bdf8;'>" . e(substr($gitHead, 0, 10)) . "</span></h2>";
    $html .= "<p>Disabled functions: " . e($disabledFunctions) . "</p>";
    if ($doDelete) {
        $html .= "<div style='padding:12px;background:#14532d;color:#4ade80;border-radius:6px;margin:10px 0;'>Deleted {$deletedCount} demo products!</div>";
    }

    $html .= "<h3>Categories (" . $categories->count() . ")</h3><table><tr><th>ID</th><th>Name</th><th>Slug</th><th>Image</th><th>Products Count</th><th>Created At</th></tr>";
    foreach ($categories as $cat) {
        $html .= "<tr><td>{$cat->id}</td><td>" . e($cat->name) . "</td><td>{$cat->slug}</td><td>" . e($cat->image) . "</td><td>{$cat->products_count}</td><td>{$cat->created_at}</td></tr>";
    }
    $html .= "</table>";

    $html .= "<h3>Combos (" . $combos->count() . ")</h3><table><tr><th>ID</th><th>Name</th><th>Slug</th><th>Price</th><th>Items</th><th>Created At</th></tr>";
    foreach ($combos as $combo) {
        $itemNames = $combo->items->pluck('product_name')->implode(', ');
        $html .= "<tr><td>{$combo->id}</td><td>" . e($combo->name) . "</td><td>{$combo->slug}</td><td>{$combo->price}</td><td>" . e($itemNames) . "</td><td>{$combo->created_at}</td></tr>";
    }
    $html .= "</table>";

    $html .= "<h3>Products (" . $products->count() . ")</h3>";
    $html .= "<a href='?action=delete_demo&token=mv_clean_2026&ids=all_seeders' class='btn' onclick='return confirm(\"Are you sure you want to delete demo products IDs 1-75?\")'>Delete Demo Products (IDs 1-75)</a>";
    $html .= "<table><tr><th>ID</th><th>Type</th><th>Name</th><th>Category</th><th>Image</th><th>Price</th><th>Created At</th></tr>";
    foreach ($products as $p) {
        $isUser = ($p->id > 75) || (str_starts_with($p->image ?? '', '01') || str_starts_with($p->image ?? '', 'products/01'));
        $typeTag = $isUser ? "<span class='user'>USER</span>" : "<span class='demo'>DEMO</span>";
        $catName = $p->category ? $p->category->name : 'None';
        $html .= "<tr><td>{$p->id}</td><td>{$typeTag}</td><td>" . e($p->name) . "</td><td>" . e($catName) . "</td><td>" . e($p->image) . "</td><td>{$p->price}</td><td>{$p->created_at}</td></tr>";
    }
    $html .= "</table></body></html>";

    return response($html);
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
