@extends('layouts.app')
@section('title', 'Wishlist — Shuvo')

@section('content')

{{-- ============================================================
     PAGE HEAD — breadcrumbs + title
     ============================================================ --}}
<div class="page-head">
    <div class="wrap">
        <div class="crumbs">
            <a href="{{ route('home') }}">Home</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            <a href="{{ route('account') }}">Account</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            <span>Wishlist</span>
        </div>
        <h1>My Wishlist</h1>
        <p class="sub">Your saved favourites — ready to add to cart any time</p>
    </div>
</div>

{{-- ============================================================
     WISHLIST CONTENT
     ============================================================ --}}
<div class="wrap section">

    @if(isset($serverWishlist) && $serverWishlist)
        {{-- ── Server-side (auth user) ─────────────────────────── --}}
        @if($products->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.6l-1-1a5.5 5.5 0 00-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 000-7.8z"/>
                </svg>
                <h3 style="font-size:22px;margin-bottom:8px;color:var(--ink)">Your wishlist is empty</h3>
                <p style="font-size:15px;margin:0 0 22px">Heart products you love and find them here</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">Browse Products</a>
            </div>
        @else
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <p style="font-size:15px;color:var(--ink-soft);margin:0">
                    <span style="font-weight:700;color:var(--ink)">{{ $products->count() }}</span>
                    saved item{{ $products->count() !== 1 ? 's' : '' }}
                </p>
            </div>
            <div class="grid-4">
                @foreach ($products as $p)
                    <x-product-card :product="$p" />
                @endforeach
            </div>
        @endif

    @else
        {{-- ── Alpine-only (guest) ─────────────────────────────── --}}
        <div x-data>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px" x-show="$store.shop.wishCount > 0" x-cloak>
                <p style="font-size:15px;color:var(--ink-soft);margin:0">
                    <span x-text="$store.shop.wishCount" style="font-weight:700;color:var(--ink)"></span>
                    saved items
                </p>
                <button
                    class="btn btn-ghost"
                    @click="$store.shop.wish = []"
                    style="font-size:13.5px;padding:9px 16px;color:var(--sale);border-color:var(--sale)">
                    Clear all
                </button>
            </div>

            <div class="empty-state" x-show="$store.shop.wishCount === 0" x-cloak>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.6l-1-1a5.5 5.5 0 00-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 000-7.8z"/>
                </svg>
                <h3 style="font-size:22px;margin-bottom:8px;color:var(--ink)">Your wishlist is empty</h3>
                <p style="font-size:15px;margin:0 0 22px">Heart products you love and find them here</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">
                    Browse Products
                </a>
            </div>

            <div class="empty-state" x-show="$store.shop.wishCount === 0" style="display:none">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.6l-1-1a5.5 5.5 0 00-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 000-7.8z"/>
                </svg>
                <h3 style="font-size:22px;margin-bottom:8px;color:var(--ink)">Your wishlist is empty</h3>
                <p style="font-size:15px;margin:0 0 22px">Heart products you love and find them here</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">Browse Products</a>
            </div>

            <div class="grid-4">
                @foreach ($products as $p)
                    <div x-show="$store.shop.isWished({{ $p['id'] }})" x-cloak>
                        <x-product-card :product="$p" />
                    </div>
                @endforeach
            </div>

        </div>
    @endif

</div>

@endsection
