<?php

namespace App\Models;

use App\Mail\OrderConfirmation;
use App\Mail\OrderStatusUpdated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;

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
        'admin_notes',
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

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderByDesc('changed_at');
    }

    public function returnRequests(): HasMany
    {
        return $this->hasMany(ReturnRequest::class)->orderByDesc('created_at');
    }

    public function cancelAndRestoreStock(): void
    {
        if ($this->status === 'cancelled') {
            return;
        }

        foreach ($this->items as $item) {
            Product::where('id', $item->product_id)->increment('stock', $item->qty);
        }

        $this->update(['status' => 'cancelled']);
    }

    // ── Auto-log status changes ─────────────────────────────────────────

    protected static function booted(): void
    {
        static::created(function (Order $order) {
            if ($order->customer_email) {
                try {
                    Mail::to($order->customer_email)->queue(new OrderConfirmation($order));
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            AdminNotification::notify(
                "New Order #{$order->number}",
                "{$order->customer_name} placed an order for ৳" . number_format($order->total) . " ({$order->payment_method})",
                'success',
                "/admin/orders/{$order->id}",
            );
        });

        static::updating(function (Order $order) {
            $statusChanged  = $order->isDirty('status');
            $paymentChanged = $order->isDirty('payment_status');

            if ($statusChanged || $paymentChanged) {
                $order->statusHistories()->create([
                    'old_status'         => $statusChanged ? $order->getOriginal('status') : null,
                    'new_status'         => $statusChanged ? $order->status : $order->getOriginal('status'),
                    'old_payment_status' => $paymentChanged ? $order->getOriginal('payment_status') : null,
                    'new_payment_status' => $paymentChanged ? $order->payment_status : null,
                    'changed_by'         => auth()->user()?->name ?? 'System',
                    'changed_at'         => now(),
                ]);

                if ($statusChanged && $order->customer_email) {
                    try {
                        Mail::to($order->customer_email)->queue(
                            new OrderStatusUpdated($order, $order->getOriginal('status'), $order->status)
                        );
                    } catch (\Throwable $e) {
                        report($e);
                    }
                }
            }
        });
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
