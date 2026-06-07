@extends('layouts.app')
@section('title', ($category['name'] ?? 'Shop').' — Shuvo')

@php
  $pageTitle = $category['name'] ?? 'All Products';
  $pageSub   = $category['note'] ?? 'Pure, organic and honestly sourced — pick your aisle.';

  $jsProducts = \Illuminate\Support\Js::from(array_map(fn($p) => [
      'id'        => $p['id'],
      'name'      => $p['name'],
      'weight'    => $p['weight'],
      'price'     => $p['price'],
      'old_price' => $p['old_price'] ?? null,
      'cat'       => $p['cat'],
      'badge'     => $p['badge'] ?? null,
      'certified' => !empty($p['certified']),
  ], $products));

  $jsCategories = \Illuminate\Support\Js::from(array_map(fn($c) => [
      'id'   => $c['id'],
      'name' => $c['name'],
  ], $categories));
@endphp

@section('content')

{{-- ============================================================
     PAGE HEAD — breadcrumb + title
     ============================================================ --}}
<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M9 18l6-6-6-6"/>
      </svg>
      @if($category)
        <a href="{{ route('shop') }}">Shop</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M9 18l6-6-6-6"/>
        </svg>
      @endif
      <span>{{ $pageTitle }}</span>
    </div>
    <h1>{{ $pageTitle }}</h1>
    <p class="sub">{{ $pageSub }}</p>
  </div>
</div>

{{-- ============================================================
     SHOP LAYOUT — filters sidebar + product results
     ============================================================ --}}
