<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Support\PaymentConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class BkashService
{
    private string $appKey;
    private string $appSecret;
    private string $username;
    private string $password;
    private bool   $sandbox;

    public function __construct()
    {
        $creds           = PaymentConfig::bkash();
        $this->appKey    = $creds['app_key'];
        $this->appSecret = $creds['app_secret'];
        $this->username  = $creds['username'];
        $this->password  = $creds['password'];
        $this->sandbox   = $creds['sandbox'];
    }

    public function isConfigured(): bool
    {
        return $this->appKey !== ''
            && $this->appSecret !== ''
            && $this->username !== ''
            && $this->password !== '';
    }

    /**
     * Is this method available (enabled AND configured)?
     */
    public function isAvailable(): bool
    {
        return PaymentConfig::enabled('bkash') && $this->isConfigured();
    }

    private function baseUrl(): string
    {
        return $this->sandbox
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout'
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout';
    }

    /**
     * Obtain (or return cached) id_token from bKash grant-token endpoint.
     */
    public function grantToken(): string
    {
        $cacheKey = 'bkash_id_token_' . md5($this->appKey);

        return Cache::remember($cacheKey, 3500, function () {
            $response = Http::timeout(30)
                ->withHeaders([
                    'username'    => $this->username,
                    'password'    => $this->password,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl() . '/token/grant', [
                    'app_key'    => $this->appKey,
                    'app_secret' => $this->appSecret,
                ]);

            $body = $response->json();

            if (! $response->successful() || empty($body['id_token'])) {
                throw new RuntimeException('bKash token grant failed: ' . $response->body());
            }

            return $body['id_token'];
        });
    }

    /**
     * Create a bKash payment and return the full response including bkashURL.
     */
    public function createPayment(Order $order): array
    {
        $token = $this->grantToken();

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => $token,
                'X-APP-Key'     => $this->appKey,
                'Content-Type'  => 'application/json',
            ])
            ->post($this->baseUrl() . '/create', [
                'mode'                  => '0011',
                'payerReference'        => $order->customer_phone,
                'callbackURL'           => route('payment.bkash.callback'),
                'amount'                => (string) $order->total,
                'currency'              => 'BDT',
                'intent'                => 'sale',
                'merchantInvoiceNumber' => $order->number,
            ]);

        $body = $response->json();

        if (! $response->successful() || empty($body['bkashURL'])) {
            throw new RuntimeException('bKash createPayment failed: ' . $response->body());
        }

        return $body;
    }

    /**
     * Execute a bKash payment after the customer completes payment on bKash UI.
     */
    public function executePayment(string $paymentID): array
    {
        $token = $this->grantToken();

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => $token,
                'X-APP-Key'     => $this->appKey,
                'Content-Type'  => 'application/json',
            ])
            ->post($this->baseUrl() . '/execute', [
                'paymentID' => $paymentID,
            ]);

        $body = $response->json();

        if (! $response->successful()) {
            throw new RuntimeException('bKash executePayment failed: ' . $response->body());
        }

        return $body;
    }

    /**
     * Query the status of a bKash payment.
     */
    public function queryPayment(string $paymentID): array
    {
        $token = $this->grantToken();

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => $token,
                'X-APP-Key'     => $this->appKey,
                'Content-Type'  => 'application/json',
            ])
            ->post($this->baseUrl() . '/payment/status', [
                'paymentID' => $paymentID,
            ]);

        return $response->json() ?? [];
    }
}
