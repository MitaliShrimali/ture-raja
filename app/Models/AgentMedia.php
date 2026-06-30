<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'type',
        'parent_id',
        'name',
        'file_path',
        'size',
        'mime_type',
    ];

    public function parent()
    {
        return $this->belongsTo(AgentMedia::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AgentMedia::class, 'parent_id');
    }

    public function isFolder()
    {
        return $this->type === 'folder';
    }

    public function isImage()
    {
        return $this->type === 'image';
    }
}
