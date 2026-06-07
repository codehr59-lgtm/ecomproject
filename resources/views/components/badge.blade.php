@props(['type' => 'save', 'label' => ''])
@php
$map = ['save' => 'bg-success', 'new' => 'bg-primary', 'best' => 'bg-sale'];
$bg = $map[$type] ?? 'bg-primary';
@endphp
<span {{ $attributes->merge(['class' => "inline-block text-white text-[11px] font-semibold px-2 py-0.5 rounded-sm {$bg}"]) }}>{{ $label }}</span>
