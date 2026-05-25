<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'formation_id',
        'title',
        'description',
        'total_questions',
        'passing_score',
        'time_limit_minutes',
        'shuffle_questions',
        'show_correct_answers',
        'attempts_count',
        'average_score',
        'status',
    ];

    protected $casts = [
        'shuffle_questions' => 'boolean',
        'show_correct_answers' => 'boolean',
        'average_score' => 'decimal:2',
    ];

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function results(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }
}
