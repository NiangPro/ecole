<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Document;
use App\Models\DocumentPurchase;
use App\Models\User;
use Carbon\Carbon;

/**
 * Service pour gérer les notifications in-app
 */
class NotificationService
{
    /**
     * Créer une notification pour un nouveau document
     * 
     * @param Document $document Le document nouvellement publié
     * @return void
     */
    public static function notifyNewDocument(Document $document): void
    {
        // Notifier tous les utilisateurs actifs qui ont acheté des documents similaires
        $users = User::where('is_active', true)
            ->whereHas('documentPurchases', function ($query) use ($document) {
                $query->where('status', 'completed');
            })
            ->get();

        foreach ($users as $user) {
            Notification::createNotification(
                $user->id,
                'nouveau_document',
                'Nouveau document disponible',
                "Un nouveau document a été publié : {$document->title}",
                route('documents.show', $document->slug),
                'fa-file-alt',
                '#06b6d4'
            );
        }
    }

    /**
     * Créer une notification de rappel d'expiration de téléchargement
     * 
     * @param DocumentPurchase $purchase L'achat dont le téléchargement expire bientôt
     * @return void
     */
    public static function notifyDownloadExpiration(DocumentPurchase $purchase): void
    {
        $user = $purchase->user;
        if (!$user) {
            return;
        }

        $document = $purchase->document;
        if (!$document) {
            return;
        }

        // Calculer les jours restants
        $expiresAt = $purchase->created_at->addDays($purchase->download_limit ?? 30);
        $daysRemaining = now()->diffInDays($expiresAt, false);

        if ($daysRemaining <= 3 && $daysRemaining > 0) {
            Notification::createNotification(
                $user->id,
                'expiration_telechargement',
                'Rappel : Téléchargement expire bientôt',
                "Votre accès au document \"{$document->title}\" expire dans {$daysRemaining} jour(s). Téléchargez-le maintenant !",
                route('documents.show', $document->slug),
                'fa-clock',
                '#f59e0b'
            );
        }
    }

    /**
     * Créer une notification de réduction
     * 
     * @param Document $document Le document en réduction
     * @param float $oldPrice L'ancien prix
     * @param float $newPrice Le nouveau prix
     * @return void
     */
    public static function notifyDiscount(Document $document, float $oldPrice, float $newPrice): void
    {
        // Notifier les utilisateurs qui ont ce document dans leur wishlist
        $users = User::whereHas('documentWishlist', function ($query) use ($document) {
            $query->where('document_id', $document->id);
        })->get();

        $discount = $oldPrice - $newPrice;
        $discountPercent = round(($discount / $oldPrice) * 100, 0);
        $discountFormatted = number_format($discount, 0, ',', ' ');

        foreach ($users as $user) {
            Notification::createNotification(
                $user->id,
                'reduction',
                'Réduction disponible !',
                "Le document \"{$document->title}\" est en réduction : {$discountPercent}% de réduction ({$discountFormatted} FCFA économisés)",
                route('documents.show', $document->slug),
                'fa-tag',
                '#10b981'
            );
        }
    }

    /**
     * Vérifier et envoyer les notifications d'expiration de téléchargement
     * Cette méthode doit être appelée via une commande cron
     * 
     * @return int Nombre de notifications créées
     */
    public static function checkDownloadExpirations(): int
    {
        $count = 0;
        
        // Récupérer tous les achats actifs
        $purchases = DocumentPurchase::where('status', 'completed')
            ->with(['user', 'document'])
            ->get();

        foreach ($purchases as $purchase) {
            if (!$purchase->user || !$purchase->document) {
                continue;
            }

            $expiresAt = $purchase->created_at->addDays($purchase->download_limit ?? 30);
            $daysRemaining = now()->diffInDays($expiresAt, false);

            // Notifier si expiration dans 3 jours ou moins
            if ($daysRemaining <= 3 && $daysRemaining > 0) {
                // Vérifier si une notification n'a pas déjà été envoyée récemment
                $existingNotification = Notification::where('user_id', $purchase->user_id)
                    ->where('type', 'expiration_telechargement')
                    ->where('link', route('documents.show', $purchase->document->slug))
                    ->where('created_at', '>', now()->subDays(1))
                    ->exists();

                if (!$existingNotification) {
                    self::notifyDownloadExpiration($purchase);
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Notifier un utilisateur spécifique
     * 
     * @param int $userId ID de l'utilisateur
     * @param string $type Type de notification
     * @param string $title Titre
     * @param string $message Message
     * @param string|null $link Lien
     * @param string|null $icon Icône
     * @param string|null $color Couleur
     * @return Notification
     */
    public static function notifyUser(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $link = null,
        ?string $icon = null,
        ?string $color = null
    ): Notification {
        return Notification::createNotification(
            $userId,
            $type,
            $title,
            $message,
            $link,
            $icon,
            $color
        );
    }
}

