<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'name',
        'price',
        'package_limit',
        'duration',
        'features',
        'description',
        'status',
        'gst',
    ];

    public function permissions()
    {
        return $this->hasMany(PlanPermission::class);
    }
}
