# Shuvo — Organic Grocery Storefront

A warm, organic grocery storefront built with **Laravel 11 + Blade + Alpine.js**. The design system uses custom CSS design tokens (earthy greens, honey amber, warm neutrals) with no Tailwind dependency. This repository contains the **frontend only** — product data is stored in config; the backend (auth, orders, payments, persistence) is wired in the next phase.

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

Feature tests cover all routes: home, shop, product, checkout, marketing pages (about/contact/blog/privacy/terms), account pages (login/register/account/wishlist/track), and the custom 404.
