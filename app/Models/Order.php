<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'number',
        'user_id',
        'status',
        'customer_name',
        'customer_phone',
        'customer_email',
        'address_line',
        'city',
        'thana',
        'notes',
        'subtotal',
        'delivery',
        'discount',
        'total',
        'coupon_code',
        'payment_method',
        'payment_status',
        'payment_ref',
        'courier',
        'courier_tracking',
        'placed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'   => 'integer',
            'delivery'   => 'integer',
            'discount'   => 'integer',
            'total'      => 'integer',
            'placed_at'  => 'datetime',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    /**
     * Ordered list of all statuses for the timeline stepper.
     */
    public static function statusSteps(): array
    {
        return ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
    }

    /**
     * Human-readable status labels.
     */
    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'pending'    => 'Order Placed',
            'confirmed'  => 'Confirmed',
            'processing' => 'Processing',
            'shipped'    => 'Shipped',
            'delivered'  => 'Delivered',
            'cancelled'  => 'Cancelled',
            default      => ucfirst($status),
        };
    }

    /**
     * Generate a unique order number like SHV-XXXXXX.
     */
    public static function generateNumber(): string
    {
        do {
            $number = 'SHV-' . strtoupper(substr(md5(uniqid('', true)), 0, 6));
        } while (static::where('number', $number)->exists());

        return $number;
    }
}
