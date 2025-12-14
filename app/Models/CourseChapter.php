<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

class CourseChapter extends Model
{
    protected $fillable = [
        'paid_course_id',
        'title',
        'title_fr',
        'title_en',
        'description',
        'description_fr',
        'description_en',
        'content',
        'content_fr',
        'content_en',
        'order',
        'duration_minutes'
    ];

    protected $casts = [
        'order' => 'integer',
        'duration_minutes' => 'integer'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(PaidCourse::class, 'paid_course_id');
    }

    // Accessor for localized title
    public function getLocalizedTitleAttribute(): ?string
    {
        $locale = App::getLocale();
        $title = $this->{'title_' . $locale};
        return $title ?? $this->title_fr ?? $this->title; // Fallback to French, then default
    }

    // Accessor for localized description
    public function getLocalizedDescriptionAttribute(): ?string
    {
        $locale = App::getLocale();
        $description = $this->{'description_' . $locale};
        return $description ?? $this->description_fr ?? $this->description; // Fallback to French, then default
    }

    // Accessor for localized content
    public function getLocalizedContentAttribute(): ?string
    {
        $locale = App::getLocale();
        $content = $this->{'content_' . $locale};
        return $content ?? $this->content_fr ?? $this->content; // Fallback to French, then default
    }
}
