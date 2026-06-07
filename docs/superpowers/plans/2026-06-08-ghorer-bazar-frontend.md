# Ghorer Bazar–Style Laravel Frontend Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the complete frontend (6 page blueprints + global cart drawer) of a Ghorer Bazar–style grocery e-commerce store as a Laravel 11 app with Blade + Tailwind + Alpine.js, using swappable placeholder data so the client can wire a backend later with no Blade changes.

**Architecture:** Fresh Laravel 11 app. Design tokens from `design.md` §7 mapped into `tailwind.config.js`. Placeholder catalog lives in `config/products.php` (plain arrays) accessed through a thin `App\Support\Catalog` class — the single backend swap point. A `CatalogController` feeds Blade views. Interactivity (cart drawer, steppers, gallery, filters, checkout selectors) is client-side via an Alpine.js store; nothing persists.

**Tech Stack:** Laravel 11, Blade, Tailwind CSS (Vite), Alpine.js, Open Sans (Google Fonts). Verification via `php artisan serve` route renders + Pest/PHPUnit feature tests (`get()->assertSee()`).

**Note on verification:** This is a static frontend. Each page task verifies by (a) a lightweight feature test asserting the route returns 200 and renders signature content, and (b) a manual browser/`curl` check. We do NOT write unit tests for visual styling.

---

## File Structure

```
config/products.php                         # placeholder catalog (categories, products, banners, settings)
app/Support/Catalog.php                      # accessor over config — BACKEND SWAP POINT
app/Http/Controllers/CatalogController.php    # home/category/product/checkout actions
routes/web.php                               # routes + fallback 404
tailwind.config.js                           # design tokens
resources/css/app.css                        # @tailwind + :root tokens + base
resources/js/app.js                          # Alpine init + cart store
resources/views/
  layouts/app.blade.php
  partials/{header,footer,cart-drawer}.blade.php
  components/{badge,price,section-heading,qty-stepper,rating-stars,product-card,product-carousel}.blade.php
  pages/{home,category,product,checkout}.blade.php
  errors/404.blade.php
tests/Feature/{HomePageTest,CategoryPageTest,ProductPageTest,CheckoutPageTest,NotFoundTest}.php
```

---

## Task 1: Scaffold Laravel 11 + Tailwind + Alpine + design tokens

