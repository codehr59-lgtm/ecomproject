@extends('layouts.app')
@section('title', 'Track Order — Shuvo')

@section('content')

{{-- ============================================================
     PAGE HEAD — breadcrumbs + title
     ============================================================ --}}
<div class="page-head">
    <div class="wrap">
        <div class="crumbs">
            <a href="{{ route('home') }}">Home</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            <a href="{{ route('account') }}">Account</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px">
                <path d="M9 18l6-6-6-6"/>
            </svg>
            <span>Track Order</span>
        </div>
        <h1>Track Your Order</h1>
        <p class="sub">Enter your order number to see real-time status</p>
    </div>
</div>

{{-- ============================================================
     TRACK CONTENT — Alpine-driven input → timeline reveal
     ============================================================ --}}
<div class="wrap section">
    <div x-data="{ tracked: false, orderNo: '' }">

        {{-- Search card --}}
        <div class="co-card" style="max-width:600px;margin:0 auto 24px">

            {{-- Eyebrow --}}
            <div style="margin-bottom:18px">
                <span class="eyebrow">Order Tracker</span>
            </div>

            {{-- Input + button row --}}
            <div style="display:flex;gap:10px;align-items:flex-end">
                <div class="field" style="flex:1;margin-bottom:0">
                    <label for="track-input">Order number</label>
                    <input
                        id="track-input"
                        type="text"
                        x-model="orderNo"
                        placeholder="e.g. SHV-651088"
                        @keydown.enter="if(orderNo.trim()) tracked = true"
                        autocomplete="off">
                </div>
                <button
                    type="button"
                    class="btn btn-primary"
                    style="flex-shrink:0;height:48px;margin-bottom:0"
                    @click="if(orderNo.trim()) tracked = true">
                    <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    Track
                </button>
            </div>

            {{-- Muted hint (before tracking) --}}
            <p x-show="!tracked" style="font-size:13.5px;color:var(--muted);margin:14px 0 0;display:flex;align-items:center;gap:7px">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                </svg>
                Find your order number in your confirmation SMS or email
            </p>
        </div>

        {{-- ============================================================
             TRACKING RESULT — shown after clicking "Track"
             ============================================================ --}}
        <div x-show="tracked" x-cloak>

            {{-- Order summary mini-card --}}
            <div class="co-card" style="max-width:600px;margin:0 auto 20px;padding:18px 22px">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                    <div>
                        <div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:4px">Order Number</div>
                        <div style="font-family:var(--font-display);font-weight:800;font-size:20px;color:var(--ink)" x-text="orderNo || 'SHV-651088'"></div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:4px">Estimated Delivery</div>
                        <div style="font-weight:700;font-size:15px;color:var(--green-deep)">Within 2–3 business days</div>
                    </div>
                </div>

                {{-- Order meta row --}}
                <div style="display:flex;gap:24px;flex-wrap:wrap;margin-top:16px;padding-top:16px;border-top:1px solid var(--line-soft);font-size:13.5px;color:var(--ink-soft)">
                    <span><b style="color:var(--ink)">Placed:</b> 7 Jun 2026</span>
                    <span><b style="color:var(--ink)">Items:</b> 5</span>
                    <span><b style="color:var(--ink)">Total:</b> ৳3,290</span>
                    <span><b style="color:var(--ink)">Carrier:</b> Steadfast Courier</span>
                </div>
            </div>

            {{-- ── Vertical stepper ─────────────────────────────── --}}
            <div class="co-card" style="max-width:600px;margin:0 auto">
                <h3 style="font-size:17px;margin-bottom:22px;display:flex;align-items:center;gap:9px">
                    <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--green)">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    Shipment Timeline
                </h3>

                <div style="position:relative;padding-left:36px">

                    {{-- Vertical line --}}
                    <div style="position:absolute;left:11px;top:8px;bottom:8px;width:2px;background:var(--line);border-radius:2px"></div>

                    {{-- Step 1 — Order Placed ✓ --}}
                    <div style="position:relative;padding-bottom:28px">
                        <div style="position:absolute;left:-26px;width:24px;height:24px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;border:2px solid var(--surface)">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12l5 5L20 6"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:15px;color:var(--green-deep)">Order Placed</div>
                            <div style="font-size:13px;color:var(--muted);margin-top:2px">7 Jun 2026 — 09:14 AM</div>
                            <div style="font-size:13.5px;color:var(--ink-soft);margin-top:4px">Your order was received and confirmed.</div>
                        </div>
                    </div>

                    {{-- Step 2 — Confirmed ✓ --}}
                    <div style="position:relative;padding-bottom:28px">
                        <div style="position:absolute;left:-26px;width:24px;height:24px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;border:2px solid var(--surface)">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12l5 5L20 6"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:15px;color:var(--green-deep)">Confirmed</div>
                            <div style="font-size:13px;color:var(--muted);margin-top:2px">7 Jun 2026 — 10:30 AM</div>
                            <div style="font-size:13.5px;color:var(--ink-soft);margin-top:4px">Payment verified and items packed.</div>
                        </div>
                    </div>

                    {{-- Step 3 — Shipped (CURRENT) --}}
                    <div style="position:relative;padding-bottom:28px">
                        <div style="position:absolute;left:-27px;width:26px;height:26px;border-radius:50%;background:var(--surface);border:2.5px solid var(--green);display:grid;place-items:center">
                            <div style="width:10px;height:10px;border-radius:50%;background:var(--green);animation:pulse 1.4s ease-in-out infinite"></div>
                        </div>
                        <div>
                            <div style="display:flex;align-items:center;gap:8px">
                                <span style="font-weight:700;font-size:15px;color:var(--ink)">Shipped</span>
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:11.5px;font-weight:700;padding:3px 9px;border-radius:999px;background:#E0EEFF;color:#1A5AB8">Current</span>
                            </div>
                            <div style="font-size:13px;color:var(--muted);margin-top:2px">8 Jun 2026 — 08:00 AM</div>
                            <div style="font-size:13.5px;color:var(--ink-soft);margin-top:4px">Your parcel is with Steadfast Courier.</div>
                        </div>
                    </div>

                    {{-- Step 4 — Out for Delivery (pending) --}}
                    <div style="position:relative;padding-bottom:28px">
                        <div style="position:absolute;left:-26px;width:24px;height:24px;border-radius:50%;background:var(--surface);border:2px solid var(--line);display:grid;place-items:center">
                            <div style="width:8px;height:8px;border-radius:50%;background:var(--line)"></div>
                        </div>
                        <div style="opacity:.55">
                            <div style="font-weight:700;font-size:15px;color:var(--ink)">Out for Delivery</div>
                            <div style="font-size:13px;color:var(--muted);margin-top:2px">Expected: 9 Jun 2026</div>
                            <div style="font-size:13.5px;color:var(--ink-soft);margin-top:4px">Delivery agent will call before arrival.</div>
                        </div>
                    </div>

                    {{-- Step 5 — Delivered (pending) --}}
                    <div style="position:relative">
                        <div style="position:absolute;left:-26px;width:24px;height:24px;border-radius:50%;background:var(--surface);border:2px solid var(--line);display:grid;place-items:center">
                            <div style="width:8px;height:8px;border-radius:50%;background:var(--line)"></div>
                        </div>
                        <div style="opacity:.45">
                            <div style="font-weight:700;font-size:15px;color:var(--ink)">Delivered</div>
                            <div style="font-size:13px;color:var(--muted);margin-top:2px">Expected: 9–10 Jun 2026</div>
                            <div style="font-size:13.5px;color:var(--ink-soft);margin-top:4px">Package at your doorstep.</div>
                        </div>
                    </div>

                </div>

                {{-- Need help link --}}
                <div style="margin-top:22px;padding-top:18px;border-top:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;font-size:14px;color:var(--muted)">
                    <span>Need help with this order?</span>
                    <a href="{{ route('contact') }}" class="btn btn-ghost" style="padding:9px 18px;font-size:13.5px">Contact Support</a>
                </div>

            </div>
            {{-- /.co-card stepper --}}

            {{-- Track another --}}
            <div style="max-width:600px;margin:16px auto 0;text-align:center">
                <button
                    type="button"
                    style="font-size:14px;font-weight:600;color:var(--green);background:none;border:none;cursor:pointer;text-decoration:underline"
                    @click="tracked = false; orderNo = ''">
                    Track another order
                </button>
            </div>

        </div>
        {{-- /.x-show="tracked" --}}

    </div>
</div>

{{-- Pulse animation for current step indicator --}}
@push('scripts')
<style>
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .5; transform: scale(1.4); }
}
</style>
@endpush

@endsection
