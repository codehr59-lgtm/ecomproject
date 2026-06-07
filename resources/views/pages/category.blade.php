@extends('layouts.app')
@section('title', $category['name'].' — Ghorer Bazar')

@section('content')
<div class="max-w-content mx-auto px-4 py-6">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-text mb-2">
        <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
        <span class="mx-1">›</span>
        <span class="text-ink">{{ $category['name'] }}</span>
    </nav>

    {{-- Page title --}}
    <h1 class="text-2xl md:text-3xl font-bold text-ink mb-4">{{ $category['name'] }}</h1>

    {{-- Two-column layout: sidebar + main --}}
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-6">

        {{-- ── Sidebar ── --}}
        <aside x-data="{min:0, max:1000}">

            {{-- Filter by Category --}}
            <div class="mb-6">
                <h3 class="text-sm font-bold text-ink uppercase border-b-2 border-primary pb-1 mb-3">FILTER BY CATEGORY</h3>
                @foreach($categories as $cat)
                    <label class="flex items-center gap-2 text-sm text-text mb-2">
                        <input type="checkbox" class="accent-primary"
                               @if($cat['slug'] === $category['slug']) checked @endif>
                        {{ $cat['name'] }}
                    </label>
                @endforeach
            </div>

            {{-- Price Range --}}
            <div class="mb-6">
                <h3 class="text-sm font-bold text-ink uppercase border-b-2 border-primary pb-1 mb-3">PRICE RANGE</h3>
                <div class="space-y-2">
                    <input type="range" min="0" max="1000" step="10" x-model.number="min" aria-label="Minimum price" class="w-full accent-primary">
                    <input type="range" min="0" max="1000" step="10" x-model.number="max" aria-label="Maximum price" class="w-full accent-primary">
                    <p class="text-sm text-text">৳<span x-text="min"></span> — ৳<span x-text="max"></span></p>
                </div>
            </div>

            {{-- Brands --}}
            <div class="mb-6">
                <h3 class="text-sm font-bold text-ink uppercase border-b-2 border-primary pb-1 mb-3">BRANDS</h3>
                <label class="flex items-center gap-2 text-sm text-text mb-2">
                    <input type="checkbox" class="accent-primary"> Ghorer Bazar
                </label>
                <label class="flex items-center gap-2 text-sm text-text mb-2">
                    <input type="checkbox" class="accent-primary"> Organic Co
                </label>
                <label class="flex items-center gap-2 text-sm text-text mb-2">
                    <input type="checkbox" class="accent-primary"> Pure Foods
                </label>
                <label class="flex items-center gap-2 text-sm text-text mb-2">
                    <input type="checkbox" class="accent-primary"> Radhuni
                </label>
            </div>

        </aside>

        {{-- ── Main column ── --}}
        <div x-data="{shown:8, view:'grid'}">

            {{-- Top bar: sort + view toggle --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <label for="sort-select" class="text-sm text-text">Sort By:</label>
                    <select id="sort-select" class="field max-w-[220px]">
                        <option>Default Sorting</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Name</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="view='grid'"
                            :class="view==='grid' ? 'text-primary' : 'text-text'"
                            aria-label="Grid view">▦</button>
                    <button @click="view='list'"
                            :class="view==='list' ? 'text-primary' : 'text-text'"
                            aria-label="List view">≣</button>
                </div>
            </div>

            {{-- Empty state --}}
            @if(count($products) === 0)
                <div class="text-center py-16 text-text">
                    No products found in this category.
                </div>
            @else

                {{-- Product grid --}}
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($products as $i => $product)
                        <div x-show="{{ $i }} < shown">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>

                {{-- Load More --}}
                <button class="btn-primary mx-auto mt-8 flex"
                        @click="shown += 8"
                        x-show="shown < {{ count($products) }}">Load More</button>

            @endif

        </div>
        {{-- end main column --}}

    </div>
    {{-- end two-column grid --}}

</div>
@endsection