**Files:**
- Create: entire Laravel skeleton (via installer)
- Create: `tailwind.config.js`, `postcss.config.js`
- Modify: `resources/css/app.css`, `resources/js/app.js`, `vite.config.js` (if needed)
- Modify: `.gitignore` (replace minimal one with Laravel's)

- [ ] **Step 1: Scaffold Laravel into the existing directory**

The directory already contains `design.md`, `docs/`, `.git`, `.gitignore`. `composer create-project` refuses a non-empty dir, so scaffold into a temp subfolder then move everything up.

```bash
cd "c:/Users/LENOVO/Documents/ecom-shuvo"
composer create-project laravel/laravel:^11.0 _scaffold --no-interaction
# move scaffold contents up (including dotfiles), preserving design.md/docs/.git
cd _scaffold
# move all files and dirs up one level, overwriting scaffold's .gitignore over ours
powershell -Command "Get-ChildItem -Force | ForEach-Object { Move-Item -Force -Path \$_.FullName -Destination '..' }"
cd ..
rmdir _scaffold
```

Expected: `artisan`, `composer.json`, `app/`, `resources/`, `routes/` now in project root. `design.md` and `docs/` still present.

- [ ] **Step 2: Verify Laravel runs**

```bash
php artisan --version
```
Expected: `Laravel Framework 11.x`

- [ ] **Step 3: Install Tailwind + Alpine**

```bash
npm install
npm install -D tailwindcss@^3 postcss autoprefixer
npm install alpinejs
npx tailwindcss init -p
```
Expected: `tailwind.config.js`, `postcss.config.js` created; `node_modules` populated.

- [ ] **Step 4: Write `tailwind.config.js` with design tokens**

```js
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#f48721',
        'primary-alt': '#ff9800',
        dark: '#041f1e',
        ink: '#222831',
        cream: '#fbf9f5',
        text: '#666666',
        'text-mute': '#5c3d1e',
        strike: '#aaaaaa',
        border: '#cccccc',
        'border-light': '#eeeeee',
        success: '#34be82',
        whatsapp: '#1daa61',
        call: '#1e3a8a',
        sale: '#ff1818',
        ink404: '#252a34',
      },
      borderRadius: { sm: '4px', DEFAULT: '6px', lg: '8px' },
      fontFamily: { base: ['"Open Sans"', 'sans-serif'] },
      boxShadow: { card: '0 4px 12px rgba(0,0,0,0.08)' },
      height: { input: '47px' },
      maxWidth: { content: '1400px' },
    },
  },
  plugins: [],
}
```

- [ ] **Step 5: Write `resources/css/app.css`**

```css
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700;800&display=swap');
@tailwind base;
@tailwind components;
@tailwind utilities;

:root {
  --color-primary: #f48721; --color-primary-alt: #ff9800;
  --color-dark: #041f1e; --color-ink: #222831; --color-cream: #fbf9f5;
  --color-text: #666666; --color-text-mute: #5c3d1e; --color-strike: #aaaaaa;
  --color-border: #cccccc; --color-border-light: #eeeeee;
  --color-success: #34be82; --color-whatsapp: #1daa61; --color-call: #1e3a8a;
  --color-sale: #ff1818;
  --radius-sm: 4px; --radius: 6px; --radius-lg: 8px;
  --shadow-card: 0 4px 12px rgba(0,0,0,0.08); --input-height: 47px;
}

@layer base {
  body { @apply font-base text-text bg-white antialiased; }
  h1,h2,h3 { @apply text-ink; }
}

@layer components {
  /* Primary CTA */
  .btn-primary { @apply bg-primary text-white font-semibold uppercase rounded px-6 h-input inline-flex items-center justify-center gap-2 hover:bg-primary-alt transition; }
  /* Outlined add-to-cart on cards */
  .btn-outline { @apply border border-primary text-primary font-semibold rounded px-4 py-2 inline-flex items-center justify-center gap-2 hover:bg-primary hover:text-white transition; }
  /* Form input */
  .field { @apply w-full h-input rounded-lg border border-border bg-white px-4 text-sm text-ink placeholder:text-text focus:border-primary focus:outline-none; }
  /* Section heading with left orange bar */
  .heading-bar { @apply relative pl-4 text-[22px] font-bold text-ink before:absolute before:left-0 before:top-1 before:bottom-1 before:w-1 before:bg-primary before:rounded; }
}
```

- [ ] **Step 6: Write `resources/js/app.js`**

```js
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('cart', {
  open: false,
  items: [],            // {slug, name, price, image, qty}
  threshold: 1000,      // overwritten by layout from config
  toggle() { this.open = !this.open; },
  show() { this.open = true; },
  hide() { this.open = false; },
  add(product, qty = 1) {
    const found = this.items.find(i => i.slug === product.slug);
    if (found) { found.qty += qty; }
    else { this.items.push({ ...product, qty }); }
    this.show();
  },
  remove(slug) { this.items = this.items.filter(i => i.slug !== slug); },
  setQty(slug, qty) {
    const it = this.items.find(i => i.slug === slug);
    if (it) it.qty = Math.max(1, qty);
  },
  get count() { return this.items.reduce((n, i) => n + i.qty, 0); },
  get total() { return this.items.reduce((s, i) => s + i.price * i.qty, 0); },
  get remaining() { return Math.max(0, this.threshold - this.total); },
  get giftProgress() { return Math.min(100, this.threshold ? (this.total / this.threshold) * 100 : 0); },
});

Alpine.start();
```

- [ ] **Step 7: Build assets to verify the toolchain**

```bash
npm run build
```
Expected: builds without error; `public/build/manifest.json` created.

- [ ] **Step 8: Commit**

```bash
git add -A
git commit -m "Scaffold Laravel 11 with Tailwind design tokens and Alpine cart store"
```

---

## Task 2: Placeholder catalog data + Catalog accessor + routes/controller

**Files:**
- Create: `config/products.php`
- Create: `app/Support/Catalog.php`
- Create: `app/Http/Controllers/CatalogController.php`
- Modify: `routes/web.php`

- [ ] **Step 1: Write `config/products.php`**

Define `categories` (≥6), `products` (≥14 across categories, each with the full shape below), `banners` (2 hero slides), and `free_gift_threshold`. Use grocery/food Unsplash URLs (`https://images.unsplash.com/photo-...?w=600&q=80`). Full product shape (repeat for every product):

```php
<?php
return [
    'free_gift_threshold' => 1000,
    'categories' => [
        ['slug' => 'cooking-essentials', 'name' => 'Cooking Essentials', 'icon' => 'oil'],
        ['slug' => 'honey-nuts',         'name' => 'Honey & Nuts',       'icon' => 'honey'],
        ['slug' => 'spices',             'name' => 'Spices',             'icon' => 'spice'],
        ['slug' => 'dairy',              'name' => 'Dairy',              'icon' => 'dairy'],
        ['slug' => 'dates',              'name' => 'Dates',              'icon' => 'dates'],
        ['slug' => 'beverages',          'name' => 'Beverages',          'icon' => 'tea'],
    ],
    'banners' => [
        ['image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=1400&q=80', 'headline' => 'Pure & Natural Groceries', 'sub' => 'Delivered to your door', 'cta' => 'Shop Now', 'href' => '/category/cooking-essentials'],
        ['image' => 'https://images.unsplash.com/photo-1506976785307-8732e854ad03?w=1400&q=80', 'headline' => 'Raw Honey Collection', 'sub' => 'Straight from the hive', 'cta' => 'Explore', 'href' => '/category/honey-nuts'],
    ],
    'products' => [
        [
            'slug' => 'mustard-oil-1l',
            'name' => 'Pure Mustard Oil 1L',
            'category' => 'cooking-essentials',
            'price' => 480,
            'old_price' => 550,
            'badge' => ['type' => 'save', 'label' => 'Save 13%'],   // type: save|new|best ; or null
            'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=600&q=80',
            'gallery' => [
                'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=800&q=80',
                'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&q=80',
            ],
            'brand' => 'Ghorer Bazar',
            'rating' => 4.7,
            'reviews' => 128,
            'in_stock' => true,
            'description' => 'Cold-pressed pure mustard oil, rich aroma, no additives.',
        ],
        // ... ≥13 more, varying category/badge (some 'new', some 'best', some old_price=null/badge=null)
    ],
];
```

- [ ] **Step 2: Write `app/Support/Catalog.php` (BACKEND SWAP POINT)**

```php
<?php

namespace App\Support;

class Catalog
{
    /** @return array<int,array> */
    public static function all(): array
    {
        return config('products.products', []);
    }

    public static function categories(): array
    {
        return config('products.categories', []);
    }

    public static function banners(): array
    {
        return config('products.banners', []);
    }

    public static function giftThreshold(): int
    {
        return (int) config('products.free_gift_threshold', 0);
    }

    public static function find(string $slug): ?array
    {
        foreach (self::all() as $p) {
            if ($p['slug'] === $slug) {
                return $p;
            }
        }
        return null;
    }

    public static function category(string $slug): ?array
    {
        foreach (self::categories() as $c) {
            if ($c['slug'] === $slug) {
                return $c;
            }
        }
        return null;
    }

    /** @return array<int,array> */
    public static function byCategory(string $slug): array
    {
        return array_values(array_filter(self::all(), fn ($p) => $p['category'] === $slug));
    }

    /** @return array<int,array> */
    public static function featured(int $limit = 8): array
    {
        return array_slice(self::all(), 0, $limit);
    }

    /** @return array<int,array> */
    public static function topSelling(int $limit = 8): array
    {
        return array_slice(array_reverse(self::all()), 0, $limit);
    }

    /** @return array<int,array> */
    public static function related(string $slug, int $limit = 6): array
    {
        $product = self::find($slug);
        if (! $product) {
            return [];
        }
        $rel = array_filter(
            self::all(),
            fn ($p) => $p['category'] === $product['category'] && $p['slug'] !== $slug
        );
        return array_slice(array_values($rel), 0, $limit);
    }
}
```

- [ ] **Step 3: Write `app/Http/Controllers/CatalogController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Support\Catalog;

class CatalogController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'banners' => Catalog::banners(),
            'categories' => Catalog::categories(),
            'topSelling' => Catalog::topSelling(),
            'featured' => Catalog::featured(),
        ]);
    }

    public function category(string $slug)
    {
        $category = Catalog::category($slug);
        abort_if(! $category, 404);

        return view('pages.category', [
            'category' => $category,
            'categories' => Catalog::categories(),
            'products' => Catalog::byCategory($slug),
        ]);
    }

    public function product(string $slug)
    {
        $product = Catalog::find($slug);
        abort_if(! $product, 404);

        return view('pages.product', [
            'product' => $product,
            'related' => Catalog::related($slug),
        ]);
    }

    public function checkout()
    {
        return view('pages.checkout');
    }
}
```

- [ ] **Step 4: Write `routes/web.php`**

```php
<?php

use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/category/{slug}', [CatalogController::class, 'category'])->name('category');
Route::get('/product/{slug}', [CatalogController::class, 'product'])->name('product');
Route::get('/checkout', [CatalogController::class, 'checkout'])->name('checkout');

Route::fallback(fn () => response()->view('errors.404', [], 404));
```

- [ ] **Step 5: Sanity-check data loads (tinker)**

```bash
php artisan tinker --execute="echo count(App\Support\Catalog::all()); echo PHP_EOL; var_dump(App\Support\Catalog::find('mustard-oil-1l')['name']);"
```
Expected: prints product count (≥14) and `string(...) "Pure Mustard Oil 1L"`.

- [ ] **Step 6: Commit**

```bash
git add -A
git commit -m "Add placeholder catalog config, Catalog accessor, controller and routes"
```

---

## Task 3: App layout + header + footer partials

**Files:**
- Create: `resources/views/layouts/app.blade.php`
- Create: `resources/views/partials/header.blade.php`
- Create: `resources/views/partials/footer.blade.php`

Follow `design.md` §4-A (nav/footer) and §2 color rules (dark green nav/footer, white utility row).

- [ ] **Step 1: Write `layouts/app.blade.php`**

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ghorer Bazar')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-text"
      x-data
      x-init="$store.cart.threshold = {{ \App\Support\Catalog::giftThreshold() }}">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.cart-drawer')
</body>
</html>
```

- [ ] **Step 2: Write `partials/header.blade.php`**

Two rows per §4-A. Top: dark-green sticky bar (`bg-dark text-white sticky top-0 z-40`) with logo + nav links (Home, Categories from config, Offers, Contact). Below it a white utility row (`bg-white border-b border-border-light`) with a search input (`.field` styled, max-w), and right-aligned icon links: Track Order, Sign In, Wishlist, and a Cart button bound to Alpine:

```blade
<button type="button" class="relative" @click="$store.cart.toggle()">
    {{-- cart icon svg --}}
    <span class="absolute -top-2 -right-2 bg-primary text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center"
          x-text="$store.cart.count" x-show="$store.cart.count > 0"></span>
</button>
```
Category links use `route('category', $cat['slug'])`. Include a mobile hamburger (`x-data="{m:false}"`) that toggles a stacked nav on small screens.

- [ ] **Step 3: Write `partials/footer.blade.php`**

White footer per §4-A: multi-column link lists (Shop / Help / Company / Contact), a row of social circle icon links, app-store badge placeholders, a "Pay With" logo strip (use simple text/emoji or inline SVG placeholders), and a centered copyright line `© {{ date('Y') }} Ghorer Bazar`. Container `max-w-content mx-auto px-4`.

- [ ] **Step 4: Temp route check**

Temporarily make `pages.home` a stub (`@extends('layouts.app')` + `@section('content')<div class="p-10">Home</div>@endsection`) OR rely on Task 6. For now verify layout compiles:

```bash
php artisan view:clear && php artisan serve &
curl -s http://127.0.0.1:8000/ | grep -c "Ghorer Bazar"
```
Expected: ≥1 (after Task 6 home exists; if testing now, create a one-line stub first). Stop the server after.

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "Add app layout, header and footer partials"
```

---

## Task 4: Reusable Blade components

**Files:**
- Create: `resources/views/components/badge.blade.php`
- Create: `resources/views/components/price.blade.php`
- Create: `resources/views/components/section-heading.blade.php`
- Create: `resources/views/components/rating-stars.blade.php`
- Create: `resources/views/components/qty-stepper.blade.php`
- Create: `resources/views/components/product-card.blade.php`
- Create: `resources/views/components/product-carousel.blade.php`

- [ ] **Step 1: `badge.blade.php`** (§5 badges)

```blade
@props(['type' => 'save', 'label' => ''])
@php
$map = ['save' => 'bg-success', 'new' => 'bg-primary', 'best' => 'bg-sale'];
@endphp
<span {{ $attributes->merge(['class' => "inline-block text-white text-[11px] font-semibold px-2 py-0.5 rounded-sm {$map[$type]}"]) }}>{{ $label }}</span>
```

- [ ] **Step 2: `price.blade.php`** (§3 price rows)

```blade
@props(['price', 'old' => null])
<span class="flex items-baseline gap-2">
    <span class="text-base font-semibold text-primary">৳{{ number_format($price) }}</span>
    @if($old)
        <span class="text-base text-strike line-through">৳{{ number_format($old) }}</span>
    @endif
</span>
```

- [ ] **Step 3: `section-heading.blade.php`** (§3 left bar / underline variants)

```blade
@props(['variant' => 'bar'])   {{-- bar = left orange accent ; underline = orange underline --}}
@if($variant === 'bar')
    <h2 class="heading-bar mb-6">{{ $slot }}</h2>
@else
    <h2 class="text-[22px] font-bold text-ink mb-6 inline-block border-b-2 border-primary pb-1">{{ $slot }}</h2>
@endif
```

- [ ] **Step 4: `rating-stars.blade.php`**

```blade
@props(['rating' => 0, 'reviews' => null])
<span class="inline-flex items-center gap-1 text-primary text-sm">
    @for($i = 1; $i <= 5; $i++)
        <span>{{ $i <= round($rating) ? '★' : '☆' }}</span>
    @endfor
    @if($reviews !== null)<span class="text-text text-xs ml-1">({{ $reviews }})</span>@endif
</span>
```

- [ ] **Step 5: `qty-stepper.blade.php`** (§3-C stepper) — Alpine, takes an initial value and optional change handler via attributes

```blade
@props(['value' => 1])
<div x-data="{ q: {{ $value }} }" class="inline-flex items-center border border-border rounded-lg overflow-hidden">
    <button type="button" class="w-9 h-9 text-ink hover:bg-cream" @click="q = Math.max(1, q-1); $dispatch('qty', q)">−</button>
    <span class="w-10 text-center text-sm" x-text="q"></span>
    <button type="button" class="w-9 h-9 text-ink hover:bg-cream" @click="q = q+1; $dispatch('qty', q)">+</button>
</div>
```

- [ ] **Step 6: `product-card.blade.php`** (§4-B card)

```blade
@props(['product'])
<div class="group bg-white border border-border rounded-sm p-2 hover:shadow-card transition flex flex-col">
    <div class="relative">
        @if(!empty($product['badge']))
            <x-badge :type="$product['badge']['type']" :label="$product['badge']['label']" class="absolute top-1 left-1 z-10" />
        @endif
        <a href="{{ route('product', $product['slug']) }}">
            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full aspect-square object-cover rounded-sm">
        </a>
    </div>
    <a href="{{ route('product', $product['slug']) }}" class="mt-2 text-sm text-ink font-medium line-clamp-2 min-h-[2.5rem]">{{ $product['name'] }}</a>
    <div class="mt-1">
        <x-price :price="$product['price']" :old="$product['old_price'] ?? null" />
    </div>
    <button type="button"
            class="btn-outline mt-3 w-full text-[13px]"
            @click="$store.cart.add({{ Illuminate\Support\Js::from([
                'slug' => $product['slug'], 'name' => $product['name'],
                'price' => $product['price'], 'image' => $product['image'],
            ]) }})">
        Add To Cart
    </button>
</div>
```

- [ ] **Step 7: `product-carousel.blade.php`** (§4-A carousels with VIEW ALL)

```blade
@props(['title', 'products', 'viewAll' => null, 'variant' => 'underline'])
<section class="max-w-content mx-auto px-4 my-12">
    <div class="flex items-center justify-between">
        <x-section-heading :variant="$variant">{{ $title }}</x-section-heading>
        @if($viewAll)
            <a href="{{ $viewAll }}" class="text-primary font-semibold text-sm">VIEW ALL →</a>
        @endif
    </div>
    <div class="flex gap-4 overflow-x-auto pb-2 snap-x">
        @foreach($products as $product)
            <div class="snap-start shrink-0 w-44 sm:w-52">
                <x-product-card :product="$product" />
            </div>
        @endforeach
    </div>
</section>
```

- [ ] **Step 8: Commit**

```bash
git add -A
git commit -m "Add reusable Blade UI components (card, price, badge, stepper, carousel, heading, stars)"
```

---

## Task 5: Global cart drawer (Blueprint D)

**Files:**
- Create: `resources/views/partials/cart-drawer.blade.php`

Follow §4-D: 400px slide-out, free-gift progress, line items with stepper + remove, total, CHECKOUT button.

- [ ] **Step 1: Write `partials/cart-drawer.blade.php`**

```blade
<div x-data x-cloak>
    {{-- overlay --}}
    <div class="fixed inset-0 bg-black/40 z-50" x-show="$store.cart.open"
         x-transition.opacity @click="$store.cart.hide()"></div>

    {{-- panel --}}
    <aside class="fixed top-0 right-0 h-full w-full max-w-[400px] bg-white z-50 shadow-card flex flex-col"
           x-show="$store.cart.open"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

        <header class="flex items-center justify-between px-4 h-14 border-b border-border-light">
            <h2 class="font-bold text-ink">SHOPPING CART</h2>
            <button @click="$store.cart.hide()" class="text-primary font-semibold">Close →</button>
        </header>

        {{-- free-gift progress --}}
        <div class="px-4 py-3 bg-cream">
            <p class="text-xs text-text-mute mb-1">
                🎁 <template x-if="$store.cart.remaining > 0"><span>Add ৳<span x-text="$store.cart.remaining"></span> more to unlock a free gift!</span></template>
                <template x-if="$store.cart.remaining === 0"><span>You unlocked a free gift!</span></template>
            </p>
            <div class="h-2 bg-border-light rounded-full overflow-hidden">
                <div class="h-full bg-primary transition-all" :style="`width: ${$store.cart.giftProgress}%`"></div>
            </div>
        </div>

        {{-- line items --}}
        <div class="flex-1 overflow-y-auto px-4 divide-y divide-border-light">
            <template x-if="$store.cart.items.length === 0">
                <p class="text-center text-text py-10">Your cart is empty.</p>
            </template>
            <template x-for="item in $store.cart.items" :key="item.slug">
                <div class="flex gap-3 py-3">
                    <img :src="item.image" class="w-16 h-16 object-cover rounded-sm border border-border-light">
                    <div class="flex-1">
                        <p class="text-sm text-ink" x-text="item.name"></p>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="inline-flex items-center border border-border rounded text-sm">
                                <button class="w-7 h-7" @click="$store.cart.setQty(item.slug, item.qty-1)">−</button>
                                <span class="w-7 text-center" x-text="item.qty"></span>
                                <button class="w-7 h-7" @click="$store.cart.setQty(item.slug, item.qty+1)">+</button>
                            </div>
                            <span class="text-xs text-text" x-text="`৳${item.price} × ${item.qty} = ৳${item.price*item.qty}`"></span>
                        </div>
                    </div>
                    <button class="text-strike hover:text-sale" @click="$store.cart.remove(item.slug)">×</button>
                </div>
            </template>
        </div>

        <footer class="border-t border-border-light p-4">
            <div class="flex justify-between font-bold text-ink mb-3">
                <span>Total:</span><span x-text="`৳${$store.cart.total}`"></span>
            </div>
            <a href="{{ route('checkout') }}" class="btn-primary w-full">Checkout</a>
        </footer>
    </aside>
</div>
```

- [ ] **Step 2: Add `[x-cloak]{display:none}` to `app.css` base layer**

Add to `@layer base` in `resources/css/app.css`:
```css
[x-cloak]{ display:none !important; }
```

- [ ] **Step 3: Manual check**

Run `npm run build && php artisan serve`, open `/`, click a card's "Add To Cart" → drawer slides in, item shows, qty +/− updates subtotal & gift bar, × removes, Close hides. (Requires Task 6 home; if not yet built, defer this check to after Task 6.)

- [ ] **Step 4: Commit**

```bash
git add -A
git commit -m "Add global slide-out cart drawer with free-gift progress (Blueprint D)"
```

---

## Task 6: Homepage (Blueprint A)

**Files:**
- Create: `resources/views/pages/home.blade.php`
- Create: `tests/Feature/HomePageTest.php`

- [ ] **Step 1: Write `pages/home.blade.php`**

`@extends('layouts.app')`, `@section('title','Ghorer Bazar — Pure & Natural Groceries')`. Sections in order per §4-A:
1. **Hero carousel** — Alpine `x-data="{active:0}"` cycling `$banners`; each slide full-width with overlaid bold headline + sub + orange CTA button; dot indicators (active dot `bg-primary`), prev/next chevrons.
2. **Featured Categories** — grid of white square cards (`grid grid-cols-2 md:grid-cols-6 gap-4`), each linking `route('category',$cat['slug'])`, with an icon (simple inline SVG or emoji by `$cat['icon']`) and name.
3. **Top Selling carousel** — `<x-product-carousel title="Top Selling" :products="$topSelling" :viewAll="route('category','cooking-essentials')" />`.
4. **Promo image band** — full-width image with overlaid text (Unsplash url).
5. **Cooking Essentials carousel** — `<x-product-carousel title="Cooking Essentials" :products="$featured" :viewAll="route('category','cooking-essentials')" />`.

Hero carousel reference markup:
```blade
<section x-data="{active:0, slides: {{ Illuminate\Support\Js::from($banners) }}}" class="relative">
    <template x-for="(s,i) in slides" :key="i">
        <div x-show="active===i" x-transition.opacity class="relative h-[320px] md:h-[460px]">
            <img :src="s.image" class="absolute inset-0 w-full h-full object-cover">
            <div class="relative max-w-content mx-auto px-6 h-full flex flex-col justify-center">
                <h1 class="text-3xl md:text-5xl font-bold text-white max-w-lg drop-shadow" x-text="s.headline"></h1>
                <p class="text-white/90 mt-2 text-lg" x-text="s.sub"></p>
                <a :href="s.href" class="btn-primary mt-5 w-max" x-text="s.cta"></a>
            </div>
        </div>
    </template>
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
        <template x-for="(s,i) in slides" :key="i">
            <button class="w-2.5 h-2.5 rounded-full" :class="active===i ? 'bg-primary' : 'bg-white/60'" @click="active=i"></button>
        </template>
    </div>
</section>
```

- [ ] **Step 2: Write `tests/Feature/HomePageTest.php`**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_renders_with_signature_sections(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Top Selling')
            ->assertSee('Featured Categories', false)
            ->assertSee('SHOPPING CART'); // cart drawer present
    }
}
```
(Ensure the literal strings `Featured Categories` and `Top Selling` appear in the markup.)

- [ ] **Step 3: Run the test**

```bash
php artisan test --filter=HomePageTest
```
Expected: PASS.

- [ ] **Step 4: Manual visual check + run deferred drawer check from Task 5**

```bash
npm run build && php artisan serve
```
Open `http://127.0.0.1:8000/` — verify hero carousel, category grid, two product carousels, promo band, footer; test Add-To-Cart → drawer.

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "Add homepage (Blueprint A) with hero carousel, categories and product carousels"
```

---

## Task 7: Category / Collection page (Blueprint B)

**Files:**
- Create: `resources/views/pages/category.blade.php`
- Create: `tests/Feature/CategoryPageTest.php`

Follow §4-B: breadcrumb, left sidebar filters, sort/view bar, 4-up grid, Load More.

- [ ] **Step 1: Write `pages/category.blade.php`**

`@extends('layouts.app')`. Container `max-w-content mx-auto px-4 py-6`. Structure:
- Breadcrumb: `Home > {{ $category['name'] }}` (`text-text text-sm`).
- `<h1 class="text-2xl md:text-3xl font-bold text-ink mb-4">{{ $category['name'] }}</h1>`
- Grid `grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-6`:
  - **Sidebar** (Alpine `x-data="{min:0,max:1000}"`): "FILTER BY CATEGORY" uppercase heading with orange underline + checkbox list of `$categories` (link or checkbox); "PRICE RANGE" with a dual range input styled orange (two `<input type=range>` bound to min/max, value labels `৳<span x-text>`); "BRANDS" checkbox list (static brand names).
  - **Main** (Alpine `x-data="{shown:8}"`):
    - Top bar: left `Sort By:` `<select class="field max-w-[200px]">` (Default, Price low→high, Price high→low); right grid/list toggle buttons (active = `text-primary`).
    - Grid: `grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4`; loop `$products` with `x-show="index < shown"`:
      ```blade
      @foreach($products as $i => $product)
          <div x-show="{{ $i }} < shown"><x-product-card :product="$product" /></div>
      @endforeach
      ```
    - `<button class="btn-primary mx-auto mt-8 flex" @click="shown += 8" x-show="shown < {{ count($products) }}">Load More</button>`

- [ ] **Step 2: Write `tests/Feature/CategoryPageTest.php`**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    public function test_category_page_renders(): void
    {
        $this->get('/category/cooking-essentials')
            ->assertOk()
            ->assertSee('Cooking Essentials')
            ->assertSee('FILTER BY CATEGORY')
            ->assertSee('Load More');
    }

    public function test_unknown_category_is_404(): void
    {
        $this->get('/category/does-not-exist')->assertNotFound();
    }
}
```

