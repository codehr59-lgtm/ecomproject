<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Shuvo — Pure, Organic &amp; Halal')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data>
    <a href="#main-content" class="skip-link sr-only focus:not-sr-only focus:absolute focus:z-[200] focus:top-2 focus:left-2 focus:bg-white focus:text-green-900 focus:px-4 focus:py-2 focus:rounded">Skip to content</a>

    <div class="stage">
        <div style="width:100%">
            @include('partials.header')

            <main id="main-content">
                @yield('content')
            </main>

            @include('partials.footer')

            @include('partials.cart-drawer')

            {{-- Toast notification --}}
            <div class="toast" :class="$store.shop.toastOn ? 'on' : ''" x-cloak>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12l5 5L20 6"/>
                </svg>
                <span x-text="$store.shop.toastMsg"></span>
            </div>
        </div>
    </div>
</body>
</html>
