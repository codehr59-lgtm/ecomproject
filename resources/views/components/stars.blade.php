@props(['rating' => 5, 'reviews' => null])
<span class="stars" role="img" aria-label="{{ round($rating) }} out of 5">
  @for($i = 0; $i < 5; $i++)
    <svg viewBox="0 0 24 24" fill="{{ $i < round($rating) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l2.6 5.6 6 .7-4.5 4.1 1.2 6-5.3-3-5.3 3 1.2-6L3.4 9.3l6-.7Z"/></svg>
  @endfor
</span>
@if($reviews !== null)<span style="font-size:12.5px;color:var(--muted);margin-left:6px">({{ $reviews }})</span>@endif
