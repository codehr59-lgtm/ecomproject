@extends('layouts.app')
@section('title', 'Shuvo — Pure, Organic & Halal Groceries')

@section('content')

{{-- ============================================================
     1. SPLIT HERO
     ============================================================ --}}
<div class="home-hero">
  <div class="wrap">
    <div class="hero-split" x-data="{ slide: 0 }">

      {{-- Left banner --}}
      <a class="hbanner hbanner-left" href="{{ route('shop') }}">
        <div class="hbanner-inner">
          <span class="hb-kicker">STRAIGHT FROM NATURE</span>
          <h2>Pure food, to your home</h2>
          <span class="btn">
            Shop the harvest
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M13 6l6 6-6 6"/>
            </svg>
          </span>
        </div>
        <span class="hbanner-tag">hero banner · product lineup</span>
      </a>

      {{-- Right banner --}}
      <a class="hbanner hbanner-right" href="{{ route('category', 'mango') }}">
        <div class="hbanner-inner">
          <span class="hb-kicker">SEASON SPECIAL</span>
          <h2>Naturally sweet mangoes</h2>
          <p>Pre-order fresh from the orchard.</p>
          <span class="btn btn-honey">Reserve now</span>
        </div>
        <span class="hbanner-tag">banner · mango</span>
      </a>

    </div>

    {{-- Decorative dots --}}
    <div class="hero-dots">
      <i class="on" @click="slide=0"></i>
      <i @click="slide=1"></i>
      <i @click="slide=2"></i>
      <i @click="slide=3"></i>
    </div>
  </div>
</div>

{{-- ============================================================
     2. FEATURED CATEGORIES
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <x-center-title title="Featured Categories" />
    <div class="fcat-row">
      @foreach($categories as $c)
        <x-category-tile :category="$c" />
      @endforeach
    </div>
  </div>
</div>

{{-- ============================================================
     3. TOP SELLING PRODUCTS
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <x-center-title title="Top Selling Products" />
    <div class="top-grid">
      @foreach(array_slice($topSelling, 0, 4) as $p)
        <x-top-card :product="$p" />
      @endforeach
    </div>
  </div>
</div>

{{-- ============================================================
     4. OUR BRANDS
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <x-rail-head title="Our Brands" :viewAll="route('shop')" />
    <div class="brands-row">
      @foreach($brands as $b)
        <x-brand-card :name="$b" />
      @endforeach
    </div>
  </div>
</div>

{{-- ============================================================
     5a. MANGO RAIL
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <x-rail-head title="Mango" :viewAll="route('category', 'mango')" />
    <div class="grid-5">
      @foreach(array_slice($mango, 0, 5) as $p)
        <x-product-card :product="$p" />
      @endforeach
    </div>
    <div class="dots">
      <i class="on"></i>
      <i></i>
      <i></i>
      <i></i>
      <i></i>
    </div>
  </div>
</div>

{{-- ============================================================
     5b. ALL NATURAL HONEY RAIL
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <x-rail-head title="All Natural Honey" :viewAll="route('category', 'honey')" />
    <div class="grid-5">
      @foreach(array_slice($honey, 0, 5) as $p)
        <x-product-card :product="$p" />
      @endforeach
    </div>
    <div class="dots">
      <i class="on"></i>
      <i></i>
      <i></i>
      <i></i>
      <i></i>
    </div>
  </div>
</div>

{{-- ============================================================
     6. EXCLUSIVE COMBO DEALS
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <div class="combo-band">
      <div class="combo-band-head">
        <span class="cb-ico">
          {{-- gift svg --}}
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
          </svg>
        </span>
        <h2>Exclusive Combo Deals</h2>
        <a class="view-all" href="{{ route('shop') }}">
          View All Combos
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M13 6l6 6-6 6"/>
          </svg>
        </a>
      </div>
      <div class="combo-strip">
        @foreach($combos as $c)
          <x-combo-card :combo="$c" />
        @endforeach
      </div>
    </div>
  </div>
</div>

{{-- ============================================================
     5c. PREMIUM DATES RAIL
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <x-rail-head title="Premium Dates" :viewAll="route('category', 'dates')" />
    <div class="grid-5">
      @foreach(array_slice($dates, 0, 5) as $p)
        <x-product-card :product="$p" />
      @endforeach
    </div>
    <div class="dots">
      <i class="on"></i>
      <i></i>
      <i></i>
      <i></i>
      <i></i>
    </div>
  </div>
</div>

{{-- ============================================================
     7. FULL-WIDTH IMAGE BAND
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <div class="img-band">
      <h3>Sourced with care, delivered with trust</h3>
      <span class="hbanner-tag">lifestyle · banner</span>
    </div>
  </div>
</div>

{{-- ============================================================
     5d. COOKING ESSENTIALS RAIL
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <x-rail-head title="Cooking Essentials" :viewAll="route('category', 'oil-ghee')" />
    <div class="grid-5">
      @foreach(array_slice($oilGhee, 0, 5) as $p)
        <x-product-card :product="$p" />
      @endforeach
    </div>
    <div class="dots">
      <i class="on"></i>
      <i></i>
      <i></i>
      <i></i>
      <i></i>
    </div>
  </div>
</div>

{{-- ============================================================
     5e. ORGANIC CERTIFIED RAIL
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <x-rail-head title="Organic Certified" :viewAll="route('shop')" />
    <div class="grid-5">
      @foreach(array_slice($certified, 0, 5) as $p)
        <x-product-card :product="$p" />
      @endforeach
    </div>
    <div class="dots">
      <i class="on"></i>
      <i></i>
      <i></i>
      <i></i>
      <i></i>
    </div>
  </div>
</div>

{{-- ============================================================
     8. JUST FOR YOU
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <x-rail-head title="Just For You" />
    <div class="grid-5">
      @foreach(array_slice($justForYou, 0, 10) as $p)
        <x-product-card :product="$p" :buyNow="false" />
      @endforeach
    </div>
    <div class="load-more">
      <button type="button">Load More</button>
    </div>
  </div>
</div>

{{-- ============================================================
     9. TESTIMONIALS
     ============================================================ --}}
<div class="section">
  <div class="wrap">
    <x-center-title title="What Our Customers Say" />
    <div class="grid-4" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:18px;margin-top:8px;">
      @foreach($testimonials as $t)
        <div class="tcard">
          {{-- 5 star rating --}}
          <div class="stars">
            @for($i = 0; $i < 5; $i++)
              <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
              </svg>
            @endfor
          </div>
          <p>"{{ $t['text'] }}"</p>
          <div class="tcard-author">
            <span class="t-avatar">{{ mb_substr($t['name'], 0, 1) }}</span>
            <span>
              <b>{{ $t['name'] }}</b>
              <span>{{ $t['role'] }}</span>
            </span>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

@endsection
