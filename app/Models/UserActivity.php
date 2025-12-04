<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    protected $table = 'user_activities';

    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_name',
        'activity_slug',
        'activity_data',
    ];

    protected $casts = [
        'activity_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $userId, string $type, string $name, ?string $slug = null, ?array $data = null): self
    {
        return self::create([
            'user_id' => $userId,
            'activity_type' => $type,
            'activity_name' => $name,
            'activity_slug' => $slug,
            'activity_data' => $data,
        ]);
    }
}
