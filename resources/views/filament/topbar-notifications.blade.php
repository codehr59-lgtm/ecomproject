<div
    x-data="{
        open: false,
        count: 0,
        orders: [],
        async load() {
            try {
                const r = await fetch('{{ route("admin.notifications") }}');
                const d = await r.json();
                this.count = d.count;
                this.orders = d.orders;
            } catch(e) {}
        }
    }"
    x-init="load(); setInterval(() => load(), 30000)"
    class="relative me-2"
>
    <button
        type="button"
        @click="open = !open"
        class="relative inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-500/5 dark:text-gray-500 dark:hover:text-gray-400 dark:hover:bg-gray-500/10 transition"
        title="Order Notifications"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 01-3.46 0"/>
        </svg>
        <span
            x-show="count > 0"
            x-text="count"
            x-cloak
            class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-danger-600 rounded-full"
        ></span>
    </button>

    <div
        x-show="open"
        @click.outside="open = false"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="right: -60px;"
        class="absolute top-full mt-2 w-[360px] rounded-xl bg-white dark:bg-gray-900 shadow-xl ring-1 ring-gray-950/5 dark:ring-white/10 z-50 overflow-hidden"
    >
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-800">
            <span class="text-sm font-semibold text-gray-950 dark:text-white">Order Notifications</span>
            <span x-show="count > 0" x-cloak class="inline-flex items-center rounded-full bg-danger-50 dark:bg-danger-500/10 px-2 py-0.5 text-xs font-semibold text-danger-600 dark:text-danger-400">
                <span x-text="count"></span> pending
            </span>
        </div>

        <div class="max-h-[380px] overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
            <template x-for="o in orders" :key="o.id">
                <a :href="'/admin/orders/' + o.id" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition">
                    <span
                        class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold"
                        :class="o.status === 'pending' ? 'bg-warning-100 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400' : o.status === 'processing' ? 'bg-info-100 text-info-700 dark:bg-info-500/10 dark:text-info-400' : 'bg-success-100 text-success-700 dark:bg-success-500/10 dark:text-success-400'"
                        x-text="o.name ? o.name.charAt(0).toUpperCase() : '#'"
                    ></span>
                    <span class="flex-1 min-w-0">
                        <span class="flex items-center justify-between gap-2">
                            <span class="text-sm font-semibold text-gray-950 dark:text-white truncate" x-text="o.name || 'Guest'"></span>
                            <span class="text-xs font-bold text-primary-600 dark:text-primary-400 whitespace-nowrap">৳<span x-text="o.total"></span></span>
                        </span>
                        <span class="flex items-center justify-between mt-0.5">
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="'#' + o.number"></span>
                            <span class="text-xs text-gray-400 dark:text-gray-500" x-text="o.time"></span>
                        </span>
                    </span>
                </a>
            </template>
            <div x-show="orders.length === 0" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                No orders yet
            </div>
        </div>

        <a href="/admin/orders" class="block text-center px-4 py-2.5 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:bg-gray-50 dark:hover:bg-white/5 border-t border-gray-100 dark:border-gray-800 transition">
            View All Orders
        </a>
    </div>
</div>
