<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationProgress extends Model
{
    protected $table = 'formation_progress';

    protected $fillable = [
        'user_id',
        'formation_slug',
        'section_id',
        'progress_percentage',
        'started_at',
        'completed_at',
        'completed_sections',
        'time_spent_minutes',
    ];

    protected $casts = [
        'progress_percentage' => 'integer',
        'time_spent_minutes' => 'integer',
        'completed_sections' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markSectionAsCompleted(string $sectionId): void
    {
        $completed = $this->completed_sections ?? [];
        if (!in_array($sectionId, $completed)) {
            $completed[] = $sectionId;
            $this->completed_sections = $completed;
            $this->save();
        }
    }

    public function isSectionCompleted(string $sectionId): bool
    {
        $completed = $this->completed_sections ?? [];
        return in_array($sectionId, $completed);
    }

    public function updateProgress(int $totalSections, int $completedSections): void
    {
        $percentage = $totalSections > 0 ? round(($completedSections / $totalSections) * 100) : 0;
        $this->progress_percentage = min(100, $percentage);
        
        if ($this->progress_percentage === 100 && !$this->completed_at) {
            $this->completed_at = now();
        }
        
        $this->save();
    }

    public function addTimeSpent(int $minutes): void
    {
        $this->time_spent_minutes += $minutes;
        $this->save();
    }

    public static function getOrCreate(int $userId, string $formationSlug): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId, 'formation_slug' => $formationSlug],
            ['progress_percentage' => 0, 'started_at' => now(), 'completed_sections' => []]
        );
    }
}
