<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-950 dark:text-white">
                    {{ config('app.name', 'Shuvo') }} — Admin Dashboard
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage your products, orders, and store settings
                </p>
            </div>
            <a
                href="{{ url('/') }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition"
            >
                <x-heroicon-o-globe-alt class="h-5 w-5" />
                View Live Site
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
