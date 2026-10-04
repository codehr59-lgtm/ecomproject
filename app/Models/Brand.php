<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Brand extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ── Booted ────────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::saved(fn () => \App\Support\Catalog::flushCache());
        static::deleted(fn () => \App\Support\Catalog::flushCache());
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
