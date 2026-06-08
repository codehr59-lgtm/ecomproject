<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'cta',
        'href',
        'position',
        'sort',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort'      => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
