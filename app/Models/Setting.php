<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Keys whose values are stored encrypted.
     */
    private static array $secretKeys = [
        'bkash_app_secret',
        'bkash_password',
        'nagad_merchant_key',
        'rocket_merchant_password',
        'sslcommerz_store_password',
        'pathao_client_secret',
        'pathao_password',
        'steadfast_api_secret',
    ];

    /**
     * Get a setting value by key. JSON-decoded if it's a JSON string.
     * Falls back to $default if not found or value is null.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::allCached();

        if (! array_key_exists($key, $all)) {
            return $default;
        }

        $value = $all[$key];

        if ($value === null) {
            return $default;
        }

        // Try JSON decode
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_bool($decoded) || is_int($decoded))) {
            return $decoded;
        }

        return $value;
    }

    /**
     * Get a decrypted secret value by key.
     */
    public static function secret(string $key, mixed $default = null): mixed
    {
        $all = self::allCached();

        if (! array_key_exists($key, $all)) {
            return $default;
        }

        $value = $all[$key];

        if ($value === null || $value === '') {
            return $default;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            // Legacy plain text or failed decryption — return raw
            return $value;
        }
    }

    /**
     * Set a setting value. Arrays/bools will be JSON-encoded.
     * Secret keys are encrypted before storage.
     */
    public static function set(string $key, mixed $value): void
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        } elseif (is_bool($value)) {
            $value = json_encode($value);
        } elseif ($value === null) {
            $value = null;
        } else {
            $value = (string) $value;
        }

        // Encrypt secrets
        if ($value !== null && $value !== '' && in_array($key, self::$secretKeys, true)) {
            $value = Crypt::encryptString($value);
        }

        DB::table('settings')->upsert(
            [['key' => $key, 'value' => $value, 'created_at' => now(), 'updated_at' => now()]],
            ['key'],
            ['value', 'updated_at']
        );

        Cache::forget('app.settings');
    }

    /**
     * Return all settings as a key => raw_value array (cached).
     */
    private static function allCached(): array
    {
        return Cache::rememberForever('app.settings', function () {
            return DB::table('settings')->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Return all settings as decoded values.
     */
    public static function all_settings(): array
    {
        $all = self::allCached();
        $result = [];

        foreach (array_keys($all) as $key) {
            $result[$key] = self::get($key);
        }

        return $result;
    }
}
