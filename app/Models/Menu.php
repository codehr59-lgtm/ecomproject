<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = ['name', 'location'];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort');
    }

    public function rootItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort');
    }

    public static function getByLocation(string $location): ?self
    {
        $res = \Illuminate\Support\Facades\Cache::remember("menu.location.{$location}", 1800, function () use ($location) {
            return static::with(['rootItems.children'])->where('location', $location)->first() ?: false;
        });

        return $res ?: null;
    }
}
