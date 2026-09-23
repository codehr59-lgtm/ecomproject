<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Support\PaymentConfig;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class NagadService
{
    private string $merchantId;
    private string $merchantKey;
    private bool   $sandbox;

    public function __construct()
    {
        $creds             = PaymentConfig::nagad();
        $this->merchantId  = $creds['merchant_id'];
        $this->merchantKey = $creds['merchant_key'];
        $this->sandbox     = $creds['sandbox'];
    }

    public function isConfigured(): bool
    {
        return $this->merchantId !== '' && $this->merchantKey !== '';
    }

    public function isAvailable(): bool
    {
        return PaymentConfig::enabled('nagad') && $this->isConfigured();
    }

    private function baseUrl(): string
    {
        return $this->sandbox
            ? 'https://sandbox.mynagad.com:10061/remote-payment-gateway-1.0/api/dfs'
            : 'https://api.mynagad.com/api/dfs';
    }

    public function initiate(Order $order): string
    {
        $dateTime  = now()->format('YmdHis');
        $orderId   = $order->number;

        $sensitiveData = json_encode([
            'merchantId' => $this->merchantId,
            'datetime'   => $dateTime,
            'orderId'    => $orderId,
            'challenge'  => bin2hex(random_bytes(16)),
        ]);

        $response = Http::timeout(30)
            ->withHeaders([
                'Content-Type'       => 'application/json',
                'X-KM-Api-Version'   => 'v-0.2.0',
                'X-KM-IP-V4'        => request()->ip(),
                'X-KM-Client-Type'   => 'PC_WEB',
            ])
            ->post($this->baseUrl() . '/check-out/initialize/' . $this->merchantId . '/' . $orderId, [
                'accountNumber' => $this->merchantId,
                'dateTime'      => $dateTime,
                'sensitiveData' => base64_encode($sensitiveData),
            ]);

        $body = $response->json();

        if (! $response->successful() || empty($body['sensitiveData'])) {
            throw new RuntimeException('Nagad initialization failed: ' . $response->body());
        }

        $decrypted = json_decode(base64_decode($body['sensitiveData']), true);
        $paymentRefId = $decrypted['paymentReferenceId'] ?? null;

        if (! $paymentRefId) {
            throw new RuntimeException('Nagad: missing paymentReferenceId');
        }

        $confirmData = json_encode([
            'merchantId'        => $this->merchantId,
            'orderId'           => $orderId,
            'currencyCode'      => '050',
            'amount'            => (string) $order->total,
            'challenge'         => $decrypted['challenge'] ?? '',
        ]);

        $completeResponse = Http::timeout(30)
            ->withHeaders([
                'Content-Type'       => 'application/json',
                'X-KM-Api-Version'   => 'v-0.2.0',
                'X-KM-IP-V4'        => request()->ip(),
                'X-KM-Client-Type'   => 'PC_WEB',
            ])
            ->post($this->baseUrl() . '/check-out/complete/' . $paymentRefId, [
                'sensitiveData'     => base64_encode($confirmData),
                'signature'         => $this->generateSignature($confirmData),
                'merchantCallbackURL' => route('payment.nagad.callback'),
            ]);

        $completeBody = $completeResponse->json();

        if (! $completeResponse->successful() || empty($completeBody['callBackUrl'])) {
            throw new RuntimeException('Nagad checkout failed: ' . $completeResponse->body());
        }

        return $completeBody['callBackUrl'];
    }

    public function verify(string $paymentRefId): array
    {
        $response = Http::timeout(30)
            ->withHeaders([
                'Content-Type'       => 'application/json',
                'X-KM-Api-Version'   => 'v-0.2.0',
                'X-KM-IP-V4'        => request()->ip(),
                'X-KM-Client-Type'   => 'PC_WEB',
            ])
            ->get($this->baseUrl() . '/verify/payment/' . $paymentRefId);

        return $response->json() ?? [];
    }

    private function generateSignature(string $data): string
    {
        return hash_hmac('sha256', $data, $this->merchantKey);
    }
}
