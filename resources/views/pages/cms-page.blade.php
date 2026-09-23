@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)

@if($page->meta_description)
@section('meta_description', $page->meta_description)
@endif

@section('content')

<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
      <span>{{ $page->title }}</span>
    </div>
    <h1>{{ $page->title }}</h1>
  </div>
</div>

<div class="section">
  <div class="wrap">
    <div style="max-width:760px;margin:0 auto;font-size:16px;line-height:1.78;color:var(--ink-soft);">
      {!! $page->body !!}
    </div>
  </div>
</div>

@endsection