- [ ] **Step 3: Run the test**

```bash
php artisan test --filter=CategoryPageTest
```
Expected: PASS (both methods).

- [ ] **Step 4: Commit**

```bash
git add -A
git commit -m "Add category page (Blueprint B) with filters, sort bar and load-more grid"
```

---

## Task 8: Single Product page (Blueprint C)

**Files:**
- Create: `resources/views/pages/product.blade.php`
- Create: `tests/Feature/ProductPageTest.php`

Follow §4-C: gallery rail, info column, the signature four action buttons, tabs, related carousel.

- [ ] **Step 1: Write `pages/product.blade.php`**

`@extends('layouts.app')`. Breadcrumb `Home > Products`. Two-column `grid grid-cols-1 md:grid-cols-2 gap-8 max-w-content mx-auto px-4 py-6`:
- **Gallery** (`x-data="{main: $refs?... }"` — simpler: `x-data="{main:0, imgs: {{ Js::from($product['gallery']) }}}"`): vertical thumbnail rail (left, `flex md:flex-col gap-2`) each thumb `@click="main=i"` with active `border-primary`; large main image `:src="imgs[main]"` with prev/next chevrons (`@click="main=(main-1+imgs.length)%imgs.length"` / `+1`).
- **Info column:**
  - `<h1 class="text-2xl md:text-3xl font-bold text-ink">{{ $product['name'] }}</h1>`
  - `<x-rating-stars :rating="$product['rating']" :reviews="$product['reviews']" />`
  - Price block: `<x-price>` large + green Save pill (`@if($product['old_price'])<x-badge type="save" :label="..."/>` computed `Save {{ round((1-$product['price']/$product['old_price'])*100) }}%`).
  - Quantity stepper: `<x-qty-stepper :value="1" />` wrapped in `x-data="{qty:1}" @qty.window="qty=$event.detail"`.
  - **Four buttons** (§4-C table) in a `grid grid-cols-2 gap-3 mt-4`:
    ```blade
    <button class="btn-primary col-span-2 sm:col-span-1"
            @click="$store.cart.add({{ Js::from([...slug,name,price,image]) }}, qty)">🛒 Add To Cart</button>
    <a href="{{ route('checkout') }}" class="bg-dark text-white font-semibold uppercase rounded h-input flex items-center justify-center col-span-2 sm:col-span-1">Buy Now</a>
    <a href="https://wa.me/" class="bg-whatsapp text-white rounded-lg h-input flex items-center justify-center gap-2">Order On WhatsApp</a>
    <a href="tel:" class="bg-call text-white rounded-lg h-input flex items-center justify-center gap-2">Call For Order</a>
    ```
  - Brand badge: `<p class="text-sm text-text mt-3">Brand: <span class="text-ink font-medium">{{ $product['brand'] }}</span></p>`
