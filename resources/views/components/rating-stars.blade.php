@props(['rating' => 0, 'reviews' => null])
<span class="inline-flex items-center gap-1 text-primary text-sm">
    @for($i = 1; $i <= 5; $i++)
        <span>{{ $i <= round($rating) ? '★' : '☆' }}</span>
    @endfor
    @if($reviews !== null)<span class="text-text text-xs ml-1">({{ $reviews }})</span>@endif
</span>
