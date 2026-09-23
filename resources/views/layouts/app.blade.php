<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', \App\Models\Setting::get('meta_title', 'Shuvo — Pure, Organic &amp; Halal'))</title>
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @else
        @if(\App\Models\Setting::get('meta_description'))
        <meta name="description" content="{{ \App\Models\Setting::get('meta_description') }}">
        @endif
    @endif
    @if(\App\Models\Setting::get('meta_keywords'))
    <meta name="keywords" content="{{ \App\Models\Setting::get('meta_keywords') }}">
    @endif
    @php
        $__siteLogo    = \App\Models\Setting::get('site_logo');
        $__siteFavicon = \App\Models\Setting::get('site_favicon');
        $__siteName    = \App\Models\Setting::get('site_name', 'Shuvo');
        $__siteTagline = \App\Models\Setting::get('site_tagline', 'Pure · Organic · Halal');
        $__fontHeading = \App\Models\Setting::get('theme_font_heading', 'Bricolage Grotesque');
        $__fontBody    = \App\Models\Setting::get('theme_font_body', 'Hanken Grotesk');
        $t = fn($k, $d = null) => \App\Models\Setting::get($k, $d);
        $__primaryColor      = $t('theme_primary_color');
        $__secondaryColor    = $t('theme_secondary_color');
        $__accentColor       = $t('theme_accent_color');
        $__topbarBg          = $t('theme_topbar_bg');
        $__topbarBgHover     = $t('theme_topbar_bg_hover');
        $__topbarText        = $t('theme_topbar_text');
        $__headerBg          = $t('theme_header_bg');
        $__headerIconColor   = $t('theme_header_icon_color');
        $__headerIconHover   = $t('theme_header_icon_hover');
        $__headerIconBgHover = $t('theme_header_icon_bg_hover');
        $__headerBadgeBg     = $t('theme_header_badge_bg');
        $__headerBadgeText   = $t('theme_header_badge_text');
        $__menuBg            = $t('theme_menu_bg');
        $__navBg             = $t('theme_nav_bg');
        $__navBgHover        = $t('theme_nav_bg_hover');
        $__navText           = $t('theme_nav_text');
        $__navLinkText       = $t('theme_nav_link_text');
        $__navLinkHoverText  = $t('theme_nav_link_hover_text');
        $__navLinkHoverBg    = $t('theme_nav_link_hover_bg');
        $__btnBg             = $t('theme_btn_bg');
        $__btnBgHover        = $t('theme_btn_bg_hover');
        $__btnText           = $t('theme_btn_text');
        $__btnTextHover      = $t('theme_btn_text_hover');
        $__addcartBg         = $t('theme_addcart_bg');
        $__addcartBgHover    = $t('theme_addcart_bg_hover');
        $__addcartText       = $t('theme_addcart_text');
        $__addcartTextHover  = $t('theme_addcart_text_hover');
        $__buynowBg          = $t('theme_buynow_bg');
        $__buynowBgHover     = $t('theme_buynow_bg_hover');
        $__buynowText        = $t('theme_buynow_text');
        $__buynowTextHover   = $t('theme_buynow_text_hover');
        $__sliderBg          = $t('theme_slider_bg');
        $__sliderBgHover     = $t('theme_slider_bg_hover');
        $__sliderText        = $t('theme_slider_text');
        $__sliderTextHover   = $t('theme_slider_text_hover');
        $__linkColor         = $t('theme_link_color');
        $__linkHover         = $t('theme_link_hover');
        $__textColor         = $t('theme_text_color');
        $__textMuted         = $t('theme_text_muted');
        $__footerBg          = $t('theme_footer_bg');
        $__footerTextColor   = $t('theme_footer_text_color');
        $__footerLinkHover   = $t('theme_footer_link_hover');
        $__baseFontSize      = $t('theme_base_font_size', '15');
        $__headingScale      = $t('theme_heading_scale', '1');
        $__mobSearchBg       = $t('theme_mob_search_bg');
        $__mobSearchText     = $t('theme_mob_search_text');
        $__mobCartBg         = $t('theme_mob_cart_bg');
        $__mobCartText       = $t('theme_mob_cart_text');
        $__mobTabText        = $t('theme_mob_tab_text');
        $__mobTabActive      = $t('theme_mob_tab_active');
        $__mobnavBg          = $t('theme_mobnav_bg');
        $__mobnavText        = $t('theme_mobnav_text');
        $__mobnavActive      = $t('theme_mobnav_active');

        $__fontFamilies = collect([$__fontHeading, $__fontBody])->unique()->map(function($f) {
            $slug = str_replace(' ', '+', $f);
            return "family={$slug}:wght@400;500;600;700;800";
        })->implode('&');
    @endphp
    @if($__siteFavicon)
    <link rel="icon" href="{{ asset('storage/' . $__siteFavicon) }}" type="image/png">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?{{ $__fontFamilies }}&display=swap" rel="stylesheet">
    <script>
        window.AUTH = {{ auth()->check() ? 'true' : 'false' }};
        window.WISHLIST = @json(auth()->check() ? auth()->user()->wishlists()->pluck('product_id') : []);
        window.DELIVERY_CONFIG = {
            inside: {{ (int) \App\Models\Setting::get('delivery_inside_dhaka', 60) }},
            outside: {{ (int) \App\Models\Setting::get('delivery_outside_dhaka', 120) }},
            freeMin: {{ (int) \App\Models\Setting::get('free_shipping_min', 1500) }}
        };
        window.GIFT_CONFIG = {
            enabled: {{ \App\Models\Setting::get('enable_free_gift', true) ? 'true' : 'false' }},
            min: {{ (int) \App\Models\Setting::get('free_gift_min', 3000) }},
            name: {!! json_encode(\App\Models\Setting::get('free_gift_name', 'free Lychee Honey sachet')) !!},
            successMsg: {!! json_encode(\App\Models\Setting::get('free_gift_success_msg', "🎉 You've unlocked a free gift! It'll be added at checkout.")) !!}
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --font-display: "{{ $__fontHeading }}", system-ui, sans-serif;
            --font-body: "{{ $__fontBody }}", system-ui, sans-serif;
            @if($__primaryColor)
            --green: {{ $__primaryColor }};
            --green-soft: {{ $__primaryColor }}1a;
            --green-tint: {{ $__primaryColor }}0d;
            --green-mid: {{ $__primaryColor }}cc;
            @endif
            @if($__secondaryColor)
            --green-deep: {{ $__secondaryColor }};
            @endif
            @if($__accentColor)
            --honey: {{ $__accentColor }};
            --honey-deep: #e07800;
            --orange: {{ $__accentColor }};
            --orange-deep: #e07800;
            --orange-soft: {{ $__accentColor }}1f;
            --orange-band: {{ $__accentColor }}14;
            @endif
            @if($__menuBg)
            --nav: {{ $__menuBg }};
            @endif
            @if($__textColor)
            --ink: {{ $__textColor }};
            @endif
            @if($__textMuted)
            --muted: {{ $__textMuted }};
            --ink-soft: {{ $__textMuted }};
            @endif
        }

        /* ── Top Bar ── */
        @if($__topbarBg)
        .announce { background: {{ $__topbarBg }} !important; }
        @endif
        @if($__topbarBgHover)
        .announce:hover { background: {{ $__topbarBgHover }} !important; }
        @endif
        @if($__topbarText)
        .announce, .announce strong { color: {{ $__topbarText }} !important; }
        @endif

        /* ── Header ── */
        @if($__headerBg)
        .hdr { background: {{ $__headerBg }} !important; }
        @endif
        @if($__headerIconColor)
        .icon-btn, .hdr-actions .icon-btn, .hdr-actions .icon-btn .icon-label { color: {{ $__headerIconColor }} !important; }
        .hdr-actions .icon-btn svg { stroke: {{ $__headerIconColor }} !important; }
        @endif
        @if($__headerIconHover)
        .icon-btn:hover, .hdr-actions .icon-btn:hover, .hdr-actions .icon-btn:hover .icon-label { color: {{ $__headerIconHover }} !important; }
        .hdr-actions .icon-btn:hover svg { stroke: {{ $__headerIconHover }} !important; }
        @endif
        @if($__headerIconBgHover)
        .icon-btn:hover, .hdr-actions .icon-btn:hover { background: {{ $__headerIconBgHover }} !important; }
        @endif
        @if($__headerBadgeBg)
        .hdr-actions .icon-btn .count { background: {{ $__headerBadgeBg }} !important; }
        @endif
        @if($__headerBadgeText)
        .hdr-actions .icon-btn .count { color: {{ $__headerBadgeText }} !important; }
        @endif

        /* ── Main Navigation Menu Bar ── */
        @if($__menuBg)
        .nav { background: {{ $__menuBg }} !important; }
        :root { --nav: {{ $__menuBg }} !important; }
        @endif

        /* ── Category Nav — All Categories button ── */
        @if($__navBg)
        .nav-all { background: {{ $__navBg }} !important; }
        @endif
        @if($__navBgHover)
        .nav-all:hover { background: {{ $__navBgHover }} !important; }
        @endif
        @if($__navText)
        .nav-all { color: {{ $__navText }} !important; }
        @endif

        /* ── Category Nav — links ── */
        @if($__navLinkText)
        .nav-link { color: {{ $__navLinkText }} !important; }
        @endif
        @if($__navLinkHoverText)
        .nav-link:hover { color: {{ $__navLinkHoverText }} !important; }
        @endif
        @if($__navLinkHoverBg)
        .nav-link:hover { background: {{ $__navLinkHoverBg }} !important; }
        @endif

        /* ── Primary Button ── */
        @if($__btnBg)
        .btn-primary { background: {{ $__btnBg }} !important; }
        @endif
        @if($__btnBgHover)
        .btn-primary:hover { background: {{ $__btnBgHover }} !important; }
        @endif
        @if($__btnText)
        .btn-primary { color: {{ $__btnText }} !important; }
        @endif
        @if($__btnTextHover)
        .btn-primary:hover { color: {{ $__btnTextHover }} !important; }
        @endif

        /* ── Add to Cart ── */
        @if($__addcartBg)
        .add-btn { background: {{ $__addcartBg }} !important; border-color: {{ $__addcartBg }} !important; }
        @endif
        @if($__addcartBgHover)
        .add-btn:hover, .add-btn.added { background: {{ $__addcartBgHover }} !important; border-color: {{ $__addcartBgHover }} !important; }
        @endif
        @if($__addcartText)
        .add-btn { color: {{ $__addcartText }} !important; }
        @endif
        @if($__addcartTextHover)
        .add-btn:hover, .add-btn.added { color: {{ $__addcartTextHover }} !important; }
        @endif

        /* ── Buy Now ── */
        @if($__buynowBg)
        .buy-btn, .btn-honey { background: {{ $__buynowBg }} !important; }
        @endif
        @if($__buynowBgHover)
        .buy-btn:hover, .btn-honey:hover { background: {{ $__buynowBgHover }} !important; }
        @endif
        @if($__buynowText)
        .buy-btn, .btn-honey { color: {{ $__buynowText }} !important; }
        @endif
        @if($__buynowTextHover)
        .buy-btn:hover, .btn-honey:hover { color: {{ $__buynowTextHover }} !important; }
        @endif

        /* ── Slider / CTA ── */
        @if($__sliderBg)
        .hero .btn, .slider .btn, .heroA .btn, .heroB .btn { background: {{ $__sliderBg }} !important; }
        @endif
        @if($__sliderBgHover)
        .hero .btn:hover, .slider .btn:hover, .heroA .btn:hover, .heroB .btn:hover { background: {{ $__sliderBgHover }} !important; }
        @endif
        @if($__sliderText)
        .hero .btn, .slider .btn, .heroA .btn, .heroB .btn { color: {{ $__sliderText }} !important; }
        @endif
        @if($__sliderTextHover)
        .hero .btn:hover, .slider .btn:hover, .heroA .btn:hover, .heroB .btn:hover { color: {{ $__sliderTextHover }} !important; }
        @endif

        /* ── Links ── */
        @if($__linkColor)
        .see-all, .crumbs a, .pcard-title:hover { color: {{ $__linkColor }} !important; }
        @endif
        @if($__linkHover)
        .see-all:hover, .crumbs a:hover { color: {{ $__linkHover }} !important; }
        @endif

        /* ── Footer ── */
        @if($__footerBg)
        .ftr, .ftr-light { background: {{ $__footerBg }} !important; }
        .ftr-light h4, .ftr h4 { color: #ffffff !important; }
        .ftr-light .brand-name, .ftr .brand-name { color: #ffffff !important; }
        .ftr-light .brand-name b, .ftr .brand-name b { color: var(--green, #56A71C) !important; }
        .ftr-light .ftr-brand p, .ftr .ftr-brand p { color: rgba(255,255,255,0.72) !important; }
        .ftr-light ul a, .ftr ul a, .ftr-brand a, .ftr-brand span { color: {{ $__footerTextColor ?: 'rgba(255,255,255,0.80)' }} !important; }
        .ftr-light ul a:hover, .ftr ul a:hover, .ftr-brand a:hover { color: {{ $__footerLinkHover ?: 'var(--honey, #FA8B01)' }} !important; }
        .ftr-light .ftr-bottom, .ftr .ftr-bottom { border-top: 1px solid rgba(255,255,255,0.12) !important; color: rgba(255,255,255,0.6) !important; }
        .ftr-light .app-badge, .ftr .app-badge { background: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.15) !important; color: #ffffff !important; }
        .ftr-light .app-badge:hover, .ftr .app-badge:hover { background: rgba(255,255,255,0.16) !important; }
        .ftr-light .app-badge svg, .ftr .app-badge svg { color: var(--green, #56A71C) !important; }
        .ftr-light .pay-chip, .ftr .pay-chip { background: rgba(255,255,255,0.1) !important; border-color: rgba(255,255,255,0.15) !important; color: #ffffff !important; }
        .ftr-social a { background: rgba(255,255,255,0.1) !important; color: #ffffff !important; }
        .ftr-social a:hover { background: var(--green, #56A71C) !important; color: #ffffff !important; }
        @endif
        @if($__footerTextColor)
        .ftr, .ftr a, .ftr-col h4 { color: {{ $__footerTextColor }} !important; }
        @endif
        @if($__footerLinkHover)
        .ftr a:hover, .ftr-social a:hover { color: {{ $__footerLinkHover }} !important; }
        @endif

        /* ── Mobile Header ── */
        @if($__mobSearchBg)
        .mh-search, .mh-search-form { background: {{ $__mobSearchBg }} !important; }
        @endif
        @if($__mobSearchText)
        .mh-search input, .mh-search-form input, .mh-search svg, .mh-search-form svg { color: {{ $__mobSearchText }} !important; }
        .mh-search input::placeholder, .mh-search-form input::placeholder { color: {{ $__mobSearchText }}88 !important; }
        @endif
        @if($__mobCartBg)
        .mh-cart, .mh-cart-btn { background: {{ $__mobCartBg }} !important; }
        @endif
        @if($__mobCartText)
        .mh-cart, .mh-cart-btn { color: {{ $__mobCartText }} !important; }
        @endif
        @if($__mobTabText)
        .mh-tab, .mh-cat-pill { color: {{ $__mobTabText }} !important; }
        @endif
        @if($__mobTabActive)
        .mh-tab-active, .mh-tab:active, .mh-cat-pill.on { color: #fff !important; background: {{ $__mobTabActive }} !important; border-color: {{ $__mobTabActive }} !important; }
        .mh-tab-active { border-bottom-color: {{ $__mobTabActive }} !important; }
        @endif

        /* ── Mobile Bottom Nav ── */
        @if($__mobnavBg)
        .mob-nav { background: {{ $__mobnavBg }} !important; }
        @endif
        @if($__mobnavText)
        .mob-nav a, .mob-nav button { color: {{ $__mobnavText }} !important; }
        @endif
        @if($__mobnavActive)
        .mob-nav .mn-active { color: {{ $__mobnavActive }} !important; }
        @endif

        /* ── Typography ── */
        @if($__baseFontSize && $__baseFontSize !== '15')
        body { font-size: {{ $__baseFontSize }}px; }
        @endif
        @if($__headingScale && $__headingScale !== '1')
        h1 { font-size: calc(36px * {{ $__headingScale }}); }
        h2 { font-size: calc(28px * {{ $__headingScale }}); }
        h3 { font-size: calc(22px * {{ $__headingScale }}); }
        h4 { font-size: calc(18px * {{ $__headingScale }}); }
        @endif
    </style>

    @if($__fbPixel = \App\Models\Setting::get('fb_pixel_id'))
    <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{{ $__fbPixel }}');fbq('track','PageView');</script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $__fbPixel }}&ev=PageView&noscript=1"/></noscript>
    @endif

    @if($__ttPixel = \App\Models\Setting::get('tiktok_pixel_id'))
    <script>!function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=r+"?sdkid="+e+"&lib="+t;var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(a,s)};ttq.load('{{ $__ttPixel }}');ttq.page()}(window,document,'ttq');</script>
    @endif

    @if($__gaId = \App\Models\Setting::get('ga_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $__gaId }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $__gaId }}');</script>
    @endif

    @if($__gtmId = \App\Models\Setting::get('gtm_id'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $__gtmId }}');</script>
    @endif

    @if($__customHead = \App\Models\Setting::get('custom_head_code'))
    {!! $__customHead !!}
    @endif
</head>
<body x-data>
    <a href="#main-content" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;" onfocus="this.style.cssText='position:absolute;left:8px;top:8px;z-index:9999;background:#fff;color:#1E2A22;padding:8px 16px;border-radius:6px;font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,.2);'" onblur="this.style.cssText='position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;'">Skip to content</a>

    {{-- Announcement Bar --}}
    @php $__announcements = \Illuminate\Support\Facades\Cache::remember('layout.announcements', 1800, fn() => \App\Models\Announcement::active()->get()); @endphp
    @foreach($__announcements as $ann)
    <div x-data="{ show: true }" x-show="show" class="text-center text-sm py-2 px-4 relative" style="background:{{ $ann->bg_color }};color:{{ $ann->text_color }}">
        <span>{{ $ann->text }}</span>
        @if($ann->link)
            <a href="{{ $ann->link }}" class="underline font-semibold ml-1" style="color:{{ $ann->text_color }}">{{ $ann->link_text ?: 'Learn More' }}</a>
        @endif
        @if($ann->is_dismissible)
        <button @click="show=false" class="absolute right-3 top-1/2 -translate-y-1/2 opacity-70 hover:opacity-100" style="color:{{ $ann->text_color }}">&times;</button>
        @endif
    </div>
    @endforeach

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
    {{-- Popup --}}
    @php $__popup = (\Illuminate\Support\Facades\Cache::remember('layout.popup', 1800, fn() => \App\Models\Popup::active()->first() ?: false)) ?: null; @endphp
    @if($__popup)
    <style>
      .shuvo-popup-bg { position:fixed;top:0;left:0;width:100%;height:100%;z-index:9999;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.55);padding:20px; }
      .shuvo-popup-bg.is-open { display:flex; }
      .shuvo-popup { background:#fff;border-radius:14px;box-shadow:0 25px 80px rgba(0,0,0,.3);max-width:580px;width:100%;position:relative;overflow:hidden; }
      .shuvo-popup-close { position:absolute;top:10px;right:14px;background:none;border:none;font-size:28px;color:#999;cursor:pointer;z-index:10;line-height:1;width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:50%;transition:all .15s; }
      .shuvo-popup-close:hover { color:#333;background:rgba(0,0,0,.08); }
    </style>
    <div x-data="{ open: false }"
         x-init="@if($__popup->trigger === 'on_load')
            setTimeout(() => { if(!sessionStorage.getItem('popup_{{ $__popup->id }}_closed')) open = true }, {{ ($__popup->delay_seconds ?: 0) * 1000 }})
         @elseif($__popup->trigger === 'after_delay')
            setTimeout(() => { if(!sessionStorage.getItem('popup_{{ $__popup->id }}_closed')) open = true }, {{ ($__popup->delay_seconds ?: 5) * 1000 }})
         @elseif($__popup->trigger === 'on_exit')
            document.addEventListener('mouseout', function(e) { if(e.clientY < 0 && !sessionStorage.getItem('popup_{{ $__popup->id }}_closed')) open = true }, { once: true })
         @endif"
         class="shuvo-popup-bg"
         :class="open ? 'is-open' : ''"
         @click.self="open=false; sessionStorage.setItem('popup_{{ $__popup->id }}_closed','1')">
        <div class="shuvo-popup" @click.stop>
            <button class="shuvo-popup-close" @click="open=false; sessionStorage.setItem('popup_{{ $__popup->id }}_closed','1')">&times;</button>
            @if($__popup->image)
            <img src="{{ asset('storage/' . $__popup->image) }}" alt="{{ $__popup->title }}" style="width:100%;max-height:300px;object-fit:cover;display:block;">
            @endif
            <div style="padding:28px 32px;">
                <h3 style="font-size:22px;font-weight:700;margin:0 0 10px;color:var(--ink);">{{ $__popup->title }}</h3>
                @if($__popup->content)
                <div style="color:var(--ink-soft);font-size:14px;margin-bottom:16px;line-height:1.6;">{!! $__popup->content !!}</div>
                @endif
                @if($__popup->button_text)
                <a href="{{ $__popup->button_url ?: '#' }}" style="display:inline-block;background:var(--green);color:#fff;padding:10px 24px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;">{{ $__popup->button_text }}</a>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Mobile bottom navigation --}}
    @php
        $__mnHome = \App\Models\Setting::get('mobnav_home', '1');
        $__mnCats = \App\Models\Setting::get('mobnav_categories', '0');
        $__mnShop = \App\Models\Setting::get('mobnav_shop', '1');
        $__mnCart = \App\Models\Setting::get('mobnav_cart', '1');
        $__mnWish = \App\Models\Setting::get('mobnav_wishlist', '1');
        $__mnAcct = \App\Models\Setting::get('mobnav_account', '1');

        $__mnCatItems = collect();
        if ($__mnCats) {
            $__mnCatItems = \App\Models\Category::active()
                ->whereNull('parent_id')
                ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('sort')])
                ->orderBy('sort')
                ->get();
        }
    @endphp
    <nav class="mob-nav" x-data aria-label="Mobile navigation">
        @if($__mnHome)
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'mn-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Home</span>
        </a>
        @endif
        @if($__mnCats)
        <div class="mn-cats-wrap" x-data="{ open: false }" @keydown.escape.window="open = false">
            <button type="button" @click="open = !open" :class="open ? 'mn-active' : ''">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h7v7H3z"/><path d="M14 3h7v7h-7z"/><path d="M14 14h7v7h-7z"/><path d="M3 14h7v7H3z"/></svg>
                <span>Categories</span>
            </button>

            {{-- Backdrop --}}
            <div class="mn-cats-backdrop" x-show="open" x-cloak @click="open = false"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            {{-- Left slide drawer --}}
            <div class="mn-cats-drawer" x-show="open" x-cloak
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">

                <div class="mnc-head">
                    <span class="mnc-title">All Categories</span>
                    <button type="button" class="mnc-close" @click="open = false" aria-label="Close">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <div class="mnc-list">
                    @foreach($__mnCatItems as $cat)
                    <a href="{{ route('category', $cat->slug) }}" class="mnc-item">
                        <span class="mnc-icon" style="background: {{ $cat->tint ?? '#356B3E' }}18;">
                            @if($cat->image)
                            <img src="{{ asset('storage/' . $cat->image) }}" alt="">
                            @else
                            <span style="font-size: 18px;">{{ mb_substr($cat->name, 0, 1) }}</span>
                            @endif
                        </span>
                        <span class="mnc-label">{{ $cat->name }}</span>
                        @if($cat->children->count())
                        <span class="mnc-count">{{ $cat->children->count() }}</span>
                        @endif
                        <svg class="mnc-chevron" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </a>
                    @endforeach
                </div>

                <div class="mnc-foot">
                    <a href="{{ route('shop') }}" class="mnc-all">
                        View All Products
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
        @endif
        @if($__mnShop)
        <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') || request()->routeIs('category') ? 'mn-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            <span>Shop</span>
        </a>
        @endif
        @if($__mnCart)
        <button type="button" @click="$store.shop.toggle()">
            <span class="mn-cart-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h15l-1.5 9h-12L5 3H2"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                <span class="mn-badge" x-show="$store.shop.count > 0" x-text="$store.shop.count" x-cloak></span>
            </span>
            <span>Cart</span>
        </button>
        @endif
        @if($__mnWish)
        <a href="{{ route('wishlist') }}" class="{{ request()->routeIs('wishlist') ? 'mn-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7-4.5-9.5-9A4.7 4.7 0 0112 6a4.7 4.7 0 019.5 5c-2.5 4.5-9.5 9-9.5 9z"/></svg>
            <span>Wishlist</span>
        </a>
        @endif
        @if($__mnAcct)
        @auth
        <a href="{{ route('account') }}" class="{{ request()->routeIs('account') ? 'mn-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12a4 4 0 100-8 4 4 0 000 8z"/><path d="M5 20a7 7 0 0114 0"/></svg>
            <span>Account</span>
        </a>
        @else
        <a href="{{ route('login') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12a4 4 0 100-8 4 4 0 000 8z"/><path d="M5 20a7 7 0 0114 0"/></svg>
            <span>Sign in</span>
        </a>
        @endauth
        @endif
    </nav>

    @stack('scripts')
</body>
</html>
