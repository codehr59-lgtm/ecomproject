<?php

/**
 * Vercel Serverless Function entry point for Laravel
 */

// Instantly serve static public storage assets without booting Laravel
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));
if (str_starts_with($uri, '/storage/')) {
    $relativePath = substr($uri, strlen('/storage/'));
    $filePath = __DIR__ . '/../storage/app/public/' . $relativePath;
    if (file_exists($filePath) && is_file($filePath)) {
        $mimes = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'svg'  => 'image/svg+xml',
            'ico'  => 'image/x-icon',
        ];
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $contentType = $mimes[$ext] ?? 'application/octet-stream';
        header("Content-Type: {$contentType}");
        header("Cache-Control: public, max-age=31536000, immutable");
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
        exit;
    }
}

// Create temporary directories in /tmp for Laravel's writable storage
$storagePath = '/tmp/storage';
$subDirs = [
    '/framework/views',
    '/framework/cache/data',
    '/framework/sessions',
    '/logs',
    '/app/public',
    '/bootstrap/cache',
];

foreach ($subDirs as $dir) {
    $target = $storagePath . $dir;
    if (!is_dir($target)) {
        @mkdir($target, 0777, true);
    }
}

// Instruct Laravel on cache and storage paths
putenv("VERCEL=1");
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';

// Enforce HTTPS
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = '443';
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
putenv("APP_URL=https://ecomproject-code-hr.vercel.app");
putenv("ASSET_URL=https://ecomproject-code-hr.vercel.app");
$_ENV['APP_URL'] = 'https://ecomproject-code-hr.vercel.app';
$_ENV['ASSET_URL'] = 'https://ecomproject-code-hr.vercel.app';
$_SERVER['APP_URL'] = 'https://ecomproject-code-hr.vercel.app';
$_SERVER['ASSET_URL'] = 'https://ecomproject-code-hr.vercel.app';

putenv("LOG_CHANNEL=stderr");
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';

putenv("APP_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
putenv("APP_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");
putenv("APP_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes-v7.php");
putenv("APP_EVENTS_CACHE={$storagePath}/bootstrap/cache/events.php");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

$_ENV['APP_PACKAGES_CACHE'] = "{$storagePath}/bootstrap/cache/packages.php";
$_ENV['APP_SERVICES_CACHE'] = "{$storagePath}/bootstrap/cache/services.php";
$_ENV['APP_ROUTES_CACHE'] = "{$storagePath}/bootstrap/cache/routes-v7.php";
$_ENV['APP_EVENTS_CACHE'] = "{$storagePath}/bootstrap/cache/events.php";
$_ENV['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";

$_SERVER['APP_PACKAGES_CACHE'] = "{$storagePath}/bootstrap/cache/packages.php";
$_SERVER['APP_SERVICES_CACHE'] = "{$storagePath}/bootstrap/cache/services.php";
$_SERVER['APP_ROUTES_CACHE'] = "{$storagePath}/bootstrap/cache/routes-v7.php";
$_SERVER['APP_EVENTS_CACHE'] = "{$storagePath}/bootstrap/cache/events.php";
$_SERVER['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";

// Ensure timezone is valid
if (empty(getenv('APP_TIMEZONE'))) {
    putenv('APP_TIMEZONE=Asia/Dhaka');
}
date_default_timezone_set('Asia/Dhaka');

// Enforce valid non-empty drivers for serverless execution
$defaultDrivers = [
    'SESSION_DRIVER' => 'database',
    'SESSION_LIFETIME' => '120',
    'SESSION_EXPIRE_ON_CLOSE' => 'false',
    'SESSION_SECURE_COOKIE' => 'true',
    'CACHE_STORE' => 'file',
    'APP_MAINTENANCE_DRIVER' => 'file',
    'QUEUE_CONNECTION' => 'sync',
    'BROADCAST_CONNECTION' => 'log',
    'FILESYSTEM_DISK' => 'local',
    'LOG_CHANNEL' => 'stderr',
    'APP_ENV' => 'production',
];

// Always enforce positive session lifetime & valid bcrypt rounds
putenv("SESSION_LIFETIME=120");
$_ENV['SESSION_LIFETIME'] = '120';
$_SERVER['SESSION_LIFETIME'] = '120';

putenv("BCRYPT_ROUNDS=12");
$_ENV['BCRYPT_ROUNDS'] = '12';
$_SERVER['BCRYPT_ROUNDS'] = '12';

foreach ($defaultDrivers as $envKey => $defaultVal) {
    if (empty(getenv($envKey))) {
        putenv("{$envKey}={$defaultVal}");
        $_ENV[$envKey] = $defaultVal;
        $_SERVER[$envKey] = $defaultVal;
    }
}

// Forward the request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
