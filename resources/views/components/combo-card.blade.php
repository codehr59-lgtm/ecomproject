@props(['combo'])
@php
  $c = $combo;
  $id = is_array($c) ? ($c['id'] ?? null) : $c->id;
  $slug = is_array($c) ? ($c['slug'] ?? null) : $c->slug;
  $name = is_array($c) ? $c['name'] : $c->name;
  $image = is_array($c) ? ($c['image'] ?? null) : $c->image;
  $price = is_array($c) ? (int)$c['price'] : (int)$c->price;
  $oldPrice = is_array($c) ? (int)($c['old_price'] ?? 0) : (int)($c->original_price ?? 0);
  $items = is_array($c) ? ($c['items'] ?? '') : $c->items_summary;
  $badge = is_array($c) ? ($c['badge'] ?? null) : $c->badge;
  $savingsAmount = ($oldPrice > $price) ? ($oldPrice - $price) : 0;
  $pct = ($oldPrice > $price) ? round((1 - $price / $oldPrice) * 100) : 0;
  $detailUrl = $slug ? route('combo.show', $slug) : route('shop');
@endphp

<div class="combo-card" x-data>
  <div class="ctag">
    @if($savingsAmount > 0)
      <span class="badge badge-save" style="background:#166534;color:#fff;font-weight:700;">Save ৳{{ number_format($savingsAmount) }}</span>
    @elseif($pct > 0)
      <span class="badge badge-save">Save {{ $pct }}%</span>
    @endif

    @if($badge)
      <span class="badge" style="background:var(--orange, #FA8B01);color:#fff;font-weight:700;">{{ $badge }}</span>
    @else
      <span class="badge" style="background:var(--orange, #FA8B01);color:#fff;font-weight:700;">COMBO</span>
    @endif
  </div>

  <a href="{{ $detailUrl }}" class="combo-card-img" style="text-decoration:none;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;background:#fdfcf9;">
    @if($image)
      <img src="{{ asset('storage/' . $image) }}" alt="{{ $name }}" style="width:100%;height:100%;object-fit:cover;transition:transform .3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
    @else
      <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:20px;color:var(--green-deep,#134423);">
        <svg viewBox="0 0 24 24" width="42" height="42" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
        </svg>
        <span style="font-size:11px;font-weight:700;letter-spacing:0.5px;color:var(--honey,#FA8B01);text-transform:uppercase;">Special Package</span>
      </div>
    @endif
  </a>

  <div class="combo-card-body" style="display:flex;flex-direction:column;flex:1;gap:6px;">
    <h5 style="margin:0;">
      <a href="{{ $detailUrl }}" style="color:var(--ink,#1E2A22);text-decoration:none;transition:color .15s;" onmouseover="this.style.color='var(--green,#56A71C)'" onmouseout="this.style.color='var(--ink,#1E2A22)'">
        {{ $name }}
      </a>
    </h5>

    @if($items)
      <div style="font-size:11.5px;color:var(--muted,#666);line-height:1.35;min-height:2.7em;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
        <span style="font-weight:600;color:var(--green-deep,#134423);">Pack:</span> {{ $items }}
      </div>
    @endif

    <div class="price-row" style="margin-top:auto;display:flex;align-items:baseline;gap:8px;padding-top:4px;">
      <span class="price" style="font-size:16px;font-weight:800;color:var(--green-deep,#134423);">
        <span class="tk">৳</span>{{ number_format($price) }}
      </span>
      @if($oldPrice > $price)
        <span class="price-old" style="font-size:13px;color:#999;text-decoration:line-through;">৳{{ number_format($oldPrice) }}</span>
      @endif
    </div>

    <div style="display:grid;grid-template-columns:1fr auto;gap:6px;margin-top:6px;">
      <a class="view-btn" href="{{ $detailUrl }}" style="padding:7px 10px;font-size:12.5px;text-decoration:none;">View Package</a>
      <button type="button"
              @click="$store.shop.addCombo({ id: {{ $id }}, slug: '{{ $slug }}', name: '{{ addslashes($name) }}', price: {{ $price }}, old_price: {{ $oldPrice }}, image: '{{ $image ? asset('storage/'.$image) : '' }}', items: '{{ addslashes($items) }}' }, 1)"
              style="background:rgba(86,167,28,0.12);border:1px solid rgba(86,167,28,0.3);color:var(--green-deep,#134423);border-radius:999px;width:34px;height:34px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;"
              title="Add Combo to Cart"
              aria-label="Add Combo to Cart"
              onmouseover="this.style.background='var(--green,#56A71C)';this.style.color='#fff';"
              onmouseout="this.style.background='rgba(86,167,28,0.12)';this.style.color='var(--green-deep,#134423)';">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 6h15l-1.5 9h-12L5 3H2"/><path d="M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/><path d="M18 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
        </svg>
      </button>
    </div>
  </div>
</div>
