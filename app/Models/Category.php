<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'slug',
        'name',
        'image',
        'tint',
        'note',
        'sort',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort'      => 'integer',
        ];
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeParents(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    // ── Booted ────────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::saved(function () {
            \App\Support\Catalog::flushCache();
        });

        static::deleted(function () {
            \App\Support\Catalog::flushCache();
        });

        static::deleting(function (Category $category) {
            // Find all affected category IDs (this category + its subcategories)
            $catIds = $category->children()->pluck('id')->push($category->id);

            // Disassociate order_items so foreign key constraint never blocks deletion
            $productIds = Product::whereIn('category_id', $catIds)->pluck('id');
            if ($productIds->isNotEmpty()) {
                OrderItem::whereIn('product_id', $productIds)->update(['product_id' => null]);
            }

            // Delete child categories
            foreach ($category->children as $child) {
                $child->delete();
            }

            // Delete products belonging to this category
            foreach ($category->products as $product) {
                $product->delete();
            }
        });
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

