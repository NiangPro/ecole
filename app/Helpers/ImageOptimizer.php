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
        $html .= '<img ' . self::attributes($src, $alt, $options) . '>';
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
}

