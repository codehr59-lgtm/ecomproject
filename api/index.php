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
        @mkdir($target, 0777, true);
    }
}

// Instruct Laravel that it is running on Vercel
putenv("VERCEL=1");
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';
putenv("LOG_CHANNEL=stderr");
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';

// Ensure timezone is valid
if (empty(getenv('APP_TIMEZONE'))) {
    putenv('APP_TIMEZONE=Asia/Dhaka');
}
date_default_timezone_set('Asia/Dhaka');

// Forward the request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
