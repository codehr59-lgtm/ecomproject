@extends('layouts.app')
@section('title', 'Page Not Found — Shuvo')
@section('content')
<div style="padding: 96px 0 120px; text-align: center;">
    <div class="wrap" style="display: flex; flex-direction: column; align-items: center; gap: 24px;">

        {{-- Leaf brand-mark --}}
        <div class="brand-mark" style="width: 72px; height: 72px; border-radius: 20px; margin-bottom: 8px;">
            <svg viewBox="0 0 24 24" width="38" height="38" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22C6 22 2 16.6 2 10c0-1.6.3-3.1.9-4.5C5 7 7 9.5 7 12c0-4.2 2.4-7.8 6-9.5 1.1 2 1.7 4.3 1.7 6.7 0 1-.1 1.9-.4 2.8 1-.7 1.7-1.8 1.7-3 1.8 1.5 3 3.8 3 6.3 0 3.7-3.1 6.7-7 6.7z"/>
            </svg>
        </div>

        {{-- Large 404 --}}
        <div style="font-family: var(--font-display); font-size: clamp(96px, 18vw, 160px); font-weight: 800; line-height: 1; color: var(--green-deep); letter-spacing: -.05em; opacity: .18; margin: -16px 0;">
            404
        </div>

        {{-- Heading --}}
        <h1 style="font-family: var(--font-display); font-size: clamp(28px, 4vw, 42px); font-weight: 800; color: var(--ink); letter-spacing: -.02em; margin: 0;">
            Page not found
        </h1>

        {{-- Subtext --}}
        <p style="color: var(--muted); font-size: 16px; max-width: 42ch; margin: 0; line-height: 1.6;">
            The page you're looking for has moved or no longer exists.
        </p>

        {{-- Actions --}}
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; justify-content: center; margin-top: 8px;">
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Back to Home
            </a>
            <a href="{{ route('shop') }}" class="btn btn-ghost btn-lg">
                Browse Shop
            </a>
        </div>

    </div>
</div>
@endsection
