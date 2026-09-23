<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequest extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'status',
        'reason',
        'details',
        'refund_amount',
        'resolution_note',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'refund_amount' => 'integer',
        'resolved_at'   => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
