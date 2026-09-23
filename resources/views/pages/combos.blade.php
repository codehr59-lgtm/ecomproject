@extends('layouts.app')

@section('title', 'Exclusive Combo Offers & Bundles — Shuvo')

@section('content')
<div class="combos-page" style="padding-bottom: 50px;">
  <div class="wrap">

    {{-- Breadcrumb --}}
    <nav class="crumbs" aria-label="Breadcrumb" style="padding: 14px 0 10px; font-size: 13px;">
      <a href="{{ route('home') }}">Home</a>
      <span>/</span>
      <span style="color: var(--ink-soft);">Combo Packages</span>
    </nav>

    {{-- Hero Banner --}}
    <div style="background: linear-gradient(135deg, rgba(86,167,28,0.12), rgba(250,139,1,0.12)), #fff; border: 1.5px solid rgba(86,167,28,0.2); border-radius: 20px; padding: 32px; margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
      <div style="max-width: 600px;">
        <span style="display: inline-flex; align-items: center; gap: 6px; background: var(--orange, #FA8B01); color: #fff; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
          Bundle & Save Big
        </span>
        <h1 style="font-size: clamp(24px, 3.5vw, 34px); font-weight: 800; color: var(--green-deep, #134423); margin: 0 0 10px; line-height: 1.2;">
          Exclusive Combo Packages
        </h1>
        <p style="font-size: 15px; color: var(--ink-soft, #555); margin: 0; line-height: 1.5;">
          Get our top-rated pure honey, ghee, premium dates, and cooking essentials bundled together at discounted bundle prices. 100% natural, guaranteed savings.
        </p>
      </div>

      <div style="display: flex; gap: 14px; flex-wrap: wrap;">
        <div style="background: #fff; border: 1px solid #e1e7dd; border-radius: 12px; padding: 12px 18px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
          <div style="font-size: 22px; font-weight: 800; color: var(--green-deep, #134423);">{{ $combos->count() }}</div>
          <div style="font-size: 12px; color: var(--muted, #666); font-weight: 600;">Active Packages</div>
        </div>
        <div style="background: #fff; border: 1px solid #e1e7dd; border-radius: 12px; padding: 12px 18px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
          <div style="font-size: 22px; font-weight: 800; color: var(--honey, #FA8B01);">Up to 20%</div>
          <div style="font-size: 12px; color: var(--muted, #666); font-weight: 600;">Bundle Savings</div>
        </div>
      </div>
    </div>

    {{-- Combos Grid --}}
    @if($combos->isNotEmpty())
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 18px;">
        @foreach($combos as $c)
          <x-combo-card :combo="$c" />
        @endforeach
      </div>
    @else
      <div style="text-align: center; padding: 60px 20px; background: #fff; border: 1px solid var(--line); border-radius: 16px;">
        <h3 style="color: var(--green-deep);">No combo packages active at the moment.</h3>
        <p style="color: var(--muted);">Please check back soon for our festive and monthly bundle offers.</p>
        <a href="{{ route('shop') }}" class="btn btn-primary" style="margin-top: 10px;">Browse All Products</a>
      </div>
    @endif

  </div>
</div>
@endsection
