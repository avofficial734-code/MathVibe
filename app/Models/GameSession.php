<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'score',
        'duration',
        'details',
        'completed_at',
    ];

    protected $casts = [
        'details' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function dailyChallenge()
    {
        return $this->hasOne(DailyChallenge::class);
    }
}
