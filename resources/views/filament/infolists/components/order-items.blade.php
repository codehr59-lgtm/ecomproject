{{-- Custom Order Items Table for Filament Infolist --}}
@php
    $order = $getRecord();
    $items = $order->items()->with('product')->get();
@endphp

<div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-16">Image</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Product</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-20">Qty</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-32">Unit Price</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-32">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-900">
            @forelse($items as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">

                    {{-- Product Image --}}
                    <td class="px-4 py-3">
                        @if($item->product && $item->product->image)
                            <img
                                src="{{ Storage::disk('public')->url($item->product->image) }}"
                                alt="{{ $item->name }}"
                                class="w-14 h-14 rounded-lg object-cover border border-gray-200 dark:border-gray-700 shadow-sm"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                            >
                            <div class="w-14 h-14 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 items-center justify-center hidden">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @else
                            <div class="w-14 h-14 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </td>

                    {{-- Product Name + Variant --}}
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-900 dark:text-white text-sm leading-tight">{{ $item->name }}</p>
                        @if($item->weight)
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-md text-xs font-medium bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-200 dark:border-primary-800">
                                {{ $item->weight }}
                            </span>
                        @endif
                        @if($item->combo_id)
                            <span class="inline-flex items-center mt-1 ml-1 px-2 py-0.5 rounded-md text-xs font-medium bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800">
                                Combo Pack
                            </span>
                        @endif
                    </td>

                    {{-- Qty --}}
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold text-sm">
                            {{ $item->qty }}
                        </span>
                    </td>

                    {{-- Unit Price --}}
                    <td class="px-4 py-3 text-right">
                        <span class="text-gray-700 dark:text-gray-300 font-medium">৳{{ number_format($item->price) }}</span>
                    </td>

                    {{-- Line Total --}}
                    <td class="px-4 py-3 text-right">
                        <span class="text-gray-900 dark:text-white font-bold text-base">৳{{ number_format($item->line_total) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span class="text-sm">No items found</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>

        {{-- Footer totals row --}}
        <tfoot class="bg-gray-50 dark:bg-gray-800 border-t-2 border-gray-200 dark:border-gray-700">
            <tr>
                <td colspan="4" class="px-4 py-3 text-right text-sm font-semibold text-gray-600 dark:text-gray-400">
                    {{ $items->count() }} item{{ $items->count() !== 1 ? 's' : '' }} &nbsp;|&nbsp; Grand Total
                </td>
                <td class="px-4 py-3 text-right font-bold text-lg text-primary-600 dark:text-primary-400">
                    ৳{{ number_format($order->total) }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
