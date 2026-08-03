<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonPricing extends Model
{
    use HasFactory;

    protected $table = 'addon_pricings';

    protected $fillable = [
        'type',
        'name',
        'description',
        'price',
        'duration_days'
    ];
}
