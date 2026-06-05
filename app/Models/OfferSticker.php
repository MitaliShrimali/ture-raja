<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferSticker extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link',
        'status',
        'bg_color'
    ];
}
