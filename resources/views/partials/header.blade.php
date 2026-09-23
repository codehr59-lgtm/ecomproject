<header class="hdr" x-data>

    @php
        $__freeShipMin = (int) \App\Models\Setting::get('free_shipping_min', 1500);
        $__giftEnabled = (bool) \App\Models\Setting::get('enable_free_gift', true);
        $__giftMin     = (int) \App\Models\Setting::get('free_gift_min', 3000);
        $__giftName    = \App\Models\Setting::get('free_gift_name', 'free gift');
    @endphp

    {{-- ── Announce bar ── --}}
    <div class="announce">
        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 6h11v9H3z"/>
            <path d="M14 9h4l3 3v3h-7"/>
            <path d="M7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
            <path d="M17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
        </svg>
        <span>Free delivery over <strong>৳{{ number_format($__freeShipMin) }}</strong> in Dhaka</span>
        <span class="dot" aria-hidden="true"></span>
        <span>Cash on delivery available</span>
        @if($__giftEnabled)
        <span class="dot" aria-hidden="true"></span>
        <span>Add <strong>৳{{ number_format($__giftMin) }}</strong> &amp; unlock a {{ $__giftName }}</span>
        @endif
    </div>

    {{-- ── Data ── --}}
    @php
        $__hdrLogo        = \App\Models\Setting::get('site_logo');
        $__hdrName        = \App\Models\Setting::get('site_name');
        $__hdrTagline     = \App\Models\Setting::get('site_tagline');
        $__siteLogoHeight = (int) \App\Models\Setting::get('site_logo_height', 52);
        if ($__siteLogoHeight < 24 || $__siteLogoHeight > 120) { $__siteLogoHeight = 52; }
        $__showTrack      = (bool) \App\Models\Setting::get('header_show_track', true);
        $__showAccount    = (bool) \App\Models\Setting::get('header_show_account', true);
        $__showWishlist   = (bool) \App\Models\Setting::get('header_show_wishlist', true);
        $__showCart       = (bool) \App\Models\Setting::get('header_show_cart', true);
        $__headerMenu     = \App\Models\Menu::getByLocation('header');
        $__menuItems      = $__headerMenu ? $__headerMenu->rootItems : collect();
        $__navCats        = \App\Models\Category::active()
            ->whereNull('parent_id')
            ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('sort')])
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort')
            ->get();
        $__quickCategories = \App\Models\Category::active()->whereNull('parent_id')->orderBy('sort')->take(10)->get();
    @endphp

    {{-- ══════════════════════════════════════════
         MOBILE HEADER (visible ≤620px only)
         Modern App-Style Mobile Header
         Row 1: Menu | Brand Logo + Name | Wishlist + Cart
         Row 2: Full-Width Search Pill with Submit Button
         Row 3: Quick Category Pills Horizontal Strip
         ══════════════════════════════════════════ --}}
    <div class="mh" x-data>
        {{-- Row 1: App Bar --}}
        <div class="mh-top">
            <div class="mh-left">
                <button type="button" class="mh-btn-menu" aria-label="Open menu" @click="$dispatch('toggle-mobile-menu')">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>

            <a href="{{ route('home') }}" class="mh-brand" aria-label="{{ $__hdrName ?: 'Home' }}">
                @if($__hdrLogo)
                <img src="{{ asset('storage/' . $__hdrLogo) }}" alt="{{ $__hdrName ?: 'Logo' }}" class="mh-brand-img">
                @endif
                <span class="mh-brand-info">
                    <span class="mh-brand-title">{{ $__hdrName ?: 'Shuvo' }}<b class="mh-brand-dot">.</b></span>
                    @if($__hdrTagline)<span class="mh-brand-sub">{{ $__hdrTagline }}</span>@endif
                </span>
            </a>

            <div class="mh-right">
                @if($__showWishlist)
                <a href="{{ route('wishlist') }}" class="mh-btn-icon" title="Wishlist" aria-label="Wishlist">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <span class="mh-badge" x-show="$store.shop.wishCount > 0" x-text="$store.shop.wishCount" x-cloak></span>
                </a>
                @endif

                @if($__showCart)
                <button type="button" class="mh-btn-icon mh-cart-btn" aria-label="Open cart" @click="$store.shop.toggle()">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h15l-1.5 9h-12L5 3H2"/><path d="M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/><path d="M18 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
                    <span class="mh-badge mh-badge-cart" x-show="$store.shop.count > 0" x-text="$store.shop.count" x-cloak></span>
                </button>
                @endif
            </div>
        </div>

        {{-- Row 2: Full-Width Search Pill --}}
        <div class="mh-search-row">
            <form action="{{ route('shop') }}" method="get" class="mh-search-form" role="search">
                <svg class="mh-search-ico" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="search" name="q" placeholder="Search honey, dates, ghee, grocery…" value="{{ request('q') }}" autocomplete="off">
                <button type="submit" class="mh-search-go" aria-label="Search">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
            </form>
        </div>

        {{-- Row 3: Quick Category Pills Strip --}}
        @if(isset($__quickCategories) && $__quickCategories->count())
        <div class="mh-cat-strip">
            <a href="{{ route('shop') }}" class="mh-cat-pill mh-cat-pill-all">
                <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M4 4h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 10h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 16h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4z"/></svg>
                All
            </a>
            @foreach($__quickCategories as $qc)
            <a href="{{ route('shop', ['category' => $qc->slug]) }}" class="mh-cat-pill {{ request('category') === $qc->slug ? 'on' : '' }}">
                {{ $qc->name }}
            </a>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════
         DESKTOP HEADER (visible >620px)
         ══════════════════════════════════════════ --}}
    <div class="wrap dh">
        <div class="hdr-main">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="brand" aria-label="{{ $__hdrName ?: 'Home' }}">
                @if($__hdrLogo)
                <img src="{{ asset('storage/' . $__hdrLogo) }}" alt="{{ $__hdrName ?: 'Logo' }}" class="brand-logo-img" style="height:{{ $__siteLogoHeight }}px; max-height:{{ max($__siteLogoHeight, 64) }}px; width:auto; max-width:240px; object-fit:contain;">
                @endif
                @if($__hdrName)
                <span>
                    <span class="brand-name">{{ $__hdrName }}<b>.</b></span>
                    @if($__hdrTagline)<span class="brand-tag" style="display:block">{{ $__hdrTagline }}</span>@endif
                </span>
                @endif
                @if(!$__hdrLogo && !$__hdrName)
                <span class="brand-mark">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 21c0-7 4-13 14-14 0 9-5 14-14 14z"/>
                        <path d="M5 21c2-5 5-8 9-10"/>
                    </svg>
                </span>
                <span><span class="brand-name">Shuvo<b>.</b></span></span>
                @endif
            </a>

            {{-- Search --}}
            <form action="{{ route('shop') }}" method="get" class="search" role="search">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/>
                    <path d="M21 21l-4.3-4.3"/>
                </svg>
                <label for="site-search" class="sr-only">Search products</label>
                <input type="search" id="site-search" name="q" placeholder="Search honey, dates, ghee…" value="{{ request('q') }}" autocomplete="off">
            </form>

            {{-- Header actions --}}
            <div class="hdr-actions">
                @if($__showTrack)
                <a href="{{ route('track') }}" class="icon-btn" title="Track order" aria-label="Track order">
                    <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h11v9H3z"/><path d="M14 9h4l3 3v3h-7"/><path d="M7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                    <span class="icon-label">Track</span>
                </a>
                @endif

                @if($__showAccount)
                    @auth
                        <a href="{{ route('account') }}" class="icon-btn" title="My Account" aria-label="My Account">
                            <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>
                            <span class="icon-label">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline">
                            @csrf
                            <button type="submit" class="icon-btn" title="Sign out" aria-label="Sign out" style="background:none;border:none;cursor:pointer">
                                <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                <span class="icon-label">Sign out</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="icon-btn" title="Sign in" aria-label="Sign in">
                            <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>
                            <span class="icon-label">Sign in</span>
                        </a>
                    @endauth
                @endif

                @if($__showWishlist)
                <a href="{{ route('wishlist') }}" class="icon-btn" title="Wishlist" aria-label="Your wishlist">
                    <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7-4.5-9.5-9A4.7 4.7 0 0 1 12 6a4.7 4.7 0 0 1 9.5 5c-2.5 4.5-9.5 9-9.5 9z"/></svg>
                    <span class="count" x-show="$store.shop.wishCount > 0" x-text="$store.shop.wishCount" x-cloak></span>
                    <span class="icon-label">Wishlist</span>
                </a>
                @endif

                @if($__showCart)
                <button type="button" class="icon-btn" title="Cart" aria-label="Open cart" @click="$store.shop.toggle()">
                    <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h15l-1.5 9h-12L5 3H2"/><path d="M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/><path d="M18 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
                    <span class="count" x-show="$store.shop.count > 0" x-text="$store.shop.count" x-cloak></span>
                    <span class="icon-label">Cart</span>
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Desktop nav bar ── --}}
    <nav class="nav" aria-label="Main navigation">
        <div class="wrap">
            <div class="nav-row">

                {{-- All Categories (hover dropdown) --}}
                <div class="nav-all-wrap" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <a href="{{ route('shop') }}" class="nav-all" aria-label="All categories">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 3h7v7H3z"/>
                            <path d="M14 3h7v7h-7z"/>
                            <path d="M14 14h7v7h-7z"/>
                            <path d="M3 14h7v7H3z"/>
                        </svg>
                        All Categories
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin-left:4px;transition:transform .2s;" :style="open ? 'transform:rotate(180deg)' : ''"><path d="M6 9l6 6 6-6"/></svg>
                    </a>

                    <div class="nav-dropdown" x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1">
                        @foreach($__navCats as $pCat)
                            <div class="nav-dd-group">
                                <a href="{{ route('category', $pCat->slug) }}" class="nav-dd-parent">
                                    @if($pCat->image)
                                        <img src="{{ asset('storage/' . $pCat->image) }}" alt="" style="width:22px;height:22px;border-radius:4px;object-fit:cover;">
                                    @endif
                                    <span>{{ $pCat->name }}</span>
                                    <small style="color:#999;font-size:11px;margin-left:auto;">{{ $pCat->products_count }}</small>
                                    @if($pCat->children->count())
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#bbb;margin-left:4px;"><path d="M9 18l6-6-6-6"/></svg>
                                    @endif
                                </a>
                                @if($pCat->children->count())
                                    <div class="nav-dd-children">
                                        @foreach($pCat->children as $child)
                                            <a href="{{ route('category', $child->slug) }}" class="nav-dd-child">{{ $child->name }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div style="border-top:1px solid #f0f0ee;padding:12px 18px;">
                            <a href="{{ route('shop') }}" style="font-size:13px;font-weight:600;color:#fff;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;background:var(--green, #356B3E);padding:9px 16px;border-radius:10px;transition:opacity .15s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                View All Products
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Admin-configured menu items --}}
                @foreach($__menuItems as $mi)
                    <a href="{{ $mi->resolvedUrl() }}" class="nav-link" @if($mi->target === '_blank') target="_blank" rel="noopener" @endif>{{ $mi->label }}</a>
                @endforeach

            </div>
        </div>
    </nav>

    {{-- ── Mobile slide-out menu (via hamburger — kept for deep navigation) ── --}}
    <div class="mob-menu-overlay" x-data="{ open: false }"
         @toggle-mobile-menu.window="open = !open"
         @keydown.escape.window="open = false">

        <div class="mob-menu-backdrop" x-show="open" x-cloak @click="open = false"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <aside class="mob-menu" x-show="open" x-cloak
               x-transition:enter="transition ease-out duration-250" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">

            <div class="mob-menu-head">
                @if($__hdrLogo)
                <img src="{{ asset('storage/' . $__hdrLogo) }}" alt="" style="height:26px;width:auto;">
                @endif
                <span class="mob-menu-brand">{{ $__hdrName ?: 'Menu' }}</span>
                <button type="button" class="mob-menu-close" @click="open = false" aria-label="Close menu">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <form action="{{ route('shop') }}" method="get" class="mob-menu-search">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/><path d="M21 21l-4.3-4.3"/></svg>
                <input type="search" name="q" placeholder="Search products…" value="{{ request('q') }}" autocomplete="off">
            </form>

            @if($__menuItems->count())
            <div class="mob-menu-section-title">Menu</div>
            <nav class="mob-menu-nav">
                @foreach($__menuItems as $mi)
                <a href="{{ $mi->resolvedUrl() }}" @if($mi->target === '_blank') target="_blank" rel="noopener" @endif>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    {{ $mi->label }}
                </a>
                @endforeach
            </nav>
            @endif

            <div class="mob-menu-section-title">Account</div>
            <nav class="mob-menu-nav">
                @auth
                <a href="{{ route('account') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>
                    My Account
                </a>
                <a href="{{ route('wishlist') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7-4.5-9.5-9A4.7 4.7 0 0 1 12 6a4.7 4.7 0 0 1 9.5 5c-2.5 4.5-9.5 9-9.5 9z"/></svg>
                    Wishlist
                </a>
                <a href="{{ route('track') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h11v9H3z"/><path d="M14 9h4l3 3v3h-7"/><path d="M7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                    Track Order
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mob-menu-logout">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Sign Out
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Sign In / Register
                </a>
                <a href="{{ route('track') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h11v9H3z"/><path d="M14 9h4l3 3v3h-7"/><path d="M7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                    Track Order
                </a>
                @endauth
            </nav>

        </aside>
    </div>

</header>
