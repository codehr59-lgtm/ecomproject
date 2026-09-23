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
];

foreach ($subDirs as $dir) {
    $target = $storagePath . $dir;
    if (!is_dir($target)) {
        mkdir($target, 0777, true);
    }
}

// Instruct Laravel to use /tmp for compiled views and storage
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("APP_CONFIG_CACHE={$storagePath}/framework/cache/config.php");
putenv("APP_ROUTES_CACHE={$storagePath}/framework/cache/routes-v7.php");
putenv("APP_EVENTS_CACHE={$storagePath}/framework/cache/events.php");

// Ensure timezone is valid
if (empty(getenv('APP_TIMEZONE'))) {
    putenv('APP_TIMEZONE=Asia/Dhaka');
}
date_default_timezone_set('Asia/Dhaka');

// Forward the request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
