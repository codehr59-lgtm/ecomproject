<?php

namespace App\Support;

use App\Models\Setting;

class PaymentConfig
{
    private const ORDER = ['cod', 'bkash', 'nagad', 'rocket', 'sslcommerz'];

    private const DEFAULTS = [
        'cod'        => true,
        'bkash'      => false,
        'nagad'      => false,
        'rocket'     => false,
        'sslcommerz' => false,
    ];

    public static function enabled(string $method): bool
    {
        $default = self::DEFAULTS[$method] ?? false;
        $value   = Setting::get($method . '_enabled');

        if ($value === null) {
            return $default;
        }

        return (bool) $value;
    }

    public static function enabledMethods(): array
    {
        return array_values(array_filter(self::ORDER, fn ($m) => self::enabled($m)));
    }

    public static function bkash(): array
    {
        return [
            'app_key'    => self::dbOrConfig('bkash_app_key',    config('payments.bkash.app_key', '')),
            'app_secret' => self::dbSecretOrConfig('bkash_app_secret', config('payments.bkash.app_secret', '')),
            'username'   => self::dbOrConfig('bkash_username',   config('payments.bkash.username', '')),
            'password'   => self::dbSecretOrConfig('bkash_password',   config('payments.bkash.password', '')),
            'sandbox'    => self::dbBoolOrConfig('bkash_sandbox',  config('payments.bkash.sandbox', true)),
        ];
    }

    public static function sslcommerz(): array
    {
        return [
            'store_id'       => self::dbOrConfig('sslcommerz_store_id',       config('payments.sslcommerz.store_id', '')),
            'store_password' => self::dbSecretOrConfig('sslcommerz_store_password', config('payments.sslcommerz.store_password', '')),
            'sandbox'        => self::dbBoolOrConfig('sslcommerz_sandbox',     config('payments.sslcommerz.sandbox', true)),
        ];
    }

    public static function nagad(): array
    {
        return [
            'merchant_id'  => self::dbOrConfig('nagad_merchant_id',  config('payments.nagad.merchant_id', '')),
            'merchant_key' => self::dbSecretOrConfig('nagad_merchant_key', config('payments.nagad.merchant_key', '')),
            'sandbox'      => self::dbBoolOrConfig('nagad_sandbox',  config('payments.nagad.sandbox', true)),
        ];
    }

    public static function rocket(): array
    {
        return [
            'merchant_id'       => self::dbOrConfig('rocket_merchant_id',       config('payments.rocket.merchant_id', '')),
            'merchant_password' => self::dbSecretOrConfig('rocket_merchant_password', config('payments.rocket.merchant_password', '')),
            'sandbox'           => self::dbBoolOrConfig('rocket_sandbox',       config('payments.rocket.sandbox', true)),
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private static function dbOrConfig(string $key, mixed $configDefault): string
    {
        $val = Setting::get($key);
        return ($val !== null && $val !== '') ? (string) $val : (string) $configDefault;
    }

    private static function dbSecretOrConfig(string $key, mixed $configDefault): string
    {
        $val = Setting::secret($key);
        return ($val !== null && $val !== '') ? (string) $val : (string) $configDefault;
    }

    private static function dbBoolOrConfig(string $key, mixed $configDefault): bool
    {
        $val = Setting::get($key);
        return ($val !== null) ? (bool) $val : (bool) $configDefault;
    }
}
