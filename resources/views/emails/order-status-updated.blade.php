<x-mail::message>
# Order Status Updated

Hi **{{ $order->customer_name }}**,

Your order **{{ $order->number }}** has been updated:

**{{ \App\Models\Order::statusLabel($oldStatus) }}** → **{{ \App\Models\Order::statusLabel($newStatus) }}**

@if($newStatus === 'shipped' && $order->courier_tracking)
**Courier:** {{ $order->courier ?? 'Courier' }}
**Tracking:** {{ $order->courier_tracking }}
@endif

<x-mail::button :url="route('track') . '?number=' . $order->number">
Track Your Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
