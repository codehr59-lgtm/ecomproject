<div x-data x-cloak>
    <div class="overlay" :class="$store.shop.open ? 'on' : ''" @click="$store.shop.hide()"></div>

    <aside class="drawer" :class="$store.shop.open ? 'on' : ''">

        <div class="drawer-head">
            <h3>
                {{-- cart icon --}}
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 6h15l-1.5 9h-12L5 3H2" /><path d="M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" /><path d="M18 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                </svg>
                Your Cart
                <span x-show="$store.shop.count > 0" x-cloak style="color:var(--muted);font-weight:500;font-size:15px">(<span x-text="$store.shop.count"></span>)</span>
            </h3>
            <button class="x" @click="$store.shop.hide()" aria-label="Close cart">
                {{-- close icon --}}
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 6l12 12" /><path d="M18 6 6 18" />
                </svg>
            </button>
        </div>

        {{-- gift progress bar (only when items exist) --}}
        <div class="gift-bar" x-show="$store.shop.items.length > 0" x-cloak>
            <p x-show="$store.shop.giftRemain > 0">
                <span>Add <b><span x-text="window.tk($store.shop.giftRemain)"></span></b> more to unlock a <b>free Lychee Honey sachet</b> 🎁</span>
            </p>
            <p x-show="$store.shop.giftRemain === 0">
                🎉 You've unlocked a <b>free gift</b>! It'll be added at checkout.
            </p>
            <div class="gift-track">
                <div class="gift-fill" :style="`width:${$store.shop.giftPct}%`"></div>
            </div>
        </div>

        <div class="drawer-body app-scroll">

            {{-- empty state --}}
            <template x-if="$store.shop.items.length === 0">
                <div class="cart-empty">
                    <div class="cart-empty-ico">
                        {{-- bag icon --}}
                        <svg viewBox="0 0 24 24" width="38" height="38" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 7h12l1 13H5z" /><path d="M9 7a3 3 0 0 1 6 0" />
                        </svg>
                    </div>
                    <h4>Your cart is empty</h4>
                    <p>Fresh, organic and ready to ship.</p>
                    <a class="btn btn-primary" href="{{ route('shop') }}" @click="$store.shop.hide()">Start shopping</a>
                </div>
            </template>

            {{-- line items --}}
            <template x-for="it in $store.shop.items" :key="it.id">
                <div class="cart-line">
                    <div class="cart-line-art" :style="`--ph-bg:${window.softBg(window.catTint(it.cat))}`">
                        <div class="ph-jar" :style="`width:34px;height:40px;margin:0;background:${window.catTint(it.cat)}44`"></div>
                    </div>
                    <div class="cart-line-info">
                        <h5 x-text="it.name"></h5>
                        <span class="w" x-text="it.weight"></span>
                        <div class="cart-line-bottom">
                            <div class="qty-mini">
                                <button @click="$store.shop.changeQty(it.id, -1)" aria-label="Decrease">
                                    {{-- minus icon --}}
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                    </svg>
                                </button>
                                <span x-text="it.qty"></span>
                                <button @click="$store.shop.changeQty(it.id, 1)" aria-label="Increase">
                                    {{-- plus icon --}}
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 5v14" /><path d="M5 12h14" />
                                    </svg>
                                </button>
                            </div>
                            <span class="lp" x-text="window.tk(it.price * it.qty)"></span>
                        </div>
                        <button class="cart-rm" @click="$store.shop.remove(it.id)">Remove</button>
                    </div>
                </div>
            </template>

        </div>

        {{-- footer (only when items exist) --}}
        <div class="drawer-foot" x-show="$store.shop.items.length > 0" x-cloak>
            <div class="free-ship-note">
                {{-- truck icon (shown when free shipping not yet unlocked) --}}
                <svg x-show="!$store.shop.freeShip" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h11v9H3z" /><path d="M14 9h4l3 3v3h-7" /><path d="M7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" /><path d="M17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
                </svg>
                {{-- check icon (shown when free shipping unlocked) --}}
                <svg x-show="$store.shop.freeShip" x-cloak viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12l5 5L20 6" />
                </svg>
                <span x-show="!$store.shop.freeShip">Add <span x-text="window.tk($store.shop.FREE_SHIP_THRESHOLD - $store.shop.subtotal)"></span> for free delivery</span>
                <span x-show="$store.shop.freeShip" x-cloak>You qualify for free delivery</span>
            </div>
            <div class="sum-row"><span>Subtotal</span><span x-text="window.tk($store.shop.subtotal)"></span></div>
            <div class="sum-row"><span>Delivery</span><span x-text="$store.shop.freeShip ? 'Free' : window.tk(60)"></span></div>
            <div class="sum-row total"><span>Total</span><span x-text="window.tk($store.shop.total)"></span></div>
            <a class="btn btn-primary btn-block btn-lg" href="{{ route('checkout') }}" @click="$store.shop.hide()">
                Checkout
                {{-- arrowR icon --}}
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" /><path d="M13 6l6 6-6 6" />
                </svg>
            </a>
        </div>

    </aside>
</div>
