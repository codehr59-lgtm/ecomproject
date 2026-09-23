@extends('layouts.app')
@section('title', 'Track Order' . ($order ? ' #' . $order->number : '') . ' — Shuvo')

@section('content')

{{-- ============================================================
     PAGE HEAD — breadcrumbs + title
     ============================================================ --}}
<div class="page-head">
    <div class="wrap">
        <div class="crumbs">
            <a href="{{ route('home') }}">Home</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
            <a href="{{ route('account') }}">Account</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:13px;height:13px"><path d="M9 18l6-6-6-6"/></svg>
            <span>Track Order</span>
        </div>
        <h1>Track Your Order</h1>
        <p class="sub">Check real-time status, shipment timeline, ordered items &amp; customer details</p>
    </div>
</div>

<div class="wrap section" style="padding-top:28px">

    {{-- ============================================================
         SEARCH BAR CARD
         ============================================================ --}}
    <div class="track-card track-search-card">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px">
            <span class="eyebrow" style="margin:0">Live Tracker</span>
        </div>
        <form method="GET" action="{{ route('track') }}">
            <div class="track-search-row">
                <div class="field" style="flex:1;margin-bottom:0">
                    <label for="track-input" style="font-size:13px;font-weight:700;color:var(--ink-soft);margin-bottom:6px;display:block">Enter Order Number</label>
                    <div style="position:relative">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;display:flex">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        </span>
                        <input
                            id="track-input"
                            type="text"
                            name="number"
                            value="{{ $number ?? '' }}"
                            placeholder="e.g. SHV-B45920"
                            autocomplete="off"
                            style="padding-left:42px;height:50px;font-size:15px;font-weight:600;letter-spacing:.02em">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="height:50px;padding:0 24px;font-size:15px;display:flex;align-items:center;gap:8px;align-self:flex-end">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    <span>Track Order</span>
                </button>
            </div>
            @if(!$number)
                <p style="font-size:13px;color:var(--muted);margin:12px 0 0;display:flex;align-items:center;gap:6px">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                    You can find your order number in your SMS or email confirmation.
                </p>
            @endif
        </form>
    </div>

    {{-- ============================================================
         ORDER NOT FOUND NOTICE
         ============================================================ --}}
    @if($number && !$order)
        <div class="track-card" style="text-align:center;padding:48px 24px;max-width:700px;margin:0 auto">
            <div style="width:64px;height:64px;border-radius:50%;background:#FEE2E2;color:#DC2626;display:grid;place-items:center;margin:0 auto 16px">
                <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6"/><path d="M9 9l6 6"/>
                </svg>
            </div>
            <h3 style="font-family:var(--font-display);font-size:22px;color:var(--ink);margin:0 0 8px">Order Not Found</h3>
            <p style="font-size:15px;color:var(--muted);max-width:440px;margin:0 auto 20px">
                We couldn't find any order matching <strong style="color:var(--ink)">"{{ $number }}"</strong>. Please double check the order number and try again.
            </p>
            <a href="{{ route('track') }}" class="btn btn-ghost" style="padding:10px 22px;font-size:14px">Clear Search</a>
        </div>
    @endif

    {{-- ============================================================
         ORDER DETAILS & TRACKING VIEW
         ============================================================ --}}
    @if($order)
        @php
            $steps = \App\Models\Order::statusSteps();
            $currentIdx = array_search($order->status, $steps);
            $isCancelled = $order->status === 'cancelled';

            $stepMeta = [
                'pending' => [
                    'label' => 'Order Placed',
                    'desc'  => 'We received your order and are awaiting verification.',
                    'icon'  => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/>',
                ],
                'confirmed' => [
                    'label' => 'Order Confirmed',
                    'desc'  => 'Your order is confirmed and scheduled for preparation.',
                    'icon'  => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
                ],
                'processing' => [
                    'label' => 'Processing & Packing',
                    'desc'  => 'Items are carefully packed and ready for handover.',
                    'icon'  => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
                ],
                'shipped' => [
                    'label' => 'Out for Delivery / Shipped',
                    'desc'  => 'Parcel has been handed over to our courier partner.',
                    'icon'  => '<rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
                ],
                'delivered' => [
                    'label' => 'Delivered',
                    'desc'  => 'Your order has been delivered successfully. Enjoy!',
                    'icon'  => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
                ],
            ];

            // Build history map by status
            $historiesByStatus = [];
            if ($order->relationLoaded('statusHistories')) {
                foreach ($order->statusHistories as $h) {
                    if (!isset($historiesByStatus[$h->status])) {
                        $historiesByStatus[$h->status] = $h;
                    }
                }
            }

            // Status color badge settings
            $statusColors = [
                'pending'    => ['bg' => '#FEF3C7', 'text' => '#92400E', 'border' => '#FDE68A', 'label' => 'Pending'],
                'confirmed'  => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'border' => '#BFDBFE', 'label' => 'Confirmed'],
                'processing' => ['bg' => '#EDE9FE', 'text' => '#5B21B6', 'border' => '#DDD6FE', 'label' => 'Processing'],
                'shipped'    => ['bg' => '#FEF9C3', 'text' => '#854D0E', 'border' => '#FEF08A', 'label' => 'Shipped'],
                'delivered'  => ['bg' => '#D1FAE5', 'text' => '#065F46', 'border' => '#A7F3D0', 'label' => 'Delivered'],
                'cancelled'  => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'border' => '#FECACA', 'label' => 'Cancelled'],
            ];
            $currentStatusStyle = $statusColors[$order->status] ?? ['bg' => '#F3F4F6', 'text' => '#374151', 'border' => '#E5E7EB', 'label' => ucfirst($order->status)];
        @endphp

        {{-- ============================================================
             1. TOP OVERVIEW HERO BANNER
             ============================================================ --}}
        <div class="track-card track-hero-card">
            <div class="track-hero-header">
                <div>
                    <span class="track-hero-eyebrow">ORDER REFERENCE</span>
                    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                        <h2 class="track-order-number">{{ $order->number }}</h2>
                        <button type="button" class="track-copy-btn" onclick="copyOrderNumber('{{ $order->number }}')" title="Copy Order Number">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            <span id="copy-text">Copy</span>
                        </button>
                    </div>
                </div>

                <div class="track-status-pill" style="background:{{ $currentStatusStyle['bg'] }};color:{{ $currentStatusStyle['text'] }};border:1px solid {{ $currentStatusStyle['border'] }}">
                    <span class="track-status-dot" style="background:{{ $currentStatusStyle['text'] }}"></span>
                    <span>{{ $currentStatusStyle['label'] }}</span>
                </div>
            </div>

            {{-- Quick Meta Strip --}}
            <div class="track-meta-strip">
                <div class="track-meta-item">
                    <span class="track-meta-label">Placed On</span>
                    <span class="track-meta-val">{{ $order->placed_at ? $order->placed_at->format('d M Y, h:i A') : $order->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="track-meta-item">
                    <span class="track-meta-label">Total Items</span>
                    <span class="track-meta-val">{{ $order->items->sum('qty') }} {{ Str::plural('item', $order->items->sum('qty')) }}</span>
                </div>
                <div class="track-meta-item">
                    <span class="track-meta-label">Total Amount</span>
                    <span class="track-meta-val" style="color:var(--green-deep);font-weight:800">৳{{ number_format($order->total) }}</span>
                </div>
                <div class="track-meta-item">
                    <span class="track-meta-label">Payment</span>
                    <span class="track-meta-val">
                        @if($order->payment_status === 'paid')
                            <span style="color:#059669;font-weight:700">● Paid</span>
                        @elseif($order->payment_method === 'cod')
                            <span style="color:#B45309;font-weight:700">● Cash on Delivery</span>
                        @else
                            <span style="color:#DC2626;font-weight:700">● Unpaid</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- ============================================================
             2. MAIN CONTENT GRID (TIMELINE & ITEMS | CUSTOMER & PAYMENT)
             ============================================================ --}}
        <div class="track-grid">

            {{-- ──────── LEFT COLUMN: TIMELINE & ITEMS ──────── --}}
            <div class="track-col-main">

                {{-- SHIPMENT TIMELINE CARD --}}
                <div class="track-card">
                    <div class="track-card-head">
                        <div class="track-card-title">
                            <span class="track-icon-badge">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </span>
                            <h3>Shipment Timeline</h3>
                        </div>
                        @if(!$isCancelled && $currentIdx !== false)
                            <span style="font-size:12px;font-weight:700;color:var(--green-deep);background:var(--green-tint);padding:4px 10px;border-radius:99px">
                                Step {{ $currentIdx + 1 }} of {{ count($steps) }}
                            </span>
                        @endif
                    </div>

                    @if($isCancelled)
                        <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:12px;padding:20px;text-align:center">
                            <div style="width:44px;height:44px;background:#FEE2E2;border-radius:50%;color:#DC2626;display:grid;place-items:center;margin:0 auto 10px">
                                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            </div>
                            <h4 style="font-size:16px;color:#991B1B;margin:0 0 4px;font-weight:700">Order Cancelled</h4>
                            <p style="font-size:13.5px;color:#7F1D1D;margin:0">This order has been cancelled and will not be processed further.</p>
                        </div>
                    @else
                        {{-- Timeline Stepper --}}
                        <div class="track-timeline">
                            @foreach($steps as $idx => $step)
                                @php
                                    $done    = $currentIdx !== false && $idx < $currentIdx;
                                    $current = $currentIdx !== false && $idx === $currentIdx;
                                    $future  = !$done && !$current;
                                    $isLast  = $idx === count($steps) - 1;
                                    $meta    = $stepMeta[$step] ?? ['label' => ucfirst($step), 'desc' => '', 'icon' => ''];
                                    $hist    = $historiesByStatus[$step] ?? null;
                                @endphp
                                <div class="track-step-item {{ $isLast ? 'track-step-last' : '' }} {{ $done ? 'is-done' : '' }} {{ $current ? 'is-current' : '' }} {{ $future ? 'is-future' : '' }}">
                                    
                                    {{-- Connecting Line --}}
                                    @if(!$isLast)
                                        <div class="track-step-line {{ $done ? 'line-done' : '' }}"></div>
                                    @endif

                                    {{-- Node Icon --}}
                                    <div class="track-step-node">
                                        @if($done)
                                            <div class="track-node-circle node-done">
                                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            </div>
                                        @elseif($current)
                                            <div class="track-node-circle node-current">
                                                <div class="node-pulse-ring"></div>
                                                <div class="node-dot"></div>
                                            </div>
                                        @else
                                            <div class="track-node-circle node-future">
                                                <div class="node-dot-mute"></div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Step Content --}}
                                    <div class="track-step-content">
                                        <div class="track-step-head">
                                            <span class="track-step-title {{ $done || $current ? 'text-active' : 'text-muted' }}">
                                                {{ $meta['label'] }}
                                            </span>
                                            @if($current)
                                                <span class="track-badge-current">In Progress</span>
                                            @endif
                                            @if($hist)
                                                <span class="track-step-time">
                                                    {{ $hist->changed_at ? $hist->changed_at->format('d M, h:i A') : '' }}
                                                </span>
                                            @elseif($step === 'pending' && $order->placed_at)
                                                <span class="track-step-time">
                                                    {{ $order->placed_at->format('d M, h:i A') }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="track-step-desc">{{ $meta['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Courier Box (if courier info exists) --}}
                        @if($order->courier || $order->courier_tracking)
                            <div class="track-courier-box">
                                <div style="display:flex;align-items:center;gap:12px">
                                    <div style="width:40px;height:40px;border-radius:10px;background:var(--surface);display:grid;place-items:center;color:var(--green-deep);border:1px solid var(--line)">
                                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                    </div>
                                    <div>
                                        <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em">Courier Partner</div>
                                        <div style="font-size:15px;font-weight:700;color:var(--ink)">{{ $order->courier ?? 'Assigned Courier' }}</div>
                                    </div>
                                </div>
                                @if($order->courier_tracking)
                                    <div style="text-align:right">
                                        <div style="font-size:12px;color:var(--muted)">Tracking Code</div>
                                        <div style="font-family:monospace;font-size:14px;font-weight:700;color:var(--green-deep)">{{ $order->courier_tracking }}</div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>

                {{-- ORDERED ITEMS CARD (Item Details) --}}
                <div class="track-card">
                    <div class="track-card-head">
                        <div class="track-card-title">
                            <span class="track-icon-badge">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            </span>
                            <h3>Ordered Items ({{ $order->items->count() }})</h3>
                        </div>
                    </div>

                    {{-- Items List --}}
                    <div class="track-items-list">
                        @foreach($order->items as $item)
                            @php
                                $prodImage = $item->product && $item->product->image ? asset('storage/' . $item->product->image) : null;
                                $prodUrl   = $item->product ? route('product', $item->product->id) : null;
                            @endphp
                            <div class="track-item-row">
                                {{-- Thumbnail --}}
                                <div class="track-item-thumb">
                                    @if($prodImage)
                                        <img src="{{ $prodImage }}" alt="{{ $item->name }}" loading="lazy">
                                    @else
                                        <div class="track-item-no-thumb">
                                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="track-item-info">
                                    <div class="track-item-name">
                                        @if($prodUrl)
                                            <a href="{{ $prodUrl }}" target="_blank">{{ $item->name }}</a>
                                        @else
                                            <span>{{ $item->name }}</span>
                                        @endif
                                    </div>
                                    <div class="track-item-meta">
                                        @if($item->weight)
                                            <span class="track-tag-pill">Variant: {{ $item->weight }}</span>
                                        @endif
                                        <span style="color:var(--muted)">৳{{ number_format($item->price) }} × {{ $item->qty }}</span>
                                    </div>
                                </div>

                                {{-- Line Total --}}
                                <div class="track-item-price">
                                    ৳{{ number_format($item->line_total) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Cost Summary Breakdown --}}
                    <div class="track-cost-box">
                        <div class="track-cost-row">
                            <span>Subtotal</span>
                            <span>৳{{ number_format($order->subtotal) }}</span>
                        </div>
                        <div class="track-cost-row">
                            <span>Delivery Fee</span>
                            <span>{{ $order->delivery == 0 ? 'Free' : '৳' . number_format($order->delivery) }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="track-cost-row text-discount">
                                <span>Discount {{ $order->coupon_code ? '(' . $order->coupon_code . ')' : '' }}</span>
                                <span>−৳{{ number_format($order->discount) }}</span>
                            </div>
                        @endif
                        <div class="track-cost-total">
                            <span>Total Payable</span>
                            <span class="track-total-val">৳{{ number_format($order->total) }}</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ──────── RIGHT COLUMN: CUSTOMER & PAYMENT ──────── --}}
            <div class="track-col-side">

                {{-- CUSTOMER DETAILS & SHIPPING ADDRESS CARD --}}
                <div class="track-card">
                    <div class="track-card-head">
                        <div class="track-card-title">
                            <span class="track-icon-badge">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                            <h3>Customer Details</h3>
                        </div>
                    </div>

                    <div class="track-info-group">
                        {{-- Customer Name & Avatar --}}
                        <div class="track-user-banner">
                            <div class="track-user-avatar">
                                {{ strtoupper(substr($order->customer_name ?: 'C', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:16px;font-weight:700;color:var(--ink)">{{ $order->customer_name }}</div>
                                <div style="font-size:12px;color:var(--muted)">Verified Customer</div>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="track-info-item">
                            <div class="track-info-icon">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div class="track-info-text">
                                <div class="track-info-lbl">Phone Number</div>
                                <a href="tel:{{ $order->customer_phone }}" class="track-info-val track-link">{{ $order->customer_phone }}</a>
                            </div>
                        </div>

                        {{-- Email (if present) --}}
                        @if($order->customer_email)
                            <div class="track-info-item">
                                <div class="track-info-icon">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                </div>
                                <div class="track-info-text">
                                    <div class="track-info-lbl">Email Address</div>
                                    <a href="mailto:{{ $order->customer_email }}" class="track-info-val track-link">{{ $order->customer_email }}</a>
                                </div>
                            </div>
                        @endif

                        {{-- Delivery Address --}}
                        <div class="track-info-item" style="border-top:1px solid var(--line-soft);padding-top:12px;margin-top:12px">
                            <div class="track-info-icon" style="color:var(--sale)">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div class="track-info-text">
                                <div class="track-info-lbl">Shipping Destination</div>
                                <div class="track-info-val" style="line-height:1.5">
                                    {{ $order->address_line }}
                                    @if($order->thana)
                                        <br><span style="color:var(--muted)">Area/Thana:</span> {{ $order->thana }}
                                    @endif
                                    @if($order->city)
                                        <br><span style="color:var(--muted)">City/District:</span> {{ $order->city }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Customer Special Notes --}}
                        @if($order->notes)
                            <div class="track-notes-box">
                                <div style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:var(--honey-deep);margin-bottom:4px">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                    Delivery Instruction
                                </div>
                                <div style="font-size:13px;color:var(--ink-soft);font-style:italic">"{{ $order->notes }}"</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- PAYMENT DETAILS CARD --}}
                <div class="track-card">
                    <div class="track-card-head">
                        <div class="track-card-title">
                            <span class="track-icon-badge">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </span>
                            <h3>Payment Details</h3>
                        </div>
                    </div>

                    <div style="display:grid;gap:12px;font-size:14px">
                        <div style="display:flex;align-items:center;justify-content:space-between">
                            <span style="color:var(--muted)">Method:</span>
                            <span style="font-weight:700;color:var(--ink)">
                                @switch($order->payment_method)
                                    @case('cod')
                                        Cash on Delivery (COD)
                                        @break
                                    @case('bkash')
                                        bKash Online Payment
                                        @break
                                    @case('nagad')
                                        Nagad
                                        @break
                                    @case('rocket')
                                        Rocket
                                        @break
                                    @case('sslcommerz')
                                        SSLCommerz (Card / MFS)
                                        @break
                                    @default
                                        {{ ucfirst($order->payment_method) }}
                                @endswitch
                            </span>
                        </div>

                        <div style="display:flex;align-items:center;justify-content:space-between">
                            <span style="color:var(--muted)">Payment Status:</span>
                            @if($order->payment_status === 'paid')
                                <span class="track-status-pill" style="background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0;padding:2px 10px;font-size:12px">
                                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Paid
                                </span>
                            @elseif($order->payment_method === 'cod')
                                <span class="track-status-pill" style="background:#FEF3C7;color:#92400E;border:1px solid #FDE68A;padding:2px 10px;font-size:12px">
                                    Pay on Delivery
                                </span>
                            @elseif($order->payment_status === 'failed')
                                <span class="track-status-pill" style="background:#FEE2E2;color:#991B1B;border:1px solid #FECACA;padding:2px 10px;font-size:12px">
                                    Payment Failed
                                </span>
                            @else
                                <span class="track-status-pill" style="background:#FEF3C7;color:#92400E;border:1px solid #FDE68A;padding:2px 10px;font-size:12px">
                                    Unpaid
                                </span>
                            @endif
                        </div>

                        @if($order->payment_ref)
                            <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px dashed var(--line);padding-top:8px">
                                <span style="color:var(--muted)">Transaction ID:</span>
                                <span style="font-family:monospace;font-weight:700;color:var(--ink)">{{ $order->payment_ref }}</span>
                            </div>
                        @endif

                        {{-- Pay Now button for unpaid online order --}}
                        @if($order->payment_status !== 'paid' && $order->payment_method !== 'cod' && !$isCancelled)
                            <div style="margin-top:8px">
                                <a href="{{ route('payment.start', $order->number) }}" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:14px;display:flex;align-items:center;gap:8px">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                    Complete Payment Now
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- QUICK ACTIONS CARD --}}
                <div class="track-card" style="display:grid;gap:10px">
                    <a href="{{ route('order.invoice', $order->number) }}" target="_blank" class="btn btn-ghost" style="width:100%;justify-content:center;font-size:13.5px;padding:12px;border:1.5px solid var(--line);display:flex;align-items:center;gap:8px">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        Download / Print Invoice
                    </a>

                    <a href="{{ route('contact') }}" class="btn btn-ghost" style="width:100%;justify-content:center;font-size:13.5px;padding:12px;display:flex;align-items:center;gap:8px">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        Need Help with this Order?
                    </a>

                    <div style="text-align:center;margin-top:6px">
                        <a href="{{ route('track') }}" style="font-size:13px;font-weight:700;color:var(--green);text-decoration:underline">
                            Track another order
                        </a>
                    </div>
                </div>

            </div>

        </div>
    @endif

</div>

{{-- ============================================================
     CUSTOM PAGE STYLES
     ============================================================ --}}
<style>
/* Base Container & Cards */
.track-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    margin-bottom: 24px;
}
.track-search-card {
    max-width: 680px;
    margin: 0 auto 28px;
}
.track-search-row {
    display: flex;
    gap: 12px;
    align-items: flex-end;
}
@media (max-width: 580px) {
    .track-search-row {
        flex-direction: column;
        align-items: stretch;
    }
}

/* Hero Overview Card */
.track-hero-card {
    background: linear-gradient(180deg, var(--surface) 0%, var(--surface-2) 100%);
    border: 1.5px solid var(--line);
}
.track-hero-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
}
.track-hero-eyebrow {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    color: var(--muted);
    text-transform: uppercase;
    display: block;
    margin-bottom: 4px;
}
.track-order-number {
    font-family: var(--font-display);
    font-size: 26px;
    font-weight: 800;
    color: var(--ink);
    margin: 0;
    letter-spacing: .02em;
}
.track-copy-btn {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 700;
    color: var(--ink-soft);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all .2s;
}
.track-copy-btn:hover {
    background: var(--line-soft);
    color: var(--ink);
}

