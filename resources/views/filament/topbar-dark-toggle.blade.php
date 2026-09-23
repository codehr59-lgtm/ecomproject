<div
    x-data="{
        dark: (localStorage.getItem('theme') || localStorage.getItem('color-theme') || 'light') === 'dark',
        init() {
            const saved = localStorage.getItem('theme') || localStorage.getItem('color-theme');
            if (saved) {
                this.dark = saved === 'dark';
            } else if (window.Alpine && window.Alpine.store('theme')) {
                this.dark = window.Alpine.store('theme') === 'dark';
            }
            this.apply();
        },
        toggle() {
            this.dark = !this.dark;
            const newTheme = this.dark ? 'dark' : 'light';
            localStorage.setItem('theme', newTheme);
            localStorage.setItem('color-theme', newTheme);

            if (window.Alpine && window.Alpine.store('theme') !== undefined) {
                window.Alpine.store('theme', newTheme);
            }

            window.dispatchEvent(new CustomEvent('theme-changed', { detail: newTheme }));
            this.apply();
        },
        apply() {
            if (this.dark) {
                document.documentElement.classList.add('dark', 'fi-dark');
            } else {
                document.documentElement.classList.remove('dark', 'fi-dark');
            }
        }
    }"
    class="relative me-2"
>
    <button
        type="button"
        @click="toggle()"
        class="inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-500/5 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-500/10 transition"
        :title="dark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
    >
        {{-- Sun icon (shown in dark mode — click to go light) --}}
        <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        {{-- Moon icon (shown in light mode — click to go dark) --}}
        <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
    </button>
</div>
