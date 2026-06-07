@extends('layouts.app')
@section('title', 'My Account — Shuvo')

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
            <span>Account</span>
        </div>
        <h1>My Account</h1>
        <p class="sub">Welcome back, Guest</p>
    </div>
</div>

{{-- ============================================================
     MAIN CONTENT — sidebar + dashboard
     ============================================================ --}}
<div class="wrap section">
    <div class="shop-layout" style="grid-template-columns:240px 1fr">

        {{-- ── LEFT sidebar ─────────────────────────────────────── --}}
        <nav class="co-card" style="padding:8px 0;margin-bottom:0" aria-label="Account navigation">
            {{-- Dashboard (active) --}}
            <a href="{{ route('account') }}" class="fopt" style="padding:12px 20px;border-radius:0;color:var(--green-deep);background:var(--green-tint);font-weight:700;border-left:3px solid var(--green)">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                Dashboard
            </a>
            <a href="#" class="fopt" style="padding:12px 20px;border-radius:0;border-left:3px solid transparent">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
                My Orders
            </a>
            <a href="{{ route('wishlist') }}" class="fopt" style="padding:12px 20px;border-radius:0;border-left:3px solid transparent">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.6l-1-1a5.5 5.5 0 00-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 000-7.8z"/>
                </svg>
                Wishlist
            </a>
            <a href="{{ route('track') }}" class="fopt" style="padding:12px 20px;border-radius:0;border-left:3px solid transparent">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Track Order
            </a>
            <a href="#" class="fopt" style="padding:12px 20px;border-radius:0;border-left:3px solid transparent">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                Addresses
            </a>
            <a href="#" class="fopt" style="padding:12px 20px;border-radius:0;border-left:3px solid transparent">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Profile Settings
            </a>
            <div style="height:1px;background:var(--line);margin:8px 0"></div>
            <a href="#" class="fopt" style="padding:12px 20px;border-radius:0;border-left:3px solid transparent;color:var(--sale)">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout
            </a>
        </nav>
        {{-- ── END sidebar ──────────────────────────────────────── --}}

        {{-- ── RIGHT main ───────────────────────────────────────── --}}
        <div>

            {{-- Profile summary card --}}
            <div class="co-card" style="display:flex;align-items:center;gap:20px;margin-bottom:18px">
                {{-- Avatar circle --}}
                <div style="width:72px;height:72px;border-radius:50%;background:var(--green-soft);color:var(--green-deep);display:grid;place-items:center;font-family:var(--font-display);font-weight:800;font-size:28px;flex-shrink:0;border:2px solid var(--green-soft)">
                    G
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:20px;margin-bottom:4px">Guest User</div>
                    <div style="font-size:14px;color:var(--muted);margin-bottom:2px">guest@shuvo.com</div>
                    <div style="font-size:14px;color:var(--muted)">+880 1700-000000</div>
                </div>
                <a href="#" class="btn btn-ghost" style="flex-shrink:0">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit Profile
                </a>
            </div>

            {{-- Stats row --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:18px">
                <div class="co-card" style="text-align:center;padding:20px 16px;margin-bottom:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:32px;color:var(--green-deep);margin-bottom:4px">12</div>
                    <div style="font-size:13px;color:var(--muted);font-weight:600;letter-spacing:.03em">Total Orders</div>
                </div>
                <div class="co-card" style="text-align:center;padding:20px 16px;margin-bottom:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:32px;color:var(--sale);margin-bottom:4px">5</div>
                    <div style="font-size:13px;color:var(--muted);font-weight:600;letter-spacing:.03em">Wishlist Items</div>
                </div>
                <div class="co-card" style="text-align:center;padding:20px 16px;margin-bottom:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:32px;color:var(--honey);margin-bottom:4px">340</div>
                    <div style="font-size:13px;color:var(--muted);font-weight:600;letter-spacing:.03em">Reward Points</div>
                </div>
            </div>

            {{-- Recent orders --}}
            <div class="co-card" style="margin-bottom:0">
                <h3 style="font-size:18px;margin-bottom:18px;display:flex;align-items:center;gap:10px">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--green)">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                        <path d="M9 12h6"/><path d="M9 16h4"/>
                    </svg>
                    Recent Orders
                </h3>

                {{-- Table header --}}
                <div style="display:grid;grid-template-columns:1.4fr 1fr 0.8fr 1.1fr 0.8fr 0.5fr;gap:12px;padding:10px 14px;background:var(--surface-2);border-radius:9px;font-size:12px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:var(--muted);margin-bottom:4px">
                    <span>Order</span>
                    <span>Date</span>
                    <span>Items</span>
                    <span>Status</span>
                    <span>Total</span>
                    <span></span>
                </div>

                {{-- Order row 1 — Delivered --}}
                <div style="display:grid;grid-template-columns:1.4fr 1fr 0.8fr 1.1fr 0.8fr 0.5fr;gap:12px;align-items:center;padding:14px;border-bottom:1px solid var(--line-soft)">
                    <span style="font-family:var(--font-display);font-weight:700;font-size:14px;color:var(--ink)">#SHV-984211</span>
                    <span style="font-size:13.5px;color:var(--ink-soft)">12 May 2026</span>
                    <span style="font-size:13.5px;color:var(--ink-soft)">3 items</span>
                    <span>
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:700;padding:4px 10px;border-radius:999px;background:#E9F5EC;color:#1E6B33">
                            <span style="width:6px;height:6px;border-radius:50%;background:#2E7D45;flex-shrink:0"></span>
                            Delivered
                        </span>
                    </span>
                    <span style="font-family:var(--font-display);font-weight:700;font-size:14.5px">৳1,840</span>
                    <a href="#" style="font-size:13px;font-weight:600;color:var(--green)">View</a>
                </div>

                {{-- Order row 2 — Processing --}}
                <div style="display:grid;grid-template-columns:1.4fr 1fr 0.8fr 1.1fr 0.8fr 0.5fr;gap:12px;align-items:center;padding:14px;border-bottom:1px solid var(--line-soft)">
                    <span style="font-family:var(--font-display);font-weight:700;font-size:14px;color:var(--ink)">#SHV-776520</span>
                    <span style="font-size:13.5px;color:var(--ink-soft)">4 Jun 2026</span>
                    <span style="font-size:13.5px;color:var(--ink-soft)">1 item</span>
                    <span>
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:700;padding:4px 10px;border-radius:999px;background:var(--honey-soft);color:var(--honey-deep)">
                            <span style="width:6px;height:6px;border-radius:50%;background:var(--honey);flex-shrink:0"></span>
                            Processing
                        </span>
                    </span>
                    <span style="font-family:var(--font-display);font-weight:700;font-size:14.5px">৳620</span>
                    <a href="#" style="font-size:13px;font-weight:600;color:var(--green)">View</a>
                </div>

                {{-- Order row 3 — Shipped --}}
                <div style="display:grid;grid-template-columns:1.4fr 1fr 0.8fr 1.1fr 0.8fr 0.5fr;gap:12px;align-items:center;padding:14px">
                    <span style="font-family:var(--font-display);font-weight:700;font-size:14px;color:var(--ink)">#SHV-651088</span>
                    <span style="font-size:13.5px;color:var(--ink-soft)">7 Jun 2026</span>
                    <span style="font-size:13.5px;color:var(--ink-soft)">5 items</span>
                    <span>
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:700;padding:4px 10px;border-radius:999px;background:#E0EEFF;color:#1A5AB8">
                            <span style="width:6px;height:6px;border-radius:50%;background:#2563EB;flex-shrink:0"></span>
                            Shipped
                        </span>
                    </span>
                    <span style="font-family:var(--font-display);font-weight:700;font-size:14.5px">৳3,290</span>
                    <a href="#" style="font-size:13px;font-weight:600;color:var(--green)">View</a>
                </div>

                {{-- View all link --}}
                <div style="padding:14px;border-top:1px solid var(--line);text-align:right">
                    <a href="#" style="font-size:14px;font-weight:600;color:var(--green);display:inline-flex;align-items:center;gap:6px">
                        View all orders
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
        {{-- ── END main ──────────────────────────────────────────── --}}

    </div>
</div>

@endsection
