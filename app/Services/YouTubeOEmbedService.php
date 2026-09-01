<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Récupère les métadonnées publiques d'une vidéo YouTube (titre, auteur, miniature)
 * via l'endpoint oEmbed public de YouTube — aucune clé API requise.
 *
 * Contrairement à TikTok, YouTube expose un ID de vidéo simple qu'on réutilise pour
 * construire nous-mêmes l'URL d'intégration (avec autoplay/boucle au clic) plutôt que
 * de dépendre du HTML retourné par oEmbed, qui ne les supporte pas.
 */
class YouTubeOEmbedService
{
    private const ENDPOINT = 'https://www.youtube.com/oembed';

    /**
     * @return array{youtube_url:string,video_id:string,title:?string,author_name:?string,author_url:?string,thumbnail_url:?string}|null
     *         null si l'URL n'est pas une URL YouTube valide ou si YouTube ne renvoie rien d'exploitable.
     */
    public static function fetch(string $url): ?array
    {
        $url = trim($url);
        $videoId = self::extractVideoId($url);

        if (!$videoId) {
            return null;
        }

        try {
            $response = Http::timeout(6)->get(self::ENDPOINT, ['url' => $url, 'format' => 'json']);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();

            if (empty($data['title']) && empty($data['thumbnail_url'])) {
                return null;
            }

            return [
                'youtube_url'   => $url,
                'video_id'      => $videoId,
                'title'         => $data['title'] ?? null,
                'author_name'   => $data['author_name'] ?? null,
                'author_url'    => $data['author_url'] ?? null,
                'thumbnail_url' => $data['thumbnail_url'] ?? "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg",
            ];
        } catch (\Throwable $e) {
            \Log::warning("Échec de récupération oEmbed YouTube pour {$url}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Supporte watch?v=, youtu.be/, shorts/ et embed/.
     */
    private static function extractVideoId(string $url): ?string
    {
        if (preg_match('/^https?:\/\/(www\.)?youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/i', $url, $m)) {
            return $m[2];
        }
        if (preg_match('/^https?:\/\/youtu\.be\/([a-zA-Z0-9_-]{11})/i', $url, $m)) {
            return $m[1];
        }
        if (preg_match('/^https?:\/\/(www\.)?youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/i', $url, $m)) {
            return $m[2];
        }
        if (preg_match('/^https?:\/\/(www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/i', $url, $m)) {
            return $m[2];
        }

        return null;
    }
}
