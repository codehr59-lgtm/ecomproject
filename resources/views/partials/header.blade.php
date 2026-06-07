@php
    $cats = \App\Support\Catalog::categories();
    $firstCat = $cats[0] ?? ['slug' => 'cooking-essentials', 'name' => 'Categories'];
@endphp

<header class="sticky top-0 z-40" x-data="{ m: false }">

    {{-- ── ROW 1: Dark green sticky navigation bar ── --}}
    <div class="bg-dark text-white">
        <div class="max-w-content mx-auto px-4 h-14 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="text-xl font-extrabold text-white shrink-0">
                Ghorer <span class="text-primary">Bazar</span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('home') }}"
                   class="text-white hover:text-primary transition-colors">Home</a>

                <a href="{{ route('category', $firstCat['slug']) }}"
                   class="text-white hover:text-primary transition-colors">Categories</a>

                @foreach(array_slice($cats, 1, 2) as $cat)
                    <a href="{{ route('category', $cat['slug']) }}"
                       class="text-white hover:text-primary transition-colors">{{ $cat['name'] }}</a>
                @endforeach

                <a href="#" class="text-white hover:text-primary transition-colors">Offers</a>
                <a href="#" class="text-white hover:text-primary transition-colors">Contact</a>
            </nav>

            {{-- Mobile hamburger --}}
            <button type="button"
                    class="md:hidden text-white p-1"
                    @click="m = !m"
                    aria-label="Toggle menu"
                    :aria-expanded="m.toString()"
                    aria-controls="mobile-nav">
                {{-- Hamburger (closed state) --}}
                <svg x-show="!m" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                {{-- Close X (open state) --}}
                <svg x-show="m" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile dropdown menu --}}
        <div id="mobile-nav" x-show="m" x-cloak
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="md:hidden bg-dark border-t border-white/10">
            <nav class="max-w-content mx-auto px-4 py-3 flex flex-col gap-1 text-sm font-medium">
                <a href="{{ route('home') }}"
                   class="py-2 text-white hover:text-primary transition-colors"
                   @click="m = false">Home</a>

                <a href="{{ route('category', $firstCat['slug']) }}"
                   class="py-2 text-white hover:text-primary transition-colors"
                   @click="m = false">Categories</a>

                @foreach(array_slice($cats, 1, 2) as $cat)
                    <a href="{{ route('category', $cat['slug']) }}"
                       class="py-2 text-white hover:text-primary transition-colors"
                       @click="m = false">{{ $cat['name'] }}</a>
                @endforeach

                <a href="#"
                   class="py-2 text-white hover:text-primary transition-colors"
                   @click="m = false">Offers</a>
                <a href="#"
                   class="py-2 text-white hover:text-primary transition-colors"
                   @click="m = false">Contact</a>
            </nav>
        </div>
    </div>

    {{-- ── ROW 2: White utility row (search + icons) ── --}}
    <div class="bg-white border-b border-border-light">
        <div class="max-w-content mx-auto px-4 h-14 flex items-center gap-4">

            {{-- Search form --}}
            <form action="#" class="flex flex-1 max-w-xl">
                <label for="site-search" class="sr-only">Search products</label>
                <input type="search"
                       id="site-search"
                       placeholder="Search products..."
                       class="field rounded-r-none flex-1">
                <button type="submit"
                        class="btn-primary rounded-l-none px-5 shrink-0">Search</button>
            </form>

            {{-- Utility links (hidden on very small screens, visible from sm) --}}
            <div class="flex items-center gap-4 text-sm text-text">
                <a href="#"
                   class="hidden sm:block hover:text-primary transition-colors whitespace-nowrap">
                    Track Order
                </a>
                <a href="#"
                   class="hidden sm:block hover:text-primary transition-colors whitespace-nowrap">
                    Sign In
                </a>

                {{-- Wishlist --}}
                <a href="#" aria-label="Wishlist"
                   class="hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                </a>

                {{-- Cart button --}}
                <button type="button"
                        class="relative"
                        @click="$store.cart.toggle()"
                        aria-label="Open cart">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-.532 2.067-1.42 2.67-2.535m0 0a23.86 23.86 0 0 0 1.087-2.25M6.106 5.272l1.394 8.978m0 0L9 6.75"/>
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-primary text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center"
                          x-text="$store.cart.count"
                          x-show="$store.cart.count > 0"
                          x-cloak></span>
                </button>
            </div>
        </div>
    </div>

</header>
