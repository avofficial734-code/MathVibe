<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyChallenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'question_id',
        'submitted_answer',
        'is_correct',
        'score',
        'duration',
        'attempt_number',
        'completed_at',
        'metadata',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get today's challenges for a user
     */
    public static function getTodayForUser($userId)
    {
        return self::where('user_id', $userId)
            ->whereDate('created_at', now()->toDateString())
            ->get();
    }

    /**
     * Get today's completion status for a user
     */
    public static function completedTodayForUser($userId)
    {
        return self::where('user_id', $userId)
            ->whereDate('completed_at', now()->toDateString())
            ->exists();
    }

    /**
     * Get today's score for a user
     */
    public static function getTodayScoreForUser($userId)
    {
        $challenge = self::where('user_id', $userId)
            ->whereDate('completed_at', now()->toDateString())
            ->first();

        return $challenge ? $challenge->score : null;
    }
}
