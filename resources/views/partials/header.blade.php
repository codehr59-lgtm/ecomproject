<header class="hdr" x-data>

    {{-- ── Announce bar ── --}}
    <div class="announce">
        {{-- Truck icon --}}
        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 6h11v9H3z"/>
            <path d="M14 9h4l3 3v3h-7"/>
            <path d="M7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
            <path d="M17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
        </svg>
        <span>Free delivery over <strong>৳1,500</strong> in Dhaka</span>
        <span class="dot" aria-hidden="true"></span>
        <span>Cash on delivery available</span>
        <span class="dot" aria-hidden="true"></span>
        <span>Add <strong>৳3,000</strong> &amp; unlock a free gift</span>
    </div>

    {{-- ── Main header row ── --}}
    <div class="wrap">
        <div class="hdr-main">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="brand" aria-label="Shuvo — home">
                <span class="brand-mark">
                    {{-- Leaf icon --}}
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 21c0-7 4-13 14-14 0 9-5 14-14 14z"/>
                        <path d="M5 21c2-5 5-8 9-10"/>
                    </svg>
                </span>
                <span>
                    <span class="brand-name">Shuvo<b>.</b></span>
                    <span class="brand-tag" style="display:block">Pure · Organic · Halal</span>
                </span>
            </a>

            {{-- Search --}}
            <form action="{{ route('shop') }}" method="get" class="search" role="search">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/>
                    <path d="M21 21l-4.3-4.3"/>
                </svg>
                <label for="site-search" class="sr-only">Search products</label>
                <input
                    type="search"
                    id="site-search"
                    name="q"
                    placeholder="Search honey, dates, ghee…"
                    value="{{ request('q') }}"
                    autocomplete="off"
                >
            </form>

            {{-- Header actions --}}
            <div class="hdr-actions">

                {{-- Track --}}
                <a href="{{ route('track') }}" class="icon-btn" title="Track order" aria-label="Track order">
                    <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 6h11v9H3z"/>
                        <path d="M14 9h4l3 3v3h-7"/>
                        <path d="M7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                        <path d="M17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                    </svg>
                    <span class="icon-label">Track</span>
                </a>

                {{-- Account / Sign in --}}
                @auth
                    <a href="{{ route('account') }}" class="icon-btn" title="My Account" aria-label="My Account">
                        <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                            <path d="M5 20a7 7 0 0 1 14 0"/>
                        </svg>
                        <span class="icon-label">{{ explode(' ', auth()->user()->name)[0] }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="icon-btn" title="Sign out" aria-label="Sign out" style="background:none;border:none;cursor:pointer">
                            <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span class="icon-label">Sign out</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="icon-btn" title="Sign in" aria-label="Sign in to your account">
                        <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                            <path d="M5 20a7 7 0 0 1 14 0"/>
                        </svg>
                        <span class="icon-label">Sign in</span>
                    </a>
                @endauth

                {{-- Wishlist --}}
                <a href="{{ route('wishlist') }}" class="icon-btn" title="Wishlist" aria-label="Your wishlist">
                    <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 20s-7-4.5-9.5-9A4.7 4.7 0 0 1 12 6a4.7 4.7 0 0 1 9.5 5c-2.5 4.5-9.5 9-9.5 9z"/>
                    </svg>
                    <span class="count" x-show="$store.shop.wishCount > 0" x-text="$store.shop.wishCount" x-cloak aria-label="wishlist count"></span>
                    <span class="icon-label">Wishlist</span>
                </a>

                {{-- Cart --}}
                <button type="button" class="icon-btn" title="Cart" aria-label="Open cart" @click="$store.shop.toggle()">
                    <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 6h15l-1.5 9h-12L5 3H2"/>
                        <path d="M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                        <path d="M18 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                    <span class="count" x-show="$store.shop.count > 0" x-text="$store.shop.count" x-cloak aria-label="cart item count"></span>
                    <span class="icon-label">Cart</span>
                </button>

            </div>
        </div>
    </div>

    {{-- ── Category nav ── --}}
    <nav class="nav" aria-label="Product categories">
        <div class="wrap">
            <div class="nav-row">

                {{-- All Categories --}}
                <a href="{{ route('shop') }}" class="nav-all" aria-label="All categories">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3h7v7H3z"/>
                        <path d="M14 3h7v7h-7z"/>
                        <path d="M14 14h7v7h-7z"/>
                        <path d="M3 14h7v7H3z"/>
                    </svg>
                    All Categories
                </a>

                {{-- Offer Zone --}}
                <a href="{{ route('shop', ['deal' => 1]) }}" class="nav-link hot">Offer Zone</a>

                {{-- Dynamic categories --}}
                @foreach(\App\Support\Catalog::categories() as $c)
                    <a href="{{ route('category', $c['id']) }}" class="nav-link">{{ $c['name'] }}</a>
                @endforeach

            </div>
        </div>
    </nav>

</header>
