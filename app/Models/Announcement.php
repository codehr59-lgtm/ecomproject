<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'text', 'link', 'link_text', 'bg_color', 'text_color',
        'is_dismissible', 'is_active', 'start_date', 'end_date', 'sort',
    ];

    protected $casts = [
        'is_dismissible' => 'boolean',
        'is_active'      => 'boolean',
        'start_date'     => 'date',
        'end_date'       => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderBy('sort');
    }
}
