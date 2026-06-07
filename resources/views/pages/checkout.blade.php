@extends('layouts.app')
@section('title', 'Checkout — Ghorer Bazar')

@section('content')
<div class="max-w-content mx-auto px-4 py-8">

    {{-- 1. Centered title + breadcrumb --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-ink">Checkout</h1>
        <nav class="text-sm text-text mt-1"><a href="{{ route('home') }}" class="hover:text-primary">Home</a> <span class="mx-1">›</span> <span>Checkout</span></nav>
    </div>

    {{-- 2. Login/Register banner --}}
    <div class="bg-cream border border-border-light rounded-lg p-4 flex flex-col sm:flex-row items-center justify-between gap-3 mb-8">
        <p class="text-sm text-text">Have any account? please login or register</p>
        <div class="flex gap-3">
            <a href="#" class="btn-outline">Login</a>
            <a href="#" class="btn-primary">Register</a>
        </div>
    </div>

    {{-- 3. Two-column layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-8">

        {{-- LEFT COLUMN --}}
        <div class="space-y-8">

            {{-- Order review --}}
            <div>
                <x-section-heading variant="bar">Order review</x-section-heading>
                <div x-data>
                    <template x-if="$store.cart.items.length === 0">
                        <p class="text-text text-sm py-4">Your cart is empty. <a href="{{ route('home') }}" class="text-primary">Continue shopping</a></p>
                    </template>
                    <template x-for="item in $store.cart.items" :key="item.slug">
                        <div class="flex items-center gap-3 py-3 border-b border-border-light">
                            <img :src="item.image" class="w-14 h-14 object-cover rounded" :alt="item.name">
                            <span class="flex-1 text-sm text-ink" x-text="item.name"></span>
                            <div class="inline-flex items-center border border-border rounded text-sm">
                                <button class="w-7 h-7" @click="$store.cart.setQty(item.slug, item.qty-1)" aria-label="Decrease quantity">−</button>
                                <span class="w-7 text-center" x-text="item.qty"></span>
                                <button class="w-7 h-7" @click="$store.cart.setQty(item.slug, item.qty+1)" aria-label="Increase quantity">+</button>
                            </div>
                            <span class="text-sm text-primary font-semibold w-16 text-right" x-text="`৳${item.price*item.qty}`"></span>
                            <button class="text-sale" @click="$store.cart.remove(item.slug)" aria-label="Remove item">🗑</button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div>
                <x-section-heading variant="bar">Shipping Address</x-section-heading>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Full Name --}}
                    <div class="sm:col-span-2">
                        <label for="full_name" class="sr-only">Full Name</label>
                        <input id="full_name" type="text" class="field" placeholder="Full Name">
                    </div>

                    {{-- Phone with +88 prefix --}}
                    <div class="sm:col-span-2">
                        <label for="phone" class="sr-only">Phone</label>
                        <div class="flex">
                            <span class="h-input px-3 flex items-center border border-border rounded-l-lg bg-cream text-sm">+88</span>
                            <input id="phone" type="tel" class="field rounded-l-none" placeholder="01XXXXXXXXX">
                        </div>
                    </div>

                    {{-- Address textarea --}}
                    <div class="sm:col-span-2">
                        <label for="address" class="sr-only">Address</label>
                        <textarea id="address" class="field !h-auto py-2" rows="3" placeholder="Full address"></textarea>
                    </div>

                    {{-- District --}}
                    <div>
                        <label for="district" class="sr-only">District</label>
                        <select id="district" class="field">
                            <option value="">Select District</option>
                            <option value="dhaka">Dhaka</option>
                            <option value="chattogram">Chattogram</option>
                            <option value="khulna">Khulna</option>
                            <option value="rajshahi">Rajshahi</option>
                            <option value="sylhet">Sylhet</option>
                            <option value="barishal">Barishal</option>
                            <option value="rangpur">Rangpur</option>
                            <option value="mymensingh">Mymensingh</option>
                        </select>
                    </div>

                    {{-- Thana --}}
                    <div>
                        <label for="thana" class="sr-only">Thana</label>
                        <select id="thana" class="field">
                            <option value="">Select Thana</option>
                            <option value="dhanmondi">Dhanmondi</option>
                            <option value="gulshan">Gulshan</option>
                            <option value="mirpur">Mirpur</option>
                            <option value="mohammadpur">Mohammadpur</option>
                            <option value="uttara">Uttara</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- Billing Address --}}
            <div>
                <x-section-heading variant="bar">Billing Address</x-section-heading>
                <div x-data="{billing:'same'}" class="space-y-3">
                    <label class="flex items-center gap-2 text-sm text-ink cursor-pointer">
                        <input type="radio" name="billing" value="same" x-model="billing" class="accent-primary">
                        Same as shipping
                    </label>
                    <label class="flex items-center gap-2 text-sm text-ink cursor-pointer">
                        <input type="radio" name="billing" value="different" x-model="billing" class="accent-primary">
                        Use a different billing address
                    </label>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="space-y-6" x-data="{pay:'cod', terms:false, notes:'', coupon:false}">

            {{-- Payment method --}}
            <div>
                <x-section-heading variant="bar">Payment method</x-section-heading>
                <div class="space-y-3">
                    <button type="button" @click="pay='cod'" class="w-full text-left border rounded-lg p-3 flex items-center justify-between" :class="pay==='cod' ? 'border-primary' : 'border-border'">
                        <span class="text-sm text-ink">Cash On Delivery</span>
                        <span class="text-success" x-show="pay==='cod'">✓</span>
                    </button>
                    <button type="button" @click="pay='online'" class="w-full text-left border rounded-lg p-3 flex items-center justify-between" :class="pay==='online' ? 'border-primary' : 'border-border'">
                        <span class="text-sm text-ink">Online Payment</span>
                        <span class="text-success" x-show="pay==='online'">✓</span>
                    </button>
                    <button type="button" @click="pay='bkash'" class="w-full text-left border rounded-lg p-3 flex items-center justify-between" :class="pay==='bkash' ? 'border-primary' : 'border-border'">
                        <span class="text-sm text-ink">bKash</span>
                        <span class="text-success" x-show="pay==='bkash'">✓</span>
                    </button>
                </div>
            </div>

            {{-- Coupon accordion --}}
            <div>
                <button type="button" @click="coupon=!coupon" class="text-sm text-primary hover:underline w-full text-left">
                    Have any coupon or gift voucher?
                </button>
                <div x-show="coupon" class="flex gap-2 mt-2">
                    <label for="coupon_code" class="sr-only">Coupon Code</label>
                    <input id="coupon_code" type="text" class="field" placeholder="Enter coupon code">
                    <button type="button" class="btn-primary shrink-0">Apply</button>
                </div>
            </div>

            {{-- Summary box --}}
            <div class="bg-cream rounded-lg p-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Sub total</span>
                    <span x-text="`৳${$store.cart.total}`"></span>
                </div>
                <div class="flex justify-between">
                    <span>Delivery cost</span>
                    <span>৳60</span>
                </div>
                <hr class="border-border-light">
                <div class="flex justify-between font-bold">
                    <span>Total</span>
                    <span class="text-primary" x-text="`৳${$store.cart.total + 60}`"></span>
                </div>
            </div>

            {{-- Special notes --}}
            <div>
                <label for="special_notes" class="sr-only">Special Notes</label>
                <textarea id="special_notes" class="field !h-auto py-2" rows="3" maxlength="90" x-model="notes" placeholder="Special notes (optional)"></textarea>
                <p class="text-xs text-text text-right" x-text="`${notes.length}/90 characters`"></p>
            </div>

            {{-- Terms checkbox --}}
            <label class="flex items-start gap-2 text-sm text-text cursor-pointer">
                <input type="checkbox" class="accent-primary mt-1" x-model="terms">
                I agree to the <a href="#" class="text-primary">Terms</a> and <a href="#" class="text-primary">Privacy Policy</a>.
            </label>

            {{-- Place Order button --}}
            <button type="button" class="btn-primary w-full !rounded-sm" :disabled="!terms" :class="!terms && 'opacity-50 cursor-not-allowed'">Place Order</button>

        </div>
        {{-- END RIGHT COLUMN --}}

    </div>
    {{-- END two-column --}}

</div>
@endsection
