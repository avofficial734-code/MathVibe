<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topic',
        'mastery_level',
        'accuracy',
        'average_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