<div class="wrap"
     x-data="shopPage({{ $jsProducts }}, {{ $jsCategories }}, '{{ $activeCat ?? '' }}')"
     x-cloak>

  <div class="shop-layout">

    {{-- ── FILTERS SIDEBAR ─────────────────────────────────────── --}}
    <aside class="filters app-scroll" id="shop-filters" :class="filtersOpen ? 'on' : ''">

      {{-- Filters header row --}}
      <div class="filter-group" style="display:flex;align-items:center;justify-content:space-between;border-bottom:none;padding-bottom:4px;">
        <h5 style="margin:0;">Filters</h5>
        <div style="display:flex;align-items:center;gap:8px;">
          <button class="cart-rm"
                  style="color:var(--green);font-size:13px;font-weight:600;"
                  x-show="hasActiveFilters()"
                  @click="clearAll()">Clear all</button>
          <button class="x icon-btn"
                  style="width:32px;height:32px;border-radius:8px;"
                  x-show="filtersOpen"
                  @click="filtersOpen = false"
                  aria-label="Close filters">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>

      {{-- Filter Group: Categories --}}
      <div class="filter-group">
        <h5>Categories</h5>
        @foreach($categories as $c)
          <div class="fopt"
               @click="toggleCat('{{ $c['id'] }}')"
               role="checkbox"
               :aria-checked="sel.includes('{{ $c['id'] }}')"
               tabindex="0"
               @keydown.space.prevent="toggleCat('{{ $c['id'] }}')">
            <span class="checkbox" :class="sel.includes('{{ $c['id'] }}') ? 'on' : ''">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 6L9 17l-5-5"/>
              </svg>
            </span>
            {{ $c['name'] }}
            <span class="fcount">{{ $c['count'] }}</span>
          </div>
        @endforeach
      </div>

      {{-- Filter Group: Highlights --}}
      <div class="filter-group">
        <h5>Highlights</h5>
        <div class="chip-row">
          <button class="chip" :class="highlights.best ? 'on' : ''" @click="highlights.best = !highlights.best">Best Selling</button>
          <button class="chip" :class="highlights.new ? 'on' : ''"  @click="highlights.new  = !highlights.new">New</button>
          <button class="chip" :class="highlights.preorder ? 'on' : ''" @click="highlights.preorder = !highlights.preorder">Pre-order</button>
          <button class="chip" :class="highlights.certified ? 'on' : ''" @click="highlights.certified = !highlights.certified">Organic Certified</button>
        </div>
      </div>

      {{-- Filter Group: Max Price --}}
      <div class="filter-group">
        <h5>
          Max Price
          <span style="color:var(--green);font-weight:800;">৳<span x-text="priceMax.toLocaleString()"></span></span>
        </h5>
        <input type="range" min="0" max="4000" step="50"
               x-model.number="priceMax"
               style="width:100%;accent-color:var(--green);"
               aria-label="Maximum price filter">
        <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--muted);margin-top:4px;">
          <span>৳0</span><span>৳4,000+</span>
        </div>
      </div>

    </aside>
    {{-- end filters --}}

    {{-- ── RESULTS MAIN COLUMN ─────────────────────────────────── --}}
    <div>

      {{-- Toolbar: mobile filter toggle + result count + sort --}}
      <div class="shop-toolbar">
        <div style="display:flex;align-items:center;gap:12px;">

          {{-- Mobile filter toggle --}}
          <button class="btn btn-ghost filter-toggle"
                  @click="filtersOpen = true"
                  aria-expanded="false"
                  aria-controls="shop-filters">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="4" y1="6"  x2="20" y2="6"/>
              <line x1="4" y1="12" x2="14" y2="12"/>
              <line x1="4" y1="18" x2="11" y2="18"/>
            </svg>
            Filters
          </button>

          <span class="result-count">
            Showing <b x-text="visibleIds.length"></b> products
          </span>
        </div>

        {{-- Sort --}}
        <div class="sortbox">
          <div class="select">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="3" y1="6" x2="21" y2="6"/>
              <line x1="3" y1="12" x2="15" y2="12"/>
              <line x1="3" y1="18" x2="9" y2="18"/>
            </svg>
            <select x-model="sort" aria-label="Sort products">
              <option value="featured">Featured</option>
              <option value="low">Price: Low → High</option>
              <option value="high">Price: High → Low</option>
              <option value="name">Name A–Z</option>
            </select>
          </div>
        </div>
      </div>
      {{-- end toolbar --}}

      {{-- Active filter pills --}}
      <div class="active-filters" x-show="hasActiveFilters()" x-cloak>

        {{-- Category pills --}}
        <template x-for="catId in sel" :key="catId">
          <span class="fpill">
            <span x-text="catLabel(catId)"></span>
            <button @click="toggleCat(catId)" aria-label="Remove category filter">
              <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                <path d="M18 6L6 18M6 6l12 12"/>
              </svg>
            </button>
          </span>
        </template>

        {{-- Highlight pills --}}
        <span class="fpill" x-show="highlights.best">
          Best Selling
          <button @click="highlights.best = false" aria-label="Remove Best Selling filter">
            <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </span>
        <span class="fpill" x-show="highlights.new">
          New
          <button @click="highlights.new = false" aria-label="Remove New filter">
            <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </span>
        <span class="fpill" x-show="highlights.preorder">
          Pre-order
          <button @click="highlights.preorder = false" aria-label="Remove Pre-order filter">
            <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </span>
        <span class="fpill" x-show="highlights.certified">
          Organic Certified
          <button @click="highlights.certified = false" aria-label="Remove Certified filter">
            <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </span>

        {{-- Price pill --}}
        <span class="fpill" x-show="priceMax < 4000">
          Under ৳<span x-text="priceMax.toLocaleString()"></span>
          <button @click="priceMax = 4000" aria-label="Remove price filter">
            <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </span>

      </div>
      {{-- end active filters --}}

      {{-- Product grid — server-rendered cards with Alpine x-show --}}
      <div class="grid-4">
        @foreach($products as $p)
          <div x-show="visibleIds.includes({{ $p['id'] }})">
            <x-product-card :product="$p" />
          </div>
        @endforeach
      </div>

      {{-- Empty state --}}
      <div class="empty-state" x-show="visibleIds.length === 0">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="11" cy="11" r="8"/>
          <path d="M21 21l-4.35-4.35"/>
        </svg>
        <h3 style="font-size:20px;margin:12px 0 8px;">No products match</h3>
        <p>Try widening your filters or clearing the search.</p>
        <button class="btn btn-primary" style="margin-top:16px;" @click="clearAll()">Clear filters</button>
      </div>

      {{-- Load more --}}
      <div style="text-align:center;padding:28px 0 16px;" x-show="filteredCount > shown">
        <button class="btn btn-ghost load-more" @click="shown += 12">
          Load More
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 5v14M5 12l7 7 7-7"/>
          </svg>
        </button>
      </div>

    </div>
    {{-- end main column --}}

  </div>
  {{-- end shop-layout --}}

