<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mnemonic extends Model
{
    use HasFactory;

    protected $fillable = ['topic', 'description', 'rules'];

    protected $casts = [
        'rules' => 'array',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