/* Status Pill */
.track-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 13.5px;
    font-weight: 700;
    letter-spacing: .02em;
}
.track-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

/* Meta Strip */
.track-meta-strip {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 16px;
    padding-top: 18px;
    border-top: 1px solid var(--line-soft);
}
.track-meta-item {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.track-meta-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}
.track-meta-val {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--ink);
}

/* Main Two-Column Grid */
.track-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}
@media (min-width: 900px) {
    .track-grid {
        grid-template-columns: 1.25fr 0.85fr;
        align-items: start;
    }
}

/* Card Header & Title */
.track-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--line-soft);
    padding-bottom: 14px;
}
.track-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
}
.track-card-title h3 {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 800;
    color: var(--ink);
    margin: 0;
}
.track-icon-badge {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--green-tint);
    color: var(--green);
    display: grid;
    place-items: center;
}

/* Timeline Stepper */
.track-timeline {
    position: relative;
    padding-left: 28px;
}
.track-step-item {
    position: relative;
    padding-bottom: 28px;
}
.track-step-item.track-step-last {
    padding-bottom: 0;
}
.track-step-line {
    position: absolute;
    left: -17px;
    top: 24px;
    bottom: 0;
    width: 2px;
    background: var(--line);
}
.track-step-line.line-done {
    background: var(--green);
}
.track-step-node {
    position: absolute;
    left: -28px;
    top: 2px;
}
.track-node-circle {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: grid;
    place-items: center;
}
.node-done {
    background: var(--green);
    color: #fff;
    border: 2px solid var(--surface);
    box-shadow: 0 0 0 2px var(--green-soft);
}
.node-current {
    background: var(--surface);
    border: 2.5px solid var(--green);
    position: relative;
}
.node-pulse-ring {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid var(--green);
    animation: trackPulse 1.6s ease-in-out infinite;
}
.node-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: var(--green);
}
.node-future {
    background: var(--surface);
    border: 2px solid var(--line);
}
.node-dot-mute {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--line);
}
@keyframes trackPulse {
    0%   { transform: scale(1); opacity: 0.8; }
    100% { transform: scale(1.6); opacity: 0; }
}

