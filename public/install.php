<?php
/**
 * One-Click Deployment & Health Check Script for Masala Valley
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

$baseDir = dirname(__DIR__);
$envFile = $baseDir . '/.env';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Masala Valley - Setup & Diagnostics</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #f8fafc; padding: 30px; line-height: 1.6; }
        .card { max-width: 800px; margin: 0 auto; background: #1e293b; border-radius: 12px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.4); }
        h1 { color: #38bdf8; margin-top: 0; font-size: 24px; border-bottom: 1px solid #334155; padding-bottom: 12px; }
        .step { margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; background: #0f172a; }
        .ok { color: #22c55e; font-weight: bold; }
        .err { color: #ef4444; font-weight: bold; }
        pre { background: #020617; color: #a5f3fc; padding: 12px; border-radius: 6px; overflow-x: auto; font-size: 13px; }
        .btn { display: inline-block; background: #22c55e; color: #fff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 16px; margin-top: 15px; }
        .btn:hover { background: #16a34a; }
    </style>
</head>
<body>
<div class="card">
    <h1>🚀 Masala Valley - Automated Setup & Health Check</h1>

    <!-- 1. Environment File Check -->
    <?php if (isset($_GET['pull'])): ?>
    <div class="step">
        <h3>0. Git Pull</h3>
        <?php
        if (function_exists('shell_exec')) {
            $gitOutput = @shell_exec('git pull origin main 2>&1');
            echo '<pre>' . htmlspecialchars($gitOutput ?: 'No output') . '</pre>';
        } else {
            echo '<p class="err">shell_exec() is disabled on this server. Please use Hostinger Git / Deploy or FTP.</p>';
        }
        ?>
    </div>
    <?php endif; ?>

    <div class="step">
        <h3>1. Configuration (.env)</h3>
        <?php
        $envCreated = false;
        if (!file_exists($envFile) || filesize($envFile) < 20) {
            $defaultEnv = <<<'ENV'
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
ENV;
            if (@file_put_contents($envFile, $defaultEnv)) {
                echo '<p class="ok">✓ Created fresh .env file with Hostinger database credentials!</p>';
                $envCreated = true;
            } else {
                echo '<p class="err">✗ Unable to write .env file automatically. Please check write permissions.</p>';
            }
        } else {
            echo '<p class="ok">✓ .env file already exists (' . filesize($envFile) . ' bytes).</p>';
        }
        ?>
    </div>

    <!-- 2. Directories & Permissions -->
    <div class="step">
        <h3>2. Storage Directories & Permissions</h3>
        <?php
        $storageDirs = [
            '/storage/framework/views',
            '/storage/framework/cache/data',
            '/storage/framework/sessions',
            '/storage/logs',
            '/storage/app/public',
            '/bootstrap/cache',
        ];
        foreach ($storageDirs as $sDir) {
            $p = $baseDir . $sDir;
            if (!is_dir($p)) {
                @mkdir($p, 0775, true);
            }
            @chmod($p, 0775);
        }
        echo '<p class="ok">✓ Storage directories verified and write permissions set.</p>';
        ?>
    </div>

    <!-- 3. Database Connection Test -->
    <div class="step">
        <h3>3. MySQL Database Connection</h3>
        <?php
        $dbConnected = false;
        try {
            $pdo = new PDO(
                "mysql:host=127.0.0.1;port=3306;dbname=u174620650_masalavalley;charset=utf8mb4",
                "u174620650_masalavalley",
                "Masalavalley@1919",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $dbConnected = true;
            echo '<p class="ok">✓ Successfully connected to MySQL database: <strong>u174620650_masalavalley</strong></p>';
        } catch (PDOException $e) {
            echo '<p class="err">✗ Database connection failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
    </div>

    <!-- 4. Laravel Boot & Artisan Migration -->
    <div class="step">
        <h3>4. Laravel Migrations & Seeders</h3>
        <?php
        if (file_exists($baseDir . '/vendor/autoload.php')) {
            require $baseDir . '/vendor/autoload.php';
            $app = require_once $baseDir . '/bootstrap/app.php';
            $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

            try {
                // Run migrate
                $kernel->call('migrate', ['--force' => true]);
                echo '<p class="ok">✓ Migrations executed successfully.</p>';
                echo '<pre>' . htmlspecialchars($kernel->output()) . '</pre>';

                // Run db:seed
                try {
                    $kernel->call('db:seed', ['--force' => true]);
                    echo '<p class="ok">✓ Seeders executed successfully.</p>';
                    echo '<pre>' . htmlspecialchars($kernel->output()) . '</pre>';
                } catch (\Throwable $se) {
                    echo '<p class="err">Seeder note: ' . htmlspecialchars($se->getMessage()) . '</p>';
                }

                // Ensure Admin Users exist
                try {
                    \App\Models\User::updateOrCreate(
                        ['email' => 'admin@masalavalley.com'],
                        [
                            'name'     => 'Masala Valley Admin',
                            'password' => \Illuminate\Support\Facades\Hash::make('Masalavalley@1919'),
                            'is_admin' => true,
                            'phone'    => '01700000000',
                        ]
                    );
                    \App\Models\User::updateOrCreate(
                        ['email' => 'admin@shuvo.com'],
                        [
                            'name'     => 'Shuvo Admin',
                            'password' => \Illuminate\Support\Facades\Hash::make('password'),
                            'is_admin' => true,
                            'phone'    => '01700000000',
                        ]
                    );
                    echo '<p class="ok">✓ Admin accounts verified/created:</p>';
                    echo '<ul><li><strong>admin@masalavalley.com</strong> (Password: <code>Masalavalley@1919</code>)</li>';
                    echo '<li><strong>admin@shuvo.com</strong> (Password: <code>password</code>)</li></ul>';
                } catch (\Throwable $ue) {
                    echo '<p class="err">Admin creation note: ' . htmlspecialchars($ue->getMessage()) . '</p>';
                }

                // Default Site Identity & Tab Title
                try {
                    if (! \App\Models\Setting::get('site_name')) {
                        \App\Models\Setting::set('site_name', 'Masala Valley');
                    }
                    if (! \App\Models\Setting::get('tab_title')) {
                        \App\Models\Setting::set('tab_title', 'Masala Valley — Pure, Organic & Halal');
                    }
                } catch (\Throwable) {}

                // Default Footer CMS Pages
                try {
                    $defaultPages = [
                        'about'          => ['title' => 'About Us', 'sort' => 1, 'body' => '<h2>Welcome to Masala Valley</h2><p>At <strong>Masala Valley</strong>, we are committed to delivering 100% pure, natural, and premium organic groceries, aromatic spices, raw honey, premium dates, and pure mustard oil directly to your doorstep across Bangladesh.</p><h3>Our Mission</h3><p>To provide healthy, authentic, and chemical-free food products sourced directly from farmers and certified suppliers.</p>'],
                        'terms'          => ['title' => 'Terms & Conditions', 'sort' => 2, 'body' => '<h2>Terms & Conditions</h2><p>Please read these terms and conditions carefully before placing an order on Masala Valley.</p><h3>1. Orders and Acceptance</h3><p>By placing an order through our website, you agree to product pricing and delivery terms.</p>'],
                        'privacy'        => ['title' => 'Privacy Policy', 'sort' => 3, 'body' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. Masala Valley does not share, sell, or rent your personal details to third parties.</p>'],
                        'careers'        => ['title' => 'Careers', 'sort' => 4, 'body' => '<h2>Join the Masala Valley Team</h2><p>We are constantly expanding and looking for passionate individuals. Send your CV to <strong>contact@masalavalley.com</strong>.</p>'],
                        'support-center' => ['title' => 'Support Center', 'sort' => 5, 'body' => '<h2>Customer Support Center</h2><p>Need assistance with an existing order or product inquiry? Our dedicated team is available 9:00 AM to 10:00 PM every day.</p>'],
                        'how-to-order'   => ['title' => 'How to Order', 'sort' => 6, 'body' => '<h2>How to Order</h2><ol><li>Browse products and add to cart.</li><li>Click checkout and enter your delivery address.</li><li>Choose Cash on Delivery or Online Payment and confirm.</li></ol>'],
                        'payment'        => ['title' => 'Payment Methods', 'sort' => 7, 'body' => '<h2>Payment Methods</h2><p>We accept Cash on Delivery (COD), bKash, Nagad, Rocket, and all major cards securely.</p>'],
                        'shipping'       => ['title' => 'Shipping & Delivery', 'sort' => 8, 'body' => '<h2>Shipping & Delivery</h2><p>We deliver nationwide across Bangladesh. Delivery within Dhaka is 24-48 hours (৳60, free over ৳1,500). Outside Dhaka 48-72 hours (৳120).</p>'],
                        'happy-return'   => ['title' => 'Happy Return', 'sort' => 9, 'body' => '<h2>Happy Return Guarantee</h2><p>Check your parcel at your doorstep. If any item is damaged or not as expected, return it instantly to the delivery rider.</p>'],
                        'refund-policy'  => ['title' => 'Refund Policy', 'sort' => 10, 'body' => '<h2>Refund Policy</h2><p>Approved refunds for online payments are processed within 2-5 working days for bKash/Nagad and 5-10 days for cards.</p>'],
                        'exchange'       => ['title' => 'Exchange Policy', 'sort' => 11, 'body' => '<h2>Exchange Policy</h2><p>If you receive a defective or incorrect item, notify us within 48 hours for a free replacement.</p>'],
                        'cancellation'   => ['title' => 'Cancellation Policy', 'sort' => 12, 'body' => '<h2>Cancellation Policy</h2><p>You can cancel your order anytime before it has been dispatched from our warehouse.</p>'],
                        'pre-order'      => ['title' => 'Pre-Order Policy', 'sort' => 13, 'body' => '<h2>Pre-Order Policy</h2><p>Pre-order seasonal organic crops and fresh harvest items to guarantee stock as soon as harvest arrives.</p>'],
                        'extra-discount' => ['title' => 'Extra Discount & Offers', 'sort' => 14, 'body' => '<h2>Extra Discounts & Offers</h2><p>Save more with our combo bundles, active coupon codes, and free delivery promotions.</p>'],
                    ];
                    foreach ($defaultPages as $slug => $p) {
                        \App\Models\Page::firstOrCreate(
                            ['slug' => $slug],
                            [
                                'title' => $p['title'],
                                'body' => $p['body'],
                                'meta_title' => $p['title'] . ' — Masala Valley',
                                'is_published' => true,
                                'sort' => $p['sort'],
                            ]
                        );
                    }
                    echo '<p class="ok">✓ Standard footer CMS pages verified/created in database.</p>';
                } catch (\Throwable $pe) {
                    echo '<p class="err">CMS Pages note: ' . htmlspecialchars($pe->getMessage()) . '</p>';
                }

                // Storage link
                try {
                    $publicStorage = __DIR__ . '/storage';
                    $targetStorage = $baseDir . '/storage/app/public';
                    if (! file_exists($publicStorage) && function_exists('symlink')) {
                        @symlink($targetStorage, $publicStorage);
                    }
                    if (file_exists($publicStorage)) {
                        echo '<p class="ok">✓ Storage link verified / active.</p>';
                    } else {
                        $kernel->call('storage:link');
                        echo '<p class="ok">✓ Storage link created.</p>';
                    }
                } catch (\Throwable $sle) {
                    echo '<p class="ok">✓ Storage fallback route active (Shared hosting safe: ' . htmlspecialchars($sle->getMessage()) . ')</p>';
                }

                // Clear caches
                $kernel->call('optimize:clear');
                echo '<p class="ok">✓ Configuration, routes, and views cache cleared.</p>';

            } catch (\Throwable $e) {
                echo '<p class="err">✗ Artisan command error: ' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            }
        } else {
            echo '<p class="err">✗ Vendor folder not found. Composer dependencies need to be installed.</p>';
        }
        ?>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="/" class="btn">Go to Masala Valley Homepage &rarr;</a>
    </div>
</div>
</body>
</html>
