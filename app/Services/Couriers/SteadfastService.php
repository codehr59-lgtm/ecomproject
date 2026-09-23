<?php

namespace App\Services\Couriers;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SteadfastService
{
    private string $apiKey;
    private string $apiSecret;

    public function __construct()
    {
        $this->apiKey    = config('couriers.steadfast.api_key', '');
        $this->apiSecret = config('couriers.steadfast.api_secret', '');
    }

    public function isConfigured(): bool
    {
        return $this->apiKey !== '' && $this->apiSecret !== '';
    }

    private function baseUrl(): string
    {
        return 'https://portal.steadfast.com.bd/api/v1';
    }

    private function headers(): array
    {
        return [
            'Api-Key'      => $this->apiKey,
            'Secret-Key'   => $this->apiSecret,
            'Content-Type' => 'application/json',
        ];
    }

    public function createOrder(Order $order): array
    {
        $response = Http::timeout(30)
            ->withHeaders($this->headers())
            ->post($this->baseUrl() . '/create_order', [
                'invoice'        => $order->number,
                'recipient_name' => $order->customer_name,
                'recipient_phone'=> $order->customer_phone,
                'recipient_address' => trim($order->address_line . ', ' . $order->thana . ', ' . $order->city),
                'cod_amount'     => $order->payment_method === 'cod' ? $order->total : 0,
                'note'           => $order->notes ?? '',
            ]);

        $body = $response->json();

        if (! $response->successful() || ($body['status'] ?? 0) !== 200) {
            throw new RuntimeException('Steadfast order creation failed: ' . $response->body());
        }

        return $body['data'] ?? [];
    }

    public function bulkCreateOrders(array $orders): array
    {
        $items = [];
        foreach ($orders as $order) {
            $items[] = [
                'invoice'           => $order->number,
                'recipient_name'    => $order->customer_name,
                'recipient_phone'   => $order->customer_phone,
                'recipient_address' => trim($order->address_line . ', ' . $order->thana . ', ' . $order->city),
                'cod_amount'        => $order->payment_method === 'cod' ? $order->total : 0,
                'note'              => $order->notes ?? '',
            ];
        }

        $response = Http::timeout(60)
            ->withHeaders($this->headers())
            ->post($this->baseUrl() . '/create_order/bulk-order', $items);

        return $response->json() ?? [];
    }

    public function getOrderStatus(string $consignmentId): array
    {
        $response = Http::timeout(30)
            ->withHeaders($this->headers())
            ->get($this->baseUrl() . '/status_by_cid/' . $consignmentId);

        return $response->json('data') ?? [];
    }

    public function getOrderStatusByInvoice(string $invoice): array
    {
        $response = Http::timeout(30)
            ->withHeaders($this->headers())
            ->get($this->baseUrl() . '/status_by_invoice/' . $invoice);

        return $response->json('data') ?? [];
    }

    public function getBalance(): array
    {
        $response = Http::timeout(30)
            ->withHeaders($this->headers())
            ->get($this->baseUrl() . '/get_balance');

        return $response->json('data') ?? [];
    }
}
