@php
    $__fcCartEnabled   = (bool) \App\Models\Setting::get('floating_cart_enabled', true);
    if (! $__fcCartEnabled) {
        return;
    }

    $__fcCartPos       = \App\Models\Setting::get('floating_cart_position', 'bottom_right');
    $__fcCartBg        = \App\Models\Setting::get('floating_cart_btn_bg', '#2e7d32');
    $__fcCartTextColor = \App\Models\Setting::get('floating_cart_btn_text_color', '#ffffff');
    $__fcCartShowPrice = (bool) \App\Models\Setting::get('floating_cart_show_price', true);
    $__fcCartHideEmpty = (bool) \App\Models\Setting::get('floating_cart_hide_empty', false);
@endphp

<style>
  .floating-cart-btn {
    position: fixed;
    z-index: 998;
    display: flex;
    align-items: center;
    gap: 10px;
    background: {{ $__fcCartBg }};
    color: {{ $__fcCartTextColor }} !important;
    padding: 10px 16px 10px 14px;
    border-radius: 999px;
    box-shadow: 0 10px 25px -4px rgba(0,0,0,0.3), 0 4px 10px -2px rgba(0,0,0,0.15);
    cursor: pointer;
    text-decoration: none;
    transition: transform .2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow .2s ease, opacity .2s ease;
    border: 2px solid rgba(255,255,255,0.22);
    user-select: none;
    -webkit-tap-highlight-color: transparent;
  }
  .floating-cart-btn:hover {
    transform: translateY(-3px) scale(1.03);
    box-shadow: 0 14px 30px -4px rgba(0,0,0,0.38), 0 6px 14px -2px rgba(0,0,0,0.2);
    filter: brightness(1.05);
  }
  .floating-cart-btn:active {
    transform: translateY(0) scale(0.97);
  }

  /* Position variants */
  .floating-cart-btn.pos-bottom_right {
    bottom: 96px;
    right: 24px;
  }
  .floating-cart-btn.pos-bottom_left {
    bottom: 24px;
    left: 24px;
  }
  .floating-cart-btn.pos-middle_right {
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    border-radius: 20px 0 0 20px;
    padding: 12px 14px 12px 12px;
    flex-direction: column;
    gap: 6px;
    box-shadow: -4px 6px 20px rgba(0,0,0,0.25);
  }
  .floating-cart-btn.pos-middle_right:hover {
    transform: translateY(-50%) translateX(-4px);
  }

  /* Cart Icon & Badge Wrapper */
  .floating-cart-icon-box {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
  }
  .floating-cart-icon-box svg {
    width: 22px;
    height: 22px;
  }
  .floating-cart-badge {
    position: absolute;
    top: -6px;
    right: -8px;
    background: #ef4444;
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    min-width: 19px;
    height: 19px;
    padding: 0 5px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid {{ $__fcCartBg }};
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    line-height: 1;
  }

  /* Text & Price */
  .floating-cart-details {
    display: flex;
    flex-direction: column;
    line-height: 1.15;
  }
  .floating-cart-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .06em;
    opacity: 0.85;
    font-weight: 700;
  }
  .floating-cart-price {
    font-size: 14px;
    font-weight: 800;
    letter-spacing: -.01em;
  }

  /* Mobile responsiveness */
  @media (max-width: 768px) {
    .floating-cart-btn.pos-bottom_right {
      bottom: 145px;
      right: 16px;
      padding: 8px 14px 8px 12px;
      gap: 8px;
    }
    .floating-cart-btn.pos-bottom_left {
      bottom: 80px;
      left: 16px;
      padding: 8px 14px 8px 12px;
      gap: 8px;
    }
    .floating-cart-btn.pos-middle_right {
      padding: 10px 12px 10px 10px;
    }
    .floating-cart-icon-box {
      width: 28px;
      height: 28px;
    }
    .floating-cart-icon-box svg {
      width: 20px;
      height: 20px;
    }
    .floating-cart-price {
      font-size: 13px;
    }
  }
</style>

<div id="floating-cart-widget"
     x-data
     x-show="!{{ $__fcCartHideEmpty ? 'true' : 'false' }} || $store.shop.count > 0"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-90 translate-y-3"
     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
     x-transition:leave-end="opacity-0 scale-90 translate-y-3"
     @click="$store.shop.show()"
     class="floating-cart-btn pos-{{ $__fcCartPos }}"
     role="button"
     tabindex="0"
     aria-label="Open Shopping Cart">

    {{-- Cart Icon + Count Badge --}}
    <div class="floating-cart-icon-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        <span class="floating-cart-badge"
              x-show="$store.shop.count > 0"
              x-text="$store.shop.count"
              x-cloak>
        </span>
    </div>

    {{-- Price & Label --}}
    <div class="floating-cart-details">
        <span class="floating-cart-label">
            <span x-text="$store.shop.count"></span> <span x-text="$store.shop.count === 1 ? 'Item' : 'Items'">Items</span>
        </span>
        @if($__fcCartShowPrice)
        <span class="floating-cart-price" x-text="window.tk ? window.tk($store.shop.total) : ('৳' + $store.shop.total)"></span>
        @endif
    </div>
</div>
