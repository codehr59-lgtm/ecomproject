@props(['category'])
<a class="fcat" href="{{ route('category', $category['id']) }}">
  <div class="fcat-img" style="--ph-bg: {{ $category['tint'] }}33;">
    <div class="ph-jar" style="background: {{ $category['tint'] }}44;"></div>
  </div>
  <span class="fcat-name">{{ $category['name'] }}</span>
</a>
