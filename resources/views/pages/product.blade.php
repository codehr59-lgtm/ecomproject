@php use Illuminate\Support\Js; @endphp
@extends('layouts.app')
@section('title', $product['name'].' — Shuvo')

@php
  $pct  = !empty($product['old_price'])
        ? round((1 - $product['price'] / $product['old_price']) * 100)
        : 0;
  $save = !empty($product['old_price']) ? ($product['old_price'] - $product['price']) : 0;

  $mainWeight = $product['weight'];
  $mainPrice  = $product['price'];

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

  // Build gallery: main image + product_images
  $allImages = [];
  if ($model->image) {
      $allImages[] = asset('storage/' . $model->image);
  }
  foreach ($model->images as $img) {
      $allImages[] = asset('storage/' . $img->path);
  }
  if (empty($allImages)) {
      $allImages[] = null; // placeholder fallback
  }

  // Variations for variable products
  $isVariable = $model->product_type === 'variable';
  $variations = $isVariable ? $model->variations : collect();

  $jsVariations = $variations->map(fn($v) => [
      'id'    => $v->id,
      'type'  => $v->type,
      'label' => $v->label,
      'price' => (int) $v->price,
      'stock' => (int) $v->stock,
      'sku'   => $v->sku,
      'image' => $v->image ? asset('storage/' . $v->image) : null,
  ])->values();

  $jsP = Js::from([
      'id'     => $product['id'],
      'name'   => $product['name'],
      'weight' => $product['weight'],
      'price'  => $product['price'],
      'cat'    => $product['cat'],
      'image'  => $allImages[0] ?? null,
  ]);
@endphp

@section('content')

<script>
  window.__pdpProduct = {!! json_encode([
      'id'     => $product['id'],
      'name'   => $product['name'],
      'weight' => $product['weight'],
      'price'  => $product['price'],
      'cat'    => $product['cat'],
      'image'  => $allImages[0] ?? null,
  ]) !!};
  window.__pdpImages = {!! json_encode($allImages) !!};
  @if($isVariable)
  window.__pdpVariations = {!! json_encode($jsVariations) !!};
  @endif

  document.addEventListener('DOMContentLoaded', function(){
    var p = window.__pdpProduct;
    if(window.ttq){ttq.track('ViewContent',{content_id:String(p.id),content_name:p.name,content_type:'product',price:Number(p.price),value:Number(p.price),currency:'BDT'});}
    if(window.fbq){fbq('track','ViewContent',{content_ids:[String(p.id)],content_name:p.name,content_type:'product',value:Number(p.price),currency:'BDT'});}
  });
</script>

{{-- Breadcrumb --}}
<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
      <a href="{{ route('shop') }}">Shop</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
      <a href="{{ route('category', $product['cat']) }}">{{ $catName }}</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
      <span>{{ $product['name'] }}</span>
    </div>
  </div>
</div>

