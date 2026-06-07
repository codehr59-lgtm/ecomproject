@extends('layouts.app')
@section('title', 'Checkout — Shuvo')

@section('content')

{{-- ============================================================
     CHECKOUT PAGE — Alpine root (form + success states)
     ============================================================ --}}
<div x-data="{
    step: 1,
    pay: 'cod',
    placed: false,
    orderNo: '',
    promo: '',
    name: '',
    phone: '',
    address: '',
    city: '',
    notes: '',
    placeOrder() {
        this.orderNo = 'SHV-' + Math.floor(100000 + Math.random() * 900000);
        this.placed = true;
        window.scrollTo({ top: 0 });
        $store.shop.items = [];
    }
}">

    {{-- ============================================================
         STATE A — Checkout form (hidden after order placed)
         ============================================================ --}}
    <div x-show="!placed">

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

            {{-- Two-column checkout layout --}}
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
                            <input id="co-name" type="text" x-model="name" placeholder="e.g. Rahim Ahmed" autocomplete="name">
                        </div>

                        {{-- Phone + City row --}}
                        <div class="field-row">
                            <div class="field">
                                <label for="co-phone">Phone Number *</label>
                                <input id="co-phone" type="tel" x-model="phone" placeholder="01XXXXXXXXX" autocomplete="tel">
                            </div>
                            <div class="field">
                                <label for="co-city">City / District</label>
                                <input id="co-city" type="text" x-model="city" placeholder="e.g. Dhaka" autocomplete="address-level2">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="field">
                            <label for="co-address">Full Address *</label>
                            <textarea id="co-address" rows="2" x-model="address" placeholder="House, road, area…" autocomplete="street-address"></textarea>
                        </div>

                        {{-- Notes --}}
                        <div class="field" style="margin-bottom:0">
                            <label for="co-notes">Notes (optional)</label>
                            <textarea id="co-notes" rows="2" x-model="notes" placeholder="Landmark, preferred delivery time…"></textarea>
                        </div>
                    </div>

                    {{-- Payment Method card --}}
                    <div class="co-card">
                        <h3>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:20px;height:20px;color:var(--green)">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            Payment Method
                        </h3>

                        {{-- Cash on Delivery --}}
                        <div class="pay-opt" :class="pay === 'cod' ? 'on' : ''" @click="pay = 'cod'" role="radio" :aria-checked="pay === 'cod'" tabindex="0" @keydown.enter="pay = 'cod'" @keydown.space.prevent="pay = 'cod'">
                            <span class="radio"></span>
                            <span>
                                <b>Cash on Delivery</b>
                                <span>Pay when it arrives at your door</span>
                            </span>
                            <span class="pay-logo">COD</span>
                        </div>

                        {{-- Online Payment (−2% discount) --}}
                        <div class="pay-opt" :class="pay === 'online' ? 'on' : ''" @click="pay = 'online'" role="radio" :aria-checked="pay === 'online'" tabindex="0" @keydown.enter="pay = 'online'" @keydown.space.prevent="pay = 'online'">
                            <span class="radio"></span>
                            <span>
                                <b>Online Payment</b>
                                <span>−2% instant discount</span>
                            </span>
                            <span class="pay-logo" style="color:var(--green)">−2%</span>
                        </div>

                        {{-- Card --}}
                        <div class="pay-opt" :class="pay === 'card' ? 'on' : ''" @click="pay = 'card'" role="radio" :aria-checked="pay === 'card'" tabindex="0" @keydown.enter="pay = 'card'" @keydown.space.prevent="pay = 'card'">
                            <span class="radio"></span>
                            <span>
                                <b>Card</b>
                                <span>Visa · Mastercard</span>
                            </span>
                            <span class="pay-logo">POS</span>
                        </div>

                        {{-- bKash --}}
                        <div class="pay-opt" :class="pay === 'bkash' ? 'on' : ''" @click="pay = 'bkash'" role="radio" :aria-checked="pay === 'bkash'" tabindex="0" @keydown.enter="pay = 'bkash'" @keydown.space.prevent="pay = 'bkash'">
                            <span class="radio"></span>
                            <span>
                                <b>bKash</b>
                                <span>Mobile banking payment</span>
                            </span>
                            <span class="pay-logo" style="color:#E2136E">bK</span>
                        </div>

                        {{-- Nagad --}}
                        <div class="pay-opt" :class="pay === 'nagad' ? 'on' : ''" @click="pay = 'nagad'" role="radio" :aria-checked="pay === 'nagad'" tabindex="0" @keydown.enter="pay = 'nagad'" @keydown.space.prevent="pay = 'nagad'">
                            <span class="radio"></span>
                            <span>
                                <b>Nagad</b>
                                <span>Bangladesh Post Office MFS</span>
                            </span>
                            <span class="pay-logo" style="color:#F6851B">Ng</span>
                        </div>

                        {{-- Rocket --}}
                        <div class="pay-opt" :class="pay === 'rocket' ? 'on' : ''" @click="pay = 'rocket'" role="radio" :aria-checked="pay === 'rocket'" tabindex="0" @keydown.enter="pay = 'rocket'" @keydown.space.prevent="pay = 'rocket'" style="margin-bottom:0">
                            <span class="radio"></span>
                            <span>
                                <b>Rocket</b>
                                <span>DBBL Mobile Banking</span>
                            </span>
                            <span class="pay-logo" style="color:#8B16A2">Rkt</span>
                        </div>
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
                                    <div class="ph-jar"
                                         :style="`width:26px;height:30px;margin:0;background:${window.catTint(it.cat)}44`"></div>
                                </div>
                                <div class="cart-line-info">
                                    <h5 x-text="it.name"></h5>
                                    <span class="w" x-text="it.weight"></span>
                                    <div class="cart-line-bottom">
                                        <div class="qty-mini">
                                            <button @click="$store.shop.changeQty(it.id, -1)" aria-label="Decrease quantity">
                                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M5 12h14"/>
                                                </svg>
                                            </button>
                                            <span x-text="it.qty"></span>
                                            <button @click="$store.shop.changeQty(it.id, 1)" aria-label="Increase quantity">
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
                            <span>Delivery</span>
                            <span x-text="$store.shop.freeShip ? 'Free' : window.tk(60)"></span>
                        </div>

                        {{-- Online discount row (only when pay === 'online') --}}
                        <div class="sum-row" x-show="pay === 'online'" x-cloak style="color:var(--green)">
                            <span>Online discount (−2%)</span>
                            <span x-text="'−' + window.tk(Math.round($store.shop.subtotal * 0.02))"></span>
                        </div>

                        {{-- Total --}}
                        <div class="sum-row total">
                            <span>Total</span>
                            <span x-text="window.tk($store.shop.total - (pay === 'online' ? Math.round($store.shop.subtotal * 0.02) : 0))"></span>
                        </div>

                        {{-- Place Order button --}}
                        <button
                            type="button"
                            class="btn btn-primary btn-block btn-lg"
                            @click="placeOrder()"
                            :disabled="$store.shop.count === 0"
                            :style="$store.shop.count === 0 ? 'opacity:.55;cursor:not-allowed' : ''">
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

        </div>
        {{-- /.wrap --}}

    </div>
    {{-- /.x-show="!placed" --}}


    {{-- ============================================================
         STATE B — Success screen (shown after order placed)
         ============================================================ --}}
    <div x-show="placed" x-cloak>
        <div class="wrap">
            <div class="success">

                {{-- Animated check icon --}}
                <div class="success-ico">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12l5 5L20 6"/>
                    </svg>
                </div>

                <h1>Order placed!</h1>
                <p>Thank you — we've received your order.</p>

                <span class="ord-no" x-text="orderNo"></span>

                <p style="font-size:14px">We'll call to confirm delivery.</p>

                <a class="btn btn-primary btn-lg" href="{{ route('shop') }}" style="margin-top:8px">
                    Continue Shopping
                </a>

            </div>
        </div>
    </div>
    {{-- /.x-show="placed" --}}

</div>
{{-- /.Alpine root --}}

@endsection
