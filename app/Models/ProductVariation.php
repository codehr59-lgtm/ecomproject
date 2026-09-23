<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'label',
        'price',
        'stock',
        'sku',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted(): void
    {
        static::saved(function (ProductVariation $variation) {
            $variation->product?->syncStock();
        });

        static::deleted(function (ProductVariation $variation) {
            $variation->product?->syncStock();
        });
    }
}
