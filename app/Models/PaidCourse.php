<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PaidCourse extends Model
{
    protected $fillable = [
        'title',
        'title_fr',
        'title_en',
        'slug',
        'description',
        'description_fr',
        'description_en',
        'content',
        'content_fr',
        'content_en',
        'cover_image',
        'cover_type',
        'price',
        'currency',
        'discount_price',
        'discount_start',
        'discount_end',
        'status',
        'duration_hours',
        'students_count',
        'rating',
        'reviews_count',
        'what_you_learn',
        'requirements'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'discount_start' => 'date',
        'discount_end' => 'date',
        'rating' => 'decimal:2',
        'what_you_learn' => 'array',
        'requirements' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(CoursePurchase::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(CourseChapter::class)->orderBy('order');
    }

    public function getCurrentPriceAttribute(): float
    {
        if ($this->hasDiscount()) {
            return $this->discount_price;
        }
        return $this->price;
    }

    public function hasDiscount(): bool
    {
        if (!$this->discount_price || !$this->discount_start || !$this->discount_end) {
            return false;
        }
        
        $now = now();
        return $now->between($this->discount_start, $this->discount_end);
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->hasDiscount()) {
            return 0;
        }
        
        // Éviter la division par zéro
        if ($this->price <= 0) {
            return 0;
        }
        
        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    /**
     * Accesseur pour obtenir le titre selon la langue
     */
    public function getLocalizedTitleAttribute(): string
    {
        $locale = app()->getLocale();
        $field = 'title_' . $locale;
        
        if (in_array($locale, ['fr', 'en']) && $this->$field) {
            return $this->$field;
        }
        
        // Fallback sur le titre par défaut
        return $this->title ?? '';
    }

    /**
     * Accesseur pour obtenir la description selon la langue
     */
    public function getLocalizedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        $field = 'description_' . $locale;
        
        if (in_array($locale, ['fr', 'en']) && $this->$field) {
            return $this->$field;
        }
        
        // Fallback sur la description par défaut
        return $this->description;
    }

    /**
     * Accesseur pour obtenir le contenu selon la langue
     */
    public function getLocalizedContentAttribute(): ?string
    {
        $locale = app()->getLocale();
        $field = 'content_' . $locale;
        
        if (in_array($locale, ['fr', 'en']) && $this->$field) {
            return $this->$field;
        }
        
        // Fallback sur le contenu par défaut
        return $this->content;
    }
}



