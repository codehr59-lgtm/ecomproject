@extends('layouts.app')
@section('title', 'Shuvo — Pure, Organic & Halal Groceries')

@section('content')

{{-- ============================================================
     1. SPLIT HERO — Left: slider carousel, Right: static banner
     ============================================================ --}}
@if($sliders->count() || $banner)
<div class="home-hero">
  <div class="wrap">
    <div class="hero-split">

      {{-- LEFT: Slider carousel --}}
      @if($sliders->count())
      <div class="hero-slider"
           x-data="{
             slide: 0,
             total: {{ $sliders->count() }},
             auto: null,
             startAuto() {
               this.auto = setInterval(() => { this.slide = (this.slide + 1) % this.total }, 5000);
             },
             goto(i) {
               this.slide = i;
               clearInterval(this.auto);
               this.startAuto();
             }
           }"
           x-init="startAuto()"
           @mouseenter="clearInterval(auto)"
           @mouseleave="startAuto()">

        <div class="hero-slider-track" :style="`transform:translateX(-${slide * 100}%)`">
          @foreach($sliders as $s)
            <div class="hero-slide">
              <a href="{{ $s->button_url ?: route('shop') }}" style="display:block;width:100%;height:100%;">
                <img src="{{ asset('storage/' . $s->image) }}" alt="{{ $s->title }}">
                @if($s->title || $s->button_text)
                <div class="hero-slide-overlay">
                  @if($s->title)<h2>{{ $s->title }}</h2>@endif
                  @if($s->subtitle)<p>{{ $s->subtitle }}</p>@endif
                  @if($s->button_text)
                    <span class="btn btn-primary">
                      {{ $s->button_text }}
                      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                  @endif
                </div>
                @endif
              </a>
            </div>
          @endforeach
        </div>

        @if($sliders->count() > 1)
          <button class="hero-arrow hero-arrow-l" @click="goto((slide - 1 + total) % total)" aria-label="Previous">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
          </button>
          <button class="hero-arrow hero-arrow-r" @click="goto((slide + 1) % total)" aria-label="Next">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
          </button>
          <div class="hero-dots-inside">
            @foreach($sliders as $i => $s)
              <i :class="slide === {{ $i }} ? 'on' : ''" @click="goto({{ $i }})"></i>
            @endforeach
          </div>
        @endif
      </div>
      @endif

      {{-- RIGHT: Static banner (from Banners, position=hero) --}}
      @if($banner)
      <a class="hero-banner-right" href="{{ $banner->href ?: route('shop') }}">
        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}">
        @if($banner->title || $banner->cta)
        <div class="hero-banner-overlay">
          @if($banner->title)<h2>{{ $banner->title }}</h2>@endif
          @if($banner->subtitle)<p>{{ $banner->subtitle }}</p>@endif
          @if($banner->cta)
            <span class="btn btn-honey">{{ $banner->cta }}</span>
          @endif
        </div>
        @endif
      </a>
      @endif

    </div>

  </div>
</div>
@endif

{{-- ============================================================
     2. FEATURED CATEGORIES (slider)
     ============================================================ --}}
@if(($showFeaturedCats ?? true) && !empty($categories))
<div class="rail">
  <div class="wrap">
    <x-center-title title="Featured Categories" />
    <div class="fcat-slider"
         x-data="{
           track: null,
           timer: null,
           init() {
             this.track = this.$refs.track;
             this.startAuto();
           },
           scrollBy(dir) {
             const gap = 12;
             const card = this.track.querySelector('.fcat');
             if (!card) return;
             const dist = (card.offsetWidth + gap);
             this.track.scrollBy({ left: dir * dist, behavior: 'smooth' });
           },
           startAuto() {
             this.timer = setInterval(() => {
               if ((this.track.scrollLeft + this.track.clientWidth) >= (this.track.scrollWidth - 4)) {
                 this.track.scrollTo({ left: 0, behavior: 'smooth' });
               } else {
                 this.scrollBy(1);
               }
             }, 3000);
           },
           stopAuto() { clearInterval(this.timer); },
           resetAuto() { this.stopAuto(); this.startAuto(); },
           get canLeft()  { return this.track && this.track.scrollLeft > 0 },
           get canRight() { return this.track && (this.track.scrollLeft + this.track.clientWidth) < (this.track.scrollWidth - 4) }
         }"
         @mouseenter="stopAuto()" @mouseleave="startAuto()"
         @touchstart.passive="stopAuto()" @touchend.passive="startAuto()"
         @scroll.passive="$el.scrollLeft"
    >
      <button type="button" class="fcat-arrow fcat-arrow-l" @click="scrollBy(-1); resetAuto()" x-show="canLeft" x-transition.opacity aria-label="Scroll left">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
      </button>
      <div class="fcat-track" x-ref="track" @scroll.passive="$dispatch('scroll')">
        @foreach($categories as $c)
          <x-category-tile :category="$c" />
        @endforeach
      </div>
      <button type="button" class="fcat-arrow fcat-arrow-r" @click="scrollBy(1); resetAuto()" x-show="canRight" x-transition.opacity aria-label="Scroll right">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
      </button>
    </div>
  </div>
