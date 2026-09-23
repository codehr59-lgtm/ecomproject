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
            'page' => $this->reference_id
                ? route('page.show', optional(Page::find($this->reference_id))->slug ?? '#')
                : '#',
            'category' => $this->reference_id
                ? route('category', optional(Category::find($this->reference_id))->slug ?? '#')
                : '#',
            default => $this->url ?: '#',
        };
    }
}
