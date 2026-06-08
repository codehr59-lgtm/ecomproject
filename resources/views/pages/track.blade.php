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
     TRACK CONTENT
     ============================================================ --}}
<div class="wrap section">

    {{-- Search form (always visible) --}}
    <div class="co-card" style="max-width:600px;margin:0 auto 24px">
        <div style="margin-bottom:18px">
            <span class="eyebrow">Order Tracker</span>
        </div>
        <form method="GET" action="{{ route('track') }}">
            <div style="display:flex;gap:10px;align-items:flex-end">
                <div class="field" style="flex:1;margin-bottom:0">
                    <label for="track-input">Order number</label>
                    <input
                        id="track-input"
                        type="text"
                        name="number"
                        value="{{ $number ?? '' }}"
                        placeholder="e.g. SHV-651088"
                        autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary" style="flex-shrink:0;height:48px;margin-bottom:0">
                    <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    Track
                </button>
            </div>
            @if(!$number)
                <p style="font-size:13.5px;color:var(--muted);margin:14px 0 0;display:flex;align-items:center;gap:7px">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                    </svg>
                    Find your order number in your confirmation email or page
                </p>
            @endif
        </form>
    </div>

    {{-- Result section --}}
    @if($number && !$order)
        <div class="co-card" style="max-width:600px;margin:0 auto;text-align:center;padding:32px 24px">
            <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin:0 auto 12px;display:block;color:var(--sale)">
                <circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6"/><path d="M9 9l6 6"/>
            </svg>
            <p style="font-size:16px;font-weight:700;color:var(--ink);margin:0 0 6px">Order not found</p>
            <p style="font-size:14px;color:var(--muted)">No order found for <strong>{{ $number }}</strong>. Please check the order number and try again.</p>
        </div>
    @endif

    @if($order)
        @php
            $steps = \App\Models\Order::statusSteps();
            $currentIdx = array_search($order->status, $steps);
            $isCancelled = $order->status === 'cancelled';

            $stepDescriptions = [
                'pending'    => 'Your order was received and is awaiting confirmation.',
                'confirmed'  => 'We have confirmed your order and are preparing it.',
                'processing' => 'Your items are being packed and prepared for dispatch.',
                'shipped'    => 'Your parcel is on its way with the courier.',
                'delivered'  => 'Your package has been delivered. Enjoy!',
            ];
        @endphp

        {{-- Order summary card --}}
        <div class="co-card" style="max-width:600px;margin:0 auto 20px;padding:18px 22px">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                <div>
                    <div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:4px">Order Number</div>
                    <div style="font-family:var(--font-display);font-weight:800;font-size:20px;color:var(--ink)">{{ $order->number }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:4px">Status</div>
                    @if($isCancelled)
                        <span style="display:inline-block;padding:4px 12px;border-radius:99px;font-size:12px;font-weight:700;background:#FEE2E2;color:#991B1B">Cancelled</span>
                    @else
                        <span style="display:inline-block;padding:4px 12px;border-radius:99px;font-size:12px;font-weight:700;background:var(--green-tint);color:var(--green-deep)">{{ ucfirst($order->status) }}</span>
                    @endif
                </div>
            </div>
            <div style="display:flex;gap:24px;flex-wrap:wrap;margin-top:16px;padding-top:16px;border-top:1px solid var(--line-soft);font-size:13.5px;color:var(--ink-soft)">
                <span><b style="color:var(--ink)">Placed:</b> {{ $order->placed_at->format('d M Y, h:i A') }}</span>
                <span><b style="color:var(--ink)">Items:</b> {{ $order->items->count() }}</span>
                <span><b style="color:var(--ink)">Total:</b> ৳{{ number_format($order->total) }}</span>
                @if($order->courier)
                    <span><b style="color:var(--ink)">Carrier:</b> {{ $order->courier }}</span>
                @endif
            </div>
        </div>

        @if(!$isCancelled)
            {{-- Status timeline --}}
            <div class="co-card" style="max-width:600px;margin:0 auto">
                <h3 style="font-size:17px;margin-bottom:22px;display:flex;align-items:center;gap:9px">
                    <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--green)">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Shipment Timeline
                </h3>

                <div style="position:relative;padding-left:36px">
                    <div style="position:absolute;left:11px;top:8px;bottom:8px;width:2px;background:var(--line);border-radius:2px"></div>

                    @foreach($steps as $idx => $step)
                        @php
                            $done    = $currentIdx !== false && $idx < $currentIdx;
                            $current = $currentIdx !== false && $idx === $currentIdx;
                            $future  = !$done && !$current;
                            $isLast  = $idx === count($steps) - 1;
                        @endphp
                        <div style="position:relative;{{ !$isLast ? 'padding-bottom:28px' : '' }}">

                            {{-- Node --}}
                            @if($done)
                                <div style="position:absolute;left:-26px;width:24px;height:24px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;border:2px solid var(--surface)">
                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 6"/></svg>
                                </div>
                            @elseif($current)
                                <div style="position:absolute;left:-27px;width:26px;height:26px;border-radius:50%;background:var(--surface);border:2.5px solid var(--green);display:grid;place-items:center">
                                    <div style="width:10px;height:10px;border-radius:50%;background:var(--green);animation:pulse 1.4s ease-in-out infinite"></div>
                                </div>
                            @else
                                <div style="position:absolute;left:-26px;width:24px;height:24px;border-radius:50%;background:var(--surface);border:2px solid var(--line);display:grid;place-items:center">
                                    <div style="width:8px;height:8px;border-radius:50%;background:var(--line)"></div>
                                </div>
                            @endif

                            {{-- Label --}}
                            <div style="{{ $future ? 'opacity:.5' : '' }}">
                                <div style="display:flex;align-items:center;gap:8px">
                                    <span style="font-weight:700;font-size:15px;color:{{ $done ? 'var(--green-deep)' : 'var(--ink)' }}">
                                        {{ \App\Models\Order::statusLabel($step) }}
                                    </span>
                                    @if($current)
                                        <span style="font-size:11.5px;font-weight:700;padding:3px 9px;border-radius:999px;background:#E0EEFF;color:#1A5AB8">Current</span>
                                    @endif
                                </div>
                                <div style="font-size:13.5px;color:var(--ink-soft);margin-top:4px">{{ $stepDescriptions[$step] ?? '' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top:22px;padding-top:18px;border-top:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;font-size:14px;color:var(--muted)">
                    <span>Need help with this order?</span>
                    <a href="{{ route('contact') }}" class="btn btn-ghost" style="padding:9px 18px;font-size:13.5px">Contact Support</a>
                </div>
            </div>
        @else
            <div class="co-card" style="max-width:600px;margin:0 auto;text-align:center;padding:28px 24px">
                <p style="font-size:15px;font-weight:700;color:var(--sale)">This order has been cancelled.</p>
                <p style="font-size:14px;color:var(--muted);margin-top:6px">Questions? <a href="{{ route('contact') }}" style="color:var(--green)">Contact support</a>.</p>
            </div>
        @endif

        <div style="max-width:600px;margin:16px auto 0;text-align:center">
            <a href="{{ route('track') }}" style="font-size:14px;font-weight:600;color:var(--green);text-decoration:underline">Track another order</a>
        </div>
    @endif

</div>

@push('scripts')
<style>
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .5; transform: scale(1.4); }
}
</style>
@endpush

@endsection
