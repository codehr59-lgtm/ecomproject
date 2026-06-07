@php use Illuminate\Support\Js; @endphp
@extends('layouts.app')
@section('title', $product['name'].' — Shuvo')

@php
  /* ── Discount calc ─────────────────────────────────────────── */
  $pct  = !empty($product['old_price'])
        ? round((1 - $product['price'] / $product['old_price']) * 100)
        : 0;
  $save = !empty($product['old_price']) ? ($product['old_price'] - $product['price']) : 0;

  /* ── Weight options: product's own size, plus a smaller alt ── */
  $mainWeight = $product['weight'];
  $mainPrice  = $product['price'];

  /* Derive alternate: half-size at ~55% price, full-size at full price */
  $altWeight = str_contains($mainWeight, 'kg')
             ? str_replace(' kg', '00 g', $mainWeight)  // 1 kg → 100 g (rough)
             : null;

  /* Better alt logic: if weight contains "kg", offer 500g; else offer 250g */
  if (str_contains($mainWeight, 'kg')) {
      $altWeight = '500 g';
  } elseif (str_contains($mainWeight, '500')) {
      $altWeight = '250 g';
  } elseif (str_contains($mainWeight, '250')) {
      $altWeight = '100 g';
  } else {
      $altWeight = null;
  }

  $altPrice = $altWeight ? (int) round($mainPrice * 0.55) : null;

  /* JS-safe product object for Alpine */
  $jsP = Js::from([
      'id'     => $product['id'],
      'name'   => $product['name'],
      'weight' => $product['weight'],
      'price'  => $product['price'],
      'cat'    => $product['cat'],
  ]);

  /* Category name lookup (categories not passed, derive from cat slug) */
  $catNames = [
      'honey'    => 'Honey',
      'dates'    => 'Dates',
      'oil-ghee' => 'Oil & Ghee',
      'spices'   => 'Spices',
      'nuts'     => 'Nuts & Seeds',
      'rice'     => 'Rice',
      'mango'    => 'Mango',
      'tea'      => 'Tea & Coffee',
  ];
  $catName = $catNames[$product['cat']] ?? ucfirst($product['cat']);
@endphp

@section('content')

{{-- ============================================================
     PAGE HEAD — breadcrumb + page title bar
     ============================================================ --}}
<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px">
        <path d="M9 18l6-6-6-6"/>
      </svg>
      <a href="{{ route('shop') }}">Shop</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px">
        <path d="M9 18l6-6-6-6"/>
      </svg>
      <a href="{{ route('category', $product['cat']) }}">{{ $catName }}</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px">
        <path d="M9 18l6-6-6-6"/>
      </svg>
      <span>{{ $product['name'] }}</span>
    </div>
  </div>
</div>

{{-- ============================================================
     PDP — two-column layout (gallery + info)
     ============================================================ --}}
