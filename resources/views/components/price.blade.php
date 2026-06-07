@props(['price', 'old' => null])
<span class="flex items-baseline gap-2">
    <span class="text-base font-semibold text-primary">৳{{ number_format($price) }}</span>
    @if($old)
        <span class="text-base text-strike line-through">৳{{ number_format($old) }}</span>
    @endif
</span>