</div>
@endif

{{-- ============================================================
     3. TOP SELLING PRODUCTS
     ============================================================ --}}
@if(($showTopSelling ?? true) && !empty($topSelling))
<div class="rail">
  <div class="wrap">
    <x-center-title :title="$topSellingTitle ?? 'Top Selling Products'" />
    <div class="top-grid">
      @foreach($topSelling as $p)
        <x-top-card :product="$p" />
      @endforeach
    </div>
  </div>
</div>
@endif

{{-- ============================================================
     4. OUR BRANDS
     ============================================================ --}}
@if(($showBrands ?? true) && !empty($brands))
<div class="rail">
  <div class="wrap">
    <x-rail-head title="Our Brands" :viewAll="route('shop')" />
    <div class="brands-row">
      @foreach($brands as $b)
        <x-brand-card :name="$b" />
      @endforeach
    </div>
  </div>
</div>
@endif

{{-- ============================================================
     5. DYNAMIC CATEGORY RAILS (Configured from Admin Dashboard)
     ============================================================ --}}
@if(!empty($categoryRails))
  @foreach($categoryRails as $index => $rail)
    <div class="rail">
      <div class="wrap">
        <x-rail-head :title="$rail['title']" :viewAll="$rail['viewAllUrl']" />
        <x-product-slider :products="$rail['products']" />
      </div>
    </div>

    {{-- Insert Combo Deals strip after 2nd category rail (or 1st if only 1) --}}
    @if(($showCombos ?? true) && !empty($combos) && ($index === 1 || (count($categoryRails) === 1 && $index === 0)))
      <div class="rail">
        <div class="wrap">
          <div class="combo-band">
            <div class="combo-band-head">
              <span class="cb-ico">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
                </svg>
              </span>
              <h2>Exclusive Combo Deals</h2>
              <a class="view-all" href="{{ route('combos.index') }}">
                View All Combos
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
              </a>
            </div>
            <div class="combo-strip">
              @foreach($combos as $c)
                <x-combo-card :combo="$c" />
              @endforeach
            </div>
          </div>
        </div>
      </div>
    @endif
  @endforeach
@endif

{{-- If combos weren't rendered yet (e.g. no category rails active), render here --}}
@if(($showCombos ?? true) && !empty($combos) && empty($categoryRails))
<div class="rail">
  <div class="wrap">
    <div class="combo-band">
      <div class="combo-band-head">
        <span class="cb-ico">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
          </svg>
        </span>
        <h2>Exclusive Combo Deals</h2>
        <a class="view-all" href="{{ route('combos.index') }}">
          View All Combos
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M13 6l6 6-6 6"/>
          </svg>
        </a>
      </div>
      <div class="combo-strip">
        @foreach($combos as $c)
          <x-combo-card :combo="$c" />
        @endforeach
      </div>
    </div>
  </div>
</div>
@endif

{{-- ============================================================
     6. FULL-WIDTH IMAGE BAND
     ============================================================ --}}
<div class="rail">
  <div class="wrap">
    <div class="img-band">
      <h3>Sourced with care, delivered with trust</h3>
      <span class="hbanner-tag">lifestyle · banner</span>
    </div>
  </div>
</div>

{{-- ============================================================
     7. ORGANIC CERTIFIED RAIL
     ============================================================ --}}
@if(($showCertified ?? true) && !empty($certified))
<div class="rail">
  <div class="wrap">
    <x-rail-head :title="$certifiedTitle ?? 'Organic Certified'" :viewAll="route('shop')" />
    <x-product-slider :products="$certified" />
  </div>
</div>
@endif

{{-- ============================================================
     8. JUST FOR YOU
     ============================================================ --}}
@if(($showJustForYou ?? true) && !empty($justForYou))
<div class="rail">
  <div class="wrap">
    <x-rail-head :title="$justForYouTitle ?? 'Just For You'" />
    <div class="grid-5">
      @foreach($justForYou as $p)
        <x-product-card :product="$p" :buyNow="false" />
      @endforeach
    </div>
    <div class="load-more">
      <button type="button">Load More</button>
    </div>
  </div>
</div>
@endif

{{-- ============================================================
     9. TESTIMONIALS
     ============================================================ --}}
@if(($showTestimonials ?? true) && $testimonials->count())
<div class="section">
  <div class="wrap">
    <x-center-title title="What Our Customers Say" />
    <div class="grid-4" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:18px;margin-top:8px;">
      @foreach($testimonials as $t)
        <div class="tcard">
          <div class="stars">
            @for($i = 0; $i < ($t->rating ?: 5); $i++)
              <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
              </svg>
            @endfor
          </div>
          <p>"{{ $t->content }}"</p>
          <div class="tcard-author">
            @if($t->avatar)
              <img src="{{ asset('storage/' . $t->avatar) }}" alt="{{ $t->name }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
            @else
              <span class="t-avatar">{{ mb_substr($t->name, 0, 1) }}</span>
            @endif
            <span>
              <b>{{ $t->name }}</b>
              @if($t->designation)<span>{{ $t->designation }}</span>@endif
            </span>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endif

@endsection
