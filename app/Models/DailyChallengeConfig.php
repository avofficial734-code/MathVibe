<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyChallengeConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'game_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
