<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormationChapter extends Model
{
    protected $fillable = [
        'formation_id',
        'title',
        'description',
        'content',
        'order',
        'duration_minutes',
        'views_count',
        'status',
    ];

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class, 'chapter_id');
    }
}
