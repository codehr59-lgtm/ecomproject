# Ghorer Bazar — Frontend

A "Ghorer Bazar"-style grocery/food e-commerce storefront built with **Laravel 11 + Blade + Tailwind CSS + Alpine.js**. This repository contains the **frontend only** — product data is placeholder; the backend (auth, orders, payments, persistence) is intended to be wired in later.

## Pages
- `/` — Homepage (hero carousel, featured categories, product carousels, promo band)
- `/category/{slug}` — Collection page (sidebar filters, sort bar, load-more grid)
- `/product/{slug}` — Single product (gallery, four-button CTA, tabs, related)
- `/checkout` — Two-column checkout (order review, addresses, payment, summary)
- Global slide-out **cart drawer** with free-gift progress (on every page)
- Custom **404** page

## Requirements
- PHP 8.2+
- Composer 2.x
- Node 18+ / npm

## Setup
```bash
composer install
npm install
cp .env.example .env        # then set APP_KEY:
php artisan key:generate
npm run build               # or: npm run dev  (for hot reload)
php artisan serve
```
Visit http://127.0.0.1:8000.

> Sessions and cache use the **file** driver (see `.env`) so **no database is required** to run the frontend.

## Where the data lives (backend swap point)
All placeholder catalog data is in **`config/products.php`** (categories, products, banners, free-gift threshold). It is accessed exclusively through **`app/Support/Catalog.php`** — a thin static accessor. To connect a real backend, replace the method bodies in `Catalog` with Eloquent queries (e.g. `Product::all()`); **no Blade view needs to change** because views only consume `Catalog`'s array output and the controller's view data.

- Routes: `routes/web.php`
- Controller: `app/Http/Controllers/CatalogController.php`
- Views: `resources/views/pages/*`, `resources/views/partials/*`, `resources/views/components/*`
- Design tokens: `tailwind.config.js` (+ CSS vars in `resources/css/app.css`)
- Cart store (client-side): `resources/js/app.js` (`Alpine.store('cart')`)

## Tests
```bash
php artisan test
```
Feature tests cover all five routed pages (home, category, product, checkout, 404).
