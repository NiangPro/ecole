<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modèle Notification
 * 
 * Gère les notifications in-app pour les utilisateurs
 * Types supportés: nouveau_document, expiration_telechargement, reduction, system, comment, reply, favorite
 */
class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'is_read',
        'icon',
        'color',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Créer une notification
     * 
     * @param int $userId ID de l'utilisateur
     * @param string $type Type de notification
     * @param string $title Titre de la notification
     * @param string $message Message de la notification
     * @param string|null $link Lien vers la ressource
     * @param string|null $icon Icône FontAwesome
     * @param string|null $color Couleur de la notification
     * @return self
     */
    public static function createNotification(
        $userId, 
        $type, 
        $title, 
        $message, 
        $link = null, 
        $icon = null, 
        $color = null
    ): self {
        // Définir l'icône par défaut selon le type
        if (!$icon) {
            $icon = self::getDefaultIcon($type);
        }
        
        // Définir la couleur par défaut selon le type
        if (!$color) {
            $color = self::getDefaultColor($type);
        }
        
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'icon' => $icon,
            'color' => $color,
            'is_read' => false,
        ]);
    }

    /**
     * Obtenir l'icône par défaut selon le type
     */
    protected static function getDefaultIcon($type): string
    {
        return match($type) {
            'nouveau_document' => 'fa-file-alt',
            'expiration_telechargement' => 'fa-clock',
            'reduction' => 'fa-tag',
            'system' => 'fa-bell',
            'comment' => 'fa-comment',
            'reply' => 'fa-reply',
            'favorite' => 'fa-heart',
            default => 'fa-bell',
        };
    }

    /**
     * Obtenir la couleur par défaut selon le type
     */
    protected static function getDefaultColor($type): string
    {
        return match($type) {
            'nouveau_document' => '#06b6d4',
            'expiration_telechargement' => '#f59e0b',
            'reduction' => '#10b981',
            'system' => '#64748b',
            'comment' => '#3b82f6',
            'reply' => '#8b5cf6',
            'favorite' => '#ef4444',
            default => '#64748b',
        };
    }

    /**
     * Marquer comme lu
     */
    public function markAsRead(): bool
    {
        return $this->update(['is_read' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public static function markAllAsRead($userId): int
    {
        return self::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Obtenir les notifications non lues
     */
    public static function getUnread($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Compter les notifications non lues
     */
    public static function countUnread($userId): int
    {
        return self::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Scope pour les notifications non lues
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope pour les notifications lues
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope pour un type spécifique
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
