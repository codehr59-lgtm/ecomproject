@props(['variant' => 'bar'])
@if($variant === 'bar')
    <h2 class="heading-bar mb-6">{{ $slot }}</h2>
@else
    <h2 class="text-[22px] font-bold text-ink mb-6 inline-block border-b-2 border-primary pb-1">{{ $slot }}</h2>
@endif
