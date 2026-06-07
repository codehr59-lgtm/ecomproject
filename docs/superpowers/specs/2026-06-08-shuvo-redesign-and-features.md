# Shuvo — Redesign + Full Feature Spec

**Date:** 2026-06-08
**Status:** Approved (scope), supersedes the original Ghorer-Bazar `design.md` spec
**Design source:** `_design-reference/` (Claude Design handoff "Shuvo — Organic Grocery").
Authoritative for visuals: `_design-reference/styles.css`, `data.js`, `components.jsx`,
`app.jsx`, `pages-home.jsx`, `pages-shop.jsx`, `pages-checkout.jsx`, `_shots/*.png`,
`chats/chat1.md`.

## 1. Why

The v1 Tailwind/Ghorer-Bazar frontend was rejected by the client. We replace the
**entire visual layer** with the "Shuvo" organic-grocery design while keeping the
Laravel plumbing (routes, the `App\Support\Catalog` swap-point, controller pattern,
Alpine cart). Then we build the **backend** per `laravel website.pdf`.

## 2. Design System (port `_design-reference/styles.css` verbatim)

- **Approach:** Use the handoff's `styles.css` as the design system — copy it to
  `resources/css/app.css` (drop `@tailwind` directives; the design is semantic-class
  based, not utility-based). Keep Vite. Tailwind may remain installed but is unused.
- **Fonts:** Bricolage Grotesque (display) + Hanken Grotesk (body), via `<link>`.
- **Tokens (CSS vars):** bg `#F6F6F2`, surface `#FFFFFF`, ink `#1E2A22`,
  green `#356B3E` / green-deep `#25492C`, honey `#C2872A`, sale `#BC3F2C`,
  **orange CTA `#E47C18`**, nav (dark green) `#143A2C`. Radii 8/14/22/pill. Shadows s/m/l.
- **Signature components (class names to reuse):** `.announce`, `.hdr`/`.nav`/`.nav-all`,
  `.hbanner`/`.hero-split`/`.hero-dots`, `.fcat`/`.fcat-row`, `.pcard` (+ `.save-pill`,
  `.ribbon-best`, `.add-btn`, `.buy-btn`, `.badge-*`, `.pcard-wish`), `.top-card`/`.top-grid`,
  `.brand-card`/`.brands-row`, `.combo-band`/`.combo-card`, `.img-band`, `.rail`/`.rail-head`,
  `.center-title`, `.drawer`/`.gift-bar`/`.cart-line`, `.shop-layout`/`.filters`,
  `.pdp`/`.weight-opt`/`.pdp-trust`, `.checkout`/`.co-step`/`.pay-opt`/`.co-summary`/`.success`,
  `.toast`, `.ftr-light`/`.app-badge`/`.pay-chip`.

## 3. Client-side behaviour (port from `app.jsx`/`components.jsx` to Alpine)

Single Alpine store (`Alpine.store('shop')`) replacing the v1 cart store:
- **Cart:** `items[]` of `{id,name,weight,price,cat,qty}`; `add(p)` (dedup by id, +qty,
  fires toast), `changeQty(id,delta)` (floor 1), `remove(id)`, `count`, `subtotal`.
- **Wishlist:** `wish[]` of ids; `toggleWish(id)`; `wishCount`.
- **Thresholds:** `FREE_GIFT_THRESHOLD = 3000`, `FREE_SHIP_THRESHOLD = 1500`.
  Gift progress = `min(100, subtotal/3000*100)`; `giftRemain`; `freeShip = subtotal>=1500`;
  delivery = `freeShip ? 0 : 60`; `total`.
- **Drawer:** `open` + `show()/hide()/toggle()`.
- **Toast:** `toast(msg)` shows the bottom pill ~1.9s.
- **Buy now:** add then navigate to `/checkout`.
- Helpers: `tk(n)` → `৳` + `n.toLocaleString()`; `catTint(cat)`, `softBg(tint)` for
  placeholder tiles; `discountPct(p)`. Placeholder product "photos" are striped `.ph`
  tiles tinted by category (real photos dropped in later).

## 4. Data model (rewrite `config/products.php` from `data.js`)

