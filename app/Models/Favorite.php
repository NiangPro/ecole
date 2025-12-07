<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'favoritable_type',
        'favoritable_slug',
        'favoritable_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si un favori existe
     */
    public static function exists($userId, $type, $slug): bool
    {
        return self::where('user_id', $userId)
            ->where('favoritable_type', $type)
            ->where('favoritable_slug', $slug)
            ->exists();
    }

    /**
     * Ajouter un favori
     */
    public static function add($userId, $type, $slug, $name): self
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'favoritable_type' => $type,
            'favoritable_slug' => $slug,
        ], [
            'favoritable_name' => $name,
        ]);
    }

    /**
     * Retirer un favori
     */
    public static function remove($userId, $type, $slug): bool
    {
        return self::where('user_id', $userId)
            ->where('favoritable_type', $type)
            ->where('favoritable_slug', $slug)
            ->delete();
    }
}
