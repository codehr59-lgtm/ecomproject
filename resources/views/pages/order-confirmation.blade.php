@extends('layouts.app')
@section('title', 'Order Confirmed — Shuvo')

@section('content')

{{-- Clear the Alpine cart now that the order is server-side --}}
<script>
    document.addEventListener('alpine:init', function () {
        Alpine.store('shop').items = [];
    });
</script>

{{-- Page Head --}}
<div class="page-head">
    <div class="wrap">
        <div class="crumbs">
            <a href="{{ route('home') }}">Home</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
            <span>Order Confirmation</span>
        </div>
        <h1>Order Confirmed</h1>
    </div>
</div>

<div class="wrap section">
    <div style="max-width:680px;margin:0 auto">

        {{-- Success Hero --}}
        <div class="co-card" style="text-align:center;padding:40px 32px 32px">
            <div class="success-ico" style="margin:0 auto 20px">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12l5 5L20 6"/>
                </svg>
            </div>
            <h2 style="font-family:var(--font-display);font-size:28px;font-weight:800;color:var(--green-deep);margin:0 0 8px">Order placed!</h2>
            <p style="color:var(--muted);margin:0 0 16px">Thank you, {{ $order->customer_name }}. We've received your order.</p>
            <span class="ord-no">{{ $order->number }}</span>
            <p style="font-size:14px;color:var(--muted);margin:12px 0 0">Placed on {{ $order->placed_at->format('d M Y, h:i A') }}</p>
        </div>

        {{-- Items Summary --}}
        <div class="co-card" style="margin-top:16px">
            <h3 style="font-size:17px;margin-bottom:16px;display:flex;align-items:center;gap:9px">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--green)">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
                Order Items
            </h3>
            <div style="border:1px solid var(--line);border-radius:10px;overflow:hidden">
                <table style="width:100%;border-collapse:collapse;font-size:14px">
                    <thead>
                        <tr style="background:var(--surface-2)">
                            <th style="padding:10px 14px;text-align:left;font-weight:700;color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.06em">Item</th>
                            <th style="padding:10px 14px;text-align:center;font-weight:700;color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.06em">Qty</th>
                            <th style="padding:10px 14px;text-align:right;font-weight:700;color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.06em">Price</th>
                            <th style="padding:10px 14px;text-align:right;font-weight:700;color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.06em">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr style="border-top:1px solid var(--line-soft)">
                                <td style="padding:12px 14px">
                                    <div style="font-weight:600;color:var(--ink)">{{ $item->name }}</div>
                                    @if($item->weight)
                                        <div style="font-size:12px;color:var(--muted)">{{ $item->weight }}</div>
                                    @endif
                                </td>
                                <td style="padding:12px 14px;text-align:center;color:var(--muted)">{{ $item->qty }}</td>
                                <td style="padding:12px 14px;text-align:right;color:var(--ink-soft)">৳{{ number_format($item->price) }}</td>
                                <td style="padding:12px 14px;text-align:right;font-weight:700;color:var(--ink)">৳{{ number_format($item->line_total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div style="margin-top:16px;border-top:1px solid var(--line);padding-top:14px">
                <div class="sum-row" style="display:flex;justify-content:space-between;padding:5px 0;font-size:14px;color:var(--ink-soft)">
                    <span>Subtotal</span>
                    <span>৳{{ number_format($order->subtotal) }}</span>
                </div>
                <div class="sum-row" style="display:flex;justify-content:space-between;padding:5px 0;font-size:14px;color:var(--ink-soft)">
                    <span>Delivery</span>
                    <span>{{ $order->delivery === 0 ? 'Free' : '৳'.number_format($order->delivery) }}</span>
                </div>
                @if($order->discount > 0)
                    <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:14px;color:var(--green-deep)">
                        <span>Discount {{ $order->coupon_code ? '(' . $order->coupon_code . ')' : '' }}</span>
                        <span>−৳{{ number_format($order->discount) }}</span>
                    </div>
                @endif
                <div style="display:flex;justify-content:space-between;padding:10px 0 0;font-size:17px;font-weight:800;color:var(--ink);border-top:1px solid var(--line);margin-top:8px">
                    <span>Total</span>
                    <span style="color:var(--green-deep)">৳{{ number_format($order->total) }}</span>
                </div>
            </div>
        </div>

        {{-- Delivery + Payment Info --}}
        <div class="co-card" style="margin-top:16px">
            <h3 style="font-size:17px;margin-bottom:14px;display:flex;align-items:center;gap:9px">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--green)">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                Delivery & Payment
            </h3>
            <div style="display:grid;gap:8px;font-size:14px">
                <div style="display:flex;gap:8px"><span style="color:var(--muted);min-width:130px">Deliver to</span><span style="font-weight:600;color:var(--ink)">{{ $order->customer_name }}</span></div>
                <div style="display:flex;gap:8px"><span style="color:var(--muted);min-width:130px">Address</span><span style="color:var(--ink-soft)">{{ $order->address_line }}, {{ $order->thana ? $order->thana.', ' : '' }}{{ $order->city }}</span></div>
                <div style="display:flex;gap:8px"><span style="color:var(--muted);min-width:130px">Phone</span><span style="color:var(--ink-soft)">{{ $order->customer_phone }}</span></div>
                <div style="display:flex;gap:8px">
                    <span style="color:var(--muted);min-width:130px">Payment</span>
                    <span style="font-weight:700;color:var(--ink)">
                        @switch($order->payment_method)
                            @case('cod') Cash on Delivery @break
                            @case('bkash') bKash @break
                            @case('nagad') Nagad @break
                            @case('rocket') Rocket @break
                            @case('sslcommerz') SSLCommerz @break
                            @default {{ ucfirst($order->payment_method) }}
                        @endswitch
                    </span>
                    <span style="margin-left:6px;padding:2px 8px;border-radius:99px;font-size:11px;font-weight:700;background:{{ $order->payment_status === 'paid' ? 'var(--green-tint)' : '#FEF3C7' }};color:{{ $order->payment_status === 'paid' ? 'var(--green-deep)' : '#92400E' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                @if($order->notes)
                    <div style="display:flex;gap:8px"><span style="color:var(--muted);min-width:130px">Notes</span><span style="color:var(--ink-soft)">{{ $order->notes }}</span></div>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;justify-content:center">
            <a href="{{ route('track', ['number' => $order->number]) }}" class="btn btn-ghost">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Track Order
            </a>
            <a href="{{ route('order.invoice', $order->number) }}" class="btn btn-ghost" target="_blank">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
                Download Invoice
            </a>
            <a href="{{ route('shop') }}" class="btn btn-primary">
                Continue Shopping
            </a>
        </div>

    </div>
</div>

@endsection
