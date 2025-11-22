<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Génère l'URL WhatsApp pour répondre au message
     */
    public function getWhatsAppUrl()
    {
        if (!$this->phone) {
            return '#';
        }

        // Nettoyer le numéro pour WhatsApp (garder uniquement les chiffres)
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->phone);
        
        // Message pré-rempli personnalisé
        $message = "Bonjour {$this->name},%0A%0A";
        $message .= "Merci pour votre message concernant : {$this->subject}%0A%0A";
        $message .= "Je vous réponds concernant votre demande.%0A%0A";
        $message .= "Cordialement";
        
        return "https://wa.me/{$cleanPhone}?text=" . $message;
    }
}
