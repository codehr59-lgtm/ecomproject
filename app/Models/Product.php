<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'slug',
        'sku',
        'name',
        'product_type',
        'weight',
        'price',
        'old_price',
        'category_id',
        'brand_id',
        'badge',
        'rating',
        'reviews',
        'blurb',
        'description',
        'certified',
        'stock',
        'is_active',
        'image',
        'video_url',
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

    // ── Stock Management ──────────────────────────────────────────────────

    public function getStockAttribute(): int
    {
        if (($this->attributes['product_type'] ?? null) === 'variable') {
            $vars = $this->relationLoaded('variations')
                ? $this->variations
                : $this->variations()->get();
            if ($vars->isNotEmpty()) {
                return (int) $vars->sum('stock');
            }
        }

        return (int) ($this->attributes['stock'] ?? 0);
    }

    public function syncStock(): void
    {
        if ($this->product_type === 'variable') {
            $total = (int) $this->variations()->sum('stock');
            $this->newQuery()->where('id', $this->id)->update(['stock' => $total]);
            $this->attributes['stock'] = $total;
        }
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

    public function productReviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class)->orderBy('sort');
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'related_products', 'product_id', 'related_product_id');
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
        $approved = $this->relationLoaded('productReviews')
            ? $this->productReviews->where('approved', true)
            : $this->productReviews()->where('approved', true)->get();
        $reviewCount = $approved->count();
        $avgRating = $reviewCount > 0 ? round($approved->avg('rating'), 1) : 0;

        $price    = (int) $this->price;
        $oldPrice = $this->old_price !== null ? (int) $this->old_price : null;

        if ($this->product_type === 'variable') {
            $vars = $this->relationLoaded('variations')
                ? $this->variations
                : $this->variations()->get();
            if ($vars->count()) {
                $price    = (int) $vars->min('price');
                $maxPrice = (int) $vars->max('price');
                $oldPrice = $maxPrice > $price ? $maxPrice : $oldPrice;
            }
        }

        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'weight'       => $this->weight,
            'price'        => $price,
            'old_price'    => $oldPrice,
            'product_type' => $this->product_type ?? 'simple',
            'cat'          => $this->category?->slug ?? 'all',
            'badge'        => $this->badge,
            'rating'       => $avgRating,
            'reviews'      => $reviewCount,
            'blurb'        => $this->blurb,
            'certified'    => (bool) $this->certified,
            'image'        => $this->image,
            'stock'        => (int) $this->stock,
        ];
    }
}
