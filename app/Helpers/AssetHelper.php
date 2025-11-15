<?php

if (!function_exists('asset_minified')) {
    /**
     * Retourne le chemin de l'asset minifié en production, sinon l'asset normal
     * 
     * @param string $path
     * @param bool $absolute
     * @return string
     */
    function asset_minified($path, $absolute = false)
    {
        // En production, utiliser les fichiers minifiés si disponibles
        if (app()->environment('production')) {
            $minifiedPath = preg_replace('/\.(js|css)$/', '.min.$1', $path);
            $minifiedFullPath = public_path($minifiedPath);
            
            // Si le fichier minifié existe, l'utiliser
            if (file_exists($minifiedFullPath)) {
                return asset($minifiedPath, $absolute);
            }
        }
        
        // Sinon, utiliser le fichier normal
        return asset($path, $absolute);
    }
}

