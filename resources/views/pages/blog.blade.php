@extends('layouts.app')
@section('title', 'Blog — Shuvo')

@section('content')

<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
      <span>Blog</span>
    </div>
    <h1>Blog</h1>
  </div>
</div>

<div class="section">
  <div class="wrap">
    @if($posts->count())
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
      @foreach($posts as $post)
        <article style="background:#fff;border-radius:12px;overflow:hidden;border:1px solid var(--line);box-shadow:var(--shadow-s);display:flex;flex-direction:column;">
          @if($post->cover)
          <a href="{{ route('blog.post', $post->slug) }}">
            <img src="{{ asset('storage/' . $post->cover) }}" alt="{{ $post->title }}" style="width:100%;height:200px;object-fit:cover;display:block;">
          </a>
          @endif
          <div style="padding:20px;display:flex;flex-direction:column;flex:1;">
            @if($post->category)
            <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--green);margin-bottom:8px;">{{ $post->category }}</span>
            @endif
            <h3 style="font-size:17px;font-weight:700;margin:0 0 8px;color:var(--ink);line-height:1.3;">
              <a href="{{ route('blog.post', $post->slug) }}" style="color:inherit;text-decoration:none;">{{ $post->title }}</a>
            </h3>
            @if($post->excerpt)
            <p style="font-size:14px;color:var(--ink-soft);line-height:1.6;margin:0 0 14px;flex:1;">{{ $post->excerpt }}</p>
            @endif
            <div style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:6px;margin-top:auto;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
            </div>
          </div>
        </article>
      @endforeach
    </div>
    @else
    <div style="text-align:center;padding:60px 0;color:var(--ink-soft);">
      <p>No blog posts yet.</p>
    </div>
    @endif
  </div>
</div>

@endsection