<div class="wrap">
  <div class="pdp"
       x-data="{
         qty: 1,
         img: 0,
         weightIdx: 0,
         P: {{ $jsP }},
         weights: [
           { label: {{ Js::from($mainWeight) }}, price: {{ $mainPrice }} }
           @if($altWeight), { label: {{ Js::from($altWeight) }}, price: {{ $altPrice }} }@endif
         ]
       }">

    {{-- ── Gallery column ───────────────────────────────────────── --}}
    <div class="pdp-gallery">

      {{-- Main image --}}
      <div class="pdp-main-img">
        <div class="ph" style="--ph-bg: {{ ['honey'=>'#E7B84B','dates'=>'#A9682F','oil-ghee'=>'#D7A53C','spices'=>'#C0432F','nuts'=>'#9C7A4D','rice'=>'#C9B98E','mango'=>'#E59A2B','tea'=>'#6E7F4F'][$product['cat']] ?? '#C9B98E' }}33; width:100%; height:100%;">
          <div class="ph-inner">
            <div class="ph-jar" style="width:90px;height:104px;margin:0 auto 16px;background:{{ ['honey'=>'#E7B84B','dates'=>'#A9682F','oil-ghee'=>'#D7A53C','spices'=>'#C0432F','nuts'=>'#9C7A4D','rice'=>'#C9B98E','mango'=>'#E59A2B','tea'=>'#6E7F4F'][$product['cat']] ?? '#C9B98E' }}44;"></div>
            <div class="ph-label" x-text="'{{ strtoupper($catName) }} · ' + weights[weightIdx].label + ' · photo ' + (img+1)"></div>
          </div>
        </div>
      </div>

      {{-- Thumbnails --}}
      <div class="pdp-thumbs">
        @for($n = 0; $n < 3; $n++)
          <div class="pdp-thumb"
               :class="img === {{ $n }} ? 'on' : ''"
               @click="img = {{ $n }}"
               style="--ph-bg: {{ ['honey'=>'#E7B84B','dates'=>'#A9682F','oil-ghee'=>'#D7A53C','spices'=>'#C0432F','nuts'=>'#9C7A4D','rice'=>'#C9B98E','mango'=>'#E59A2B','tea'=>'#6E7F4F'][$product['cat']] ?? '#C9B98E' }}33;"
               aria-label="Product image {{ $n + 1 }}">
            <div class="ph-jar" style="width:26px;height:30px;margin:0;background:{{ ['honey'=>'#E7B84B','dates'=>'#A9682F','oil-ghee'=>'#D7A53C','spices'=>'#C0432F','nuts'=>'#9C7A4D','rice'=>'#C9B98E','mango'=>'#E59A2B','tea'=>'#6E7F4F'][$product['cat']] ?? '#C9B98E' }}44;"></div>
          </div>
        @endfor
      </div>
    </div>

    {{-- ── Info column ───────────────────────────────────────────── --}}
    <div class="pdp-info">

      {{-- Category label --}}
      <span class="pcard-cat">{{ strtoupper($catName) }}</span>

      {{-- Badges --}}
      <div style="display:flex;gap:6px;flex-wrap:wrap;margin:6px 0 10px;">
        @if($pct > 0)<span class="badge badge-save">Save {{ $pct }}%</span>@endif
        @if(($product['badge'] ?? null) === 'new')<span class="badge badge-new">New</span>@endif
        @if(($product['badge'] ?? null) === 'best')<span class="badge badge-best">Best Seller</span>@endif
        @if(($product['badge'] ?? null) === 'preorder')<span class="badge badge-pre">Pre-order</span>@endif
        @if(!empty($product['certified']))<span class="badge badge-new">Certified</span>@endif
      </div>

      {{-- Product name --}}
      <h1>{{ $product['name'] }}</h1>

      {{-- Rating row --}}
      <div class="pdp-rate">
        <x-stars :rating="$product['rating']" />
        <b style="color:var(--ink)">{{ $product['rating'] }}</b>
        <span>· {{ $product['reviews'] }} reviews</span>
        <span style="color:var(--green);font-weight:600">· In stock</span>
      </div>

      {{-- Price row --}}
      <div class="pdp-price">
        <span class="price"><span class="tk">৳</span>{{ number_format($product['price']) }}</span>
        @if(!empty($product['old_price']))
          <span class="price-old">৳{{ number_format($product['old_price']) }}</span>
        @endif
      </div>

      {{-- Save line --}}
      @if($save > 0)
        <div class="pdp-save-line">You save ৳{{ number_format($save) }} ({{ $pct }}% off)</div>
      @endif

      {{-- Blurb --}}
      <p class="pdp-blurb">{{ $product['blurb'] }}</p>

      {{-- Weight options --}}
      <div>
        <div style="font-size:13px;font-weight:700;margin-bottom:10px;color:var(--ink-soft);">Choose size</div>
        <div class="pdp-weights">
          <template x-for="(w, i) in weights" :key="i">
            <div class="weight-opt"
                 :class="weightIdx === i ? 'on' : ''"
                 @click="weightIdx = i; P = { ...P, weight: w.label, price: w.price }">
              <span x-text="w.label"></span>
              <small x-text="'৳' + w.price.toLocaleString()"></small>
            </div>
          </template>
        </div>
      </div>

      {{-- Buy row: qty + add to cart + buy now --}}
      <div class="pdp-buy">
        <x-qty model="qty" />
        <button class="btn btn-primary"
                style="flex:1"
                @click="for(let k=0;k<qty;k++){$store.shop.add(P)}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.7 13.4a2 2 0 002 1.6h9.7a2 2 0 002-1.6L23 6H6"/>
          </svg>
          Add to Cart
        </button>
        <button class="btn btn-honey"
                @click="$store.shop.buyNow(P)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
          </svg>
          Buy Now
        </button>
      </div>

      {{-- Trust grid --}}
      <div class="pdp-trust">

        {{-- 1. 100% Organic --}}
        <div class="pdp-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 22s-8-4.5-8-11.8A8 8 0 0112 2a8 8 0 018 8.2c0 7.3-8 11.8-8 11.8z"/>
            <path d="M12 8v4M12 12l3-3"/>
          </svg>
          <span><b>100% Organic</b><span>Certified pure</span></span>
        </div>

        {{-- 2. Free Delivery --}}
        <div class="pdp-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="1" y="3" width="15" height="13" rx="1"/>
            <path d="M16 8h4l3 3v5h-7V8z"/>
            <circle cx="5.5" cy="18.5" r="2.5"/>
            <circle cx="18.5" cy="18.5" r="2.5"/>
          </svg>
          <span><b>Free delivery</b><span>Over ৳1,500</span></span>
        </div>

        {{-- 3. Cash on Delivery --}}
        <div class="pdp-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="2" y="5" width="20" height="14" rx="2"/>
            <path d="M2 10h20"/>
            <circle cx="12" cy="15" r="2"/>
          </svg>
          <span><b>Cash on Delivery</b><span>Pay at door</span></span>
        </div>

        {{-- 4. Easy Returns --}}
        <div class="pdp-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M1 4v6h6"/>
            <path d="M3.51 15a9 9 0 102.13-9.36L1 10"/>
          </svg>
          <span><b>Easy Returns</b><span>7-day policy</span></span>
        </div>

      </div>{{-- /.pdp-trust --}}

    </div>{{-- /.pdp-info --}}

  </div>{{-- /.pdp --}}
</div>{{-- /.wrap (pdp) --}}

{{-- ============================================================
     RELATED PRODUCTS RAIL
     ============================================================ --}}
@if(count($related) > 0)
<div class="wrap">
  <div class="rail">
    <x-rail-head title="You may also like" :viewAll="route('category', $product['cat'])" />
    <div class="grid-5">
      @foreach($related as $rp)
        <x-product-card :product="$rp" />
      @endforeach
    </div>
  </div>
</div>
@endif

@endsection
