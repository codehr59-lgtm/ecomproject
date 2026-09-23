<x-mail::message>
# Order Confirmed!

Thank you for your order, **{{ $order->customer_name }}**!

**Order Number:** {{ $order->number }}
**Date:** {{ $order->placed_at->format('d M Y, h:i A') }}
**Payment Method:** {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}

---

## Order Summary

@foreach($order->items as $item)
- {{ $item->name }} × {{ $item->qty }} — ৳{{ number_format($item->line_total) }}
@endforeach

| | |
|---|---|
| **Subtotal** | ৳{{ number_format($order->subtotal) }} |
| **Delivery** | {{ $order->delivery === 0 ? 'Free' : '৳' . number_format($order->delivery) }} |
@if($order->discount > 0)
| **Discount** | −৳{{ number_format($order->discount) }} |
@endif
| **Total** | **৳{{ number_format($order->total) }}** |

---

**Delivery Address:**
{{ $order->address_line }}, {{ $order->thana }}, {{ $order->city }}

<x-mail::button :url="route('order.confirmation', $order->number)">
View Order
</x-mail::button>

<x-mail::button :url="route('track') . '?number=' . $order->number">
Track Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
