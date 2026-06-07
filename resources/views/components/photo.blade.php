@props(['cat' => 'rice', 'label' => null])
@php
$tint = [
    'honey'   => '#E7B84B',
    'dates'   => '#A9682F',
    'oil-ghee'=> '#D7A53C',
    'spices'  => '#C0432F',
    'nuts'    => '#9C7A4D',
    'rice'    => '#C9B98E',
    'mango'   => '#E59A2B',
    'tea'     => '#6E7F4F',
][$cat] ?? '#C9B98E';
@endphp
<div class="ph" style="--ph-bg: {{ $tint }}33;">
  <div class="ph-inner">
    <div class="ph-jar" style="background: {{ $tint }}44;"></div>
    @if($label)<div class="ph-label">{{ $label }}</div>@endif
  </div>
</div>
