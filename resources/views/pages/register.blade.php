@extends('layouts.app')
@section('title', 'Create Account — Shuvo')

@section('content')

{{-- ============================================================
     REGISTER — centered auth card
     ============================================================ --}}
<div class="wrap" style="padding-top:48px;padding-bottom:64px">
    <div class="co-card" style="max-width:440px;margin:0 auto">

        {{-- Brand mark + heading --}}
        <div style="text-align:center;margin-bottom:28px">
            <div class="brand-mark" style="width:52px;height:52px;border-radius:16px;margin:0 auto 18px;display:grid;place-items:center;background:linear-gradient(145deg,var(--green) 0%,var(--green-deep) 100%)">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2C6 2 3 7 3 12c0 4.4 2.8 8.1 6.7 9.5C11 19.3 12 17.2 12 15c0-3.3-2-6-5-7 2 0 5 1 7 4 2-3 5-4 7-4-3 1-5 3.7-5 7 0 2.2 1 4.3 2.3 6.5C19.2 20.1 22 16.4 22 12c0-5-3-10-10-10z"/>
                </svg>
            </div>
            <h1 style="font-size:clamp(24px,3vw,32px);margin-bottom:8px">Create your account</h1>
            <p style="color:var(--ink-soft);font-size:15px;margin:0">Fresh, organic goodness — one sign-up away</p>
        </div>

        {{-- Register form --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- First / Last name row --}}
            <div class="field-row">
                <div class="field">
                    <label for="reg-first">First name</label>
                    <input id="reg-first" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Rahim" autocomplete="given-name" required>
                    @error('first_name')
                        <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field">
                    <label for="reg-last">Last name</label>
                    <input id="reg-last" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Ahmed" autocomplete="family-name">
                </div>
            </div>

            @error('name')
                <span style="color:var(--sale);font-size:13px;margin-bottom:12px;display:block">{{ $message }}</span>
            @enderror

            <div class="field">
                <label for="reg-email">Email address</label>
                <input id="reg-email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
                @error('email')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="reg-phone">Phone number</label>
                <input id="reg-phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" autocomplete="tel">
                @error('phone')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="reg-password">Password</label>
                <input id="reg-password" type="password" name="password" placeholder="Min. 8 characters" autocomplete="new-password" required>
                @error('password')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="reg-confirm">Confirm password</label>
                <input id="reg-confirm" type="password" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required>
            </div>

            {{-- Terms checkbox --}}
            <div style="margin-bottom:20px">
                <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-size:14px;color:var(--ink-soft);line-height:1.5;font-weight:500">
                    <input type="checkbox" name="terms" style="width:17px;height:17px;margin-top:2px;accent-color:var(--green);cursor:pointer;flex-shrink:0" required>
                    I agree to the <a href="{{ route('terms') }}" style="color:var(--green);font-weight:600">Terms &amp; Conditions</a>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Create Account
            </button>
        </form>

        {{-- Sign-in link --}}
        <div style="text-align:center;margin-top:20px;font-size:15px;color:var(--ink-soft)">
            Already have an account?
            <a href="{{ route('login') }}" style="color:var(--green);font-weight:600">Sign in</a>
        </div>

    </div>
</div>

@endsection
