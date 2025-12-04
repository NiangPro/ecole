<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGoal extends Model
{
    protected $table = 'user_goals';

    protected $fillable = [
        'user_id',
        'goal_type',
        'title',
        'description',
        'target_value',
        'current_value',
        'unit',
        'deadline',
        'completed',
        'completed_at',
    ];

    protected $casts = [
        'target_value' => 'integer',
        'current_value' => 'integer',
        'completed' => 'boolean',
        'deadline' => 'date',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_value === 0) {
            return 0;
        }
        return min(100, round(($this->current_value / $this->target_value) * 100, 2));
    }

    public function updateProgress(int $value): void
    {
        $this->current_value = min($this->target_value, $value);
        
        if ($this->current_value >= $this->target_value && !$this->completed) {
            $this->completed = true;
            $this->completed_at = now();
        }
        
        $this->save();
    }

    public function isOverdue(): bool
    {
        return $this->deadline && !$this->completed && now()->greaterThan($this->deadline);
    }
}
