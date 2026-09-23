<?php

namespace App\Services\Couriers;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PathaoService
{
    private string $clientId;
    private string $clientSecret;
    private string $username;
    private string $password;
    private bool   $sandbox;

    public function __construct()
    {
        $this->clientId     = config('couriers.pathao.client_id', '');
        $this->clientSecret = config('couriers.pathao.client_secret', '');
        $this->username     = config('couriers.pathao.username', '');
        $this->password     = config('couriers.pathao.password', '');
        $this->sandbox      = (bool) config('couriers.pathao.sandbox', true);
    }

    public function isConfigured(): bool
    {
        return $this->clientId !== ''
            && $this->clientSecret !== ''
            && $this->username !== ''
            && $this->password !== '';
    }

    private function baseUrl(): string
    {
        return $this->sandbox
            ? 'https://hermes-api.pathao.com'
            : 'https://api-hermes.pathao.com';
    }

    public function getToken(): string
    {
        $cacheKey = 'pathao_token_' . md5($this->clientId);

        return Cache::remember($cacheKey, 3500, function () {
            $response = Http::timeout(30)->post($this->baseUrl() . '/aladdin/api/v1/issue-token', [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username'      => $this->username,
                'password'      => $this->password,
                'grant_type'    => 'password',
            ]);

            $body = $response->json();

            if (! $response->successful() || empty($body['access_token'])) {
                throw new RuntimeException('Pathao token failed: ' . $response->body());
            }

            return $body['access_token'];
        });
    }

    public function getStores(): array
    {
        $response = Http::timeout(30)
            ->withToken($this->getToken())
            ->get($this->baseUrl() . '/aladdin/api/v1/stores');

        return $response->json('data.data') ?? [];
    }

    public function getCities(): array
    {
        $response = Http::timeout(30)
            ->withToken($this->getToken())
            ->get($this->baseUrl() . '/aladdin/api/v1/countries/1/city-list');

        return $response->json('data.data') ?? [];
    }

    public function getZones(int $cityId): array
    {
        $response = Http::timeout(30)
            ->withToken($this->getToken())
            ->get($this->baseUrl() . '/aladdin/api/v1/cities/' . $cityId . '/zone-list');

        return $response->json('data.data') ?? [];
    }

    public function getAreas(int $zoneId): array
    {
        $response = Http::timeout(30)
            ->withToken($this->getToken())
            ->get($this->baseUrl() . '/aladdin/api/v1/zones/' . $zoneId . '/area-list');

        return $response->json('data.data') ?? [];
    }

    public function createOrder(Order $order, array $params): array
    {
        $totalWeight = $order->items->sum(fn ($item) => ($item->weight ?? 0.5) * $item->qty);

        $payload = [
            'store_id'            => $params['store_id'],
            'merchant_order_id'   => $order->number,
            'recipient_name'      => $order->customer_name,
            'recipient_phone'     => $order->customer_phone,
            'recipient_address'   => $order->address_line,
            'recipient_city'      => $params['recipient_city_id'],
            'recipient_zone'      => $params['recipient_zone_id'],
            'recipient_area'      => $params['recipient_area_id'] ?? null,
            'delivery_type'       => $params['delivery_type'] ?? 48,
            'item_type'           => $params['item_type'] ?? 2,
            'special_instruction' => $order->notes ?? '',
            'item_quantity'       => $order->items->sum('qty'),
            'item_weight'         => max(0.5, round($totalWeight, 2)),
            'amount_to_collect'   => $order->payment_method === 'cod' ? $order->total : 0,
            'item_description'    => 'Order ' . $order->number,
        ];

        $response = Http::timeout(30)
            ->withToken($this->getToken())
            ->post($this->baseUrl() . '/aladdin/api/v1/orders', $payload);

        $body = $response->json();

        if (! $response->successful() || empty($body['data']['consignment_id'])) {
            throw new RuntimeException('Pathao order creation failed: ' . $response->body());
        }

        return $body['data'];
    }

    public function trackOrder(string $consignmentId): array
    {
        $response = Http::timeout(30)
            ->withToken($this->getToken())
            ->get($this->baseUrl() . '/aladdin/api/v1/orders/' . $consignmentId);

        return $response->json('data') ?? [];
    }

    public function priceCalculation(array $params): array
    {
        $response = Http::timeout(30)
            ->withToken($this->getToken())
            ->post($this->baseUrl() . '/aladdin/api/v1/merchant/price-plan', $params);

        return $response->json('data') ?? [];
    }
}
