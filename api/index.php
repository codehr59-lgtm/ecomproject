<?php

/**
 * Vercel Serverless Function entry point for Laravel
 */

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

// Forward the request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
