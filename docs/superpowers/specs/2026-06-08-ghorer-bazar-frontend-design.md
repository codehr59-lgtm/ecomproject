# Ghorer Bazar–Style Laravel Frontend — Implementation Spec

**Date:** 2026-06-08
**Status:** Approved (design), pending implementation plan
**Visual reference:** [`design.md`](../../../design.md) (authoritative for colors, typography, spacing, blueprints)

## 1. Goal

Build the **complete frontend** of a Ghorer Bazar–style grocery/food e-commerce
store as a Laravel application. The client will wire the backend later, so every
view must render fully with swappable placeholder data and zero backend
dependencies (no DB, no auth, no real endpoints).

## 2. Stack

| Concern | Choice |
|---------|--------|
| Framework | Laravel 11 (fresh `composer create-project`) |
| Templating | Blade |
| CSS | Tailwind CSS via Vite |
| JS / interactivity | Alpine.js |
| Fonts | Open Sans (Google Fonts) |
| Data | `config/products.php` plain-array fixtures |
| Images | Themed remote placeholder URLs (Unsplash/picsum, grocery/food) |

Rationale: Blade + Tailwind + Alpine is the canonical modern Laravel frontend.
Array-based data means the backend swap is `array source → Eloquent` with **no
view changes**. Remote images keep the repo light and are trivially replaceable.

## 3. Design Tokens → Tailwind

The `:root` tokens in `design.md` §7 are mirrored into `tailwind.config.js`:

```js
colors: {
  primary: '#f48721', 'primary-alt': '#ff9800',
  dark: '#041f1e', ink: '#222831', cream: '#fbf9f5',
  text: '#666666', 'text-mute': '#5c3d1e', strike: '#aaaaaa',
  border: '#cccccc', 'border-light': '#eeeeee',
  success: '#34be82', whatsapp: '#1daa61', call: '#1e3a8a', sale: '#ff1818',
}
borderRadius: { sm: '4px', DEFAULT: '6px', lg: '8px' }
fontFamily: { base: ['"Open Sans"', 'sans-serif'] }
```

The raw CSS variables from §7 are also kept in `resources/css/app.css` as a
documentation/fallback layer. `--input-height: 47px` and `--shadow-card` are
exposed as a Tailwind `h-input` utility / `shadow-card`.

Usage rules from design.md §2 are enforced in components: white dominates (~70%),
orange is the only primary-action accent, dark forest-green reserved for nav,
footer, and the "Buy Now" button. White text on green/orange; ink on white.

## 4. Routes

| Method | URI | Controller/closure | View | Blueprint |
|--------|-----|--------------------|------|-----------|
| GET | `/` | `CatalogController@home` | `pages.home` | A |
| GET | `/category/{slug}` | `CatalogController@category` | `pages.category` | B |
| GET | `/product/{slug}` | `CatalogController@product` | `pages.product` | C |
| GET | `/checkout` | `CatalogController@checkout` | `pages.checkout` | E |
| — | fallback | `Route::fallback` | `errors.404` | F |

The cart (Blueprint D) is a **global slide-out drawer** present in the layout on
every page, not a route.

`{slug}` lookups resolve against the `config/products.php` fixtures; an unknown
slug returns the 404 view.

## 5. Data Model (fixtures)

`config/products.php` returns:

```php
return [
  'categories' => [
    ['slug' => 'cooking-essentials', 'name' => 'Cooking Essentials', 'icon' => '...'],
    // ...
  ],
  'products' => [
    [
      'slug' => 'mustard-oil-1l',
      'name' => 'Pure Mustard Oil 1L',
      'category' => 'cooking-essentials',
      'price' => 480, 'old_price' => 550,        // ৳, old_price nullable
      'badge' => ['type' => 'save', 'label' => 'Save 13%'], // save|new|best, nullable
      'image' => 'https://images.unsplash.com/...',
      'gallery' => ['url', 'url', ...],
      'brand' => 'Ghorer Bazar',
      'rating' => 4.7, 'reviews' => 128,
      'description' => '...',
      'in_stock' => true,
    ],
    // ~12–20 demo products across categories
  ],
  'banners' => [
    ['image' => '...', 'headline' => '...', 'cta' => 'Shop Now', 'href' => '...'],
  ],
  'free_gift_threshold' => 1000, // ৳ to unlock free gift (cart progress bar)
];
```

