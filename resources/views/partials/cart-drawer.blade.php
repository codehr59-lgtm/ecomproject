<div x-data x-cloak>
    {{-- overlay --}}
    <div class="fixed inset-0 bg-black/40 z-50" x-show="$store.cart.open"
         x-transition.opacity @click="$store.cart.hide()"></div>

    {{-- panel --}}
    <aside class="fixed top-0 right-0 h-full w-full max-w-[400px] bg-white z-50 shadow-card flex flex-col"
           x-show="$store.cart.open"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

        <header class="flex items-center justify-between px-4 h-14 border-b border-border-light">
            <h2 class="font-bold text-ink">SHOPPING CART</h2>
            <button @click="$store.cart.hide()" class="text-primary font-semibold">Close →</button>
        </header>

        {{-- free-gift progress --}}
        <div class="px-4 py-3 bg-cream">
            <p class="text-xs text-text-mute mb-1">
                🎁 <template x-if="$store.cart.remaining > 0"><span>Add ৳<span x-text="$store.cart.remaining"></span> more to unlock a free gift!</span></template>
                <template x-if="$store.cart.remaining === 0"><span>You unlocked a free gift!</span></template>
            </p>
            <div class="h-2 bg-border-light rounded-full overflow-hidden">
                <div class="h-full bg-primary transition-all" :style="`width: ${$store.cart.giftProgress}%`"></div>
            </div>
        </div>

        {{-- line items --}}
        <div class="flex-1 overflow-y-auto px-4 divide-y divide-border-light">
            <template x-if="$store.cart.items.length === 0">
                <p class="text-center text-text py-10">Your cart is empty.</p>
            </template>
            <template x-for="item in $store.cart.items" :key="item.slug">
                <div class="flex gap-3 py-3">
                    <img :src="item.image" class="w-16 h-16 object-cover rounded-sm border border-border-light" :alt="item.name">
                    <div class="flex-1">
                        <p class="text-sm text-ink" x-text="item.name"></p>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="inline-flex items-center border border-border rounded text-sm">
                                <button class="w-7 h-7" @click="$store.cart.setQty(item.slug, item.qty-1)" aria-label="Decrease quantity">−</button>
                                <span class="w-7 text-center" x-text="item.qty"></span>
                                <button class="w-7 h-7" @click="$store.cart.setQty(item.slug, item.qty+1)" aria-label="Increase quantity">+</button>
                            </div>
                            <span class="text-xs text-text" x-text="`৳${item.price} × ${item.qty} = ৳${item.price*item.qty}`"></span>
                        </div>
                    </div>
                    <button class="text-strike hover:text-sale" @click="$store.cart.remove(item.slug)" aria-label="Remove item">×</button>
                </div>
            </template>
        </div>

        <footer class="border-t border-border-light p-4">
            <div class="flex justify-between font-bold text-ink mb-3">
                <span>Total:</span><span x-text="`৳${$store.cart.total}`"></span>
            </div>
            <a href="{{ route('checkout') }}" class="btn-primary w-full">Checkout</a>
        </footer>
    </aside>
</div>
