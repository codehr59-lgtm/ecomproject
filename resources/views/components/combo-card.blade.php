@props(['combo'])
@php
  $c    = $combo;
  $pct  = !empty($c['old_price']) ? round((1 - $c['price'] / $c['old_price']) * 100) : 0;
  $cat  = $c['cat'] ?? 'honey';
@endphp
<div class="combo-card">
  <div class="ctag">
    @if($pct > 0)<span class="badge badge-save">Save {{ $pct }}%</span>@endif
    <span class="badge" style="background:var(--orange);color:#fff;">COMBO</span>
  </div>
  <div class="combo-card-img" style="--ph-bg: var(--cream);">
    <x-photo :cat="$cat" />
  </div>
  <h5>{{ $c['name'] }}</h5>
  <div class="price-row">
    <span class="price"><span class="tk">৳</span>{{ number_format($c['price']) }}</span>
    @if(!empty($c['old_price']))<span class="price-old">৳{{ number_format($c['old_price']) }}</span>@endif
  </div>
  <a class="view-btn" href="{{ route('shop') }}">View Details</a>
</div>