Helper accessors (a small `Catalog` support class or controller methods):
`all()`, `byCategory($slug)`, `find($slug)`, `featured()`, `topSelling()`,
`related($slug)`. **Backend swap point:** replace these method bodies with
Eloquent queries; views and Blade components stay identical.

## 6. View / Component Structure

```
resources/views/
  layouts/app.blade.php            # head, fonts, @yield('content'), includes header/footer/cart-drawer
  partials/
    header.blade.php               # dark-green sticky nav + white utility row (search, track, sign in, wishlist, cart)
    footer.blade.php               # multi-column links, social circles, app badges, "Pay With" strip, copyright
    cart-drawer.blade.php          # Alpine drawer: free-gift progress, line items, "You May Also Like", checkout btn
  components/                      # Blade components (<x-...>)
    product-card.blade.php         # white card, corner badge, title, price row, outlined add-to-cart
    section-heading.blade.php      # left orange accent bar OR orange underline variant (prop)
    price.blade.php                # current orange bold + struck-through grey old price
    badge.blade.php                # save(green)/new(orange)/best(red) pill
    qty-stepper.blade.php          # − n + bordered pill (Alpine)
    product-carousel.blade.php     # horizontal scroll row + "VIEW ALL →"
    rating-stars.blade.php
  pages/
    home.blade.php                 # A: dual hero carousel, featured categories, product carousels, promo bands
    category.blade.php             # B: breadcrumb, sidebar filters, sort bar, 4-up grid, Load More
    product.blade.php              # C: gallery rail, info column, 4-button CTA, tabs, related carousel
    checkout.blade.php             # E: two-column, order review, addresses, payment cards, summary, place order
  errors/
    404.blade.php                  # F: line-art 404, "OPPS! Page Not Found", orange back-to-home
```

## 7. Interactivity (Alpine.js, client-only)

A single Alpine store (`Alpine.store('cart')`) holds cart line items in memory:

- **Cart drawer:** open/close; add/remove items; qty change recomputes subtotal,
  total, and free-gift progress (`min(100, total/threshold*100)%` orange fill);
  "Add ৳X more to unlock!" text updates live.
- **Quantity steppers:** `−/+` on product page and checkout order-review.
- **Product gallery:** thumbnail rail switches main image; active thumb = orange
  border; prev/next chevrons.
- **Category page:** price dual-handle slider (orange), category/brand checkbox
  filters, sort dropdown, grid/list view toggle, "Load More" reveals more cards.
- **Checkout:** payment-method card selection (COD default, green check),
  coupon accordion, billing-address toggle, special-notes `0/90` char counter,
  terms checkbox gating the Place Order button.
- **Tabs:** Description / Customer Reviews on product page.

State is **not persisted** and submits nowhere. Backend wiring point: "Add to
cart", "Checkout", and "Place Order" later target real endpoints; forms currently
POST to `#`.

## 8. Responsive Behaviour

- Max content width ~1200–1400px, centered (Tailwind `max-w-screen-xl` container).
- Category grid: 4–5 across desktop → 2 on mobile (offer pages 5-up).
- Header collapses to a mobile menu; cart drawer full-width on small screens.
- Section spacing ~48–64px; corner radius 4–8px throughout.

## 9. Out of Scope (backend — client will build)

- Persistence, real cart/session, orders, payments, auth/login/register logic.
- Product CRUD, inventory, district/thana data sources, coupon validation.
- Email, search backend (search box is a styled input only).

## 10. Success Criteria

- All 6 blueprints (A–F) render pixel-faithfully to `design.md` tokens.
- Cart drawer, steppers, gallery, filters, checkout selectors are interactive.
- `npm run build` and `php artisan serve` produce a working browsable site.
- Swapping `config/products.php` fixture methods for Eloquent requires **no
  Blade changes**.
