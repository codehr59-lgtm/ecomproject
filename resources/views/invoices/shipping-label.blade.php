<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Shipping Label {{ $order->number }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 14px;
        color: #000;
        background: #fff;
        padding: 24px;
    }
    .label-box {
        border: 3px solid #000;
        padding: 20px;
        max-width: 400px;
        margin: 0 auto;
    }
    .from-section {
        border-bottom: 2px dashed #999;
        padding-bottom: 14px;
        margin-bottom: 14px;
    }
    .to-section {
        margin-bottom: 14px;
    }
    .section-title {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #666;
        margin-bottom: 6px;
    }
    .name { font-size: 18px; font-weight: 700; }
    .address { font-size: 14px; line-height: 1.5; margin-top: 4px; }
    .phone { font-size: 16px; font-weight: 700; margin-top: 6px; }
    .order-info {
        border-top: 2px solid #000;
        padding-top: 12px;
        margin-top: 12px;
    }
    .order-info table { width: 100%; border-collapse: collapse; }
    .order-info td { padding: 3px 0; font-size: 13px; }
    .order-info td.label { font-weight: 700; width: 120px; }
    .cod-badge {
        display: inline-block;
        background: #000;
        color: #fff;
        font-size: 16px;
        font-weight: 800;
        padding: 6px 14px;
        margin-top: 10px;
        letter-spacing: 0.05em;
    }
</style>
</head>
<body>

<div class="label-box">
    <div class="from-section">
        <div class="section-title">From</div>
        <div style="font-size:14px;font-weight:600;">Shuvo — Organic & Natural</div>
        <div style="font-size:12px;color:#666;">Rampura, Dhaka 1219</div>
    </div>

    <div class="to-section">
        <div class="section-title">Ship To</div>
        <div class="name">{{ $order->customer_name }}</div>
        <div class="address">
            {{ $order->address_line }}<br>
            @if($order->thana){{ $order->thana }}, @endif{{ $order->city }}
        </div>
        <div class="phone">{{ $order->customer_phone }}</div>
    </div>

    <div class="order-info">
        <table>
            <tr>
                <td class="label">Order</td>
                <td>{{ $order->number }}</td>
            </tr>
            <tr>
                <td class="label">Date</td>
                <td>{{ $order->placed_at->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="label">Items</td>
                <td>{{ $order->items->sum('qty') }} item(s)</td>
            </tr>
            <tr>
                <td class="label">Total</td>
                <td style="font-weight:700;">&#2547;{{ number_format($order->total) }}</td>
            </tr>
            @if($order->courier)
            <tr>
                <td class="label">Courier</td>
                <td>{{ ucfirst($order->courier) }}</td>
            </tr>
            @endif
            @if($order->courier_tracking)
            <tr>
                <td class="label">Tracking</td>
                <td>{{ $order->courier_tracking }}</td>
            </tr>
            @endif
        </table>

        @if($order->payment_method === 'cod')
        <div class="cod-badge">COD: &#2547;{{ number_format($order->total) }}</div>
        @endif
    </div>
</div>

</body>
</html>
