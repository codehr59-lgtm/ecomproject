@php use Illuminate\Support\Js; @endphp
@extends('layouts.app')
@section('title', $product['name'].' — Ghorer Bazar')

@section('content')

<div class="max-w-content mx-auto px-4 py-6">

    {{-- 1. Breadcrumb --}}
    <nav class="text-sm text-text mb-4">
        <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
        <span class="mx-1">›</span>
        <span class="text-ink">Products</span>
    </nav>

    {{-- 2. Two-column layout --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- A. Gallery --}}
        <div class="flex gap-3" x-data="{main:0, imgs: {{ Js::from($product['gallery']) }}}">
            {{-- thumbnail rail --}}
            <div class="flex flex-col gap-2">
                <template x-for="(img,i) in imgs" :key="i">
                    <button class="w-16 h-16 rounded border-2 overflow-hidden" :class="main===i ? 'border-primary' : 'border-border'" @click="main=i">
                        <img :src="img" class="w-full h-full object-cover" :alt="`thumbnail ${i+1}`">
                    </button>
                </template>
            </div>
            {{-- main image --}}
            <div class="relative flex-1">
                <img :src="imgs[main]" class="w-full aspect-square object-cover rounded-lg border border-border" alt="{{ $product['name'] }}">
                <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white w-9 h-9 rounded-full" @click="main=(main-1+imgs.length)%imgs.length" aria-label="Previous image">‹</button>
                <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white w-9 h-9 rounded-full" @click="main=(main+1)%imgs.length" aria-label="Next image">›</button>
            </div>
        </div>

        {{-- B. Info column --}}
        <div x-data="{qty:1}" @qty.window="qty=$event.detail">

            {{-- Title --}}
            <h1 class="text-2xl md:text-3xl font-bold text-ink">{{ $product['name'] }}</h1>

            {{-- Rating --}}
            <div class="mt-2">
                <x-rating-stars :rating="$product['rating']" :reviews="$product['reviews']" />
            </div>

            {{-- Price block --}}
            <div class="mt-4 flex items-center gap-3">
                <x-price :price="$product['price']" :old="$product['old_price'] ?? null" />
                @if(!empty($product['old_price']))
                    <x-badge type="save" label="Save {{ round((1 - $product['price']/$product['old_price'])*100) }}%" />
                @endif
            </div>

            {{-- Stock line --}}
            <p class="mt-2 text-sm {{ $product['in_stock'] ? 'text-success' : 'text-sale' }}">
                {{ $product['in_stock'] ? 'In Stock' : 'Out of Stock' }}
            </p>

            {{-- Quantity stepper --}}
            <div class="mt-4 flex items-center gap-3">
                <span class="text-sm text-text">Quantity:</span>
                <x-qty-stepper :value="1" />
            </div>

            {{-- Four action buttons --}}
            <div class="grid grid-cols-2 gap-3 mt-6">
                <button type="button" class="btn-primary col-span-2 sm:col-span-1"
                        @click="$store.cart.add({{ Js::from(['slug'=>$product['slug'],'name'=>$product['name'],'price'=>$product['price'],'image'=>$product['image']]) }}, qty)">
                    🛒 Add To Cart
                </button>
                <a href="{{ route('checkout') }}" class="col-span-2 sm:col-span-1 bg-dark text-white font-semibold uppercase rounded h-input flex items-center justify-center hover:opacity-90 transition">Buy Now</a>
                <a href="https://wa.me/8801000000000" class="bg-whatsapp text-white font-semibold rounded-lg h-input flex items-center justify-center gap-2 hover:opacity-90 transition">Order On WhatsApp</a>
                <a href="tel:+8801000000000" class="bg-call text-white font-semibold rounded-lg h-input flex items-center justify-center gap-2 hover:opacity-90 transition">Call For Order</a>
            </div>

            {{-- Brand line --}}
            <p class="mt-4 text-sm text-text">Brand: <span class="text-ink font-medium">{{ $product['brand'] }}</span></p>

        </div>

    </div>

    {{-- 3. Tabs --}}
    <div class="mt-12" x-data="{tab:'desc'}">

        {{-- Tab button row --}}
        <div class="flex gap-6 border-b border-border">
            <button type="button"
                    class="pb-3 text-sm font-semibold transition"
                    :class="tab==='desc' ? 'text-primary border-b-2 border-primary -mb-px' : 'text-text hover:text-ink'"
                    @click="tab='desc'">
                Description
            </button>
            <button type="button"
                    class="pb-3 text-sm font-semibold transition"
                    :class="tab==='reviews' ? 'text-primary border-b-2 border-primary -mb-px' : 'text-text hover:text-ink'"
                    @click="tab='reviews'">
                Customer Reviews ({{ $product['reviews'] }})
            </button>
        </div>

        {{-- Description panel --}}
        <div x-show="tab==='desc'" class="py-6 text-text">
            {{ $product['description'] }}
        </div>

        {{-- Reviews panel --}}
        <div x-show="tab==='reviews'" x-cloak class="py-6 space-y-4">
            <div class="border border-border rounded-lg p-4">
                <p class="font-semibold text-ink text-sm mb-1">Rahim Uddin</p>
                <x-rating-stars :rating="5" />
                <p class="mt-2 text-sm text-text">Excellent quality oil! The aroma is amazing and it tastes just like homemade. Will definitely order again.</p>
            </div>
            <div class="border border-border rounded-lg p-4">
                <p class="font-semibold text-ink text-sm mb-1">Fatema Begum</p>
                <x-rating-stars :rating="5" />
                <p class="mt-2 text-sm text-text">Delivered fresh and well-packaged. Pure and natural, exactly what I was looking for. Highly recommended!</p>
            </div>
        </div>

    </div>

    {{-- 4. Related products --}}
    <div class="mt-12">
        <x-product-carousel title="Related products" :products="$related" />
    </div>

</div>

@endsection