{{-- PDP --}}
<div class="wrap">
  <div class="pdp"
       x-data="{
         qty: 1,
         img: 0,
         selectedVar: null,
         images: window.__pdpImages || [],
         origImages: window.__pdpImages || [],
         P: window.__pdpProduct || {},
         variations: window.__pdpVariations || [],
         isVariable: {{ $isVariable ? 'true' : 'false' }},
         baseStock: {{ (int) $model->stock }},
         selectVariation(i) {
           this.selectedVar = i;
           const v = this.variations[i];
           if (!v) return;
           this.P = { ...this.P, weight: v.label, price: v.price, variation_id: v.id, image: v.image || this.P.image };
           if (v.image) {
             this.images = [v.image, ...this.origImages.filter(u => u !== v.image)];
             this.img = 0;
           }
         },
         get currentStock() {
           if (!this.isVariable) return this.baseStock;
           if (this.selectedVar !== null && this.variations[this.selectedVar]) {
             return Number(this.variations[this.selectedVar].stock || 0);
           }
           return this.variations.reduce((sum, v) => sum + Number(v.stock || 0), 0);
         },
         get isOutOfStock() {
           return this.currentStock <= 0;
         },
         get canBuy() {
           if (!this.isVariable) return this.baseStock > 0;
           if (this.selectedVar === null) return false;
           return this.currentStock > 0;
         }
       }">

    {{-- Gallery column --}}
    <div class="pdp-gallery">
      <div class="pdp-main-img" style="background:#f8f6f3;border-radius:12px;overflow:hidden;aspect-ratio:1/1;display:flex;align-items:center;justify-content:center;">
        <template x-if="images[img]">
          <img :src="images[img]" alt="{{ $product['name'] }}" style="width:100%;height:100%;object-fit:contain;">
        </template>
        <template x-if="!images[img]">
          <div style="color:#ccc;font-size:14px;">No Image</div>
        </template>
      </div>

      @if(count($allImages) > 1)
      <div class="pdp-thumbs">
        @foreach($allImages as $i => $imgUrl)
          <div class="pdp-thumb" :class="img === {{ $i }} ? 'on' : ''" @click="img = {{ $i }}" style="border-radius:8px;overflow:hidden;cursor:pointer;border:2px solid transparent;aspect-ratio:1/1;" :style="img === {{ $i }} ? 'border-color:var(--green)' : ''">
            @if($imgUrl)
              <img src="{{ $imgUrl }}" alt="Thumbnail {{ $i + 1 }}" style="width:100%;height:100%;object-fit:cover;">
            @endif
          </div>
        @endforeach
      </div>
      @endif
    </div>

    {{-- Info column --}}
    <div class="pdp-info">
      <span class="pcard-cat">{{ strtoupper($catName) }}</span>

      <div style="display:flex;gap:6px;flex-wrap:wrap;margin:6px 0 10px;">
        @if($pct > 0)<span class="badge badge-save">Save {{ $pct }}%</span>@endif
        @if(($product['badge'] ?? null) === 'new')<span class="badge badge-new">New</span>@endif
        @if(($product['badge'] ?? null) === 'best')<span class="badge badge-best">Best Seller</span>@endif
        @if(($product['badge'] ?? null) === 'preorder')<span class="badge badge-pre">Pre-order</span>@endif
        @if(!empty($product['certified']))<span class="badge badge-new">Certified</span>@endif
      </div>

      <h1>{{ $product['name'] }}</h1>

      <div class="pdp-rate">
        <x-stars :rating="$product['rating']" />
        <b style="color:var(--ink)">{{ $product['rating'] }}</b>
        <span>· {{ $product['reviews'] }} reviews</span>
        <span :style="!isOutOfStock ? 'color:var(--green);font-weight:600' : 'color:#dc2626;font-weight:600'">
          · <span x-text="!isOutOfStock ? 'In stock' : 'Out of stock'">{{ $model->stock > 0 ? 'In stock' : 'Out of stock' }}</span>
          <template x-if="isVariable && selectedVar !== null && currentStock > 0 && currentStock <= 10">
            <span style="font-size:12px;font-weight:500;color:var(--muted)" x-text="' (' + currentStock + ' left)'"></span>
          </template>
        </span>
      </div>

      {{-- Price --}}
      @if($isVariable && $variations->count())
        <div class="pdp-price">
          <template x-if="selectedVar !== null">
            <span class="price"><span class="tk">৳</span><span x-text="Number(P.price).toLocaleString('en-US')"></span></span>
          </template>
          <template x-if="selectedVar === null">
            <span class="price"><span class="tk">৳</span>{{ number_format($variations->min('price')) }}@if($variations->min('price') !== $variations->max('price')) – ৳{{ number_format($variations->max('price')) }}@endif</span>
          </template>
        </div>
      @else
        <div class="pdp-price">
          <span class="price"><span class="tk">৳</span>{{ number_format($product['price']) }}</span>
          @if(!empty($product['old_price']))
            <span class="price-old">৳{{ number_format($product['old_price']) }}</span>
          @endif
        </div>
        @if($save > 0)
          <div class="pdp-save-line">You save ৳{{ number_format($save) }} ({{ $pct }}% off)</div>
        @endif
      @endif

      {{-- Blurb --}}
      @if($product['blurb'])
        <p class="pdp-blurb">{{ $product['blurb'] }}</p>
      @endif

      {{-- Variations for variable products --}}
      @if($isVariable && $variations->count())
        <div style="margin:16px 0;">
          <div style="font-size:13px;font-weight:700;margin-bottom:10px;color:var(--ink-soft);">Choose {{ $variations->first()->type ?? 'option' }}</div>
          <div class="pdp-weights">
            @foreach($variations as $i => $var)
              <div class="weight-opt" :class="selectedVar === {{ $i }} ? 'on' : ''" @click="selectVariation({{ $i }})" style="cursor:pointer;">
                <span>{{ $var->label }}</span>
                <small>৳{{ number_format($var->price) }}</small>
                @if($var->stock <= 0)
                  <span style="color:#dc2626;font-size:10px;font-weight:700;display:block;">(Out of stock)</span>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @elseif($mainWeight)
        <div>
          <div style="font-size:13px;font-weight:700;margin-bottom:10px;color:var(--ink-soft);">Size</div>
          <div class="pdp-weights">
            <div class="weight-opt on">
              <span>{{ $mainWeight }}</span>
              <small>৳{{ number_format($mainPrice) }}</small>
            </div>
          </div>
        </div>
      @endif

      {{-- Buy row --}}
      {{-- Variation warning --}}
      <div x-show="isVariable && selectedVar === null" x-cloak
           style="background:#FEF3C7;color:#92400E;padding:10px 14px;border-radius:8px;font-size:13px;font-weight:600;margin-bottom:8px;display:flex;align-items:center;gap:8px;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01"/><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        Please select a {{ $variations->first()->type ?? 'variant' }} first
      </div>
      <div x-show="isVariable && selectedVar !== null && isOutOfStock" x-cloak
           style="background:#FEE2E2;color:#991B1B;padding:10px 14px;border-radius:8px;font-size:13px;font-weight:600;margin-bottom:8px;display:flex;align-items:center;gap:8px;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        This variant is currently out of stock
      </div>

      <div class="pdp-buy">
        <x-qty model="qty" />
        <button class="btn btn-primary" style="flex:1"
                :disabled="!canBuy"
                :style="!canBuy ? 'opacity:0.5;cursor:not-allowed' : ''"
                @click="canBuy && $store.shop.add(P, qty)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 002 1.6h9.7a2 2 0 002-1.6L23 6H6"/></svg>
          <span x-text="isVariable && selectedVar !== null && isOutOfStock ? 'Out of Stock' : 'Add to Cart'">Add to Cart</span>
        </button>
        <button class="btn btn-honey"
                :disabled="!canBuy"
                :style="!canBuy ? 'opacity:0.5;cursor:not-allowed' : ''"
                @click="canBuy && $store.shop.buyNow(P, qty)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          Buy Now
        </button>
      </div>

      {{-- Trust grid --}}
      <div class="pdp-trust">
        <div class="pdp-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0112 2a8 8 0 018 8.2c0 7.3-8 11.8-8 11.8z"/><path d="M12 8v4M12 12l3-3"/></svg>
          <span><b>100% Organic</b><span>Certified pure</span></span>
        </div>
        <div class="pdp-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          <span><b>Free delivery</b><span>Over ৳1,500</span></span>
        </div>
        <div class="pdp-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/><circle cx="12" cy="15" r="2"/></svg>
          <span><b>Cash on Delivery</b><span>Pay at door</span></span>
        </div>
        <div class="pdp-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
          <span><b>Easy Returns</b><span>7-day policy</span></span>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Full Description --}}
