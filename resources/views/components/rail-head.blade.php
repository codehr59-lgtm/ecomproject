@props(['title', 'viewAll' => null])
<div class="rail-head">
  <h2>{{ $title }}</h2>
  @if($viewAll)
    <a class="view-all" href="{{ $viewAll }}">View All Items
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12h14M13 6l6 6-6 6"/>
      </svg>
    </a>
  @endif
</div>
