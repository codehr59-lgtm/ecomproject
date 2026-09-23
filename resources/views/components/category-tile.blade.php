@props(['category'])
<a class="fcat" href="{{ route('category', $category['id']) }}">
  <div class="fcat-img" style="--ph-bg: {{ $category['tint'] }}33;">
    @if(!empty($category['image']))
      <img src="{{ asset('storage/' . $category['image']) }}" alt="{{ $category['name'] }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
    @else
      <div class="ph-jar" style="background: {{ $category['tint'] }}44;"></div>
    @endif
  </div>
  <span class="fcat-name">{{ $category['name'] }}</span>
</a>
