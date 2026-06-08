<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Invoice {{ $order->number }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 13px;
        color: #1A1A1A;
        background: #fff;
        padding: 32px 40px;
    }
    .header {
        display: block;
        border-bottom: 3px solid #2E7D32;
        padding-bottom: 18px;
        margin-bottom: 24px;
    }
    .brand {
        font-size: 28px;
        font-weight: 700;
        color: #2E7D32;
        letter-spacing: -0.5px;
    }
    .brand-tagline {
        font-size: 11px;
        color: #666;
        margin-top: 3px;
    }
    .invoice-title {
        font-size: 22px;
        font-weight: 700;
        color: #111;
        margin-top: 6px;
    }
    .meta-grid {
        width: 100%;
        margin-bottom: 22px;
        border-collapse: collapse;
    }
    .meta-grid td {
        vertical-align: top;
        padding: 0;
        width: 50%;
    }
    .section-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #888;
        margin-bottom: 6px;
    }
    .section-value {
        font-size: 13px;
        color: #333;
        line-height: 1.6;
    }
    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-green { background: #E8F5E9; color: #1B5E20; }
    .badge-orange { background: #FFF3E0; color: #E65100; }
    .badge-blue { background: #E3F2FD; color: #0D47A1; }
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .items-table th {
        background: #F5F5F5;
        padding: 9px 12px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #666;
        border-bottom: 2px solid #E0E0E0;
    }
    .items-table th.right { text-align: right; }
    .items-table th.center { text-align: center; }
    .items-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #EEEEEE;
        color: #333;
        font-size: 13px;
    }
    .items-table td.right { text-align: right; }
    .items-table td.center { text-align: center; color: #666; }
    .items-table tr:last-child td { border-bottom: none; }
    .item-name { font-weight: 600; color: #111; }
    .item-weight { font-size: 11px; color: #999; margin-top: 2px; }
    .totals-table {
        width: 280px;
        margin-left: auto;
        border-collapse: collapse;
    }
    .totals-table td {
        padding: 5px 8px;
        font-size: 13px;
        color: #333;
    }
    .totals-table td.label { color: #666; }
    .totals-table td.value { text-align: right; font-weight: 600; }
    .total-row td {
        padding-top: 10px;
        font-size: 16px;
        font-weight: 700;
        color: #111;
        border-top: 2px solid #2E7D32;
    }
    .total-row td.value { color: #2E7D32; }
    .discount-row td { color: #2E7D32; }
    .footer {
        margin-top: 32px;
        padding-top: 16px;
        border-top: 1px solid #E0E0E0;
        font-size: 11px;
        color: #999;
        text-align: center;
    }
    .divider {
        border: none;
        border-top: 1px solid #EEEEEE;
        margin: 20px 0;
    }
    .info-block {
        background: #FAFAFA;
        border: 1px solid #EEEEEE;
        border-radius: 6px;
        padding: 14px 16px;
    }
</style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <div style="display: table; width: 100%;">
        <div style="display: table-cell; vertical-align: middle;">
            <div class="brand">Shuvo</div>
            <div class="brand-tagline">Organic &amp; Natural Products</div>
        </div>
        <div style="display: table-cell; vertical-align: middle; text-align: right;">
            <div class="invoice-title">INVOICE</div>
            <div style="font-size:13px;color:#666;margin-top:4px">{{ $order->number }}</div>
        </div>
    </div>
</div>

{{-- Order Meta: Customer + Order Info --}}
<table class="meta-grid">
    <tr>
        <td>
            <div class="info-block" style="margin-right:16px">
                <div class="section-label">Bill To</div>
                <div class="section-value">
                    <strong>{{ $order->customer_name }}</strong><br>
                    {{ $order->address_line }}<br>
                    @if($order->thana){{ $order->thana }}, @endif{{ $order->city }}<br>
                    {{ $order->customer_phone }}<br>
                    @if($order->customer_email){{ $order->customer_email }}@endif
                </div>
            </div>
        </td>
        <td>
            <div class="info-block">
                <div class="section-label">Order Details</div>
                <div class="section-value">
                    <strong>Date:</strong> {{ $order->placed_at->format('d M Y') }}<br>
                    <strong>Status:</strong>
                    <span class="badge badge-{{ in_array($order->status, ['delivered']) ? 'green' : (in_array($order->status, ['shipped','processing','confirmed']) ? 'blue' : 'orange') }}">
                        {{ ucfirst($order->status) }}
                    </span><br>
                    <strong>Payment:</strong>
                    @switch($order->payment_method)
                        @case('cod') Cash on Delivery @break
                        @case('bkash') bKash @break
                        @case('nagad') Nagad @break
                        @case('rocket') Rocket @break
                        @case('sslcommerz') SSLCommerz @break
                        @default {{ ucfirst($order->payment_method) }}
                    @endswitch
                    &nbsp;<span class="badge badge-{{ $order->payment_status === 'paid' ? 'green' : 'orange' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span><br>
                    @if($order->coupon_code)
                        <strong>Coupon:</strong> {{ $order->coupon_code }}<br>
                    @endif
                </div>
            </div>
        </td>
    </tr>
</table>

{{-- Items --}}
<table class="items-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Item</th>
            <th class="center">Qty</th>
            <th class="right">Unit Price</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $i => $item)
            <tr>
                <td style="color:#999">{{ $i + 1 }}</td>
                <td>
                    <div class="item-name">{{ $item->name }}</div>
                    @if($item->weight)<div class="item-weight">{{ $item->weight }}</div>@endif
                </td>
                <td class="center">{{ $item->qty }}</td>
                <td class="right">&#2547;{{ number_format($item->price) }}</td>
                <td class="right" style="font-weight:600">&#2547;{{ number_format($item->line_total) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Totals --}}
<table class="totals-table">
    <tr>
        <td class="label">Subtotal</td>
        <td class="value">&#2547;{{ number_format($order->subtotal) }}</td>
    </tr>
    <tr>
        <td class="label">Delivery</td>
        <td class="value">{{ $order->delivery === 0 ? 'Free' : '&#2547;'.number_format($order->delivery) }}</td>
    </tr>
    @if($order->discount > 0)
        <tr class="discount-row">
            <td class="label">Discount {{ $order->coupon_code ? '('.$order->coupon_code.')' : '' }}</td>
            <td class="value">−&#2547;{{ number_format($order->discount) }}</td>
        </tr>
    @endif
    <tr class="total-row">
        <td class="label">Grand Total</td>
        <td class="value">&#2547;{{ number_format($order->total) }}</td>
    </tr>
</table>

@if($order->notes)
    <hr class="divider">
    <div style="font-size:12px;color:#666"><strong>Notes:</strong> {{ $order->notes }}</div>
@endif

{{-- Footer --}}
<div class="footer">
    <p>Thank you for shopping with Shuvo — Organic &amp; Natural Products</p>
    <p style="margin-top:4px">This is a computer-generated invoice and does not require a signature.</p>
    <p style="margin-top:4px">Questions? Contact us at support@shuvo.com</p>
</div>

</body>
</html>
