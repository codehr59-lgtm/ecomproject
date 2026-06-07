@extends('layouts.app')
@section('title', 'About — Shuvo')

@section('content')

{{-- PAGE HEAD --}}
<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
      <span>About</span>
    </div>
    <h1>Our Story</h1>
    <p class="sub">How a family passion for pure food became Bangladesh's trusted organic grocery.</p>
  </div>
</div>

{{-- OUR STORY --}}
<div class="section">
  <div class="wrap">

    <div style="max-width:760px; margin-bottom:52px;">
      <span class="eyebrow" style="margin-bottom:20px; display:inline-flex;">From Farm to Your Table</span>
      <h2 style="font-size:clamp(28px,3.4vw,42px); margin:16px 0 24px; letter-spacing:-.02em;">
        We started with one jar of raw honey<br>
        <em style="font-style:normal; color:var(--honey);">and a promise to never compromise.</em>
      </h2>
      <p style="font-size:16.5px; line-height:1.75; color:var(--ink-soft); margin-bottom:18px;">
        Shuvo was born in a Dhaka kitchen in 2018 when our founder Rafiq Ahmed couldn't find honey that was
        actually raw — every supermarket jar had been heated, diluted or adulterated. He reached out directly
        to a beekeeper in Sundarbans, brought back a small batch, and shared it with neighbours. Within a week
        the phones wouldn't stop ringing. That single jar became Shuvo — meaning "auspicious" in Bangla — and
        a promise that every product we stock would meet the same standard: pure, traceable, and honestly priced.
      </p>
      <p style="font-size:16.5px; line-height:1.75; color:var(--ink-soft); margin-bottom:18px;">
        Today we source directly from over 60 small farms and producers across Bangladesh, India and the Middle
        East — bringing you Sundarban forest honey, Ajwa dates from Madinah, cultured cow ghee from Rajshahi,
        cold-pressed mustard oil from Rajshahi's char lands, and heritage varieties of rice from Dinajpur. Every
        batch is lab-tested for purity before it reaches your door. We don't sell anything we wouldn't eat
        ourselves.
      </p>
      <p style="font-size:16.5px; line-height:1.75; color:var(--ink-soft);">
        Our warehouse is in Rampura, Dhaka, and we deliver nationwide within 48–72 hours. For Dhaka city
        customers, same-day delivery is available on most products. We maintain Halal certification across our
        entire product line, and we publish third-party lab test reports for every SKU on our site. We believe
        transparency is the most important ingredient in food.
      </p>
    </div>

    {{-- TRUST STRIP --}}
    <div class="trust" style="margin-bottom:52px;">
      <div class="trust-item">
        <div class="trust-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
          </svg>
        </div>
        <div>
          <b>Direct Sourcing</b>
          <span>No middlemen. 60+ farm partners.</span>
        </div>
      </div>
      <div class="trust-item">
        <div class="trust-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <b>Lab Tested Purity</b>
          <span>Every batch, third-party verified.</span>
        </div>
      </div>
      <div class="trust-item">
        <div class="trust-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
          </svg>
        </div>
        <div>
          <b>Halal Certified</b>
          <span>Full product line certified.</span>
        </div>
      </div>
      <div class="trust-item">
        <div class="trust-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13" rx="2"/>
            <path d="M16 8h4l3 5v3h-7V8z"/>
            <circle cx="5.5" cy="18.5" r="2.5"/>
            <circle cx="18.5" cy="18.5" r="2.5"/>
          </svg>
        </div>
        <div>
          <b>Nationwide Delivery</b>
          <span>Dhaka same-day, 64 districts.</span>
        </div>
      </div>
    </div>

    {{-- VALUES --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:28px; margin-bottom:52px;">
      <div class="co-card" style="margin:0;">
        <h3 style="font-size:20px; margin-bottom:12px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px;color:var(--green);vertical-align:middle;margin-right:8px;"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2z"/><path d="M12 8v4l3 3"/></svg>
          Our Mission
        </h3>
        <p style="color:var(--ink-soft); line-height:1.7; font-size:15px; margin:0;">
          To make genuinely pure, organic and Halal food accessible to every Bangladeshi household — at fair
          prices, with full transparency, delivered on time. No marketing fluff, no hidden additives, no
          compromise on quality.
        </p>
      </div>
      <div class="co-card" style="margin:0;">
        <h3 style="font-size:20px; margin-bottom:12px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px;color:var(--honey);vertical-align:middle;margin-right:8px;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
          Our Values
        </h3>
        <p style="color:var(--ink-soft); line-height:1.7; font-size:15px; margin:0;">
          Honesty, traceability and respect — for our farmers, our customers and the land that provides.
          We pay fair prices to producers, publish our lab reports publicly and stand behind every
          product with a full satisfaction guarantee.
        </p>
      </div>
    </div>

    {{-- STATS --}}
    <div style="background:var(--green-tint); border:1px solid var(--line); border-radius:var(--radius-l); padding:40px 48px; margin-bottom:48px;">
      <div class="hero-stats" style="border-top:none; padding-top:0; margin-top:0; justify-content:space-around;">
        <div class="hero-stat" style="text-align:center;">
          <b>10,000+</b>
          <span>Happy customers</span>
        </div>
        <div class="hero-stat" style="text-align:center;">
          <b>100%</b>
          <span>Organic &amp; natural</span>
        </div>
        <div class="hero-stat" style="text-align:center;">
          <b>8</b>
          <span>Product categories</span>
        </div>
        <div class="hero-stat" style="text-align:center;">
          <b>4.8★</b>
          <span>Average rating</span>
        </div>
        <div class="hero-stat" style="text-align:center;">
          <b>60+</b>
          <span>Farm partners</span>
        </div>
      </div>
    </div>

    <div style="text-align:center;">
      <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        Shop our products
      </a>
    </div>

  </div>
</div>

@endsection
