<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Télécharge une image hébergée en externe et la réhéberge sur le disque 'public'
 * local, pour ne plus dépendre d'un tiers non maîtrisé (lien mort si la source
 * externe change/supprime l'image — ex. thumbnails Google Images, actu.rts.sn, msf.fr).
 */
class ExternalImageRehoster
{
    /**
     * Retourne le chemin local relatif (disque 'public') en cas de succès,
     * ou null en cas d'échec (réseau, réponse non-image...). L'appelant doit
     * alors garder l'URL externe telle quelle plutôt que de bloquer la publication.
     */
    public static function rehost(string $url, string $folder = 'job-covers'): ?string
    {
        try {
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return null;
            }

            $contentType = $response->header('Content-Type', '');
            if (!str_starts_with($contentType, 'image/')) {
                return null;
            }

            $extension = match (true) {
                str_contains($contentType, 'png') => 'png',
                str_contains($contentType, 'webp') => 'webp',
                str_contains($contentType, 'gif') => 'gif',
                default => 'jpg',
            };

            $filename = $folder . '/' . Str::random(20) . '.' . $extension;

            // Écrit directement dans public/storage (pas storage/app/public) : sur Infomaniak,
            // le PHP servant les requêtes web a un open_basedir restreint au webroot public/,
            // donc un fichier sous storage/ n'y est pas lisible même avec le lien symbolique.
            $destination = public_path('storage/' . $filename);
            File::ensureDirectoryExists(dirname($destination));
            File::put($destination, $response->body());

            return $filename;
        } catch (\Throwable $e) {
            \Log::warning("Échec du réhébergement de l'image externe {$url}: " . $e->getMessage());
            return null;
        }
    }
}
