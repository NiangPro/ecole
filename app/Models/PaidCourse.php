<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PaidCourse extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image',
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
        
        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }
}



