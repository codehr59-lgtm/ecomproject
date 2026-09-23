@extends('layouts.app')

@section('title', $combo->name . ' — Special Combo Pack')

@section('content')
@php
  $savings = $combo->savings_amount;
  $savingsPct = $combo->savings_percent;
  $imageUrl = $combo->image ? asset('storage/' . $combo->image) : null;
@endphp

<div class="combo-detail-page" x-data="{ qty: 1 }">
  <div class="wrap">

    {{-- Breadcrumbs --}}
    <nav class="crumbs" aria-label="Breadcrumb" style="padding: 14px 0 10px; font-size: 13px;">
      <a href="{{ route('home') }}">Home</a>
      <span>/</span>
      <a href="{{ route('combos.index') }}">Combo Packages</a>
      <span>/</span>
      <span style="color: var(--ink-soft);">{{ $combo->name }}</span>
    </nav>

    {{-- Top Grid: Image + Details & Pricing --}}
    <div class="combo-hero-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 36px; margin-top: 10px; background: #fff; border: 1px solid var(--line); border-radius: 20px; padding: 28px; box-shadow: 0 4px 20px rgba(19,68,35,0.04);">

      {{-- Left: Combo Photo / Banner --}}
      <div class="combo-visual">
        <div class="combo-visual-box" style="position: relative; border-radius: 16px; overflow: hidden; background: #faf9f5; border: 1px solid var(--line); aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center;">
          @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $combo->name }}" style="width: 100%; height: 100%; object-fit: cover;">
          @else
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; color: var(--green-deep, #134423); padding: 40px; text-align: center;">
              <svg viewBox="0 0 24 24" width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
              </svg>
              <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: var(--green-deep);">{{ $combo->name }}</h3>
              <span style="font-size: 13px; color: var(--honey, #FA8B01); font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;">Exclusive Bundle Offer</span>
            </div>
          @endif

          {{-- Badge top left --}}
          <div style="position: absolute; top: 14px; left: 14px; display: flex; gap: 6px; flex-wrap: wrap;">
            @if($combo->badge)
              <span style="background: var(--orange, #FA8B01); color: #fff; font-size: 12px; font-weight: 800; padding: 4px 10px; border-radius: 99px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                {{ $combo->badge }}
              </span>
            @endif
            @if($savings > 0)
              <span style="background: #15803d; color: #fff; font-size: 12px; font-weight: 800; padding: 4px 10px; border-radius: 99px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                Save ৳{{ number_format($savings) }} ({{ $savingsPct }}% OFF)
              </span>
            @endif
          </div>
        </div>

        {{-- Trust micro-bar --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 14px; text-align: center;">
          <div style="background: #f7f9f6; border: 1px solid #e5ebe2; border-radius: 10px; padding: 10px 8px;">
            <div style="font-weight: 700; font-size: 12px; color: var(--green-deep, #134423);">100% Pure</div>
            <div style="font-size: 11px; color: var(--muted, #777);">Direct from Source</div>
          </div>
          <div style="background: #f7f9f6; border: 1px solid #e5ebe2; border-radius: 10px; padding: 10px 8px;">
            <div style="font-weight: 700; font-size: 12px; color: var(--green-deep, #134423);">Value Bundle</div>
            <div style="font-size: 11px; color: var(--muted, #777);">Guaranteed Savings</div>
          </div>
          <div style="background: #f7f9f6; border: 1px solid #e5ebe2; border-radius: 10px; padding: 10px 8px;">
            <div style="font-weight: 700; font-size: 12px; color: var(--green-deep, #134423);">Fast Delivery</div>
            <div style="font-size: 11px; color: var(--muted, #777);">Cash on Delivery</div>
          </div>
        </div>
      </div>

      {{-- Right: Content, Pricing, Buy Action --}}
      <div class="combo-info" style="display: flex; flex-direction: column; gap: 16px;">
        <div>
          <div style="display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 800; letter-spacing: 0.8px; text-transform: uppercase; color: var(--honey, #FA8B01); margin-bottom: 6px;">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            Special Combo Package
          </div>
          <h1 style="font-size: clamp(22px, 3vw, 30px); font-weight: 800; color: var(--green-deep, #134423); margin: 0 0 8px; line-height: 1.2;">
            {{ $combo->name }}
          </h1>
          @if($combo->short_description)
            <p style="font-size: 14.5px; color: var(--ink-soft, #555); margin: 0; line-height: 1.5;">
              {{ $combo->short_description }}
            </p>
          @endif
        </div>

        {{-- Pricing Card --}}
        <div style="background: linear-gradient(135deg, rgba(86,167,28,0.06), rgba(250,139,1,0.08)); border: 1.5px solid rgba(86,167,28,0.22); border-radius: 14px; padding: 16px 20px;">
          <div style="display: flex; align-items: baseline; gap: 12px; flex-wrap: wrap;">
            <span style="font-size: 32px; font-weight: 800; color: var(--green-deep, #134423);">
              ৳{{ number_format($combo->price) }}
            </span>
            @if($combo->original_price && $combo->original_price > $combo->price)
              <span style="font-size: 18px; color: #888; text-decoration: line-through;">
                ৳{{ number_format($combo->original_price) }}
              </span>
              <span style="background: #166534; color: #fff; font-size: 13px; font-weight: 800; padding: 3px 10px; border-radius: 99px;">
                Save ৳{{ number_format($savings) }}
              </span>
            @endif
          </div>
          <div style="font-size: 12.5px; color: var(--muted, #666); margin-top: 6px;">
            Included in pack: <b>{{ $combo->items->count() }} items</b>. You save <b>{{ $savingsPct }}%</b> compared to buying individually.
          </div>
        </div>

        {{-- Stock Indicator --}}
        <div style="display: flex; align-items: center; gap: 8px; font-size: 13.5px;">
          @if($combo->stock > 0)
            <span style="width: 10px; height: 10px; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
            <span style="color: #15803d; font-weight: 600;">In Stock ({{ $combo->stock }} packages available)</span>
          @else
            <span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
            <span style="color: #b91c1c; font-weight: 600;">Temporarily Out of Stock</span>
          @endif
        </div>

        {{-- Quantity & Action Buttons --}}
        <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 8px;">
          <div style="display: flex; align-items: center; gap: 14px;">
            <span style="font-size: 14px; font-weight: 600; color: var(--ink);">Quantity:</span>
            <div class="qty-mini" style="background: #f3f5f1; border-radius: 99px; padding: 4px; display: inline-flex; align-items: center; border: 1px solid #e1e7dd;">
              <button type="button" @click="qty = Math.max(1, qty - 1)" style="width: 32px; height: 32px; border: none; background: #fff; border-radius: 50%; cursor: pointer; display: grid; place-items: center; font-weight: bold; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">-</button>
              <span x-text="qty" style="min-width: 34px; text-align: center; font-weight: 700; font-size: 15px;"></span>
              <button type="button" @click="qty = qty + 1" style="width: 32px; height: 32px; border: none; background: #fff; border-radius: 50%; cursor: pointer; display: grid; place-items: center; font-weight: bold; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">+</button>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 10px; margin-top: 6px;">
            <button type="button"
                    class="btn btn-secondary"
                    @click="$store.shop.addCombo({ id: {{ $combo->id }}, slug: '{{ $combo->slug }}', name: '{{ addslashes($combo->name) }}', price: {{ $combo->price }}, old_price: {{ $combo->original_price ?? 0 }}, image: '{{ $imageUrl }}', items: '{{ addslashes($combo->items_summary) }}' }, qty)"
                    style="border: 2px solid var(--green, #56A71C); background: #fff; color: var(--green-deep, #134423); font-weight: 700; border-radius: 12px; padding: 13px; font-size: 14.5px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all .15s;">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 6h15l-1.5 9h-12L5 3H2"/><path d="M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/><path d="M18 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
              </svg>
              Add to Cart
            </button>

            <button type="button"
                    class="btn btn-primary"
                    @click="$store.shop.buyComboNow({ id: {{ $combo->id }}, slug: '{{ $combo->slug }}', name: '{{ addslashes($combo->name) }}', price: {{ $combo->price }}, old_price: {{ $combo->original_price ?? 0 }}, image: '{{ $imageUrl }}', items: '{{ addslashes($combo->items_summary) }}' }, qty)"
                    style="background: linear-gradient(135deg, var(--green, #56A71C), var(--green-deep, #134423)); color: #fff; font-weight: 800; border: none; border-radius: 12px; padding: 13px; font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 14px rgba(86,167,28,0.3); transition: transform .15s;">
              Buy Combo Now ➔
            </button>
          </div>
        </div>

      </div>
    </div>

    {{-- Items Breakdown: What's inside this Combo Pack --}}
    <div style="margin-top: 36px; background: #fff; border: 1px solid var(--line); border-radius: 18px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.03);">
      <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 14px; margin-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 800; color: var(--green-deep, #134423); margin: 0; display: flex; align-items: center; gap: 10px;">
          <span style="background: rgba(86,167,28,0.12); color: var(--green-deep); width: 34px; height: 34px; border-radius: 8px; display: grid; place-items: center;">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </span>
          What's Included in this Combo Pack ({{ $combo->items->count() }} Items)
        </h2>
        <span style="font-size: 13px; color: var(--muted); font-weight: 600;">Each item verified for purity</span>
      </div>

      <div class="combo-items-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
        @foreach($combo->items as $item)
          @php
            $prod = $item->product;
            $pImg = $prod && $prod->image ? asset('storage/' . $prod->image) : null;
            $pPrice = $item->unit_price;
          @endphp
          <div style="display: flex; align-items: center; gap: 14px; padding: 14px; border: 1px solid #e6ede3; border-radius: 12px; background: #fbfdfa; transition: border-color .15s;">
            <div style="width: 64px; height: 64px; border-radius: 10px; overflow: hidden; background: #eee; flex-shrink: 0; border: 1px solid #e1e7dd;">
              @if($pImg)
                <img src="{{ $pImg }}" alt="{{ $prod->name }}" style="width: 100%; height: 100%; object-fit: cover;">
              @else
                <div style="width: 100%; height: 100%; display: grid; place-items: center; color: var(--muted); font-size: 12px;">No Photo</div>
              @endif
            </div>
            <div style="flex: 1; min-width: 0;">
              <div style="display: flex; align-items: center; gap: 6px;">
                <span style="background: var(--green, #56A71C); color: #fff; font-size: 11px; font-weight: 800; padding: 2px 7px; border-radius: 4px;">
                  {{ $item->quantity }}x
                </span>
                <h4 style="font-size: 14.5px; font-weight: 700; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                  @if($prod)
                    <a href="{{ route('product', $prod->id) }}" style="color: var(--ink); text-decoration: none;" target="_blank">
                      {{ $item->custom_name ?: $prod->name }}
                    </a>
                  @else
                    {{ $item->custom_name ?: 'Product Item' }}
                  @endif
                </h4>
              </div>
              <div style="font-size: 12.5px; color: var(--muted, #666); margin-top: 4px;">
                Regular: <b>৳{{ number_format($pPrice) }}</b> each
                @if($prod && $prod->weight) · {{ $prod->weight }} @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    {{-- Detailed Description (if any) --}}
    @if($combo->description)
    <div style="margin-top: 30px; background: #fff; border: 1px solid var(--line); border-radius: 18px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.03);">
      <h3 style="font-size: 18px; font-weight: 800; color: var(--green-deep, #134423); margin-top: 0; margin-bottom: 14px;">Package Details & Benefits</h3>
      <div class="prose" style="font-size: 14.5px; line-height: 1.7; color: var(--ink-soft);">
        {!! $combo->description !!}
      </div>
    </div>
    @endif

    {{-- Other Combo Packs --}}
    @if(isset($otherCombos) && $otherCombos->count())
    <div style="margin-top: 40px; margin-bottom: 30px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <h2 style="font-size: 20px; font-weight: 800; color: var(--green-deep, #134423); margin: 0;">
          More Exclusive Combo Offers
        </h2>
        <a href="{{ route('combos.index') }}" style="color: var(--honey, #FA8B01); font-weight: 700; font-size: 13.5px; text-decoration: none;">
          View All Offers ➔
        </a>
      </div>
      <div class="combo-strip" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 14px;">
        @foreach($otherCombos as $oc)
          <x-combo-card :combo="$oc" />
        @endforeach
      </div>
    </div>
    @endif

  </div>
</div>
@endsection
