<footer class="ftr ftr-light">
    <div class="wrap">

        {{-- ── Data ── --}}
        @php
            $__ftrMenu = \App\Models\Menu::getByLocation('footer');
            $__ftrMenuItems = $__ftrMenu ? $__ftrMenu->rootItems : collect();
        @endphp

        {{-- ── Top grid: 4 columns ── --}}
        <div class="ftr-top">

            {{-- Col 1: Brand + contact + social + app badges --}}
            <div class="ftr-brand">
                @php
                    $__ftrLogo = \App\Models\Setting::get('site_logo');
                    $__ftrName = \App\Models\Setting::get('site_name');
                @endphp
                <a href="{{ route('home') }}" class="brand" aria-label="{{ $__ftrName ?: 'Home' }}" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none">
                    @if($__ftrLogo)
                    <img src="{{ asset('storage/' . $__ftrLogo) }}" alt="{{ $__ftrName ?: 'Logo' }}" style="height:36px;width:auto;object-fit:contain;">
                    @endif
                    @if($__ftrName)
                    <span class="brand-name">{{ $__ftrName }}<b>.</b></span>
                    @endif
                    @if(!$__ftrLogo && !$__ftrName)
                    <span class="brand-name">Shuvo<b>.</b></span>
                    @endif
                </a>

                <p>{{ \App\Models\Setting::get('footer_about', 'Pure, organic & halal groceries — honey, dates, ghee and more, delivered across Bangladesh.') }}</p>

                {{-- Contact info (from Settings) --}}
                @php
                    $__cAddr  = \App\Models\Setting::get('contact_address');
                    $__cPhone = \App\Models\Setting::get('contact_phone');
                    $__cEmail = \App\Models\Setting::get('contact_email');
                    $__sFb    = \App\Models\Setting::get('social_facebook');
                    $__sIg    = \App\Models\Setting::get('social_instagram');
                    $__sYt    = \App\Models\Setting::get('social_youtube');
                    $__sWa    = \App\Models\Setting::get('social_whatsapp');
                @endphp
                @if($__cAddr || $__cPhone || $__cEmail)
                <div style="display:flex;flex-direction:column;gap:8px;font-size:13.5px;margin-bottom:14px">
                    @if($__cAddr)
                    <span style="display:flex;align-items:center;gap:7px">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11z"/>
                            <path d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        </svg>
                        {{ $__cAddr }}
                    </span>
                    @endif
                    @if($__cPhone)
                    <a href="tel:{{ $__cPhone }}" style="display:flex;align-items:center;gap:7px">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 4h4l1.5 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 1.5V19a2 2 0 0 1-2 2A16 16 0 0 1 5 4z"/>
                        </svg>
                        {{ $__cPhone }}
                    </a>
                    @endif
                    @if($__cEmail)
                    <a href="mailto:{{ $__cEmail }}" style="display:flex;align-items:center;gap:7px">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 6h18v12H3z"/>
                            <path d="M3 7l9 6 9-6"/>
                        </svg>
                        {{ $__cEmail }}
                    </a>
                    @endif
                </div>
                @endif

                {{-- Social links (from Settings) --}}
                @if($__sFb || $__sIg || $__sYt || $__sWa || $__cEmail)
                <div class="ftr-social">
                    @if($__sFb)
                    <a href="{{ $__sFb }}" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H8v3h3v6h3v-6h2.5l.5-3H14V9.5c0-.3.2-.5.5-.5z"/>
                        </svg>
                    </a>
                    @endif
                    @if($__sIg)
                    <a href="{{ $__sIg }}" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4z"/>
                            <path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/>
                            <path d="M17 7h0"/>
                        </svg>
                    </a>
                    @endif
                    @if($__sYt)
                    <a href="{{ $__sYt }}" target="_blank" rel="noopener" aria-label="YouTube">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.35 29 29 0 0 0-.46-5.33z"/>
                            <path d="M9.75 15.02l5.75-3.27-5.75-3.27v6.54z"/>
                        </svg>
                    </a>
                    @endif
                    @if($__sWa)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $__sWa) }}" target="_blank" rel="noopener" aria-label="WhatsApp">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            <path d="M12 2a10 10 0 0 0-8.535 15.15L2 22l4.985-1.393A10 10 0 1 0 12 2z"/>
                        </svg>
                    </a>
                    @endif
                    @if($__cEmail)
                    <a href="mailto:{{ $__cEmail }}" aria-label="Email">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 6h18v12H3z"/>
                            <path d="M3 7l9 6 9-6"/>
                        </svg>
                    </a>
                    @endif
                </div>
                @endif

                {{-- App badges --}}
                <div class="app-badges">
                    <a href="#" class="app-badge" aria-label="Get Shuvo on Google Play">
                        {{-- Play / shopping bag icon --}}
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 3l14 9-14 9V3z"/>
                        </svg>
                        <b>Google Play</b>
                    </a>
                    <a href="#" class="app-badge" aria-label="Download Shuvo on the App Store">
                        {{-- Apple-like icon --}}
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 2a2 2 0 0 1 2 2 6 6 0 0 1-6 6 2 2 0 0 1-2-2 6 6 0 0 1 6-6z"/>
                            <path d="M5 11a7 7 0 0 0 13.6 2.4c.3-.8.4-1.6.4-2.4H5z"/>
                        </svg>
                        <b>App Store</b>
                    </a>
                </div>
            </div>

            {{-- Col 2: Categories (from admin footer menu) --}}
            @if($__ftrMenuItems->count())
            <div>
                <h4>Categories</h4>
                <ul aria-label="Category links">
                    @foreach($__ftrMenuItems as $fmi)
                        <li><a href="{{ $fmi->resolvedUrl() }}" @if($fmi->target === '_blank') target="_blank" rel="noopener" @endif>{{ $fmi->label }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Col 3: Information --}}
            <div>
                <h4>Information</h4>
                <ul aria-label="Information links">
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('blog') }}">Blog</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('terms') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>

            {{-- Col 4: Support --}}
            <div>
                <h4>Support</h4>
                <ul aria-label="Support links">
                    <li><a href="{{ route('page.show', 'support-center') }}">Support Center</a></li>
                    <li><a href="{{ route('page.show', 'how-to-order') }}">How to Order</a></li>
                    <li><a href="{{ route('track') }}">Order Tracking</a></li>
                    <li><a href="{{ route('page.show', 'payment') }}">Payment</a></li>
                    <li><a href="{{ route('page.show', 'shipping') }}">Shipping</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                </ul>
            </div>

            {{-- Col 5: Consumer Policy --}}
            <div>
                <h4>Consumer Policy</h4>
                <ul aria-label="Consumer policy links">
                    <li><a href="{{ route('page.show', 'happy-return') }}">Happy Return</a></li>
                    <li><a href="{{ route('page.show', 'refund-policy') }}">Refund Policy</a></li>
                    <li><a href="{{ route('page.show', 'exchange') }}">Exchange</a></li>
                    <li><a href="{{ route('page.show', 'cancellation') }}">Cancellation</a></li>
                    <li><a href="{{ route('page.show', 'pre-order') }}">Pre-Order</a></li>
                    <li><a href="{{ route('page.show', 'extra-discount') }}">Extra Discount</a></li>
                </ul>
            </div>

        </div>{{-- /.ftr-top --}}

        {{-- ── Bottom strip ── --}}
        <div class="ftr-bottom">
            <span>&copy; {{ date('Y') }} Shuvo. Pure, organic &amp; halal — delivered with care.</span>

            <div class="pay-row" aria-label="Accepted payment methods">
                <span class="pay-chip">VISA</span>
                <span class="pay-chip">Mastercard</span>
                <span class="pay-chip">bKash</span>
                <span class="pay-chip">Nagad</span>
                <span class="pay-chip">Rocket</span>
                <span class="pay-chip">DBBL</span>
                <span class="pay-chip">COD</span>
            </div>
        </div>

    </div>
</footer>
