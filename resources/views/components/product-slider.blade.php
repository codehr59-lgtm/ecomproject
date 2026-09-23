@props([
    'products' => [],
    'buyNow'   => false,
    'autoplay' => null,
    'speed'    => null,
    'arrows'   => null,
    'dots'     => null,
    'perView'  => null,
])

@if(empty($products) || count($products) === 0)
  <div class="rail-empty">
    <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
      <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
      <line x1="3" y1="6" x2="21" y2="6"/>
      <path d="M16 10a4 4 0 0 1-8 0"/>
    </svg>
    <p>No products currently available in this section.</p>
  </div>
@else
  @php
    $count        = count($products);
    $autoplayVal  = $autoplay ?? \App\Models\Setting::get('homepage_slider_autoplay', true);
    $speedVal     = (int) ($speed ?? \App\Models\Setting::get('homepage_slider_speed', 4));
    if ($speedVal < 1 || $speedVal > 20) { $speedVal = 4; }
    $arrowsVal    = $arrows ?? \App\Models\Setting::get('homepage_slider_arrows', true);
    $dotsVal      = $dots ?? \App\Models\Setting::get('homepage_slider_dots', true);
    $perViewVal   = (string) ($perView ?? \App\Models\Setting::get('homepage_slider_per_view', '4'));
    $dotsCount    = max(2, min(6, $count));
  @endphp
  <div class="prod-slider {{ $perViewVal === '5' ? 'prod-slider-5' : 'prod-slider-4' }}"
       x-data="{
         track: null,
         activeDot: 0,
         dotsCount: {{ $dotsCount }},
         canLeft: false,
         canRight: false,
         canScroll: false,
         autoTimer: null,
         autoplayEnabled: {{ $autoplayVal ? 'true' : 'false' }},
         autoplaySpeed: {{ $speedVal * 1000 }},
         isDown: false,
         startX: 0,
         scrollStart: 0,

         init() {
           this.track = this.$refs.track;
           this.$nextTick(() => {
             this.update();
             this.startAuto();
           });
         },

         update() {
           if (!this.track) return;
           const maxScroll = this.track.scrollWidth - this.track.clientWidth;
           this.canScroll = maxScroll > 8;
           this.canLeft = this.track.scrollLeft > 8;
           this.canRight = (this.track.scrollLeft + this.track.clientWidth) < (this.track.scrollWidth - 8);

           if (this.canScroll && this.dotsCount > 1) {
             const ratio = Math.max(0, Math.min(1, this.track.scrollLeft / maxScroll));
             this.activeDot = Math.min(this.dotsCount - 1, Math.round(ratio * (this.dotsCount - 1)));
           } else {
             this.activeDot = 0;
           }
         },

         scrollBy(dir) {
           if (!this.track) return;
           const slide = this.track.querySelector('.prod-slide');
           const amount = slide ? (slide.offsetWidth + 16) * (window.innerWidth < 768 ? 1 : 2) : 380;
           const maxScroll = this.track.scrollWidth - this.track.clientWidth;

           // Smooth circular loop when reaching ends
           if (dir > 0 && (this.track.scrollLeft + this.track.clientWidth) >= (this.track.scrollWidth - 10)) {
             this.track.scrollTo({ left: 0, behavior: 'smooth' });
           } else if (dir < 0 && this.track.scrollLeft <= 8) {
             this.track.scrollTo({ left: maxScroll, behavior: 'smooth' });
           } else {
             this.track.scrollBy({ left: dir * amount, behavior: 'smooth' });
           }

           setTimeout(() => this.update(), 350);
         },

         scrollToDot(idx) {
           if (!this.track) return;
           const maxScroll = this.track.scrollWidth - this.track.clientWidth;
           if (maxScroll <= 0 || this.dotsCount <= 1) return;
           const target = (idx / (this.dotsCount - 1)) * maxScroll;
           this.track.scrollTo({ left: target, behavior: 'smooth' });
           this.activeDot = idx;
           setTimeout(() => this.update(), 350);
         },

         startAuto() {
           if (!this.autoplayEnabled || !this.canScroll) return;
           this.stopAuto();
           this.autoTimer = setInterval(() => {
             if (!this.track || !this.canScroll) return;
             if ((this.track.scrollLeft + this.track.clientWidth) >= (this.track.scrollWidth - 10)) {
               this.track.scrollTo({ left: 0, behavior: 'smooth' });
             } else {
               this.scrollBy(1);
             }
           }, this.autoplaySpeed);
         },

         stopAuto() {
           if (this.autoTimer) {
             clearInterval(this.autoTimer);
             this.autoTimer = null;
           }
         },

         resetAuto() {
           this.stopAuto();
           this.startAuto();
         },

         onMouseDown(e) {
           if (e.target.closest('button, a, input')) return;
           this.isDown = true;
           this.stopAuto();
           this.startX = e.pageX - this.track.offsetLeft;
           this.scrollStart = this.track.scrollLeft;
         },
         onMouseMove(e) {
           if (!this.isDown) return;
           e.preventDefault();
           const x = e.pageX - this.track.offsetLeft;
           const walk = (x - this.startX) * 1.3;
           this.track.scrollLeft = this.scrollStart - walk;
         },
         onMouseUp() {
           if (this.isDown) {
             this.isDown = false;
             this.startAuto();
           }
         }
       }"
       @mouseenter="stopAuto()"
       @mouseleave="startAuto()"
       @touchstart.passive="stopAuto()"
       @touchend.passive="startAuto()"
       @resize.window.debounce.150ms="update()"
  >
    @if($arrowsVal)
    <!-- Left Navigation Arrow -->
    <button type="button"
            class="prod-arrow prod-arrow-l"
            @click="scrollBy(-1); resetAuto()"
            x-show="canScroll"
            x-transition.opacity
            aria-label="Previous products">
      <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M15 18l-6-6 6-6"/>
      </svg>
    </button>
    @endif

    <!-- Product Scroll Track -->
    <div class="prod-track"
         x-ref="track"
         @scroll.passive="update()"
         @mousedown="onMouseDown($event)"
         @mousemove="onMouseMove($event)"
         @mouseup="onMouseUp()"
         @mouseleave="onMouseUp()">
      @foreach($products as $p)
        <div class="prod-slide">
          <x-product-card :product="$p" :buyNow="$buyNow" />
        </div>
      @endforeach
    </div>

    @if($arrowsVal)
    <!-- Right Navigation Arrow -->
    <button type="button"
            class="prod-arrow prod-arrow-r"
            @click="scrollBy(1); resetAuto()"
            x-show="canScroll"
            x-transition.opacity
            aria-label="Next products">
      <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 18l6-6-6-6"/>
      </svg>
    </button>
    @endif

    @if($dotsVal)
    <!-- Interactive Pagination Dots -->
    <div class="dots prod-dots" x-show="canScroll" x-cloak>
      @for($d = 0; $d < $dotsCount; $d++)
        <i :class="activeDot === {{ $d }} ? 'on' : ''"
           @click="scrollToDot({{ $d }}); resetAuto()"
           role="button"
           tabindex="0"
           aria-label="Go to slide {{ $d + 1 }}"></i>
      @endfor
    </div>
    @endif
  </div>
@endif
