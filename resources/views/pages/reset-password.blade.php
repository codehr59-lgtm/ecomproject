@extends('layouts.app')
@section('title', 'Reset Password — Shuvo')

@section('content')

{{-- ============================================================
     RESET PASSWORD — centered auth card
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
            <h1 style="font-size:clamp(22px,3vw,28px);margin-bottom:8px">Reset your password</h1>
            <p style="color:var(--ink-soft);font-size:15px;margin:0">Choose a strong new password</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- Hidden fields --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ $request->email ?? old('email') }}">

            <div class="field">
                <label for="rp-email">Email address</label>
                <input id="rp-email" type="email" name="email" value="{{ $request->email ?? old('email') }}" placeholder="you@example.com" autocomplete="email" required>
                @error('email')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="rp-password">New password</label>
                <input id="rp-password" type="password" name="password" placeholder="Min. 8 characters" autocomplete="new-password" required>
                @error('password')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="rp-confirm">Confirm new password</label>
                <input id="rp-confirm" type="password" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Reset Password
            </button>
        </form>

    </div>
</div>

@endsection