- **Tabs** (`x-data="{tab:'desc'}"`): buttons "Description" / "Customer Reviews ({{ $product['reviews'] }})"; panels `x-show`. Description shows `$product['description']`; reviews shows a couple of static sample reviews.
- **Related products:** `<x-product-carousel title="Related products" :products="$related" />`.

Add `@php use Illuminate\Support\Js; @endphp` at top, or fully-qualify `\Illuminate\Support\Js::from(...)`.

- [ ] **Step 2: Write `tests/Feature/ProductPageTest.php`**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductPageTest extends TestCase
{
    public function test_product_page_renders_signature_buttons(): void
    {
        $this->get('/product/mustard-oil-1l')
            ->assertOk()
            ->assertSee('Pure Mustard Oil 1L')
            ->assertSee('Add To Cart')
            ->assertSee('Buy Now')
            ->assertSee('Order On WhatsApp')
            ->assertSee('Call For Order')
            ->assertSee('Related products');
    }

    public function test_unknown_product_is_404(): void
    {
        $this->get('/product/nope')->assertNotFound();
    }
}
```

- [ ] **Step 3: Run the test**

```bash
php artisan test --filter=ProductPageTest
```
Expected: PASS.

- [ ] **Step 4: Manual check**

Serve and open `/product/mustard-oil-1l` — gallery thumb switching, qty stepper, four buttons, tabs toggle, related carousel; Add-To-Cart respects qty.

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "Add single product page (Blueprint C) with gallery, four-button CTA and tabs"
```

