<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Formation extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'cover_image',
        'chapters_count',
        'duration_hours',
        'rating',
        'reviews_count',
        'views_count',
        'enrollments_count',
        'level',
        'category',
        'what_you_learn',
        'requirements',
        'status',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'what_you_learn' => 'array',
        'requirements' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($formation) {
            if (empty($formation->slug)) {
                $formation->slug = Str::slug($formation->title);
            }
        });
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(FormationChapter::class)->orderBy('order');
    }

    public function quiz(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(FormationProgress::class);
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'formation_id');
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->rating ?? 0;
    }

    public function getUserProgressAttribute()
    {
        if (auth()->check()) {
            return $this->progress()
                ->where('user_id', auth()->id())
                ->first();
        }
        return null;
    }

    public function getCompletionPercentageAttribute(): int
    {
        if (auth()->check()) {
            $progress = $this->progress()
                ->where('user_id', auth()->id())
                ->first();
            
            return $progress ? $progress->completion_percentage : 0;
        }
        return 0;
    }
}