- **categories** (8): `{id,name,tint,note}` — honey, dates, oil-ghee, spices, nuts,
  rice, mango, tea. (Add a derived `count` per category.)
- **products** (~33): `{id,name,weight,price,oldPrice|null,cat,badge: best|new|preorder|null,
  rating,reviews,blurb,certified?:bool}`. (Carry the full `data.js` list verbatim.)
- **brands** (5): Shuvo Farms, Khejuri, Honeyraj, Glarvest, Shosti Food.
- **testimonials** (4): `{name,role,text}`.
- **combos:** derive ~5 combo entries (name, items summary, price, oldPrice, save%) for
  the Combo Deals band.
- `App\Support\Catalog` gains: `categories/products/brands/testimonials/combos/
  byCategory/find/topSelling(best)/newArrivals/preorder/certified/related/search`.
  Remains the single backend swap point.

## 5. Pages (routes)

Shopping flow:
- `/` home — split hero (carousel), featured categories, Top Selling 2×2, Our Brands,
  per-category rails (Mango / All Natural Honey / Premium Dates / Cooking Essentials /
  Organic Certified) with dots + View All, orange Combo Deals band, full-width image
  band, "Just For You" grid + Load More, testimonials.
- `/shop` and `/shop?cat={id}` (also `/category/{slug}` kept as alias) — page head +
  breadcrumb, left filters (category checkboxes, highlight chips, price), toolbar
  (result count + sort), product grid, active-filter pills, empty state, load more.
- `/product/{id}` — gallery + thumbs, weight options, qty stepper, add/buy, trust grid,
  blurb, related rail.
- `/checkout` — 3 steps, delivery form, payment radios (COD / Online −2% / Card /
  bKash / Nagad / Rocket), promo field, sticky order summary (reads cart) → animated
  `/checkout` success state with order number.
- Global cart drawer + toast (all pages). `/404`.

Marketing/static:
- `/about`, `/contact`, `/blog` (+ `/blog/{slug}`), `/privacy`, `/terms`.

Account UI shells (front-end only this phase; wired to backend later):
- `/login`, `/register`, `/account` (dashboard: profile + order history + tracking),
  `/wishlist`, `/track` (order tracking).

## 6. Backend feature map (PHASE 2 — after frontend; from `laravel website.pdf`)

Stack: **MySQL** + Laravel; admin via **Filament v3** (chosen 2026-06-08 over
tyro-dashboard — Filament's RelationManagers/Table-Actions/Widgets fit the
order-workflow / invoices / sales-reports / product-variation needs; basic CRUD is
equally fast; far more mature ecosystem). Complex feature logic (order status workflow,
invoice PDF, reports, payment & courier integrations) is custom regardless of admin tool.

1. **Product mgmt:** Product/Category/Brand CRUD, **variations (size/weight/color)**,
   **stock**, image gallery.
2. **Orders:** customer order dashboard, status workflow, **invoice generation**,
   order history.
3. **Customer:** registration/login (Laravel auth/tyro-login), profile, **order
   tracking**, **wishlist** (persisted).
4. **Payments:** SSLCommerz, bKash, Nagad, Rocket, COD.
5. **Courier:** Pathao, Steadfast integration.
6. **Admin panel (tyro-dashboard):** products, orders, customers, **sales report**,
   coupons/discount.
7. **Marketing:** discount coupons, campaign banner mgmt, email notifications,
   Facebook Pixel, Google Analytics, basic SEO (meta/sitemap).
8. **Security/perf:** secure login, DB backup, speed/caching, basic hardening.
9. **Content:** Blog/Articles, Contact, About, Privacy, Terms (frontend shells from §5,
   backed by DB/admin here).

Delivered iteratively, each sub-system its own spec→plan→implement cycle. The frontend
(this spec) is the prerequisite and is delivered first.

## 7. Success criteria (frontend phase)

- Every page matches `_design-reference/_shots/*` and `styles.css` faithfully.
- Cart drawer, wishlist, toast, qty steppers, hero carousel, shop filters, product
  weight/gallery, checkout→success all interactive (Alpine), no console errors.
- `php artisan test` green; `npm run build` clean; all routes 200/404 as expected.
- `Catalog` remains the single data swap-point for the backend phase.
- The v1 Tailwind page/component/Tailwind-token files are removed.
