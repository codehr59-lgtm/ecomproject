<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'weight',
        'price',
        'old_price',
        'category_id',
        'brand_id',
        'badge',
        'rating',
        'reviews',
        'blurb',
        'certified',
        'stock',
        'is_active',
        'image',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'integer',
            'old_price' => 'integer',
            'reviews'   => 'integer',
            'stock'     => 'integer',
            'rating'    => 'float',
            'certified' => 'boolean',
            'is_active' => 'boolean',
            'sort'      => 'integer',
        ];
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ── Array shape for Blade views ───────────────────────────────────────

    /**
     * Return the exact array shape consumed by every Blade view.
     * Keys: id, name, weight, price, old_price, cat, badge, rating, reviews, blurb, certified.
     * `cat` is the category slug (e.g. 'honey'), matching old config['cat'] values.
     *
     * IMPORTANT: category must already be loaded to avoid N+1.
     */
    public function toCardArray(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'weight'    => $this->weight,
            'price'     => (int) $this->price,
            'old_price' => $this->old_price !== null ? (int) $this->old_price : null,
            'cat'       => $this->category->slug,
            'badge'     => $this->badge,
            'rating'    => (float) $this->rating,
            'reviews'   => (int) $this->reviews,
            'blurb'     => $this->blurb,
            'certified' => (bool) $this->certified,
        ];
    }
}
