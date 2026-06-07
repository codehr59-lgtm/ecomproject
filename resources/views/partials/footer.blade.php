<footer class="bg-white border-t border-border-light mt-16">
    <div class="max-w-content mx-auto px-4 py-12">

        {{-- ── 4-column link grid ── --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">

            {{-- Shop column --}}
            <div>
                <h4 class="font-bold text-ink mb-4">Shop</h4>
                <ul aria-label="Shop links" class="space-y-2 text-sm text-text">
                    <li><a href="#" class="hover:text-primary transition-colors">All Products</a></li>
                    <li>
                        <a href="{{ route('category', 'cooking-essentials') }}"
                           class="hover:text-primary transition-colors">Cooking Essentials</a>
                    </li>
                    <li>
                        <a href="{{ route('category', 'honey-nuts') }}"
                           class="hover:text-primary transition-colors">Honey &amp; Nuts</a>
                    </li>
                    <li>
                        <a href="{{ route('category', 'spices') }}"
                           class="hover:text-primary transition-colors">Spices</a>
                    </li>
                </ul>
            </div>

            {{-- Help column --}}
            <div>
                <h4 class="font-bold text-ink mb-4">Help</h4>
                <ul aria-label="Help links" class="space-y-2 text-sm text-text">
                    <li><a href="#" class="hover:text-primary transition-colors">Track Order</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Shipping Info</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Returns</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">FAQ</a></li>
                </ul>
            </div>

            {{-- Company column --}}
            <div>
                <h4 class="font-bold text-ink mb-4">Company</h4>
                <ul aria-label="Company links" class="space-y-2 text-sm text-text">
                    <li><a href="#" class="hover:text-primary transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Contact</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Blog</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Careers</a></li>
                </ul>
            </div>

            {{-- Contact column --}}
            <div>
                <h4 class="font-bold text-ink mb-4">Contact</h4>
                <div aria-label="Contact info" class="space-y-2 text-sm text-text">
                    <p>+880 1XXX-XXXXXX</p>
                    <p>support@ghorerbazar.com</p>
                    <p>Dhaka, Bangladesh</p>
                </div>
            </div>
        </div>

        {{-- ── Bottom strip ── --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pt-8 border-t border-border-light">

            {{-- Left: social icons + app-store badges --}}
            <div class="flex flex-col gap-4">
                {{-- Social circles --}}
                <div class="flex gap-3">
                    <a href="#"
                       aria-label="Facebook"
                       class="w-9 h-9 rounded-full bg-cream hover:bg-primary hover:text-white flex items-center justify-center text-text transition-colors text-sm font-semibold">
                        f
                    </a>
                    <a href="#"
                       aria-label="Instagram"
                       class="w-9 h-9 rounded-full bg-cream hover:bg-primary hover:text-white flex items-center justify-center text-text transition-colors text-sm font-semibold">
                        i
                    </a>
                    <a href="#"
                       aria-label="YouTube"
                       class="w-9 h-9 rounded-full bg-cream hover:bg-primary hover:text-white flex items-center justify-center text-text transition-colors text-sm font-semibold">
                        y
                    </a>
                    <a href="#"
                       aria-label="Twitter / X"
                       class="w-9 h-9 rounded-full bg-cream hover:bg-primary hover:text-white flex items-center justify-center text-text transition-colors text-sm font-semibold">
                        x
                    </a>
                </div>

                {{-- App-store badges --}}
                <div class="flex gap-3">
                    <a href="#"
                       aria-label="Download on the App Store"
                       class="bg-dark text-white text-xs rounded px-3 py-2 hover:opacity-80 transition-opacity">
                        App Store
                    </a>
                    <a href="#"
                       aria-label="Get it on Google Play"
                       class="bg-dark text-white text-xs rounded px-3 py-2 hover:opacity-80 transition-opacity">
                        Google Play
                    </a>
                </div>
            </div>

            {{-- Right: Pay With strip --}}
            <div class="flex flex-col gap-2">
                <span class="text-xs text-text font-medium">Pay With</span>
                <div class="flex flex-wrap gap-2">
                    <span class="border border-border rounded px-2 py-1 text-xs text-ink">Visa</span>
                    <span class="border border-border rounded px-2 py-1 text-xs text-ink">Mastercard</span>
                    <span class="border border-border rounded px-2 py-1 text-xs text-ink">bKash</span>
                    <span class="border border-border rounded px-2 py-1 text-xs text-ink">Nagad</span>
                    <span class="border border-border rounded px-2 py-1 text-xs text-ink">COD</span>
                </div>
            </div>
        </div>

        {{-- ── Copyright ── --}}
        <p class="text-center text-xs text-text mt-8 pt-6 border-t border-border-light">
            &copy; {{ date('Y') }} Ghorer Bazar. All rights reserved.
        </p>
    </div>
</footer>
