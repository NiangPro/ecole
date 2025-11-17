<?php

namespace App\Helpers;

class CdnHelper
{
    /**
     * URL du CDN (à configurer dans .env)
     */
    private static $cdnUrl = null;

    /**
     * Initialiser l'URL du CDN depuis la config
     */
    private static function init()
    {
        if (self::$cdnUrl === null) {
            self::$cdnUrl = config('app.cdn_url', '');
        }
    }

    /**
     * Obtenir l'URL CDN pour une image externe
     * 
     * @param string $url URL de l'image
     * @return string URL avec CDN si configuré, sinon URL originale
     */
    public static function image($url)
    {
        self::init();

        // Si pas de CDN configuré, retourner l'URL originale
        if (empty(self::$cdnUrl)) {
            return $url;
        }

        // Si l'URL est déjà une URL complète (http:// ou https://)
        if (preg_match('/^https?:\/\//', $url)) {
            // Utiliser le CDN pour les images externes
            // Exemple: https://images.unsplash.com/photo-xxx -> https://cdn.example.com/images.unsplash.com/photo-xxx
            if (strpos($url, 'images.unsplash.com') !== false || 
                strpos($url, 'cdnjs.cloudflare.com') !== false ||
                strpos($url, 'fonts.googleapis.com') !== false) {
                // Pour l'instant, retourner l'URL originale
                // Le CDN peut être configuré pour proxy ces domaines
                return $url;
            }
        }

        return $url;
    }

    /**
     * Obtenir l'URL CDN pour un asset local
     * 
     * @param string $path Chemin de l'asset
     * @return string URL avec CDN si configuré
     */
    public static function asset($path)
    {
        self::init();

        if (empty(self::$cdnUrl)) {
            return asset($path);
        }

        // Enlever le slash initial si présent
        $path = ltrim($path, '/');
        
        return rtrim(self::$cdnUrl, '/') . '/' . $path;
    }

    /**
     * Vérifier si le CDN est activé
     * 
     * @return bool
     */
    public static function isEnabled()
    {
        self::init();
        return !empty(self::$cdnUrl);
    }
}

