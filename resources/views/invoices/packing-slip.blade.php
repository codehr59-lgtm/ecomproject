<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Packing Slip {{ $order->number }}</title>
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
        display: table;
        width: 100%;
        border-bottom: 3px solid #2E7D32;
        padding-bottom: 16px;
        margin-bottom: 24px;
    }
    .brand { font-size: 24px; font-weight: 700; color: #2E7D32; }
    .title { font-size: 18px; font-weight: 700; color: #111; text-align: right; }
    .info-grid {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .info-grid td { vertical-align: top; padding: 0; width: 50%; }
    .label { font-size: 10px; font-weight: 700; text-transform: uppercase; color: #888; letter-spacing: 0.08em; margin-bottom: 4px; }
    .value { font-size: 13px; color: #333; line-height: 1.6; }
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .items-table th {
        background: #F5F5F5;
        padding: 8px 12px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #666;
        border-bottom: 2px solid #E0E0E0;
    }
    .items-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #EEE;
        font-size: 13px;
    }
    .items-table td.center { text-align: center; }
    .item-name { font-weight: 600; }
    .item-weight { font-size: 11px; color: #999; }
    .check-box {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid #999;
        border-radius: 2px;
        vertical-align: middle;
    }
    .footer {
        margin-top: 24px;
        padding-top: 12px;
        border-top: 1px solid #E0E0E0;
        font-size: 11px;
        color: #999;
        text-align: center;
    }
</style>
</head>
<body>

<div class="header">
    <div style="display: table-cell; vertical-align: middle;">
        <div class="brand">Shuvo</div>
    </div>
    <div style="display: table-cell; vertical-align: middle; text-align: right;">
        <div class="title">PACKING SLIP</div>
        <div style="font-size:13px;color:#666;">{{ $order->number }}</div>
        <div style="font-size:12px;color:#999;">{{ $order->placed_at->format('d M Y') }}</div>
    </div>
</div>

<table class="info-grid">
    <tr>
        <td style="padding-right:16px;">
            <div class="label">Ship To</div>
            <div class="value">
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->address_line }}<br>
                @if($order->thana){{ $order->thana }}, @endif{{ $order->city }}<br>
                {{ $order->customer_phone }}
            </div>
        </td>
        <td>
            <div class="label">Order Info</div>
            <div class="value">
                <strong>Order:</strong> {{ $order->number }}<br>
                <strong>Items:</strong> {{ $order->items->sum('qty') }}<br>
                <strong>Payment:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}<br>
                @if($order->courier)<strong>Courier:</strong> {{ ucfirst($order->courier) }}<br>@endif
            </div>
        </td>
    </tr>
</table>

@if($order->notes)
<div style="background:#FFFDE7;border:1px solid #FFF9C4;border-radius:4px;padding:10px 14px;margin-bottom:16px;font-size:12px;">
    <strong>Customer Note:</strong> {{ $order->notes }}
</div>
@endif

<table class="items-table">
    <thead>
        <tr>
            <th style="width:30px;">#</th>
            <th>Item</th>
            <th class="center" style="width:60px;">Qty</th>
            <th style="width:50px;">Packed</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $i => $item)
        <tr>
            <td style="color:#999;">{{ $i + 1 }}</td>
            <td>
                <div class="item-name">{{ $item->name }}</div>
                @if($item->weight)<div class="item-weight">{{ $item->weight }}</div>@endif
            </td>
            <td class="center" style="font-weight:600;">{{ $item->qty }}</td>
            <td class="center"><span class="check-box"></span></td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="font-size:12px;color:#666;margin-top:16px;">
    <strong>Total Items:</strong> {{ $order->items->sum('qty') }}
</div>

<div class="footer">
    Packed by: ________________________ &nbsp;&nbsp; Date: ________________________
</div>

</body>
</html>