</div>
{{-- end .wrap --}}

@endsection

@push('scripts')
<script>
/**
 * Alpine component factory for the shop/listing page.
 *
 * DOM-order sorting note: because product cards are server-rendered Blade,
 * we cannot reorder DOM nodes without re-rendering. Sort affects which
 * products are included in the "first N shown" slice (i.e. sorting changes
 * the priority of which IDs are passed to visibleIds up to the `shown` limit)
 * but the visual card order matches the original server-rendered order.
 * This is a known simplification acceptable for this phase.
 *
 * @param {Array}  allProducts  - all products passed from blade via Js::from
 * @param {Array}  allCats      - categories [{id, name}]
 * @param {string} activeCat    - pre-selected category id or ''
 */
function shopPage(allProducts, allCats, activeCat) {
  return {
    // ── State ──────────────────────────────────────────────────
    all:        allProducts,
    cats:       allCats,
    sel:        activeCat ? [activeCat] : [],
    highlights: { best: false, new: false, preorder: false, certified: false },
    priceMax:   4000,
    sort:       'featured',
    shown:      12,
    filtersOpen: false,

    // ── Computed: filtered + sorted list ──────────────────────
    filtered() {
      let list = this.all.filter(p => {
        // category filter
        if (this.sel.length && !this.sel.includes(p.cat)) return false;
        // price filter
        if (p.price > this.priceMax) return false;
        // highlight filters
        if (this.highlights.best      && p.badge !== 'best')      return false;
        if (this.highlights.new       && p.badge !== 'new')        return false;
        if (this.highlights.preorder  && p.badge !== 'preorder')   return false;
        if (this.highlights.certified && !p.certified)             return false;
        return true;
      });

      // sort
      list = [...list].sort((a, b) => {
        if (this.sort === 'low')  return a.price - b.price;
        if (this.sort === 'high') return b.price - a.price;
        if (this.sort === 'name') return a.name.localeCompare(b.name);
        // 'featured': best-badge first, then by id (insertion order)
        const ab = a.badge === 'best' ? 1 : 0;
        const bb = b.badge === 'best' ? 1 : 0;
        return bb - ab || a.id - b.id;
      });

      return list;
    },

    // ── Derived counts/IDs ────────────────────────────────────
    get filteredCount() {
      return this.filtered().length;
    },

    get visibleIds() {
      return this.filtered().slice(0, this.shown).map(p => p.id);
    },

    // ── Actions ───────────────────────────────────────────────
    toggleCat(id) {
      if (this.sel.includes(id)) {
        this.sel = this.sel.filter(x => x !== id);
      } else {
        this.sel = [...this.sel, id];
      }
      this.shown = 12; // reset pagination on filter change
    },

    clearAll() {
      this.sel        = [];
      this.highlights = { best: false, new: false, preorder: false, certified: false };
      this.priceMax   = 4000;
      this.sort       = 'featured';
      this.shown      = 12;
    },

    hasActiveFilters() {
      return this.sel.length > 0
        || this.highlights.best
        || this.highlights.new
        || this.highlights.preorder
        || this.highlights.certified
        || this.priceMax < 4000;
    },

    catLabel(id) {
      const c = this.cats.find(x => x.id === id);
      return c ? c.name : id;
    },
  };
}
</script>
@endpush

