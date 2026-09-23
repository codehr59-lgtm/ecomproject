<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="fi-form-actions sticky bottom-4 z-20 mt-8 flex items-center justify-between gap-4 rounded-xl border border-gray-200/80 bg-white/95 p-4 shadow-xl backdrop-blur-md dark:border-white/10 dark:bg-gray-900/95">
            <div class="hidden text-xs font-medium text-gray-500 dark:text-gray-400 sm:block">
                💡 Tip: Save your changes anytime right here or from the top-right button.
            </div>
            <div class="flex items-center gap-3">
                <x-filament::button type="submit" size="lg" icon="heroicon-m-check">
                    Save Homepage Settings
                </x-filament::button>
            </div>
        </div>
    </form>
</x-filament-panels::page>
