<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'title', 'content', 'image', 'button_text', 'button_url',
        'trigger', 'delay_seconds', 'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'delay_seconds' => 'integer',
        'start_date'    => 'date',
        'end_date'      => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }
}
