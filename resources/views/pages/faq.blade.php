@extends('layouts.app')

@section('title', 'Frequently Asked Questions — Shuvo')

@section('content')

<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
      <span>FAQ</span>
    </div>
    <h1>Frequently Asked Questions</h1>
  </div>
</div>

<div class="section">
  <div class="wrap">
    <div style="max-width:760px;margin:0 auto;">
      @forelse($faqs as $category => $items)
        @if($category)
          <h2 style="font-size:20px;font-weight:700;margin:32px 0 16px;color:var(--green);">{{ $category }}</h2>
        @endif

        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px;">
          @foreach($items as $faq)
          <details style="border:1px solid var(--line);border-radius:10px;overflow:hidden;background:#fff;">
            <summary style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;padding:16px 20px;font-weight:600;font-size:15px;color:var(--ink);list-style:none;">
              <span style="flex:1;">{{ $faq->question }}</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;color:var(--muted);flex-shrink:0;margin-left:12px;transition:transform .2s;">
                <path d="M19 9l-7 7-7-7"/>
              </svg>
            </summary>
            <div style="padding:0 20px 16px;font-size:14px;color:var(--ink-soft);line-height:1.7;">
              {!! $faq->answer !!}
            </div>
          </details>
          @endforeach
        </div>
      @empty
        <div style="text-align:center;padding:60px 0;color:var(--ink-soft);">
          <p>No FAQs available yet.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

@endsection
