<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'image', 'button_text',
        'button_url', 'is_active', 'sort', 'type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => \App\Support\Catalog::flushCache());
        static::deleted(fn () => \App\Support\Catalog::flushCache());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort');
    }
}
