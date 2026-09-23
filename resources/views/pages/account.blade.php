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
        <p class="sub">Welcome back, {{ explode(' ', $user->name)[0] }}</p>
    </div>
</div>

{{-- ============================================================
     MAIN CONTENT — sidebar + dashboard
     ============================================================ --}}
<div class="wrap section">
    <div class="shop-layout" style="grid-template-columns:240px 1fr">

        {{-- ── LEFT sidebar ─────────────────────────────────────── --}}
        <nav class="co-card" style="padding:8px 0;margin-bottom:0" aria-label="Account navigation">
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
            <a href="#addresses" class="fopt" style="padding:12px 20px;border-radius:0;border-left:3px solid transparent">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                Addresses
            </a>
            <a href="#profile" class="fopt" style="padding:12px 20px;border-radius:0;border-left:3px solid transparent">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Profile Settings
            </a>
            <div style="height:1px;background:var(--line);margin:8px 0"></div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="fopt" style="width:100%;padding:12px 20px;border-radius:0;border-left:3px solid transparent;color:var(--sale);background:none;border-top:none;border-right:none;border-bottom:none;text-align:left;cursor:pointer;display:flex;align-items:center;gap:10px;font-size:14px;font-weight:600">
                    <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Logout
                </button>
            </form>
        </nav>
        {{-- ── END sidebar ──────────────────────────────────────── --}}

        {{-- ── RIGHT main ───────────────────────────────────────── --}}
        <div>

            {{-- Status messages --}}
            @if (session('status'))
                <div style="background:var(--green-tint);color:var(--green-deep);padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:14px;font-weight:600">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Profile summary card --}}
            <div class="co-card" style="display:flex;align-items:center;gap:20px;margin-bottom:18px">
                <div style="width:72px;height:72px;border-radius:50%;background:var(--green-soft);color:var(--green-deep);display:grid;place-items:center;font-family:var(--font-display);font-weight:800;font-size:28px;flex-shrink:0;border:2px solid var(--green-soft)">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:20px;margin-bottom:4px">{{ $user->name }}</div>
                    <div style="font-size:14px;color:var(--muted);margin-bottom:2px">{{ $user->email }}</div>
                    @if($user->phone)
                        <div style="font-size:14px;color:var(--muted)">{{ $user->phone }}</div>
                    @endif
                </div>
                <a href="#profile" class="btn btn-ghost" style="flex-shrink:0">
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
                    <div style="font-family:var(--font-display);font-weight:800;font-size:32px;color:var(--green-deep);margin-bottom:4px">{{ $orders->count() }}</div>
                    <div style="font-size:13px;color:var(--muted);font-weight:600;letter-spacing:.03em">Total Orders</div>
                </div>
                <div class="co-card" style="text-align:center;padding:20px 16px;margin-bottom:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:32px;color:var(--sale);margin-bottom:4px">{{ $user->wishlists()->count() }}</div>
                    <div style="font-size:13px;color:var(--muted);font-weight:600;letter-spacing:.03em">Wishlist Items</div>
                </div>
                <div class="co-card" style="text-align:center;padding:20px 16px;margin-bottom:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:32px;color:var(--honey);margin-bottom:4px">{{ $addresses->count() }}</div>
                    <div style="font-size:13px;color:var(--muted);font-weight:600;letter-spacing:.03em">Saved Addresses</div>
                </div>
            </div>

            {{-- Recent Orders (real) --}}
            <div class="co-card" style="margin-bottom:18px">
                <h3 style="font-size:18px;margin-bottom:18px;display:flex;align-items:center;gap:10px">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--green)">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                        <path d="M9 12h6"/><path d="M9 16h4"/>
                    </svg>
                    My Orders
                </h3>

                @if($orders->isEmpty())
                    <div style="text-align:center;padding:32px 16px;color:var(--muted)">
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin:0 auto 12px;display:block;color:var(--line)">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                        </svg>
                        <p style="font-size:15px;font-weight:600;color:var(--ink-soft);margin:0 0 6px">No orders yet</p>
                        <p style="font-size:13.5px;margin:0">Your order history will appear here.</p>
                    </div>
                @else
                    <div style="overflow-x:auto">
                        <table style="width:100%;border-collapse:collapse;font-size:14px">
                            <thead>
                                <tr style="background:var(--surface-2)">
                                    <th style="padding:10px 12px;text-align:left;font-size:11.5px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Order #</th>
                                    <th style="padding:10px 12px;text-align:left;font-size:11.5px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Date</th>
                                    <th style="padding:10px 12px;text-align:center;font-size:11.5px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Items</th>
                                    <th style="padding:10px 12px;text-align:left;font-size:11.5px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Status</th>
                                    <th style="padding:10px 12px;text-align:right;font-size:11.5px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $ord)
                                    <tr style="border-top:1px solid var(--line-soft)">
                                        <td style="padding:11px 12px">
                                            <span style="font-family:var(--font-display);font-weight:700;color:var(--ink)">{{ $ord->number }}</span>
                                        </td>
                                        <td style="padding:11px 12px;color:var(--muted)">{{ $ord->placed_at->format('d M Y') }}</td>
                                        <td style="padding:11px 12px;text-align:center;color:var(--muted)">{{ $ord->items->count() }}</td>
                                        <td style="padding:11px 12px">
                                            @php
                                                $statusColors = [
                                                    'pending'    => ['bg'=>'#FEF9C3','color'=>'#78350F'],
                                                    'confirmed'  => ['bg'=>'#DBEAFE','color'=>'#1E40AF'],
                                                    'processing' => ['bg'=>'#EDE9FE','color'=>'#5B21B6'],
                                                    'shipped'    => ['bg'=>'#DBEAFE','color'=>'#1E40AF'],
                                                    'delivered'  => ['bg'=>'var(--green-tint)','color'=>'var(--green-deep)'],
                                                    'cancelled'  => ['bg'=>'#FEE2E2','color'=>'#991B1B'],
                                                ];
                                                $sc = $statusColors[$ord->status] ?? ['bg'=>'#F3F4F6','color'=>'#374151'];
                                            @endphp
                                            <span style="display:inline-block;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:700;background:{{ $sc['bg'] }};color:{{ $sc['color'] }}">
                                                {{ ucfirst($ord->status) }}
                                            </span>
                                        </td>
                                        <td style="padding:11px 12px;text-align:right;font-weight:700;color:var(--ink)">৳{{ number_format($ord->total) }}</td>
                                        <td style="padding:11px 12px;text-align:right;white-space:nowrap">
                                            <div style="display:inline-flex;gap:6px;align-items:center;justify-content:flex-end">
                                                <a href="{{ route('order.invoice', $ord->number) }}" target="_blank" class="btn btn-ghost" style="padding:4px 10px;font-size:12px;display:inline-flex;align-items:center;gap:4px;border:1px solid var(--line);border-radius:6px" title="Download Invoice PDF">
                                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                                    Invoice
                                                </a>
                                                <a href="{{ route('track', ['number' => $ord->number]) }}" class="btn btn-ghost" style="padding:4px 10px;font-size:12px;display:inline-flex;align-items:center;gap:4px;border:1px solid var(--line);border-radius:6px" title="Track Order">
                                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                    Track
                                                </a>
                                                <a href="{{ route('order.confirmation', $ord->number) }}" style="font-size:12.5px;font-weight:700;color:var(--green);text-decoration:none;padding:4px 6px">
                                                    Details
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- ── Addresses section ──────────────────────────────── --}}
            <div id="addresses" class="co-card" style="margin-bottom:18px">
                <h3 style="font-size:18px;margin-bottom:18px;display:flex;align-items:center;gap:10px">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--green)">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                    My Addresses
                </h3>

                @if($addresses->isEmpty())
                    <p style="color:var(--muted);font-size:14px;margin-bottom:18px">No addresses saved yet.</p>
                @else
                    <div style="display:grid;gap:12px;margin-bottom:18px">
                        @foreach($addresses as $addr)
                            <div style="border:1px solid var(--line);border-radius:10px;padding:16px;position:relative">
                                @if($addr->is_default)
                                    <span style="position:absolute;top:12px;right:12px;font-size:11px;font-weight:700;background:var(--green-tint);color:var(--green-deep);padding:3px 8px;border-radius:99px">Default</span>
                                @endif
                                <div style="font-weight:700;margin-bottom:4px">{{ $addr->name }}</div>
                                @if($addr->phone)<div style="font-size:13.5px;color:var(--muted)">{{ $addr->phone }}</div>@endif
                                <div style="font-size:13.5px;color:var(--ink-soft);margin-top:4px">{{ $addr->line }}, {{ $addr->thana ? $addr->thana.', ' : '' }}{{ $addr->city }}</div>
                                <div style="display:flex;gap:10px;margin-top:10px">
                                    @if(!$addr->is_default)
                                        <form method="POST" action="{{ route('addresses.default', $addr) }}" style="display:inline">
                                            @csrf
                                            <button type="submit" style="font-size:12.5px;color:var(--green);font-weight:600;background:none;border:none;padding:0;cursor:pointer">Set as default</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('addresses.destroy', $addr) }}" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="font-size:12.5px;color:var(--sale);font-weight:600;background:none;border:none;padding:0;cursor:pointer" onclick="return confirm('Remove this address?')">Remove</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Add address form --}}
                <details style="margin-top:4px">
                    <summary style="cursor:pointer;font-weight:700;font-size:14.5px;color:var(--green);list-style:none;display:flex;align-items:center;gap:8px">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Add new address
                    </summary>
                    <form method="POST" action="{{ route('addresses.store') }}" style="margin-top:16px">
                        @csrf
                        <div class="field-row">
                            <div class="field">
                                <label>Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Recipient name" required>
                                @error('name')<span style="color:var(--sale);font-size:13px">{{ $message }}</span>@enderror
                            </div>
                            <div class="field">
                                <label>Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="01XXXXXXXXX">
                            </div>
                        </div>
                        <div class="field">
                            <label>Address Line</label>
                            <input type="text" name="line" value="{{ old('line') }}" placeholder="House/Road/Area" required>
                            @error('line')<span style="color:var(--sale);font-size:13px">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-row">
                            <div class="field">
                                <label>City</label>
                                <input type="text" name="city" value="{{ old('city', 'Dhaka') }}" placeholder="Dhaka" required>
                                @error('city')<span style="color:var(--sale);font-size:13px">{{ $message }}</span>@enderror
                            </div>
                            <div class="field">
                                <label>Thana / Upazila</label>
                                <input type="text" name="thana" value="{{ old('thana') }}" placeholder="Mirpur">
                            </div>
                        </div>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;color:var(--ink-soft);font-weight:500;margin-bottom:14px">
                            <input type="checkbox" name="is_default" value="1" style="accent-color:var(--green)">
                            Set as default address
                        </label>
                        <button type="submit" class="btn btn-primary">Save Address</button>
                    </form>
                </details>
            </div>

            {{-- ── Edit Profile section ────────────────────────────── --}}
            <div id="profile" class="co-card" style="margin-bottom:0">
                <h3 style="font-size:18px;margin-bottom:18px;display:flex;align-items:center;gap:10px">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--green)">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Profile Settings
                </h3>

                @if($errors->updateProfileInformation->any())
                    <div style="background:#FEF2F2;color:var(--sale);padding:12px 16px;border-radius:9px;margin-bottom:16px;font-size:14px">
                        @foreach($errors->updateProfileInformation->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('user-profile-information.update') }}">
                    @csrf @method('PUT')

                    <div class="field">
                        <label>Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')<span style="color:var(--sale);font-size:13px">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label>Email address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')<span style="color:var(--sale);font-size:13px">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label>Phone number</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="01XXXXXXXXX">
                        @error('phone')<span style="color:var(--sale);font-size:13px">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>

        </div>
        {{-- ── END main ──────────────────────────────────────────── --}}

    </div>
</div>

@endsection
