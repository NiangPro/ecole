<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Achievement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'image',
        'image_type',
        'link_url',
        'order',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_type === 'internal' && $this->image) {
            return Storage::url($this->image);
        }
        return $this->image;
    }

    /**
     * Scope to get only visible achievements
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope to order by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }
}
