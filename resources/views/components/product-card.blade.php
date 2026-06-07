@props(['product', 'buyNow' => false])
@php
  $p   = $product;
  $pct = !empty($p['old_price']) ? round((1 - $p['price'] / $p['old_price']) * 100) : 0;
  $save = !empty($p['old_price']) ? $p['old_price'] - $p['price'] : 0;
  $jsP  = \Illuminate\Support\Js::from([
      'id'     => $p['id'],
      'name'   => $p['name'],
      'weight' => $p['weight'],
      'price'  => $p['price'],
      'cat'    => $p['cat'],
  ]);
@endphp
<div class="pcard" x-data="{ added: false }">
  <div class="pcard-media">
    <div class="pcard-badges">
      @if($pct > 0)<span class="badge badge-save">Save {{ $pct }}%</span>@endif
      @if(($p['badge'] ?? null) === 'new')<span class="badge badge-new">New</span>@endif
      @if(($p['badge'] ?? null) === 'preorder')<span class="badge badge-pre">Pre-order</span>@endif
    </div>
    @if(($p['badge'] ?? null) === 'best')<span class="ribbon-best">Best Selling</span>@endif
    <button class="pcard-wish"
            :class="$store.shop.isWished({{ $p['id'] }}) ? 'on' : ''"
            @click.stop="$store.shop.toggleWish({{ $p['id'] }}); window.persistWish({{ $p['id'] }})"
            aria-label="Add to wishlist">
      <svg viewBox="0 0 24 24" :fill="$store.shop.isWished({{ $p['id'] }}) ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.6l-1-1a5.5 5.5 0 00-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 000-7.8z"/>
      </svg>
    </button>
    <a href="{{ route('product', $p['id']) }}">
      <x-photo :cat="$p['cat']" :label="strtoupper($p['cat'] . ' · ' . $p['weight'])" />
    </a>
  </div>
  <div class="pcard-body">
    <a href="{{ route('product', $p['id']) }}"><h3 class="pcard-title">{{ $p['name'] }}</h3></a>
    <div class="price-row">
      <span class="price"><span class="tk">৳</span>{{ number_format($p['price']) }}</span>
      @if(!empty($p['old_price']))<span class="price-old">৳{{ number_format($p['old_price']) }}</span>@endif
      @if($save > 0)<span class="save-pill">Save ৳{{ number_format($save) }}</span>@endif
    </div>
    <div class="pcard-foot">
      <button class="add-btn"
              :class="added ? 'added' : ''"
              @click="$store.shop.add({{ $jsP }}); added = true; setTimeout(() => added = false, 1100)">
        <svg x-show="!added" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.7 13.4a2 2 0 002 1.6h9.7a2 2 0 002-1.6L23 6H6"/>
        </svg>
        <svg x-show="added" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M20 6L9 17l-5-5"/>
        </svg>
        <span x-text="added ? 'Added' : 'Add To Cart'"></span>
      </button>
      @if($buyNow)
        <button class="buy-btn" @click="$store.shop.buyNow({{ $jsP }})">Buy now</button>
      @endif
    </div>
  </div>
</div>
