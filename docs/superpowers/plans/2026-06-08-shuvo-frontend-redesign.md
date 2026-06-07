# Shuvo Frontend Redesign — Implementation Plan

> **For agentic workers:** Execute task-by-task. Each task: read the named
> `_design-reference/` source files, port to Blade+Alpine using the design's own CSS
> classes, verify (build + render/curl + test where applicable), commit.

**Goal:** Replace the entire v1 visual layer with the "Shuvo — Organic Grocery" design
(pixel-faithful to `_design-reference/styles.css` + `_shots/*`), keeping Laravel routes
+ the `Catalog` swap-point, with full Alpine interactivity. Build all pages incl.
marketing + account UI shells.

**Architecture:** Port `_design-reference/styles.css` to `resources/css/app.css` as the
design system (semantic classes, no Tailwind utilities). One Alpine store
(`Alpine.store('shop')`) for cart/wishlist/toast/thresholds. Blade partials/components
mirror the React components in `_design-reference/components.jsx`. Data from
`config/products.php` (ported from `_design-reference/data.js`) via `App\Support\Catalog`.

**Reference files (READ THESE):** `_design-reference/{styles.css, data.js, icons.jsx,
components.jsx, app.jsx, pages-home.jsx, pages-shop.jsx, pages-checkout.jsx,
HANDOFF-README.md}` and `_shots/{home-ref,s2,s3,s4,s5,footer}.png`.

---

## Task 1 — Design system + Alpine store (foundation)

**Files:** `resources/css/app.css` (replace), `resources/js/app.js` (replace),
`resources/views/layouts/app.blade.php` (replace fonts).

- [ ] Copy `_design-reference/styles.css` verbatim into `resources/css/app.css`,
  REMOVING the three `@tailwind` directives (the design is self-contained). Append
  `[x-cloak]{display:none!important}`.
- [ ] Layout `<head>`: load Bricolage Grotesque + Hanken Grotesk via the exact Google
  Fonts `<link>` from `_design-reference/index.html` (preconnect + the css2 url).
- [ ] Rewrite `resources/js/app.js` Alpine store `shop` per spec §3: cart `items[]`
  `{id,name,weight,price,cat,qty}`, `add/changeQty/remove/count/subtotal`, wishlist
  `wish[]`+`toggleWish/wishCount`, thresholds 3000/1500, `giftPct/giftRemain/freeShip/
  delivery/total`, drawer `open/show/hide/toggle`, `toast(msg)` (1.9s), `buyNow`.
  Expose JS helpers `tk(n)`, `catTint(cat)`, `softBg(tint)`, `discountPct(p)` on
  `window` for use in Alpine expressions; seed category tints from a JS object.
- [ ] `npm run build` succeeds. Commit: "Shuvo: port design system CSS + Alpine shop store".

## Task 2 — Data + Catalog + routes/controller

**Files:** `config/products.php` (rewrite), `app/Support/Catalog.php` (rewrite),
`routes/web.php` (rewrite), `app/Http/Controllers/CatalogController.php` (rewrite) +
new `PageController` for marketing/account shells.

- [ ] `config/products.php`: port ALL of `_design-reference/data.js` verbatim —
  `categories` (8, `{id,name,tint,note}`), `products` (~33, `{id,name,weight,price,
  oldPrice,cat,badge,rating,reviews,blurb,certified}`), `brands` (5), `testimonials`
  (4); add `combos` (~5 derived: `{name,items,price,oldPrice}`); `free_gift_threshold`
  3000, `free_ship_threshold` 1500.
- [ ] `Catalog`: methods `categories/products/brands/testimonials/combos/find($id)/
  byCategory($catId)/category($id)/topSelling()` (badge==='best')`/newArrivals()`
  (badge==='new')`/preorder()/certified()/related($id)/search($q)` + `giftThreshold/
  shipThreshold`. Each category gets a derived product `count`.
- [ ] Routes: `/`→home, `/shop`→shop (query `cat`,`deal`,`search`), `/category/{slug}`
  alias→shop, `/product/{id}`→product, `/checkout`→checkout, marketing `/about /contact
  /blog /blog/{slug} /privacy /terms`, account shells `/login /register /account
  /wishlist /track`, `Route::fallback`→errors.404. Named routes throughout.