.track-step-content {
    padding-left: 6px;
}
.track-step-head {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.track-step-title {
    font-size: 15px;
    font-weight: 700;
}
.track-step-title.text-active {
    color: var(--ink);
}
.track-step-title.text-muted {
    color: var(--muted);
}
.track-badge-current {
    font-size: 11px;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 999px;
    background: #E0EEFF;
    color: #1D4ED8;
    text-transform: uppercase;
}
.track-step-time {
    font-size: 12px;
    color: var(--muted);
    margin-left: auto;
}
.track-step-desc {
    font-size: 13.5px;
    color: var(--ink-soft);
    margin: 4px 0 0;
}

/* Courier Box */
.track-courier-box {
    margin-top: 20px;
    padding: 14px 18px;
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Ordered Items List */
.track-items-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}
.track-item-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px;
    background: var(--surface-2);
    border: 1px solid var(--line-soft);
    border-radius: 12px;
    transition: all .2s;
}
.track-item-row:hover {
    border-color: var(--line);
    background: var(--surface);
}
.track-item-thumb {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    border: 1px solid var(--line-soft);
    flex-shrink: 0;
    display: grid;
    place-items: center;
}
.track-item-thumb img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.track-item-no-thumb {
    color: var(--muted);
}
.track-item-info {
    flex: 1;
    min-width: 0;
}
.track-item-name {
    font-size: 15px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.track-item-name a {
    color: var(--ink);
    text-decoration: none;
    transition: color .2s;
}
.track-item-name a:hover {
    color: var(--green);
}
.track-item-meta {
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.track-tag-pill {
    background: #fff;
    border: 1px solid var(--line);
    padding: 1px 7px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--ink-soft);
}
.track-item-price {
    font-size: 16px;
    font-weight: 800;
    color: var(--ink);
    flex-shrink: 0;
}

/* Cost Breakdown Box */
.track-cost-box {
    border-top: 1.5px dashed var(--line);
    padding-top: 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.track-cost-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: var(--ink-soft);
}
.track-cost-row.text-discount {
    color: var(--green-deep);
    font-weight: 600;
}
.track-cost-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid var(--line);
    padding-top: 12px;
    margin-top: 6px;
    font-size: 16px;
    font-weight: 800;
    color: var(--ink);
}
.track-total-val {
    font-size: 20px;
    font-weight: 900;
    color: var(--green-deep);
}

