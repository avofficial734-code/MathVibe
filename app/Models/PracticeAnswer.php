<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'practice_session_id',
        'question_id',
        'student_answer',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(PracticeSession::class, 'practice_session_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
