@extends('layouts.app')
@section('title', 'Ghorer Bazar — Pure & Natural Groceries')

@section('content')

{{-- 1. Hero Carousel --}}
<section x-data="{active:0, slides: {{ \Illuminate\Support\Js::from($banners) }}}" class="relative">
    <template x-for="(s,i) in slides" :key="i">
        <div x-show="active===i" x-transition.opacity class="relative h-[320px] md:h-[460px]">
            <img :src="s.image" class="absolute inset-0 w-full h-full object-cover" :alt="s.headline">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="relative max-w-content mx-auto px-6 h-full flex flex-col justify-center">
                <h1 class="text-3xl md:text-5xl font-bold text-white max-w-lg drop-shadow" x-text="s.headline"></h1>
                <p class="text-white/90 mt-2 text-lg" x-text="s.sub"></p>
                <a :href="s.href" class="btn-primary mt-5 w-max" x-text="s.cta"></a>
            </div>
        </div>
    </template>
    {{-- prev/next chevrons --}}
    <button class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white w-10 h-10 rounded-full flex items-center justify-center" @click="active = (active - 1 + slides.length) % slides.length" aria-label="Previous slide">‹</button>
    <button class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white w-10 h-10 rounded-full flex items-center justify-center" @click="active = (active + 1) % slides.length" aria-label="Next slide">›</button>
    {{-- dots --}}
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
        <template x-for="(s,i) in slides" :key="i">
            <button class="w-2.5 h-2.5 rounded-full" :class="active===i ? 'bg-primary' : 'bg-white/60'" @click="active=i" :aria-label="`Go to slide ${i+1}`"></button>
        </template>
    </div>
</section>

{{-- 2. Featured Categories --}}
<section class="max-w-content mx-auto px-4 my-12">
    <x-section-heading variant="underline">Featured Categories</x-section-heading>
    <div class="grid grid-cols-3 md:grid-cols-6 gap-4 mt-6">
        @foreach($categories as $cat)
            <a href="{{ route('category', $cat['slug']) }}" class="bg-white border border-border rounded-lg p-4 flex flex-col items-center gap-2 hover:shadow-card transition text-center">
                <div class="w-14 h-14 rounded-full bg-cream flex items-center justify-center text-2xl">{{ ['oil'=>'🛢️','honey'=>'🍯','spice'=>'🌶️','dairy'=>'🥛','dates'=>'🌴','tea'=>'🍵'][$cat['icon']] ?? '🛒' }}</div>
                <span class="text-sm font-medium text-ink">{{ $cat['name'] }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- 3. Top Selling carousel --}}
<x-product-carousel title="Top Selling" :products="$topSelling" :viewAll="route('category','cooking-essentials')" />

{{-- 4. Promo image band (full-bleed) --}}
<section class="relative my-12 h-[220px] md:h-[300px]">
    <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?w=1400&q=80" class="absolute inset-0 w-full h-full object-cover" alt="Fresh groceries promo">
    <div class="absolute inset-0 bg-dark/50"></div>
    <div class="relative max-w-content mx-auto px-6 h-full flex flex-col justify-center items-start">
        <h2 class="text-2xl md:text-4xl font-bold text-white max-w-md">Fresh Picks, Every Week</h2>
        <p class="text-white/90 mt-2">Save more on your weekly grocery basket.</p>
        <a href="{{ route('category','cooking-essentials') }}" class="btn-primary mt-4">Shop Deals</a>
    </div>
</section>

{{-- 5. Cooking Essentials / featured carousel --}}
<x-product-carousel title="Cooking Essentials" :products="$featured" :viewAll="route('category','cooking-essentials')" />

@endsection
