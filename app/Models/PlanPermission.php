<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'permission_key',
        'permission_type',
        'boolean_value',
        'limit_value',
    ];
}
