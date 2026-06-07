# Design Skill — "Ghorer Bazar" Style (Full E-commerce Flow)

Reference: ghorerbazar.com — a warm, trustworthy grocery/food e-commerce store.
Earthy orange primary + dark forest-green accents on clean white, photo-led,
with clear product cards and a friendly, conversion-focused purchase flow.

## 1. Brand Personality
- Warm, natural, organic, trustworthy; food-first
- Clean white canvas so product photography pops
- Single strong accent (orange) used consistently for all primary actions
- Conversion-oriented: multiple order paths, free-gift progress, COD-friendly

## 2. Color Palette

### Core
| Token                | Hex       | Use                                          |
|----------------------|-----------|----------------------------------------------|
| `--color-primary`    | `#f48721` | Brand orange — all primary CTAs, links, accents |
| `--color-primary-alt`| `#ff9800` | Hover orange / strong price / floating cart  |
| `--color-dark`       | `#041f1e` | Forest-green nav bar, footer, "Buy Now"      |
| `--color-ink`        | `#222831` | Headings & primary text                      |

### Neutrals
| Token              | Hex       | Use                                            |
|--------------------|-----------|------------------------------------------------|
| `--color-white`    | `#ffffff` | Page background, cards, drawers                |
| `--color-cream`    | `#fbf9f5` | Soft section tint                              |
| `--color-text`     | `#666666` | Body text, breadcrumbs                         |
| `--color-text-mute`| `#5c3d1e` | Muted brown captions                           |
| `--color-strike`   | `#aaaaaa` | Struck-through original prices                 |
| `--color-border`   | `#cccccc` | Card / input borders (light: `#eeeeee`)        |

### Status / Accent
| Token              | Hex       | Use                                            |
|--------------------|-----------|------------------------------------------------|
| `--color-success`  | `#34be82` | "Save X%" badges, in-stock                     |
| `--color-whatsapp` | `#1daa61` | WhatsApp order button                          |
| `--color-call`     | `#1e3a8a` | "Call For Order" button (navy)                 |
| `--color-sale`     | `#ff1818` | Sale / best-seller badge                       |
| `--color-404`      | `#252a34` | 404 illustration ink                           |

### Usage Rules
- White dominates (~70%). Orange is the only accent for primary actions.
- Dark forest-green is reserved for nav bar, footer, and the "Buy Now" button.
- White text on green/orange; dark ink on white; never orange text on green.

## 3. Typography
- **Font:** `"Open Sans", sans-serif` sitewide. Weights: 400, 500, 600, 700, 800.

| Element        | Size  | Weight | Color     |
|----------------|-------|--------|-----------|
| Page title (h1)| 28–32px| 700   | `#222831` |
| Section (h2)   | 22px  | 700    | `#222831` |
| Product price (current) | 16px | 600 | `#f48721` |
| Old price (strike)      | 16px | 400 | `#aaaaaa` line-through |
| Body           | 14px  | 400    | `#666666` |
| Caption        | 12px  | 400    | `#5c3d1e` |
| Buttons        | 13–15px| 600   | white/varies |

- Section headings on inner pages use a **left orange accent bar** (e.g.
  "Order review", "Shipping Address", "Payment method").
- Product-card section titles use a short **orange underline** beneath the title.

## 4. Page Blueprints

### A. Homepage
- Dark green sticky nav + white utility row (search, track order, sign in, wishlist, cart).
- Dual hero banners (carousel, active dot = orange) with bold overlaid headlines.
- "Featured Categories": row of white square cards with illustrated icons.
- Product carousels ("Top Selling", "Cooking Essentials") with orange "VIEW ALL →".
- Promo image bands between sections.
- Footer: white, multi-column links, social circles, app badges, "Pay With" logo strip, centered copyright.

### B. Category / Collection Page
- Breadcrumb: `Home > Category` in `#666666`, 14px.
- Page title left, large bold.
- **Left sidebar filters:** "FILTER BY CATEGORY" (checkboxes, uppercase headings
  with orange underline), "PRICE RANGE" (orange dual-handle slider), "BRANDS".
- Top bar: "Sort By: Default Sorting" dropdown (left) + view toggle (right, orange).
- **Product grid:** 4 cards across (5 on offer pages), white cards.
  - Card: white bg, `0.8px solid #cccccc`, `border-radius: 4px`, `padding: 8px`, no shadow (subtle shadow on hover recommended).
  - Corner badges: green "Save X%" / orange "New Arrival" / red "Best Selling".
  - Title → price row (current orange bold + struck-through grey) → outlined orange "Add To Cart".
