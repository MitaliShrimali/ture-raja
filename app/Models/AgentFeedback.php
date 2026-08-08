<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentFeedback extends Model
{
    use HasFactory;

    protected $table = 'agent_feedback';

    protected $fillable = [
        'agent_id',
        'customer_name',
        'rating',
        'message',
        'image_path',
        'package_id',
    ];
}
