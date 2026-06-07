<footer class="ftr ftr-light">
    <div class="wrap">

        {{-- ── Top grid: 4 columns ── --}}
        <div class="ftr-top">

            {{-- Col 1: Brand + contact + social + app badges --}}
            <div class="ftr-brand">
                <a href="{{ route('home') }}" class="brand" aria-label="Shuvo — home" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none">
                    <span class="brand-mark">
                        {{-- Leaf icon --}}
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 21c0-7 4-13 14-14 0 9-5 14-14 14z"/>
                            <path d="M5 21c2-5 5-8 9-10"/>
                        </svg>
                    </span>
                    <span class="brand-name">Shuvo<b>.</b></span>
                </a>

                <p>Pure, organic &amp; halal groceries — honey, dates, ghee and more, delivered across Bangladesh.</p>

                {{-- Contact info --}}
                <div style="display:flex;flex-direction:column;gap:8px;font-size:13.5px;margin-bottom:14px">
                    <span style="display:flex;align-items:center;gap:7px">
                        {{-- Pin / location icon --}}
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11z"/>
                            <path d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        </svg>
                        Rampura, Dhaka, Bangladesh
                    </span>
                    <span style="display:flex;align-items:center;gap:7px">
                        {{-- Phone icon --}}
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 4h4l1.5 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 1.5V19a2 2 0 0 1-2 2A16 16 0 0 1 5 4z"/>
                        </svg>
                        09642-XXXXXX
                    </span>
                    <a href="mailto:hello@shuvo.com" style="display:flex;align-items:center;gap:7px">
                        {{-- Mail icon --}}
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 6h18v12H3z"/>
                            <path d="M3 7l9 6 9-6"/>
                        </svg>
                        hello@shuvo.com
                    </a>
                </div>

                {{-- Social links --}}
                <div class="ftr-social">
                    <a href="#" aria-label="Shuvo on Facebook">
                        {{-- Facebook icon --}}
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H8v3h3v6h3v-6h2.5l.5-3H14V9.5c0-.3.2-.5.5-.5z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Shuvo on Instagram">
                        {{-- Instagram icon --}}
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4z"/>
                            <path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/>
                            <path d="M17 7h0"/>
                        </svg>
                    </a>
                    <a href="mailto:hello@shuvo.com" aria-label="Email Shuvo">
                        {{-- Mail icon --}}
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 6h18v12H3z"/>
                            <path d="M3 7l9 6 9-6"/>
                        </svg>
                    </a>
                </div>

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

            {{-- Col 2: Information --}}
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

            {{-- Col 3: Support --}}
            <div>
                <h4>Support</h4>
                <ul aria-label="Support links">
                    <li><a href="#">Support Center</a></li>
                    <li><a href="#">How to Order</a></li>
                    <li><a href="{{ route('track') }}">Order Tracking</a></li>
                    <li><a href="#">Payment</a></li>
                    <li><a href="#">Shipping</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>

            {{-- Col 4: Consumer Policy --}}
            <div>
                <h4>Consumer Policy</h4>
                <ul aria-label="Consumer policy links">
                    <li><a href="#">Happy Return</a></li>
                    <li><a href="#">Refund Policy</a></li>
                    <li><a href="#">Exchange</a></li>
                    <li><a href="#">Cancellation</a></li>
                    <li><a href="#">Pre-Order</a></li>
                    <li><a href="#">Extra Discount</a></li>
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
