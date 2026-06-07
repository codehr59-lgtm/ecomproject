@props(['product'])
@php
  $p    = $product;
  $pct  = !empty($p['old_price']) ? round((1 - $p['price'] / $p['old_price']) * 100) : 0;
  $save = !empty($p['old_price']) ? $p['old_price'] - $p['price'] : 0;
  $jsP  = \Illuminate\Support\Js::from([
      'id'     => $p['id'],
      'name'   => $p['name'],
      'weight' => $p['weight'],
      'price'  => $p['price'],
      'cat'    => $p['cat'],
  ]);
@endphp
<div class="top-card" x-data="{ added: false }">
  <a class="top-card-img" href="{{ route('product', $p['id']) }}" style="--ph-bg: var(--cream);">
    @if(($p['badge'] ?? null) === 'best')<span class="ribbon-best">Best Selling</span>@endif
    <x-photo :cat="$p['cat']" />
  </a>
  <div class="top-card-info">
    @if($pct > 0)<span class="badge badge-save">Save {{ $pct }}%</span>@endif
    <a href="{{ route('product', $p['id']) }}"><h4>{{ $p['name'] }}</h4></a>
    <div class="price-row">
      <span class="price"><span class="tk">৳</span>{{ number_format($p['price']) }}</span>
      @if(!empty($p['old_price']))<span class="price-old">৳{{ number_format($p['old_price']) }}</span>@endif
      @if($save > 0)<span class="save-pill">Save ৳{{ number_format($save) }}</span>@endif
    </div>
    <div class="top-card-foot">
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
      <button class="buy-btn" @click="$store.shop.buyNow({{ $jsP }})">Buy now</button>
    </div>
  </div>
</div>
