<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = [
        'email',
        'is_active',
        'is_read',
        'token',
        'subscribed_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_read' => 'boolean',
        'subscribed_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
}
