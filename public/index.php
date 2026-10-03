<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Auto-create .env with Hostinger credentials if not present or empty
$baseDir = dirname(__DIR__);
$envFile = $baseDir . '/.env';
if (!file_exists($envFile) || filesize($envFile) < 20) {
    $envContent = <<<'ENV'
APP_NAME="Masala Valley"
APP_ENV=production
APP_KEY=base64:5N28+WmxmUCPos7oTo4eneqQzfT+/7gYVheB/ap3E3w=
APP_DEBUG=true
APP_TIMEZONE=Asia/Dhaka
APP_URL=https://masalavalley.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u174620650_masalavalley
DB_USERNAME=u174620650_masalavalley
DB_PASSWORD="Masalavalley@1919"

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@masalavalley.com
MAIL_PASSWORD=
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@masalavalley.com
MAIL_FROM_NAME="Masala Valley"
ENV;
    @file_put_contents($envFile, $envContent);
}

// Auto-create required writable storage subdirectories
foreach ([
    '/storage/framework/views',
    '/storage/framework/cache/data',
    '/storage/framework/sessions',
    '/storage/logs',
    '/storage/app/public',
    '/bootstrap/cache',
] as $dir) {
    $fullPath = $baseDir . $dir;
    if (!is_dir($fullPath)) {
        @mkdir($fullPath, 0775, true);
    }
}

// Attempt to ensure public/storage symlink exists
if (!file_exists(__DIR__ . '/storage') && function_exists('symlink')) {
    @symlink($baseDir . '/storage/app/public', __DIR__ . '/storage');
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
