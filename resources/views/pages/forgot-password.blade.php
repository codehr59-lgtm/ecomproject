@extends('layouts.app')
@section('title', 'Forgot Password — Shuvo')

@section('content')

{{-- ============================================================
     FORGOT PASSWORD — centered auth card
     ============================================================ --}}
<div class="wrap" style="padding-top:48px;padding-bottom:64px">
    <div class="co-card" style="max-width:440px;margin:0 auto">

        {{-- Brand mark + heading --}}
        <div style="text-align:center;margin-bottom:28px">
            <div class="brand-mark" style="width:52px;height:52px;border-radius:16px;margin:0 auto 18px;display:grid;place-items:center;background:linear-gradient(145deg,var(--green) 0%,var(--green-deep) 100%)">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
            </div>
            <h1 style="font-size:clamp(22px,3vw,28px);margin-bottom:8px">Forgot your password?</h1>
            <p style="color:var(--ink-soft);font-size:15px;margin:0">Enter your email and we'll send you a reset link</p>
        </div>

        {{-- Status message --}}
        @if (session('status'))
            <div style="background:var(--green-tint);color:var(--green-deep);padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:14px;font-weight:600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="field">
                <label for="fp-email">Email address</label>
                <input id="fp-email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
                @error('email')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Send Reset Link
            </button>
        </form>

        <div style="text-align:center;margin-top:20px;font-size:15px;color:var(--ink-soft)">
            <a href="{{ route('login') }}" style="color:var(--green);font-weight:600">Back to sign in</a>
        </div>

    </div>
</div>

@endsection
