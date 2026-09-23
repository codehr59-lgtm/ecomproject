<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Support\PaymentConfig;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class RocketService
{
    private string $merchantId;
    private string $merchantPassword;
    private bool   $sandbox;

    public function __construct()
    {
        $creds                  = PaymentConfig::rocket();
        $this->merchantId       = $creds['merchant_id'];
        $this->merchantPassword = $creds['merchant_password'];
        $this->sandbox          = $creds['sandbox'];
    }

    public function isConfigured(): bool
    {
        return $this->merchantId !== '' && $this->merchantPassword !== '';
    }

    public function isAvailable(): bool
    {
        return PaymentConfig::enabled('rocket') && $this->isConfigured();
    }

    private function baseUrl(): string
    {
        return $this->sandbox
            ? 'https://sandbox.dutchbanglabank.com/rocket/api'
            : 'https://pay.dutchbanglabank.com/rocket/api';
    }

    public function initiate(Order $order): string
    {
        $response = Http::timeout(30)
            ->asForm()
            ->post($this->baseUrl() . '/create-payment', [
                'merchant_id'       => $this->merchantId,
                'merchant_password' => $this->merchantPassword,
                'amount'            => (string) $order->total,
                'currency'          => 'BDT',
                'order_id'          => $order->number,
                'customer_name'     => $order->customer_name,
                'customer_phone'    => $order->customer_phone,
                'success_url'       => route('payment.rocket.callback', ['status' => 'success']),
                'fail_url'          => route('payment.rocket.callback', ['status' => 'fail']),
                'cancel_url'        => route('payment.rocket.callback', ['status' => 'cancel']),
            ]);

        $body = $response->json();

        if (! $response->successful() || empty($body['redirect_url'])) {
            throw new RuntimeException('Rocket initiation failed: ' . $response->body());
        }

        return $body['redirect_url'];
    }

    public function verify(string $transactionId): array
    {
        $response = Http::timeout(30)
            ->asForm()
            ->post($this->baseUrl() . '/verify-payment', [
                'merchant_id'       => $this->merchantId,
                'merchant_password' => $this->merchantPassword,
                'transaction_id'    => $transactionId,
            ]);

        return $response->json() ?? [];
    }
}
