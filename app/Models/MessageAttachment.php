<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle représentant une pièce jointe d'un message
 * 
 * Une pièce jointe est un fichier attaché à un message.
 * Elle peut être de différents types (image, PDF, document, etc.).
 * 
 * @property int $id
 * @property int $message_id ID du message parent
 * @property string $file_path Chemin du fichier dans le storage
 * @property string $file_name Nom original du fichier
 * @property string|null $file_type Type MIME du fichier
 * @property int|null $file_size Taille du fichier en octets
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Message $message Message parent
 * @property-read string $url URL complète du fichier (accesseur)
 * @property-read string $formatted_size Taille formatée du fichier (accesseur)
 * 
 * @package App\Models
 * @author NiangProgrammeur
 * @since 2024-12-25
 */
class MessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    /**
     * Relation avec le message parent
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $attachment = MessageAttachment::find(1);
     * $message = $attachment->message; // Retourne Message
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * Accesseur pour obtenir l'URL complète du fichier
     * 
     * Si le chemin commence par http:// ou https://, retourne le chemin tel quel.
     * Sinon, retourne l'URL via asset() avec le chemin storage.
     * 
     * @return string URL complète du fichier
     * 
     * @example
     * $attachment = MessageAttachment::find(1);
     * $url = $attachment->url; // Retourne "http://example.com/storage/path/to/file.pdf"
     */
    public function getUrlAttribute()
    {
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }
        return asset('storage/' . $this->file_path);
    }

    /**
     * Accesseur pour obtenir la taille formatée du fichier
     * 
     * Convertit la taille en octets en format lisible (B, KB, MB, GB).
     * 
     * @return string Taille formatée (ex: "1.5 MB")
     * 
     * @example
     * $attachment = MessageAttachment::find(1);
     * $size = $attachment->formatted_size; // Retourne "1.5 MB" ou "N/A"
     */
    public function getFormattedSizeAttribute()
    {
        if (!$this->file_size) {
            return 'N/A';
        }
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }
}
