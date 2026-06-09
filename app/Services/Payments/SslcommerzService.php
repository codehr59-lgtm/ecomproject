<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SslcommerzService
{
    private string $storeId;
    private string $storePassword;
    private bool   $sandbox;

    public function __construct()
    {
        $this->storeId       = (string) config('payments.sslcommerz.store_id', '');
        $this->storePassword = (string) config('payments.sslcommerz.store_password', '');
        $this->sandbox       = (bool)   config('payments.sslcommerz.sandbox', true);
    }

    public function isConfigured(): bool
    {
        return $this->storeId !== '' && $this->storePassword !== '';
    }

    private function baseUrl(): string
    {
        return $this->sandbox
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';
    }

    /**
     * Initiate a payment session and return the gateway redirect URL.
     *
     * @throws RuntimeException if the gateway returns a non-success status.
     */
    public function initiate(Order $order): string
    {
        $params = [
            'store_id'        => $this->storeId,
            'store_passwd'    => $this->storePassword,
            'total_amount'    => $order->total,
            'currency'        => 'BDT',
            'tran_id'         => $order->number,
            'success_url'     => route('payment.sslcommerz.success'),
            'fail_url'        => route('payment.sslcommerz.fail'),
            'cancel_url'      => route('payment.sslcommerz.cancel'),
            'ipn_url'         => route('payment.sslcommerz.ipn'),
            'cus_name'        => $order->customer_name,
            'cus_email'       => $order->customer_email ?? 'noreply@shuvo.com.bd',
            'cus_phone'       => $order->customer_phone,
            'cus_add1'        => $order->address_line,
            'cus_city'        => $order->city,
            'cus_country'     => 'Bangladesh',
            'product_name'    => 'Shuvo Order',
            'product_category' => 'grocery',
            'product_profile' => 'general',
            'shipping_method' => 'Courier',
            'num_of_item'     => $order->items()->count(),
            'ship_name'       => $order->customer_name,
            'ship_add1'       => $order->address_line,
            'ship_city'       => $order->city,
            'ship_country'    => 'Bangladesh',
        ];

        $response = Http::asForm()
            ->timeout(30)
            ->post($this->baseUrl() . '/gwprocess/v4/api.php', $params);

        $body = $response->json();

        if (! $response->successful() || ($body['status'] ?? '') !== 'SUCCESS') {
            throw new RuntimeException('SSLCommerz initiation failed: ' . ($body['failedreason'] ?? $response->body()));
        }

        return $body['GatewayPageURL'];
    }

    /**
     * Validate a success callback payload against SSLCommerz validation API.
     * Returns true if valid + amount matches + tran_id matches.
     */
    public function validate(array $payload, Order $order): bool
    {
        $valId = $payload['val_id'] ?? '';

        if (empty($valId)) {
            return false;
        }

        $response = Http::timeout(30)->get(
            $this->baseUrl() . '/validator/api/validationserverAPI.php',
            [
                'val_id'       => $valId,
                'store_id'     => $this->storeId,
                'store_passwd' => $this->storePassword,
                'format'       => 'json',
            ]
        );

        $body = $response->json();

        if (! $response->successful()) {
            return false;
        }

        $status = $body['status'] ?? '';
        if (! in_array($status, ['VALID', 'VALIDATED'], true)) {
            return false;
        }

        // Guard amount tampering (within ±1 BDT tolerance for float math)
        $gatewayAmount = (float) ($body['amount'] ?? 0);
        if (abs($gatewayAmount - $order->total) > 1) {
            return false;
        }

        // Guard tran_id mismatch
        if (($body['tran_id'] ?? '') !== $order->number) {
            return false;
        }

        return true;
    }
}