---

## Task 9: Checkout page (Blueprint E)

**Files:**
- Create: `resources/views/pages/checkout.blade.php`
- Create: `tests/Feature/CheckoutPageTest.php`

Follow §4-E exactly: centered title+breadcrumb, login banner, two columns, §4-E form input spec (`.field`).

- [ ] **Step 1: Write `pages/checkout.blade.php`**

`@extends('layouts.app')`. Centered `<h1>Checkout</h1>` + breadcrumb. Login/Register banner card. Then `grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-6 max-w-content mx-auto px-4 pb-12`.

**Left column** (`space-y-6`):
- `<x-section-heading>Order review</x-section-heading>` then cart items rendered client-side from the Alpine store:
  ```blade
  <template x-for="item in $store.cart.items" :key="item.slug">
      <div class="flex items-center gap-3 py-3 border-b border-border-light">
          <img :src="item.image" class="w-14 h-14 object-cover rounded">
          <span class="flex-1 text-sm text-ink" x-text="item.name"></span>
          <div class="inline-flex items-center border border-border rounded text-sm">
              <button class="w-7 h-7" @click="$store.cart.setQty(item.slug,item.qty-1)">−</button>
              <span class="w-7 text-center" x-text="item.qty"></span>
              <button class="w-7 h-7" @click="$store.cart.setQty(item.slug,item.qty+1)">+</button>
          </div>
          <span class="text-sm text-primary font-semibold" x-text="`৳${item.price*item.qty}`"></span>
          <button class="text-sale" @click="$store.cart.remove(item.slug)">🗑</button>
      </div>
  </template>
  ```
