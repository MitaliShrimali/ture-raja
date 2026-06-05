<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePackage extends Model
{
    protected $fillable = ['type', 'title', 'subtitle', 'image', 'price', 'status'];
}
