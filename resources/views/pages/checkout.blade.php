@extends('layouts.app')
@section('title', 'Checkout — Shuvo')

@section('content')

{{-- TikTok + FB Pixel: InitiateCheckout --}}
<script>document.addEventListener('DOMContentLoaded',function(){
    var s=window.Alpine&&Alpine.store('shop'),sub=s?s.subtotal:0,cnt=s?s.count:0;
    if(window.ttq){ttq.track('InitiateCheckout',{content_type:'product',quantity:cnt,value:sub,currency:'BDT'});}
    if(window.fbq){fbq('track','InitiateCheckout',{content_type:'product',num_items:cnt,value:sub,currency:'BDT'});}
});</script>

{{-- ============================================================
     CHECKOUT PAGE — Alpine root (form + live summary)
     Real server-side order via POST /checkout
     ============================================================ --}}
<div x-data="{
    step: 1,
    pay: '{{ $paymentMethods[0] ?? 'cod' }}',
    promo: '',
    name: '',
    phone: '',
    email: '',
    address: '',
    city: '',
    thana: '',
    notes: '',
    dlvInside: {{ $deliveryConfig['inside'] }},
    dlvOutside: {{ $deliveryConfig['outside'] }},
    dlvFreeMin: {{ $deliveryConfig['freeMin'] }},
    zone1Label: '{{ $deliveryConfig['zone1Label'] }}',
    zone2Label: '{{ $deliveryConfig['zone2Label'] }}',
    dlvZone: 'inside',
    get deliveryFee() {
        let sub = this.$store.shop.subtotal;
        if (this.dlvFreeMin > 0 && sub >= this.dlvFreeMin) return 0;
        return this.dlvZone === 'inside' ? this.dlvInside : this.dlvOutside;
    },
    get orderTotal() {
        return this.$store.shop.subtotal + this.deliveryFee;
    }
}">

    {{-- Page head --}}
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
                <span>Checkout</span>
            </div>
            <h1>Checkout</h1>
        </div>
    </div>

    <div class="wrap">

        {{-- Steps bar --}}
        <div class="co-steps" style="padding-top:28px">
            <div class="co-step on">
                <span class="n">1</span> Delivery
            </div>
            <div class="bar"></div>
            <div class="co-step" :class="step >= 2 ? 'on' : ''">
                <span class="n">2</span> Payment
            </div>
            <div class="bar"></div>
            <div class="co-step" :class="step >= 3 ? 'on' : ''">
                <span class="n">3</span> Done
            </div>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div style="background:#FEF2F2;color:#991B1B;padding:12px 16px;border-radius:9px;margin-top:16px;font-size:14px">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Two-column checkout layout --}}
        <form method="POST" action="{{ route('order.store') }}" id="checkout-form">
            @csrf

            {{-- Hidden: items JSON filled by JS before submit --}}
            <input type="hidden" name="items" id="items-input">
            {{-- Hidden: coupon --}}
            <input type="hidden" name="coupon_code" id="coupon-input">
            {{-- Hidden: delivery zone --}}
            <input type="hidden" name="delivery_zone" :value="dlvZone">

            <div class="checkout">

                {{-- ── LEFT: Forms ─────────────────────────────────────── --}}
                <div>

                    {{-- Delivery Details card --}}
                    <div class="co-card">
                        <h3>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:20px;height:20px;color:var(--green)">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Delivery Details
                        </h3>

                        {{-- Full Name --}}
                        <div class="field">
                            <label for="co-name">Full Name *</label>
                            <input id="co-name" type="text" name="customer_name" x-model="name" value="{{ old('customer_name') }}" placeholder="e.g. Rahim Ahmed" autocomplete="name">
                        </div>

                        {{-- Phone + City row --}}
                        <div class="field-row">
                            <div class="field">
                                <label for="co-phone">Phone Number *</label>
                                <input id="co-phone" type="tel" name="customer_phone" x-model="phone" value="{{ old('customer_phone') }}" placeholder="01XXXXXXXXX" autocomplete="tel">
                            </div>
                            <div class="field">
                                <label for="co-city">City / District *</label>
                                <input id="co-city" type="text" name="city" x-model="city" value="{{ old('city') }}" placeholder="e.g. Dhaka" autocomplete="address-level2">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="field">
                            <label for="co-address">Full Address *</label>
                            <textarea id="co-address" rows="2" name="address_line" x-model="address" placeholder="House, road, area…" autocomplete="street-address">{{ old('address_line') }}</textarea>
                        </div>

                        {{-- Email (optional) --}}
                        <div class="field">
                            <label for="co-email">Email (optional)</label>
                            <input id="co-email" type="email" name="customer_email" x-model="email" value="{{ old('customer_email') }}" placeholder="you@example.com" autocomplete="email">
                        </div>

                        {{-- Notes --}}
                        <div class="field" style="margin-bottom:0">
                            <label for="co-notes">Notes (optional)</label>
                            <textarea id="co-notes" rows="2" name="notes" x-model="notes" placeholder="Landmark, preferred delivery time…">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    {{-- Delivery Zone card --}}
                    <div class="co-card">
                        <h3>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:20px;height:20px;color:var(--green)">
                                <path d="M3 6h11v9H3z"/>
                                <path d="M14 9h4l3 3v3h-7"/>
                                <path d="M7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                <path d="M17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                            </svg>
                            Delivery Area
                        </h3>

                        <div class="pay-opt" :class="dlvZone === 'inside' ? 'on' : ''" @click="dlvZone = 'inside'" role="radio" :aria-checked="dlvZone === 'inside'" tabindex="0" @keydown.enter="dlvZone = 'inside'" @keydown.space.prevent="dlvZone = 'inside'">
                            <span class="radio"></span>
                            <span>
                                <b x-text="zone1Label"></b>
                                <span>Delivery charge: <strong x-text="window.tk(dlvInside)"></strong></span>
                            </span>
                        </div>

                        <div class="pay-opt" :class="dlvZone === 'outside' ? 'on' : ''" @click="dlvZone = 'outside'" role="radio" :aria-checked="dlvZone === 'outside'" tabindex="0" @keydown.enter="dlvZone = 'outside'" @keydown.space.prevent="dlvZone = 'outside'" style="margin-bottom:0">
                            <span class="radio"></span>
                            <span>
                                <b x-text="zone2Label"></b>
                                <span>Delivery charge: <strong x-text="window.tk(dlvOutside)"></strong></span>
                            </span>
                        </div>

                        <template x-if="dlvFreeMin > 0">
                            <div style="margin-top:12px;padding:10px 14px;background:var(--green-tint);border-radius:8px;font-size:13px;color:var(--green-deep);display:flex;align-items:center;gap:8px">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 6h11v9H3z"/><path d="M14 9h4l3 3v3h-7"/><path d="M7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                </svg>
                                <span>Free delivery on orders over <strong x-text="window.tk(dlvFreeMin)"></strong></span>
                            </div>
                        </template>
                    </div>

                    {{-- Payment Method card --}}
                    <div class="co-card">
                        <h3>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:20px;height:20px;color:var(--green)">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            Payment Method
                        </h3>

                        {{-- Hidden payment_method value driven by Alpine --}}
                        <input type="hidden" name="payment_method" :value="pay">

                        {{-- Cash on Delivery --}}
                        @if(in_array('cod', $paymentMethods))
                        <div class="pay-opt" :class="pay === 'cod' ? 'on' : ''" @click="pay = 'cod'" role="radio" :aria-checked="pay === 'cod'" tabindex="0" @keydown.enter="pay = 'cod'" @keydown.space.prevent="pay = 'cod'">
                            <span class="radio"></span>
                            <span>
                                <b>Cash on Delivery</b>
                                <span>Pay when your order arrives</span>
                            </span>
                            <span class="pay-logo" style="background:#2d6a36">COD</span>
                        </div>
                        @endif

                        {{-- bKash --}}
                        @if(in_array('bkash', $paymentMethods))
                        <div class="pay-opt" :class="pay === 'bkash' ? 'on' : ''" @click="pay = 'bkash'" role="radio" :aria-checked="pay === 'bkash'" tabindex="0" @keydown.enter="pay = 'bkash'" @keydown.space.prevent="pay = 'bkash'">
                            <span class="radio"></span>
                            <span>
                                <b>bKash</b>
                                <span>Pay securely with bKash</span>
                            </span>
                            <span class="pay-logo" style="background:#E2136E">bKash</span>
                        </div>
                        @endif

                        {{-- Nagad --}}
                        @if(in_array('nagad', $paymentMethods))
                        <div class="pay-opt" :class="pay === 'nagad' ? 'on' : ''" @click="pay = 'nagad'" role="radio" :aria-checked="pay === 'nagad'" tabindex="0" @keydown.enter="pay = 'nagad'" @keydown.space.prevent="pay = 'nagad'">
                            <span class="radio"></span>
                            <span>
                                <b>Nagad</b>
                                <span>Pay securely with Nagad</span>
                            </span>
                            <span class="pay-logo" style="background:#F6921E">Nagad</span>
                        </div>
                        @endif

                        {{-- Rocket --}}
                        @if(in_array('rocket', $paymentMethods))
                        <div class="pay-opt" :class="pay === 'rocket' ? 'on' : ''" @click="pay = 'rocket'" role="radio" :aria-checked="pay === 'rocket'" tabindex="0" @keydown.enter="pay = 'rocket'" @keydown.space.prevent="pay = 'rocket'">
                            <span class="radio"></span>
                            <span>
                                <b>Rocket (DBBL)</b>
                                <span>Pay with Dutch-Bangla Rocket</span>
                            </span>
                            <span class="pay-logo" style="background:#8B2F89">Rocket</span>
                        </div>
                        @endif

                        {{-- SSLCommerz --}}
                        @if(in_array('sslcommerz', $paymentMethods))
                        <div class="pay-opt" :class="pay === 'sslcommerz' ? 'on' : ''" @click="pay = 'sslcommerz'" role="radio" :aria-checked="pay === 'sslcommerz'" tabindex="0" @keydown.enter="pay = 'sslcommerz'" @keydown.space.prevent="pay = 'sslcommerz'" style="margin-bottom:0">
                            <span class="radio"></span>
                            <span>
                                <b>Cards &amp; Mobile Banking (SSLCommerz)</b>
                                <span>Visa, Mastercard, bKash, Nagad, Rocket</span>
                            </span>
                            <span class="pay-logo" style="background:#1a5276">SSL</span>
                        </div>
                        @endif
                    </div>

                </div>
                {{-- ── END LEFT ──────────────────────────────────────── --}}

                {{-- ── RIGHT: Order Summary ────────────────────────────── --}}
                <div class="co-summary">

                    <div class="co-summary-head">
                        Order Summary
                        <span x-show="$store.shop.count > 0" x-cloak style="font-weight:500;color:var(--muted);font-size:14px"> · <span x-text="$store.shop.count"></span> items</span>
                    </div>

                    <div class="co-summary-body app-scroll">

                        {{-- Empty state --}}
                        <template x-if="$store.shop.items.length === 0">
                            <p style="color:var(--muted);font-size:14px;padding:16px 0;text-align:center">
                                Your cart is empty —
                                <a href="{{ route('shop') }}" style="color:var(--green);font-weight:600">add items</a>
                            </p>
                        </template>

                        {{-- Line items --}}
                        <template x-for="it in $store.shop.items" :key="it.id">
                            <div class="cart-line" style="padding:14px 0">
                                <div class="cart-line-art"
                                     :style="`--ph-bg:${window.softBg(window.catTint(it.cat))};width:56px;height:56px`">
                                    <template x-if="it.image">
                                        <img :src="it.image" :alt="it.name" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                                    </template>
                                    <template x-if="!it.image">
                                        <div class="ph-jar"
                                             :style="`width:26px;height:30px;margin:0;background:${window.catTint(it.cat)}44`"></div>
                                    </template>
                                </div>
                                <div class="cart-line-info">
                                    <h5 x-text="it.name"></h5>
                                    <span class="w" x-text="it.weight"></span>
                                    <div class="cart-line-bottom">
                                        <div class="qty-mini">
                                            <button type="button" @click="$store.shop.changeQty(it.id, -1)" aria-label="Decrease quantity">
                                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M5 12h14"/>
                                                </svg>
                                            </button>
                                            <span x-text="it.qty"></span>
                                            <button type="button" @click="$store.shop.changeQty(it.id, 1)" aria-label="Increase quantity">
                                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M12 5v14"/><path d="M5 12h14"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <span class="lp" x-text="window.tk(it.price * it.qty)"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                    {{-- /.co-summary-body --}}

                    <div class="co-summary-foot">

                        {{-- Promo code --}}
                        <div class="co-promo">
                            <input type="text" x-model="promo" placeholder="Promo code" aria-label="Promo code">
                            <button class="btn btn-ghost" type="button">Apply</button>
                        </div>

                        {{-- Subtotal --}}
                        <div class="sum-row">
                            <span>Subtotal</span>
                            <span x-text="window.tk($store.shop.subtotal)"></span>
                        </div>

                        {{-- Delivery --}}
                        <div class="sum-row">
                            <span>
                                Delivery
                                <small style="display:block;font-size:11px;color:var(--muted);font-weight:400" x-text="'(' + (dlvZone === 'inside' ? zone1Label : zone2Label) + ')'"></small>
                            </span>
                            <span x-text="deliveryFee === 0 ? 'Free' : window.tk(deliveryFee)"></span>
                        </div>

                        {{-- Total --}}
                        <div class="sum-row total">
                            <span>Total</span>
                            <span x-text="window.tk(orderTotal)"></span>
                        </div>

                        {{-- Place Order — populate hidden inputs before submit --}}
                        <button
                            type="button"
                            class="btn btn-primary btn-block btn-lg"
                            :disabled="$store.shop.count === 0"
                            :style="$store.shop.count === 0 ? 'opacity:.55;cursor:not-allowed' : ''"
                            @click="
                                if ($store.shop.count > 0) {
                                    document.getElementById('items-input').value = JSON.stringify($store.shop.items);
                                    document.getElementById('coupon-input').value = promo;
                                    document.getElementById('checkout-form').submit();
                                }
                            ">
                            Place Order
                        </button>

                        {{-- Secure note --}}
                        <div class="free-ship-note" style="justify-content:center;margin-top:12px;margin-bottom:0">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            Secure checkout · COD available
                        </div>

                    </div>
                    {{-- /.co-summary-foot --}}

                </div>
                {{-- ── END RIGHT ────────────────────────────────────── --}}

            </div>
            {{-- /.checkout --}}

        </form>

    </div>
    {{-- /.wrap --}}

</div>
{{-- /.Alpine root --}}

@endsection