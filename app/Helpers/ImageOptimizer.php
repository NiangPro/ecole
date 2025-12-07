<?php

namespace App\Helpers;

class ImageOptimizer
{
    /**
     * Génère les attributs optimisés pour une image
     * 
     * @param string $src URL de l'image
     * @param string $alt Texte alternatif
     * @param array $options Options supplémentaires (width, height, class, etc.)
     * @return string Attributs HTML pour l'image
     */
    public static function attributes($src, $alt = '', $options = [])
    {
        $attributes = [];
        
        // Utiliser asset() pour les chemins locaux (qui ne commencent pas par http:// ou https://)
        // et qui ne sont pas déjà des URLs complètes (Storage::url() retourne déjà une URL complète)
        if (!empty($src) && !preg_match('/^(https?:\/\/|\/\/)/', $src) && !str_starts_with($src, '/storage/')) {
            $src = asset($src);
        }
        
        // Support WebP : générer une version WebP si disponible (seulement si GD/Imagick disponible)
        if (function_exists('imagecreatefromjpeg') || function_exists('imagecreatefrompng')) {
            try {
                $webpSrc = self::getWebpVersion($src);
                if ($webpSrc && !isset($options['no_webp'])) {
                    $attributes[] = 'srcset="' . htmlspecialchars($webpSrc, ENT_QUOTES, 'UTF-8') . '"';
                    $attributes[] = 'type="image/webp"';
                }
            } catch (\Exception $e) {
                // Ignorer les erreurs de conversion WebP et continuer avec l'image originale
            }
        }
        
        // Source de l'image
        $attributes[] = 'src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"';
        
        // Texte alternatif
        if (!empty($alt)) {
            $attributes[] = 'alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Lazy loading par défaut (sauf si explicitement désactivé)
        if (!isset($options['loading']) || $options['loading'] !== false) {
            $attributes[] = 'loading="lazy"';
        }
        
        // Fetch priority (pour les images au-dessus de la ligne de flottaison)
        if (isset($options['priority']) && $options['priority'] === true) {
            $attributes[] = 'fetchpriority="high"';
        }
        
        // Dimensions pour éviter le Cumulative Layout Shift (CLS)
        if (isset($options['width'])) {
            $attributes[] = 'width="' . (int)$options['width'] . '"';
        }
        if (isset($options['height'])) {
            $attributes[] = 'height="' . (int)$options['height'] . '"';
        }
        
        // Classe CSS
        if (isset($options['class'])) {
            $attributes[] = 'class="' . htmlspecialchars($options['class'], ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Style inline
        if (isset($options['style'])) {
            $attributes[] = 'style="' . htmlspecialchars($options['style'], ENT_QUOTES, 'UTF-8') . '"';
        }
        
        // Decoding async pour améliorer les performances
        if (!isset($options['decoding']) || $options['decoding'] !== 'sync') {
            $attributes[] = 'decoding="async"';
        }
        
        // Gestion des erreurs
        if (!isset($options['onerror'])) {
            $fallback = $options['fallback'] ?? asset('images/placeholder.jpg');
            // Utiliser asset() pour le fallback si c'est un chemin local
            if (!empty($fallback) && !preg_match('/^(https?:\/\/|\/\/)/', $fallback) && !str_starts_with($fallback, '/storage/')) {
                $fallback = asset($fallback);
            }
            $attributes[] = 'onerror="this.onerror=null;this.src=\'' . htmlspecialchars($fallback, ENT_QUOTES, 'UTF-8') . '\'"';
        } else {
            $attributes[] = 'onerror="' . htmlspecialchars($options['onerror'], ENT_QUOTES, 'UTF-8') . '"';
        }
        
        return implode(' ', $attributes);
    }
    
    /**
     * Génère une balise img optimisée
     * 
     * @param string $src URL de l'image
     * @param string $alt Texte alternatif
     * @param array $options Options supplémentaires
     * @return string Balise HTML img
     */
    public static function img($src, $alt = '', $options = [])
    {
        return '<img ' . self::attributes($src, $alt, $options) . '>';
    }
    
    /**
     * Génère une balise picture avec sources multiples pour le responsive
     * 
     * @param string $src URL de l'image par défaut
     * @param string $alt Texte alternatif
     * @param array $sources Sources pour différentes tailles (format: ['srcset' => '...', 'media' => '...'])
     * @param array $options Options supplémentaires
     * @return string Balise HTML picture
     */
    public static function picture($src, $alt = '', $sources = [], $options = [])
    {
        $html = '<picture>';
        
        // Ajouter la source WebP en premier si disponible
        $webpSrc = self::getWebpVersion($src);
        if ($webpSrc && !isset($options['no_webp'])) {
            $html .= '<source srcset="' . htmlspecialchars($webpSrc, ENT_QUOTES, 'UTF-8') . '" type="image/webp">';
        }
        
        // Sources responsives
        foreach ($sources as $source) {
            $html .= '<source';
            if (isset($source['srcset'])) {
                // Traiter le srcset pour utiliser asset() sur les chemins locaux
                $srcset = self::processSrcset($source['srcset']);
                $html .= ' srcset="' . htmlspecialchars($srcset, ENT_QUOTES, 'UTF-8') . '"';
            }
            if (isset($source['media'])) {
                $html .= ' media="' . htmlspecialchars($source['media'], ENT_QUOTES, 'UTF-8') . '"';
            }
            if (isset($source['type'])) {
                $html .= ' type="' . htmlspecialchars($source['type'], ENT_QUOTES, 'UTF-8') . '"';
            }
            $html .= '>';
        }
        
        // Image par défaut
        $html .= '<img ' . self::attributes($src, $alt, array_merge($options, ['no_webp' => true])) . '>';
        $html .= '</picture>';
        
        return $html;
    }
    
    /**
     * Traite un srcset pour utiliser asset() sur les chemins locaux
     * 
     * @param string $srcset Le srcset à traiter (peut contenir plusieurs URLs séparées par des virgules)
     * @return string Le srcset traité
     */
    private static function processSrcset($srcset)
    {
        // Le srcset peut contenir plusieurs URLs avec des descripteurs (ex: "image.jpg 1x, image@2x.jpg 2x")
        $parts = explode(',', $srcset);
        $processed = [];
        
        foreach ($parts as $part) {
            $part = trim($part);
            // Extraire l'URL et le descripteur (s'il existe)
            if (preg_match('/^(.+?)(\s+\d+x|\s+\d+w)?$/', $part, $matches)) {
                $url = trim($matches[1]);
                $descriptor = isset($matches[2]) ? $matches[2] : '';
                
                // Utiliser asset() pour les chemins locaux
                if (!empty($url) && !preg_match('/^(https?:\/\/|\/\/)/', $url) && !str_starts_with($url, '/storage/')) {
                    $url = asset($url);
                }
                
                $processed[] = $url . $descriptor;
            } else {
                $processed[] = $part;
            }
        }
        
        return implode(', ', $processed);
    }
    
    /**
     * Génère une version WebP d'une image si elle existe
     * 
     * @param string $src URL de l'image originale
     * @return string|null URL de la version WebP ou null si non disponible
     */
    private static function getWebpVersion($src)
    {
        // Ne pas convertir les images externes
        if (preg_match('/^(https?:\/\/|\/\/)/', $src)) {
            return null;
        }
        
        // Extraire le chemin de l'image
        $path = parse_url($src, PHP_URL_PATH);
        if (!$path) {
            return null;
        }
        
        // Enlever le slash initial
        $path = ltrim($path, '/');
        
        // Vérifier si c'est un chemin public
        $publicPath = public_path($path);
        if (!file_exists($publicPath)) {
            return null;
        }
        
        // Générer le chemin WebP
        $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
        $webpPublicPath = public_path($webpPath);
        
        // Si le fichier WebP existe, retourner son URL
        if (file_exists($webpPublicPath)) {
            return asset($webpPath);
        }
        
        // Sinon, essayer de créer le WebP à la volée (nécessite GD ou Imagick)
        if (function_exists('imagecreatefromjpeg') || function_exists('imagecreatefrompng')) {
            return self::convertToWebp($publicPath, $webpPublicPath) ? asset($webpPath) : null;
        }
        
        return null;
    }
    
    /**
     * Convertit une image en WebP
     * 
     * @param string $sourcePath Chemin source
     * @param string $destinationPath Chemin destination
     * @return bool Succès de la conversion
     */
    private static function convertToWebp($sourcePath, $destinationPath)
    {
        // Créer le répertoire de destination si nécessaire
        $destinationDir = dirname($destinationPath);
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }
        
        // Détecter le type d'image
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            return false;
        }
        
        $mimeType = $imageInfo['mime'];
        
        // Charger l'image selon son type
        switch ($mimeType) {
            case 'image/jpeg':
                if (!function_exists('imagecreatefromjpeg')) {
                    return false;
                }
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                if (!function_exists('imagecreatefrompng')) {
                    return false;
                }
                $image = imagecreatefrompng($sourcePath);
                // Préserver la transparence
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            default:
                return false;
        }
        
        if (!$image) {
            return false;
        }
        
        // Convertir en WebP avec qualité 85 (bon compromis taille/qualité)
        $result = imagewebp($image, $destinationPath, 85);
        imagedestroy($image);
        
        return $result;
    }
}