/* Customer Details Section */
.track-user-banner {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: var(--surface-2);
    border-radius: 12px;
    border: 1px solid var(--line-soft);
    margin-bottom: 16px;
}
.track-user-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: var(--green);
    color: #fff;
    font-weight: 800;
    font-size: 18px;
    display: grid;
    place-items: center;
}
.track-info-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.track-info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.track-info-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--surface-2);
    border: 1px solid var(--line-soft);
    color: var(--green);
    display: grid;
    place-items: center;
    flex-shrink: 0;
    margin-top: 2px;
}
.track-info-text {
    flex: 1;
}
.track-info-lbl {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    margin-bottom: 2px;
}
.track-info-val {
    font-size: 14.5px;
    font-weight: 600;
    color: var(--ink);
}
.track-link {
    color: var(--green-deep);
    text-decoration: none;
}
.track-link:hover {
    text-decoration: underline;
}
.track-notes-box {
    margin-top: 8px;
    background: #FFFBEB;
    border: 1px solid #FDE68A;
    border-radius: 10px;
    padding: 10px 14px;
}
</style>

@push('scripts')
<script>
function copyOrderNumber(num) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(num).then(function() {
            var btn = document.getElementById('copy-text');
            if (btn) {
                var old = btn.innerText;
                btn.innerText = 'Copied!';
                setTimeout(function(){ btn.innerText = old; }, 2000);
            }
        });
    }
}
</script>
@endpush

@endsection
