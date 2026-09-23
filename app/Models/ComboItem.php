<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComboItem extends Model
{
    protected $fillable = [
        'combo_id',
        'product_id',
        'product_variation_id',
        'quantity',
        'custom_name',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'sort'     => 'integer',
        ];
    }

    public function combo(): BelongsTo
    {
        return $this->belongsTo(Combo::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }

    public function getUnitPriceAttribute(): int
    {
        if ($this->variation) {
            return (int) $this->variation->price;
        }

        return (int) ($this->product?->price ?? 0);
    }

    public function getLineTotalAttribute(): int
    {
        return $this->unit_price * max(1, (int) $this->quantity);
    }
}