- `<x-section-heading>Shipping Address</x-section-heading>`: inputs using `.field` — Full Name; phone with a `+88` prefix box (`<div class="flex"><span class="h-input px-3 flex items-center border border-border rounded-l-lg bg-cream">+88</span><input class="field rounded-l-none"></div>`); address `<textarea class="field h-auto py-2" rows="3">`; "Select District" + "Select Thana" `<select class="field">` with a few static options.
- `<x-section-heading>Billing Address</x-section-heading>`: orange radio toggle "Same as shipping" / "Different".

**Right column** (`space-y-6`), Alpine `x-data="{pay:'cod', terms:false, notes:''}"`:
- `<x-section-heading>Payment method</x-section-heading>`: three selectable cards (Cash On Delivery default, Online Payment, Bkash) — `@click="pay='cod'"`, selected card `border-primary` with green check `x-show="pay==='cod'"`.
- Coupon accordion (`x-data="{open:false}"`): "Have any coupon or gift voucher?" toggles an input + apply button.
- Summary box: Sub total (`x-text="`৳${$store.cart.total}`"`), Delivery cost (static ৳60), **Total** bold (`x-text="`৳${$store.cart.total + 60}`"`).
- Special notes `<textarea maxlength="90" x-model="notes" class="field h-auto py-2">` + counter `<span x-text="`${notes.length}/90 characters`"></span>`.
- Terms checkbox (orange) `x-model="terms"` with orange policy links.
- `<button class="btn-primary w-full rounded-sm" :disabled="!terms" :class="!terms && 'opacity-50 cursor-not-allowed'">Place Order</button>` (form `action="#"`).

