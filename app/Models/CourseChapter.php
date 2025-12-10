<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseChapter extends Model
{
    protected $fillable = [
        'paid_course_id',
        'title',
        'description',
        'content',
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
}
