<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ── cPanel: point to project folder outside public_html ──
$projectPath = __DIR__ . '/../ecom-shuvo';

if (file_exists($maintenance = $projectPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $projectPath . '/vendor/autoload.php';

(require_once $projectPath . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