- [ ] Controller actions pass the needed Catalog data to each view (views land in later
  tasks; verify data via a tinker/bootstrap script + `route:list`). Commit.

## Task 3 — Layout + header + footer

**Files:** `resources/views/layouts/app.blade.php`, `resources/views/partials/header.blade.php`,
`resources/views/partials/footer.blade.php`. Port from `components.jsx` Header + `_shots/footer.png`.

- [ ] Layout: `.stage` wrapper, `@include` header, `<main id="main-content">@yield`, footer,
  cart drawer, toast element; body uses design fonts; skip-link.
- [ ] Header: `.announce` (free delivery ৳1500 / COD / unlock ৳3000 gift), `.hdr-main`
  (logo "Shuvo." + tag "Pure · Organic · Halal", `.search`, `.hdr-actions` Track/Sign
  in/Wishlist/Cart with Alpine count badges bound to the store, cart opens drawer), dark
  green `.nav` (orange "All Categories"→/shop, "Offer Zone" hot, the 8 category links
  with `.active` state).
- [ ] Footer `.ftr-light`: brand + blurb + contact, social, app badges (Google Play/App
  Store), columns (Information/Support/Consumer Policy), `.pay-row` chips
  (VISA/Mastercard/bKash/Nagad/Rocket/DBBL/COD), copyright. Build green. Commit.

## Task 4 — Shared components

**Files:** `resources/views/components/` — `icon.blade.php` (inline SVG set ported from
`icons.jsx`), `product-card.blade.php`, `category-tile.blade.php`, `top-card.blade.php`,
`brand-card.blade.php`, `combo-card.blade.php`, `rail-head.blade.php`, `center-title.blade.php`,
`qty.blade.php`, `stars.blade.php`, `photo.blade.php` (striped `.ph` tinted by category).
Port markup from `components.jsx` ProductCard/CardBadges + the CSS classes.

- [ ] `product-card`: badges (Save%, New, Pre-order), `.ribbon-best` when best, wishlist
  heart (Alpine `toggleWish`, `.on` when wished), `.ph` photo, title (links
  `route('product',id)`), price row (price + old + `.save-pill` Save ৳X), `.add-btn`
  (Alpine add + "Added" flash) + optional `.buy-btn` (buyNow). Verify via temp render. Commit.

## Task 5 — Cart drawer + toast

**File:** `resources/views/partials/cart-drawer.blade.php` (port `components.jsx` CartDrawer).

- [ ] Overlay + `.drawer`, head with live count, `.gift-bar` (progress + remain message
  / unlocked), `.drawer-body` line items (`.cart-line`, qty-mini, remove) or `.cart-empty`,
  `.drawer-foot` (free-ship note, subtotal/delivery/total, Checkout→/checkout). All bound
  to `$store.shop`. Toast element in layout bound to `$store.shop` toast state. Commit.

## Task 6 — Home page

**File:** `resources/views/pages/home.blade.php`. Port `_design-reference/pages-home.jsx`
(reference-layout version) + match `_shots/{home-ref,s2,s3,s4,s5}.png`.

- [ ] Sections in order: split hero (`.hero-split` left light banner + right green mango
  pre-order, `.hero-dots` Alpine carousel), Featured Categories (`.center-title` +
  `.fcat-row` of 8 tiles → `route('category',...)`), Top Selling (`.center-title` +
  `.top-grid` 2×2 `.top-card` with Add+Buy), Our Brands (`.rail-head` + `.brands-row`),
  per-category rails (Mango / All Natural Honey / Premium Dates / Cooking Essentials /
  Organic Certified) each `.rail-head` + 5-up `.grid-5` of product-cards + `.dots`,
  Combo Deals (`.combo-band` orange + `.combo-strip`), `.img-band`, "Just For You" grid
  + `.load-more`, testimonials. Feature test `assertSee` signature strings. Build+test. Commit.

## Task 7 — Shop / listing page

**File:** `resources/views/pages/shop.blade.php`. Port `_design-reference/pages-shop.jsx`.

