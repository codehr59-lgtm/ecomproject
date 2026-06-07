@extends('layouts.app')
@section('title', 'Sign In — Shuvo')

@section('content')

{{-- ============================================================
     LOGIN — centered auth card
     ============================================================ --}}
<div class="wrap" style="padding-top:48px;padding-bottom:64px">
    <div class="co-card" style="max-width:440px;margin:0 auto">

        {{-- Brand mark + heading --}}
        <div style="text-align:center;margin-bottom:28px">
            <div class="brand-mark" style="width:52px;height:52px;border-radius:16px;margin:0 auto 18px;display:grid;place-items:center;background:linear-gradient(145deg,var(--green) 0%,var(--green-deep) 100%)">
                {{-- Leaf SVG --}}
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2C6 2 3 7 3 12c0 4.4 2.8 8.1 6.7 9.5C11 19.3 12 17.2 12 15c0-3.3-2-6-5-7 2 0 5 1 7 4 2-3 5-4 7-4-3 1-5 3.7-5 7 0 2.2 1 4.3 2.3 6.5C19.2 20.1 22 16.4 22 12c0-5-3-10-10-10z"/>
                </svg>
            </div>
            <h1 style="font-size:clamp(24px,3vw,32px);margin-bottom:8px">Welcome back</h1>
            <p style="color:var(--ink-soft);font-size:15px;margin:0">Sign in to your Shuvo account</p>
        </div>

        {{-- Session status --}}
        @if (session('status'))
            <div style="background:var(--green-tint);color:var(--green-deep);padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:14px;font-weight:600">
                {{ session('status') }}
            </div>
        @endif

        {{-- Login form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="login-email">Email address</label>
                <input id="login-email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
                @error('email')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="login-password">Password</label>
                <input id="login-password" type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                @error('password')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Remember + Forgot row --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;font-size:14px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:var(--ink-soft);font-size:14px;font-weight:500">
                    <input type="checkbox" name="remember" style="width:17px;height:17px;accent-color:var(--green);cursor:pointer">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color:var(--green);font-weight:600">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Sign In
            </button>
        </form>

        {{-- Divider --}}
        <div style="display:flex;align-items:center;gap:12px;margin:22px 0;color:var(--muted);font-size:13px">
            <span style="flex:1;height:1px;background:var(--line)"></span>
            New to Shuvo?
            <span style="flex:1;height:1px;background:var(--line)"></span>
        </div>

        {{-- Register link --}}
        <div style="text-align:center;font-size:15px;color:var(--ink-soft)">
            <a href="{{ route('register') }}" class="btn btn-ghost btn-block">Create an account</a>
        </div>

    </div>
</div>

@endsection
