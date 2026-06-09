<?php

namespace App\Support;

use App\Models\Setting;

class PaymentConfig
{
    /**
     * Display order for payment methods.
     */
    private const ORDER = ['cod', 'bkash', 'sslcommerz'];

    /**
     * Default enabled state per method (if no DB setting exists).
     */
    private const DEFAULTS = [
        'cod'        => true,
        'bkash'      => false,
        'sslcommerz' => false,
    ];

    /**
     * Is the given payment method enabled?
     */
    public static function enabled(string $method): bool
    {
        $default = self::DEFAULTS[$method] ?? false;
        $value   = Setting::get($method . '_enabled');

        if ($value === null) {
            return $default;
        }

        return (bool) $value;
    }

    /**
     * Return ordered list of enabled method identifiers.
     */
    public static function enabledMethods(): array
    {
        return array_values(array_filter(self::ORDER, fn ($m) => self::enabled($m)));
    }

    /**
     * bKash credentials — DB first, fall back to config/.env.
     */
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

    /**
     * SSLCommerz credentials — DB first, fall back to config/.env.
     */
    public static function sslcommerz(): array
    {
        return [
            'store_id'       => self::dbOrConfig('sslcommerz_store_id',       config('payments.sslcommerz.store_id', '')),
            'store_password' => self::dbSecretOrConfig('sslcommerz_store_password', config('payments.sslcommerz.store_password', '')),
            'sandbox'        => self::dbBoolOrConfig('sslcommerz_sandbox',     config('payments.sslcommerz.sandbox', true)),
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
