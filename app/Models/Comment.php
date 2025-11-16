<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'commentable_type',
        'commentable_id',
        'content',
        'parent_id',
        'status',
        'likes',
        'ip_address',
    ];

    protected $casts = [
        'likes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('status', 'approved')->orderBy('created_at', 'asc');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function getAuthorNameAttribute(): string
    {
        return $this->user ? $this->user->name : ($this->name ?? 'Anonyme');
    }

    public function getAuthorEmailAttribute(): ?string
    {
        return $this->user ? $this->user->email : $this->email;
    }
}
