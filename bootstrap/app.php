<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Exclude payment gateway callbacks from CSRF — they come from external servers
        $middleware->validateCsrfTokens(except: [
            'payment/sslcommerz/success',
            'payment/sslcommerz/fail',
            'payment/sslcommerz/cancel',
            'payment/sslcommerz/ipn',
            'payment/rocket/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

if (isset($_ENV['VERCEL']) || env('VERCEL') || isset($_SERVER['VERCEL']) || is_dir('/tmp/storage')) {
    $app->useStoragePath('/tmp/storage');
}

return $app;
