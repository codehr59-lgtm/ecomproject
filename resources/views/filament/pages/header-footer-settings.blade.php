<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        {{-- Dynamic Footer Pages Overview Card --}}
        <div class="mt-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-5 dark:border-white/5">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>📄</span>
                        <span>Footer Dynamic CMS Pages</span>
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        All links shown in your website footer can be edited or created with your own custom text, images, and policies.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ url('/admin/pages') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        All CMS Pages
                    </a>
                    <a href="{{ url('/admin/pages/create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Create Custom Page
                    </a>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="py-3 px-4">Footer Column</th>
                            <th class="py-3 px-4">Page Title</th>
                            <th class="py-3 px-4">URL Slug</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($this->getFooterPages() as $item)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="py-3 px-4 font-medium text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    {{ $item['column'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-gray-900 dark:text-white">
                                {{ $item['title'] }}
                            </td>
                            <td class="py-3 px-4 font-mono text-xs text-gray-500">
                                /{{ $item['slug'] }}
                            </td>
                            <td class="py-3 px-4">
                                @if($item['is_published'])
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-950/50 dark:text-amber-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft / Not Created
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a href="{{ $item['live_url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    View
                                </a>
                                <a href="{{ $item['edit_url'] }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit Content
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bottom Sticky Save Bar --}}
        <div class="fi-form-actions sticky bottom-4 z-20 mt-8 flex items-center justify-between gap-4 rounded-xl border border-gray-200/80 bg-white/95 p-4 shadow-xl backdrop-blur-md dark:border-white/10 dark:bg-gray-900/95">
            <div class="hidden text-xs font-medium text-gray-500 dark:text-gray-400 sm:block">
                💡 Tip: Customizing header, footer, or any CMS page updates your live store immediately.
            </div>
            <div class="flex items-center gap-3">
                <x-filament::button type="submit" size="lg" icon="heroicon-m-check">
                    Save Header & Footer Settings
                </x-filament::button>
            </div>
        </div>
    </form>
</x-filament-panels::page>
