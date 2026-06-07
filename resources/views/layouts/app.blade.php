<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ghorer Bazar')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700;800&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-text"
      x-data
      x-init="$store.cart.threshold = {{ \App\Support\Catalog::giftThreshold() }}">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-[60] focus:top-2 focus:left-2 focus:bg-primary focus:text-white focus:px-4 focus:py-2 focus:rounded">Skip to content</a>
    @include('partials.header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.cart-drawer')
</body>
</html>
