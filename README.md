# Shuvo — Organic Grocery Storefront

A warm, organic grocery storefront built with **Laravel 11 + Blade + Alpine.js + Filament v3 admin**. Full backend: auth (Fortify), real orders/invoices, DB-backed catalog, admin panel, security hardening, daily DB backups.

## Pages & Routes

| Route | Name | Description |
|---|---|---|
| `/` | `home` | Homepage — hero, featured categories, product rails, combo deals, promo band |
| `/shop` | `shop` | Shop listing — category sidebar, product grid, filters |
| `/shop?cat={slug}` | — | Shop filtered to a category (e.g. `?cat=honey`) |
| `/category/{slug}` | `category` | Category alias → resolves to shop filtered view |
| `/product/{id}` | `product` | Single product detail — gallery, add to cart, related products |
| `/checkout` | `checkout` | Checkout — delivery details, payment method, order summary |
| `/about` | `about` | Our story / brand page |
| `/contact` | `contact` | Contact form |
| `/blog` | `blog` | Blog / journal index |
| `/blog/{slug}` | `blog.post` | Single blog post |
| `/privacy` | `privacy` | Privacy policy |
| `/terms` | `terms` | Terms & conditions |
| `/login` | `login` | Login shell (frontend only) |
| `/register` | `register` | Register shell (frontend only) |
| `/account` | `account` | Account dashboard shell |
| `/wishlist` | `wishlist` | Wishlist page |
| `/track` | `track` | Order tracking page |
| `*` (fallback) | — | Custom 404 — "Page not found" |

## Design System

The design system lives in **`resources/css/app.css`** (ported from the Shuvo design bundle). It defines:
- CSS custom properties (design tokens) in `:root` — `--green`, `--green-deep`, `--honey`, `--ink`, `--muted`, etc.
- Utility classes — `.btn`, `.btn-primary`, `.btn-ghost`, `.btn-honey`, `.badge`, `.pcard`, `.wrap`, `.section`, `.eyebrow`, etc.
- No Tailwind — pure custom CSS with the **Bricolage Grotesque** (display) and **Hanken Grotesk** (body) typefaces from Google Fonts.

## Architecture

- **Routes:** `routes/web.php`
- **Controllers:** `app/Http/Controllers/CatalogController.php`, `app/Http/Controllers/PageController.php`
- **Views:** `resources/views/pages/*`, `resources/views/partials/*`, `resources/views/components/*`, `resources/views/layouts/app.blade.php`
- **Design tokens & styles:** `resources/css/app.css`
- **Cart / wishlist store (client-side Alpine.js):** `resources/js/app.js` — `$store.shop`
- **Catalog data (backend swap point):** `config/products.php` accessed exclusively via `App\Support\Catalog` — replace method bodies with Eloquent queries to connect a real database; no Blade views need to change.

## Requirements

- PHP 8.2+
- Composer 2.x
- Node 18+ / npm

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run build
php artisan serve
```

Visit http://127.0.0.1:8000.

> Sessions and cache use the **file** driver so **no database is required** to run the frontend.

## Backend Phase (planned)

The next phase wires in:
- **MySQL** for products, orders, users, wishlists
- **Filament v3** admin panel for catalog management
- Real auth (Laravel Breeze or custom) replacing the current frontend shells

## Tests

```bash
php artisan test
```

Feature tests cover: all routes, order placement, price integrity, authorization (order invoice/confirmation ownership), security headers, and the custom 404.

---

## Deployment

### 1. Environment

```bash
cp .env.example .env
# Edit .env: set APP_ENV=production, APP_DEBUG=false, APP_URL, DB_*, MAIL_*, payment/courier creds
```

### 2. Database (MySQL)

Create a `shuvo` database, then update `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shuvo
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

### 3. Install & build

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan key:generate
php artisan migrate --force
php artisan db:seed --class=CatalogSeeder
php artisan db:seed --class=AdminSeeder
```

### 4. Storage & cache

```bash
php artisan storage:link          # public/storage -> storage/app/public
php artisan optimize              # caches routes + config for production
```

> Note: do NOT run `php artisan config:cache` in local dev — it bakes `.env` values and masks changes.

### 5. Web server

Point your Nginx/Apache document root to `public/`. Example Nginx server block:

```nginx
root /var/www/shuvo/public;
index index.php;
location / { try_files $uri $uri/ /index.php?$query_string; }
location ~ \.php$ { fastcgi_pass unix:/run/php/php8.2-fpm.sock; include fastcgi_params; }
```

### 6. Scheduler (cron)

Add to crontab (`crontab -e`):

```cron
* * * * * cd /var/www/shuvo && php artisan schedule:run >> /dev/null 2>&1
```

The scheduler runs:
- `backup:clean` daily at 01:00 — removes old backups per retention policy
- `backup:run`   daily at 01:30 — creates DB + files backup to `storage/app/shuvo/`

### 7. Queue worker (if using queued jobs/notifications)

```bash
php artisan queue:work --daemon --sleep=3 --tries=3
```

Use Supervisor to keep the worker alive in production.

### 8. Admin login

Default admin credentials are created by `AdminSeeder`. Change the password after first login at `/admin`.

### 9. Pending phase configuration

| Phase | What to configure |
|-------|-------------------|
| B5 — Payments | `SSLCOMMERZ_*`, `BKASH_*` in `.env` |
| B6 — Courier  | `PATHAO_*`, `STEADFAST_*` in `.env` |
| B7 — Analytics | `FB_PIXEL_ID`, `GA_ID` in `.env` |

All placeholders are already present in `.env.example`.

### 10. Backup configuration

Backups are stored locally at `storage/app/shuvo/`. For offsite backups, configure an S3 disk in `config/filesystems.php` and add it to `config/backup.php` `destination.disks`. Set `BACKUP_ARCHIVE_PASSWORD` for encrypted archives.

Set mail credentials (`MAIL_*`) and update `config/backup.php` notification channels from `[]` to `['mail']` for email alerts on backup failure.