- "Load More" button at bottom.

### C. Single Product Page
- Breadcrumb `Home > Products`.
- **Gallery:** vertical thumbnail rail (left) with active thumb = orange border, large main image (right) with prev/next chevrons.
- **Info column:** title (28–32px) → price block (orange current `#f48721`, struck-through old, green "Save 10%" pill).
- **Quantity stepper:** `−  1  +` in a bordered pill.
- **Four action buttons (this is the signature pattern):**
  | Button            | BG        | Text  | Radius | Notes               |
  |-------------------|-----------|-------|--------|---------------------|
  | ADD TO CART       | `#f48721` | white | 6px    | uppercase, 600, cart icon |
  | BUY NOW           | `#041f1e` | white | 6px    | uppercase, 600      |
  | Order On WhatsApp | `#1daa61` | white | 8px    | WhatsApp icon       |
  | Call For Order    | `#1e3a8a` | white | 8px    | phone icon          |
- Brand badge below buttons.
- Tabs: "Description" / "Customer Reviews (N)".
- "Related products" carousel at bottom.

### D. Cart (Slide-out Drawer, width 400px)
- Header: "SHOPPING CART" left, "Close →" right.
- **Free-gift progress bar:** gift icon + "Add ৳X more to unlock!" with an orange
  progress fill (`#f48721`) — strong upsell pattern.
- Line items: thumbnail, name, qty stepper, `unit × qty = subtotal`, "×" remove.
- "You May Also Like" mini carousel with orange circular arrows + "View" pills.
- "Total: ৳X" then full-width uppercase orange **CHECKOUT** button (radius 6px).

### E. Checkout Page (two-column)
- Title "Checkout" + breadcrumb, both centered.
- Login/Register banner: "Have any account? please login or register" with
  outlined "Login" + filled orange "Register" buttons.
- **Left column:**
  - "Order review" — items with qty stepper + red delete icon.
  - "Shipping Address" — inputs: Full Name, phone (with `+88` prefix box),
    address textarea, "Select District" + "Select Thana" dropdowns.
  - "Billing Address" — toggle (orange radio).
- **Right column:**
  - "Payment method" — selectable cards: Cash On Delivery (default, green check),
    Online Payment, Bkash.
  - "Have any coupon or gift voucher?" accordion.
  - Summary: Sub total / Delivery cost / **Total** (bold).
  - "Special notes (Optional)" textarea with `0/90 characters` counter.
  - Terms checkbox (orange) with linked policies (orange links).
  - Full-width uppercase orange **PLACE ORDER** button (radius 4px).
- **Form inputs:** `0.8px solid` border, `border-radius: 8px`, `~47px` height,
  padding `9px 16px`, white bg, 14px text.

### F. 404 Page
- Centered purple line-art "404" illustration (`#252a34`).
- Bold "OPPS! Page Not Found" heading + muted subtext.
- Orange "← BACK TO HOME" button.

## 5. Components Summary
- **Primary button:** orange `#f48721`, white text, radius 4–6px, weight 600, uppercase for primary CTAs.
- **Outlined button (card add-to-cart):** white fill, orange border+text, fills orange on hover.
- **Badges:** small pills — green save, orange new, red best-seller.
- **Inputs/selects:** light border, 4–8px radius, ~47px tall.
- **Section heading:** left orange accent bar, bold ink text.
- **Progress bar / sliders:** orange fill.

## 6. Layout & Spacing
- Max content width ~1200–1400px, centered.
- Section spacing ~48–64px; consistent 4–8px corner radius.
- Category grid: 4–5 across desktop → 2 on mobile.
- Checkout & cart: clean white cards with generous internal padding.

## 7. CSS Tokens
```css
:root {
  --color-primary: #f48721;
  --color-primary-alt: #ff9800;
  --color-dark: #041f1e;
  --color-ink: #222831;
  --color-cream: #fbf9f5;
  --color-text: #666666;
  --color-text-mute: #5c3d1e;
  --color-strike: #aaaaaa;
  --color-border: #cccccc;
  --color-border-light: #eeeeee;
  --color-success: #34be82;
  --color-whatsapp: #1daa61;
  --color-call: #1e3a8a;
  --color-sale: #ff1818;

  --font-base: "Open Sans", sans-serif;
  --radius-sm: 4px;
  --radius: 6px;
  --radius-lg: 8px;
  --shadow-card: 0 4px 12px rgba(0,0,0,0.08);
  --input-height: 47px;
}
```