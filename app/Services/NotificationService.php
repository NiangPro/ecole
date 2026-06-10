<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentPurchase;
use App\Models\JobArticle;
use App\Models\Notification;
use App\Models\PushSubscription;
use App\Models\User;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    // ─────────────────────────────────────────────
    // WEB PUSH (navigateur)
    // ─────────────────────────────────────────────

    /**
     * Envoie une notification web push à tous les abonnés.
     */
    public static function sendWebPushToAll(string $title, string $body, string $url = '/', ?string $icon = null): void
    {
        $publicKey  = config('services.vapid.public_key');
        $privateKey = config('services.vapid.private_key');
        $subject    = config('services.vapid.subject');

        if (empty($publicKey) || empty($privateKey)) {
            return;
        }

        $subscriptions = PushSubscription::all();
        if ($subscriptions->isEmpty()) {
            return;
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject'    => $subject,
                'publicKey'  => $publicKey,
                'privateKey' => $privateKey,
            ],
        ]);

        $payload = json_encode([
            'title'   => $title,
            'body'    => $body,
            'icon'    => $icon ?? '/images/logo.png',
            'badge'   => '/images/logo.png',
            'tag'     => 'new-content',
            'requireInteraction' => false,
            'data'    => ['url' => $url],
            'actions' => [
                ['action' => 'open', 'title' => 'Voir'],
            ],
        ]);

        foreach ($subscriptions as $sub) {
            $keys = is_array($sub->keys) ? $sub->keys : json_decode($sub->keys, true);
            if (empty($keys['p256dh']) || empty($keys['auth'])) {
                continue;
            }

            try {
                $webPush->queueNotification(
                    Subscription::create([
                        'endpoint'        => $sub->endpoint,
                        'contentEncoding' => 'aesgcm',
                        'keys'            => $keys,
                    ]),
                    $payload
                );
            } catch (\Throwable $e) {
                Log::warning('WebPush queue error: ' . $e->getMessage());
            }
        }

        foreach ($webPush->flush() as $report) {
            if (!$report->isSuccess()) {
                $endpoint = $report->getRequest()->getUri()->__toString();
                // Supprimer les abonnements expirés / invalides
                if ($report->isSubscriptionExpired()) {
                    PushSubscription::where('endpoint', $endpoint)->delete();
                }
                Log::warning('WebPush send failed: ' . $report->getReason());
            }
        }
    }

    // ─────────────────────────────────────────────
    // IN-APP NOTIFICATIONS (cloche)
    // ─────────────────────────────────────────────

    /**
     * Notifie tous les utilisateurs actifs d'un nouvel article publié.
     */
    public static function notifyNewArticle(JobArticle $article): void
    {
        $users = User::where('is_active', true)->get(['id']);

        foreach ($users as $user) {
            Notification::createNotification(
                $user->id,
                'nouvel_article',
                'Nouvel article publié',
                "Un nouvel article est disponible : {$article->title}",
                route('emplois.article', $article->slug),
                'fa-newspaper',
                '#06b6d4'
            );
        }

        // Web push en parallèle
        self::sendWebPushToAll(
            'Nouvel article — NiangProgrammeur',
            $article->title,
            route('emplois.article', $article->slug),
            '/images/logo.png'
        );
    }

    /**
     * Notifie tous les utilisateurs actifs d'un nouveau document publié.
     */
    public static function notifyNewDocument(Document $document): void
    {
        $users = User::where('is_active', true)->get(['id']);

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

        // Web push en parallèle
        self::sendWebPushToAll(
            'Nouveau document — NiangProgrammeur',
            $document->title,
            route('documents.show', $document->slug),
            '/images/logo.png'
        );
    }

    // ─────────────────────────────────────────────
    // AUTRES NOTIFICATIONS EXISTANTES
    // ─────────────────────────────────────────────

    public static function notifyDownloadExpiration(DocumentPurchase $purchase): void
    {
        $user     = $purchase->user;
        $document = $purchase->document;
        if (!$user || !$document) {
            return;
        }

        $expiresAt     = $purchase->created_at->addDays($purchase->download_limit ?? 30);
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

    public static function notifyDiscount(Document $document, float $oldPrice, float $newPrice): void
    {
        $users           = User::whereHas('documentWishlist', fn($q) => $q->where('document_id', $document->id))->get();
        $discountPercent = round(($oldPrice - $newPrice) / $oldPrice * 100);
        $discountFmt     = number_format($oldPrice - $newPrice, 0, ',', ' ');

        foreach ($users as $user) {
            Notification::createNotification(
                $user->id,
                'reduction',
                'Réduction disponible !',
                "Le document \"{$document->title}\" est en réduction : {$discountPercent}% ({$discountFmt} FCFA économisés)",
                route('documents.show', $document->slug),
                'fa-tag',
                '#10b981'
            );
        }
    }

    public static function checkDownloadExpirations(): int
    {
        $count     = 0;
        $purchases = DocumentPurchase::where('status', 'completed')->with(['user', 'document'])->get();

        foreach ($purchases as $purchase) {
            if (!$purchase->user || !$purchase->document) {
                continue;
            }
            $expiresAt     = $purchase->created_at->addDays($purchase->download_limit ?? 30);
            $daysRemaining = now()->diffInDays($expiresAt, false);

            if ($daysRemaining <= 3 && $daysRemaining > 0) {
                $exists = Notification::where('user_id', $purchase->user_id)
                    ->where('type', 'expiration_telechargement')
                    ->where('link', route('documents.show', $purchase->document->slug))
                    ->where('created_at', '>', now()->subDays(1))
                    ->exists();

                if (!$exists) {
                    self::notifyDownloadExpiration($purchase);
                    $count++;
                }
            }
        }

        return $count;
    }

    public static function notifyUser(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $link = null,
        ?string $icon = null,
        ?string $color = null
    ): Notification {
        return Notification::createNotification($userId, $type, $title, $message, $link, $icon, $color);
    }
}
