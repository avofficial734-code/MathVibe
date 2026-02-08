<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'choices',
        'correct_answer',
        'topic',
        'difficulty',
        'mnemonic_id',
    ];

    protected $casts = [
        'choices' => 'array',
    ];

    public function mnemonic()
    {
        return $this->belongsTo(Mnemonic::class);
    }

    public function dailyChallenges()
    {
        return $this->hasMany(DailyChallenge::class);
    }

    public function practiceAnswers()
    {
        return $this->hasMany(PracticeAnswer::class);
    }
}
