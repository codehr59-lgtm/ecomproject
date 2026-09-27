@props(['category'])
@php
  $catImg = !empty($category['image'])
    ? (\Illuminate\Support\Str::startsWith($category['image'], ['http://', 'https://']) ? $category['image'] : asset('storage/' . $category['image']))
    : null;
  $tint = $category['tint'] ?? '#3b7d56';
@endphp
<a class="fcat" href="{{ route('category', $category['id']) }}">
  <div class="fcat-img" style="--ph-bg: {{ $tint }}33;">
    @if($catImg)
      <img src="{{ $catImg }}" alt="{{ $category['name'] }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">
    @else
      <div class="ph-jar" style="background: {{ $tint }}44;"></div>
    @endif
  </div>
  <span class="fcat-name">{{ $category['name'] }}</span>
</a>
