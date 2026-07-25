<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerApplication extends Model
{
    protected $fillable = [
        'role',
        'resume_path',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'location',
        'location_other',
        'notice_period',
        'gender',
        'education',
        'total_exp',
        'relevant_exp',
        'current_ctc',
        'expected_ctc',
        'custom_fields'
    ];

    protected $casts = [
        'custom_fields' => 'array'
    ];
}
