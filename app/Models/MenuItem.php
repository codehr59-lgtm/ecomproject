<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id', 'parent_id', 'label', 'url', 'type',
        'reference_id', 'target', 'icon', 'sort', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort');
    }

    public function resolvedUrl(): string
    {
        return match ($this->type) {
            'page' => $this->reference_id && ($slug = optional(Page::find($this->reference_id))->slug)
                ? route('page.show', $slug)
                : ($this->url ?: '#'),
            'category' => $this->reference_id && ($slug = optional(Category::find($this->reference_id))->slug)
                ? route('category', $slug)
                : ($this->url ?: '#'),
            default => $this->url ?: '#',
        };
    }

    public function getResolvedUrlAttribute(): string
    {
        return $this->resolvedUrl();
    }

    protected static function booted(): void
    {
        static::saved(function (MenuItem $item) {
            if ($item->menu) {
                \Illuminate\Support\Facades\Cache::forget("menu.location.{$item->menu->location}");
            }
        });
        static::deleted(function (MenuItem $item) {
            if ($item->menu) {
                \Illuminate\Support\Facades\Cache::forget("menu.location.{$item->menu->location}");
            }
        });
    }
}
