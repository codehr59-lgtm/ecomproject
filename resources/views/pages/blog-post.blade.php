@extends('layouts.app')

@section('title', $post->title . ' — Shuvo Journal')

@if($post->excerpt)
@section('meta_description', $post->excerpt)
@endif

@section('content')

{{-- PAGE HEAD --}}
<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
      <a href="{{ route('blog') }}">Blog</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
      <span>{{ $post->title }}</span>
    </div>
    <h1>{{ $post->title }}</h1>
  </div>
</div>

{{-- ARTICLE --}}
<div class="section">
  <div class="wrap">
    <div style="max-width:760px;margin:0 auto;">

      {{-- Cover image --}}
      @if($post->cover)
      <div style="border-radius:12px;overflow:hidden;aspect-ratio:16/7;margin-bottom:32px;border:1px solid var(--line);">
        <img src="{{ asset('storage/' . $post->cover) }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;display:block;">
      </div>
      @endif

      {{-- Category + meta --}}
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:18px;flex-wrap:wrap;">
        @if($post->category)
        <span class="eyebrow">{{ $post->category }}</span>
        @endif
        <div class="pcard-meta">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
        </div>
      </div>

      {{-- Article body --}}
      <div style="font-size:16.5px;line-height:1.78;color:var(--ink-soft);">
        {!! $post->body !!}
      </div>

      {{-- Back link --}}
      <div style="margin-top:40px;padding-top:28px;border-top:1px solid var(--line);">
        <a href="{{ route('blog') }}" class="btn btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          Back to Blog
        </a>
      </div>

    </div>
  </div>
</div>

@endsection
