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
     * Obtenir l'URL CDN pour une image externe ou locale
     * 
     * @param string $url URL de l'image
     * @param bool $webp Utiliser la version WebP si disponible
     * @return string URL avec CDN si configuré, sinon URL originale
     */
    public static function image($url, $webp = false)
    {
        self::init();

        // Si pas de CDN configuré, retourner l'URL originale
        if (empty(self::$cdnUrl)) {
            // Si WebP est demandé, essayer de trouver la version WebP
            if ($webp) {
                $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $url);
                if (file_exists(public_path($webpPath))) {
                    return asset($webpPath);
                }
            }
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

        // Pour les images locales, utiliser asset() avec CDN
        $path = ltrim(parse_url($url, PHP_URL_PATH) ?? $url, '/');
        
        // Si WebP est demandé, essayer de trouver la version WebP
        if ($webp) {
            $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
            if (file_exists(public_path($webpPath))) {
                return self::asset($webpPath);
            }
        }
        
        return self::asset($path);
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
        
        // Ajouter un hash de version pour le cache busting si en production
        if (app()->environment('production') && file_exists(public_path($path))) {
            $version = substr(md5_file(public_path($path)), 0, 8);
            $path = $path . '?v=' . $version;
        }
        
        return rtrim(self::$cdnUrl, '/') . '/' . $path;
    }
    
    /**
     * Obtenir l'URL CDN pour un fichier CSS
     * 
     * @param string $path Chemin du fichier CSS
     * @return string URL avec CDN
     */
    public static function css($path)
    {
        return self::asset($path);
    }
    
    /**
     * Obtenir l'URL CDN pour un fichier JS
     * 
     * @param string $path Chemin du fichier JS
     * @return string URL avec CDN
     */
    public static function js($path)
    {
        return self::asset($path);
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

