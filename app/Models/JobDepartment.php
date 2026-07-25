<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobDepartment extends Model
{
    protected $fillable = ['name'];

    public function positions()
    {
        return $this->hasMany(OpenPosition::class, 'department_id');
    }
}
