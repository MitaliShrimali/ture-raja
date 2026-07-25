<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenPosition extends Model
{
    protected $fillable = [
        'title',
        'department_id',
        'locations',
        'experience',
        'job_type',
        'salary',
        'status'
    ];

    protected $casts = [
        'locations' => 'array'
    ];

    public function department()
    {
        return $this->belongsTo(JobDepartment::class, 'department_id');
    }
}
