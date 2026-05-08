<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'title',
        'location',
        'price',
        'rating',
        'reviews',
        'duration',
        'image',
        'category',
        'badge',
        'agent',
    ];

    protected $casts = [
        'agent' => 'array',
        'price' => 'float',
        'rating' => 'float',
    ];
}
