<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseProgress extends Model
{
    protected $table = 'exercise_progress';

    protected $fillable = [
        'user_id',
        'exercise_id',
        'language',
        'completed',
        'score',
        'time_spent_seconds',
        'code_submitted',
        'completed_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'score' => 'integer',
        'time_spent_seconds' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsCompleted(int $score, int $timeSpent, ?string $code = null): void
    {
        $this->completed = true;
        $this->score = $score;
        $this->time_spent_seconds = $timeSpent;
        $this->code_submitted = $code;
        $this->completed_at = now();
        $this->save();
    }
}
