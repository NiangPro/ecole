<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaidCourseProgress extends Model
{
    protected $table = 'paid_course_progress';

    protected $fillable = [
        'user_id',
        'paid_course_id',
        'last_chapter_id',
        'completed_chapters',
        'progress_percentage',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'completed_chapters' => 'array',
        'progress_percentage' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(PaidCourse::class, 'paid_course_id');
    }

    public function isChapterCompleted(int $chapterId): bool
    {
        return in_array($chapterId, $this->completed_chapters ?? []);
    }

    public function markChapterCompleted(int $chapterId, int $totalChapters): void
    {
        $completed = $this->completed_chapters ?? [];
        if (!in_array($chapterId, $completed)) {
            $completed[] = $chapterId;
            $this->completed_chapters = $completed;
        }

        $this->progress_percentage = $totalChapters > 0
            ? (int) min(100, round((count($completed) / $totalChapters) * 100))
            : 0;

        if ($this->progress_percentage >= 100 && !$this->completed_at) {
            $this->completed_at = now();
        }

        $this->save();
    }

    public static function getOrCreate(int $userId, int $paidCourseId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId, 'paid_course_id' => $paidCourseId],
            ['completed_chapters' => [], 'progress_percentage' => 0, 'started_at' => now()]
        );
    }
}
