<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Combo extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'badge',
        'short_description',
        'description',
        'price',
        'original_price',
        'stock',
        'is_active',
        'is_featured',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'price'          => 'integer',
            'original_price' => 'integer',
            'stock'          => 'integer',
            'is_active'      => 'boolean',
            'is_featured'    => 'boolean',
            'sort'           => 'integer',
        ];
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function items(): HasMany
    {
        return $this->hasMany(ComboItem::class)->orderBy('sort');
    }

    // ── Accessors & Helpers ───────────────────────────────────────────────

    /**
     * Calculate savings in BDT
     */
    public function getSavingsAmountAttribute(): int
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return $this->original_price - $this->price;
        }

        return 0;
    }

    /**
     * Calculate savings percentage
     */
    public function getSavingsPercentAttribute(): int
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return (int) round((1 - ($this->price / $this->original_price)) * 100);
        }

        return 0;
    }

    /**
     * Returns a string summary of included items (e.g. "Honey + Ghee + Dates")
     */
    public function getItemsSummaryAttribute(): string
    {
        if (! $this->relationLoaded('items')) {
            $this->load('items.product');
        }

        return $this->items->map(function ($item) {
            $qty = $item->quantity > 1 ? "{$item->quantity}x " : '';
            $name = $item->custom_name ?: ($item->product?->name ?? 'Product');
            return "{$qty}{$name}";
        })->implode(' + ');
    }

    /**
     * Compute the sum of current prices of all included items
     */
    public function calculateOriginalPrice(): int
    {
        if (! $this->relationLoaded('items')) {
            $this->load(['items.product', 'items.variation']);
        }

        return (int) $this->items->sum(function ($item) {
            $unitPrice = $item->variation
                ? (int) $item->variation->price
                : (int) ($item->product?->price ?? 0);

            return $unitPrice * max(1, $item->quantity);
        });
    }

    /**
     * Format array for cards in views
     */
    public function toCardArray(): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'image'          => $this->image,
            'badge'          => $this->badge ?: ($this->savings_percent > 0 ? "Save {$this->savings_percent}%" : 'COMBO'),
            'price'          => $this->price,
            'old_price'      => $this->original_price,
            'savings_amount' => $this->savings_amount,
            'savings_pct'    => $this->savings_percent,
            'items'          => $this->items_summary,
            'stock'          => $this->stock,
        ];
    }
}