@if($model->description)
<div class="wrap" style="margin-top:40px;">
  <div style="background:#fff;border-radius:12px;padding:24px 28px;border:1px solid #eee;">
    <h2 style="font-size:20px;margin:0 0 12px;color:var(--ink);">Description</h2>
    <div style="color:var(--ink-soft);line-height:1.7;font-size:15px;" class="prose">
      {!! $model->description !!}
    </div>
  </div>
</div>
@endif

{{-- Specifications --}}
@if($model->specifications->count())
<div class="wrap" style="margin-top:24px;">
  <div style="background:#fff;border-radius:12px;padding:24px 28px;border:1px solid #eee;">
    <h2 style="font-size:20px;margin:0 0 16px;color:var(--ink);">Specifications</h2>
    <table style="width:100%;border-collapse:collapse;">
      @foreach($model->specifications as $spec)
        <tr style="border-bottom:1px solid #f0f0f0;">
          <td style="padding:10px 12px;font-weight:600;color:var(--ink);width:35%;font-size:14px;">{{ $spec->label }}</td>
          <td style="padding:10px 12px;color:var(--ink-soft);font-size:14px;">{{ $spec->value }}</td>
        </tr>
      @endforeach
    </table>
  </div>
</div>
@endif

{{-- FAQs --}}
@if($model->faqs->count())
<div class="wrap" style="margin-top:24px;">
  <div style="background:#fff;border-radius:12px;padding:24px 28px;border:1px solid #eee;">
    <h2 style="font-size:20px;margin:0 0 16px;color:var(--ink);">Frequently Asked Questions</h2>
    <div x-data="{ open: null }">
      @foreach($model->faqs as $i => $faq)
        <div style="border-bottom:1px solid #f0f0f0;">
          <button @click="open = open === {{ $i }} ? null : {{ $i }}" style="width:100%;text-align:left;padding:14px 0;font-size:15px;font-weight:600;color:var(--ink);background:none;border:none;cursor:pointer;display:flex;justify-content:space-between;align-items:center;">
            <span style="flex:1;">{{ $faq->question }}</span>
            <svg :style="open === {{ $i }} ? 'transform:rotate(180deg)' : ''" width="18" height="18" style="min-width:18px;min-height:18px;max-width:18px;max-height:18px;transition:transform .2s;flex-shrink:0;margin-left:12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          <div x-show="open === {{ $i }}" x-transition.duration.200ms style="padding:0 0 14px;color:var(--ink-soft);font-size:14px;line-height:1.6;overflow:hidden;">
            {{ $faq->answer }}
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endif

{{-- Video --}}
@if($model->video_url)
<div class="wrap" style="margin-top:24px;">
  <div style="background:#fff;border-radius:12px;padding:24px 28px;border:1px solid #eee;">
    <h2 style="font-size:20px;margin:0 0 16px;color:var(--ink);">Product Video</h2>
    @php
      preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $model->video_url, $m);
      $ytId = $m[1] ?? null;
    @endphp
    @if($ytId)
      <div style="position:relative;padding-bottom:56.25%;height:0;border-radius:8px;overflow:hidden;">
        <iframe src="https://www.youtube.com/embed/{{ $ytId }}" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" allowfullscreen></iframe>
      </div>
    @else
      <a href="{{ $model->video_url }}" target="_blank" rel="noopener" style="color:var(--green);">Watch Video →</a>
    @endif
  </div>
</div>
@endif

{{-- Related Products --}}
@if(count($related) > 0)
<div class="wrap" style="margin-top:40px;">
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