- [ ] **Step 2: Write `tests/Feature/CheckoutPageTest.php`**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class CheckoutPageTest extends TestCase
{
    public function test_checkout_renders_sections(): void
    {
        $this->get('/checkout')
            ->assertOk()
            ->assertSee('Order review')
            ->assertSee('Shipping Address')
            ->assertSee('Payment method')
            ->assertSee('Place Order');
    }
}
```

- [ ] **Step 3: Run the test**

```bash
php artisan test --filter=CheckoutPageTest
```
Expected: PASS.

- [ ] **Step 4: Commit**

```bash
git add -A
git commit -m "Add checkout page (Blueprint E) with two-column layout, payment cards and summary"
```

---

## Task 10: 404 page (Blueprint F)

**Files:**
- Create: `resources/views/errors/404.blade.php`
- Create: `tests/Feature/NotFoundTest.php`

- [ ] **Step 1: Write `resources/views/errors/404.blade.php`**

`@extends('layouts.app')`. Centered column (`min-h-[60vh] flex flex-col items-center justify-center text-center px-4`):
- Large line-art "404" — an inline SVG or big text `<div class="text-[120px] font-extrabold text-ink404 leading-none">404</div>`.
- `<h2 class="text-2xl font-bold text-ink mt-4">OPPS! Page Not Found</h2>`
- `<p class="text-text mt-2">The page you are looking for doesn’t exist or has been moved.</p>`
- `<a href="{{ route('home') }}" class="btn-primary mt-6">← Back To Home</a>`

(Laravel resolves `resources/views/errors/404.blade.php` automatically for 404s; the `Route::fallback` from Task 2 also renders it.)

- [ ] **Step 2: Write `tests/Feature/NotFoundTest.php`**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class NotFoundTest extends TestCase
{
    public function test_unknown_url_shows_custom_404(): void
    {
        $this->get('/some/missing/page')
            ->assertNotFound()
            ->assertSee('OPPS! Page Not Found');
    }
}
```

