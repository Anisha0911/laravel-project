<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
        'file_path',
        'audio_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
