# Shuvo Backend — Phased Implementation Plan

> Backend for the Shuvo storefront, implementing `laravel website.pdf`. Dev DB =
> **SQLite** (portable migrations); production target = **MySQL** (`.env.example`).
> Admin = **Filament v3**. Each phase is a spec→build→verify chunk; checkpoint with the
> user between phases.

**Prereqs done:** PHP extensions enabled (pdo_mysql, pdo_sqlite, gd, intl, mysqli,
sqlite3). `.env` → sqlite, `database/database.sqlite` created, base Laravel migrations run.

**Golden rule:** The storefront frontend (Blade/Alpine, branch `feature/frontend`) must
keep working at every step. The seam is `App\Support\Catalog` — once the DB + models
exist, Catalog's methods switch from `config/products.php` to Eloquent and the views are
untouched.

---

## Phase B1 — Data model + migrations + models + seed + Catalog→Eloquent
**Goal:** Real database behind the existing storefront.
- Migrations: `categories`, `brands`, `products` (slug,name,weight,price,old_price,
  category_id,brand_id,badge,rating,reviews,blurb,certified,stock,is_active,image),
  `product_variations` (product_id,label,price,stock,sku), `product_images`, `orders`
  (number,user_id,status,subtotal,delivery,discount,total,payment_method,payment_status,
  courier,address fields,notes), `order_items` (order_id,product_id,name,weight,price,qty),
  `coupons` (code,type,value,min_spend,expires_at,usage_limit,used), `banners`
  (title,subtitle,image,cta,href,position,is_active,sort), `blog_posts` (slug,title,
  excerpt,body,category,cover,published_at), `reviews` (product_id,user_id,rating,body,
  approved), `wishlists` (user_id,product_id), `addresses` (user_id,name,phone,line,
  city,thana,is_default), `settings` (key,value); extend `users` (phone, is_admin).
- Models with relationships (Category hasMany Product; Product belongsTo Category/Brand,
  hasMany Variation/Image/Review; Order hasMany Items belongsTo User; etc.). Casts, slugs.
- Seeder: import the 35 products + 8 categories + 5 brands + combos/testimonials (move
  testimonials to a config or table) from current `config/products.php` into the DB.
- **Swap `App\Support\Catalog`** methods to Eloquent queries returning the SAME array
  shape the Blade views expect (id,name,weight,price,old_price,cat,badge,rating,reviews,
  blurb,certified,count) — OR return models and adjust views minimally. Prefer keeping
  the array shape (map models → arrays) so zero view changes.
- Verify: storefront still renders identically from the DB; `php artisan test` green.

## Phase B2 — Customer auth + account
**Goal:** Real customer accounts wired to the login/register/account/wishlist/track shells.
- Laravel Breeze (Blade) for register/login/logout/password reset/profile — themed to
  Shuvo (reuse the existing shell markup; point forms at Breeze routes).
- Persistent **wishlist** (sync Alpine wishlist → DB for logged-in users), **addresses**,
  profile edit. Account dashboard shows the user's real orders.
- Auth-gate `/account`, `/wishlist` (or merge guest+user wishlist).

## Phase B3 — Admin panel (Filament v3)
**Goal:** Full admin for the catalog + content.
- Install Filament v3, admin guard, first admin user, `/admin`.
- Resources: Product (with Variation + Image RelationManagers, stock, category/brand,
  badge, active), Category, Brand, Coupon, Banner, BlogPost, Review (moderation),
  Customer (read/manage). Media uploads (gd enabled).

## Phase B4 — Cart persistence, orders, invoices
**Goal:** Real orders end-to-end.
- Checkout posts a real order (server validates cart, prices, stock; decrements stock;
  applies coupon). Order confirmation + email. Order number.
- Order history + detail + status tracking on `/account` and `/track` (real status).
- **Invoice** PDF (barryvdh/laravel-dompdf) downloadable by customer + admin.
- Filament Order resource: status workflow (pending→confirmed→shipped→delivered/cancelled)
  via table actions; sales figures.

## Phase B5 — Payments
**Goal:** Bangladesh payment methods.
- COD (immediate), and an online gateway: **SSLCommerz** (covers cards + bKash/Nagad/
  Rocket via aggregator) using sandbox creds; plus direct bKash if needed. Payment
  callback/IPN, payment_status updates, success/fail/cancel handling.

## Phase B6 — Courier
**Goal:** Dispatch + tracking.
- **Pathao** + **Steadfast** API clients (create consignment from an order, store
  tracking id, status sync). Admin action "Send to courier". Customer `/track` reflects
  courier status.

## Phase B7 — Marketing + SEO
- Coupon apply at checkout (validation, limits). Campaign **banners** (admin-managed,
  shown in the storefront hero/sections). **Email notifications** (order placed/shipped).
  **Facebook Pixel** + **Google Analytics** (settings-driven injection). Basic **SEO**:
  per-page meta titles/descriptions, Open Graph, `sitemap.xml`, `robots.txt`.

## Phase B8 — Security, performance, backup, polish
- Harden auth (rate limiting, verified email optional), CSRF (already), validation
  everywhere, authorization policies. **DB backup** (spatie/laravel-backup) scheduled.
  Caching (config/route/view + query cache where useful), image optimization. Final
  full-suite test + smoke + deployment notes (MySQL switch, queue worker, cron).

---

## Verification per phase
- `php artisan test` stays green; new feature tests per phase.
- Storefront + admin smoke-tested (routes/status, key flows).
- Checkpoint with the user after each phase before proceeding.

## Production switch (documented, not run locally)
`.env` → `DB_CONNECTION=mysql` + creds; `php artisan migrate`; build assets; queue
worker + scheduler; real gateway/courier credentials.