- [ ] **Step 3: Run the test**

```bash
php artisan test --filter=NotFoundTest
```
Expected: PASS.

- [ ] **Step 4: Commit**

```bash
git add -A
git commit -m "Add custom 404 page (Blueprint F)"
```

---

## Task 11: Final integration pass

**Files:**
- Modify: `resources/views/welcome.blade.php` (delete — replaced by home)
- Possibly modify: `README.md`

- [ ] **Step 1: Remove default welcome view**

```bash
git rm resources/views/welcome.blade.php
```

- [ ] **Step 2: Run the full test suite**

```bash
php artisan test
```
Expected: all feature tests PASS (Home, Category, Product, Checkout, NotFound).

- [ ] **Step 3: Production build + full manual walkthrough**

```bash
npm run build && php artisan serve
```
Walk the full funnel: Home → click category → category filters/load-more → click product → product gallery/4 buttons/tabs → Add To Cart (drawer + gift bar) → Checkout (payment cards, notes counter, terms gating Place Order) → visit a bad URL → 404 → Back To Home. Confirm colors match `design.md` tokens (orange CTAs, dark-green nav/footer/Buy Now).

- [ ] **Step 4: Write a brief `README.md`**

Document: stack, `composer install && npm install && npm run build`, `php artisan serve`, and the **backend swap point** (`app/Support/Catalog.php` + `config/products.php`).

- [ ] **Step 5: Final commit**

```bash
git add -A
git commit -m "Remove default welcome view, add README, finalize frontend"
```

---

## Self-Review Notes (coverage vs spec)

- Blueprint A (Home) → Task 6 ✓; B (Category) → Task 7 ✓; C (Product) → Task 8 ✓; D (Cart drawer) → Task 5 ✓; E (Checkout) → Task 9 ✓; F (404) → Task 10 ✓.
- Design tokens (§2/§7) → Task 1 tailwind config ✓. Typography/Open Sans → Task 1 app.css ✓.
- Components (§5: primary/outline buttons, badges, inputs, section heading, progress/slider) → Tasks 1 (CSS), 4 (components), 5 (progress), 7 (slider) ✓.
- Data swap point (spec §5) → Task 2 `Catalog` ✓.
- Responsive (§8) → grid breakpoints across Tasks 6–9 ✓.
- All `Js::from(...)` references fully qualified or imported; `$store.cart` API (add/remove/setQty/total/count/remaining/giftProgress/threshold) defined in Task 1 and used consistently in Tasks 4,5,8,9 ✓.