- [ ] `.page-head` (breadcrumb + title + sub), `.shop-layout` = `.filters` (category
  checkboxes with counts, highlight chips Best/New/Pre-order/Certified, price range) +
  main (`.shop-toolbar` result count + `.select` sort, `.active-filters` pills, product
  grid `.grid-4`, `.empty-state`, `.load-more`). Alpine: filter/sort/load-more client
  state, `cat` from query pre-selected. Mobile filter drawer toggle. Feature test
  (`/shop` 200, `/shop?cat=honey` 200, sees a known product). Commit.

## Task 8 — Product detail page

**File:** `resources/views/pages/product.blade.php`. Port `_design-reference/pages-shop.jsx`
ProductDetail (it lives in that file) + `.pdp*` CSS.

- [ ] `.pdp` two-col: `.pdp-gallery` (main `.ph` + thumbs, Alpine switch), `.pdp-info`
  (cat, h1, rate, price block + save line, blurb, `.pdp-weights` weight-opt selector,
  `.pdp-buy` qty + add-to-cart + buy-now, `.pdp-trust` 4 trust items). Related rail
  (`Catalog::related`). 404 for unknown id. Feature test (`/product/1` 200 sees name +
  Add To Cart; `/product/9999` 404). Commit.

## Task 9 — Checkout page (+ success)

**File:** `resources/views/pages/checkout.blade.php`. Port `_design-reference/pages-checkout.jsx`.

- [ ] `.checkout` two-col: left `.co-steps` + `.co-card` delivery form (`.field`s,
  `.field-row`), payment `.pay-opt` radios (COD / Online −2% / Card / bKash / Nagad /
  Rocket) Alpine-selectable; right sticky `.co-summary` reading `$store.shop` items +
  `.co-promo` + totals + Place Order. Place Order → `.success` state (Alpine `placed`
  flag) with order number + clears cart. Feature test (`/checkout` 200 sees Place Order).
  Commit.

## Task 10 — Marketing pages

**Files:** `resources/views/pages/{about,contact,blog,blog-post,privacy,terms}.blade.php`.
Use design system classes (`.page-head`, `.wrap`, `.section`, cards) — no new reference,
match the design language.

- [ ] About (story + values + trust grid), Contact (`.co-card` form + info + map
  placeholder), Blog index (`.grid-4` of article cards from a small `config` array or
  Catalog stub) + Blog post (`.wrap` article body), Privacy + Terms (long-form `.wrap`
  prose). Feature test: each route 200 + a heading. Commit.

## Task 11 — Account UI shells

**Files:** `resources/views/pages/{login,register,account,wishlist,track}.blade.php`.
Front-end only (forms POST to `#`, wired to backend in phase 2).

- [ ] Login + Register (centered `.co-card` auth forms, design fields/buttons),
  Account dashboard (sidebar + profile card + order-history table + tracking widget),
  Wishlist (grid of product-cards from `$store.shop.wish`, empty state), Track order
  (order-id input + status timeline placeholder). Feature test: each route 200. Commit.

## Task 12 — 404 + cleanup + integration pass

- [ ] Redesign `resources/views/errors/404.blade.php` to the Shuvo look (`.wrap`
  centered, leaf mark, green CTA home).
- [ ] DELETE v1 leftovers: old Tailwind component files no longer referenced
  (`components/{badge,price,section-heading,qty-stepper,rating-stars,product-carousel}.blade.php`
  if superseded), `tailwind.config.js`/`postcss.config.js` only if Tailwind fully
  removed (otherwise leave). Update feature tests that referenced v1 markup.
- [ ] Update `README.md` for the Shuvo design + new routes. Run full `php artisan test`
  (all green) + `npm run build` + smoke-test every route (200/404). Commit.

---

## Notes
- Verification for visual pages = build + `curl` render check for signature strings +
  feature test for 200/route; pixel fidelity judged against `_shots/*`.
- Keep `Catalog` the only data source; no DB this phase (file sessions remain).
- Phase 2 (backend, MySQL + tyro-dashboard) is a separate spec/plan after this.
