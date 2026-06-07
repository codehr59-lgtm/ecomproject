@props(['rating' => 0, 'reviews' => null])
<span role="img" aria-label="{{ round($rating) }} out of 5 stars" class="inline-flex items-center gap-1 text-primary text-sm">
    @for($i = 1; $i <= 5; $i++)
        <span aria-hidden="true">{{ $i <= round($rating) ? '★' : '☆' }}</span>
    @endfor
    @if($reviews !== null)<span class="text-text text-xs ml-1">({{ $reviews }})</span>@endif
</span>
