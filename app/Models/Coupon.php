<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend',
        'expires_at',
        'usage_limit',
        'used',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value'       => 'integer',
            'min_spend'   => 'integer',
            'usage_limit' => 'integer',
            'used'        => 'integer',
            'is_active'   => 'boolean',
            'expires_at'  => 'date',
        ];
    }
}
