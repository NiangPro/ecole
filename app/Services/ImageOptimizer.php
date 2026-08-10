<?php

namespace App\Services;

/**
 * Recompresse une image en place (même chemin, même format) pour réduire son poids
 * sans jamais changer la référence stockée en base (cover_image, etc.) — pas de
 * migration de données nécessaire.
 *
 * Ajouté suite à l'audit du 27/07/2026 : 3,4 Mo d'économies possibles sur les images
 * du site, certaines couvertures d'articles/cours étant des captures PNG de 300 Ko à
 * 1,4 Mo jamais redimensionnées à leur taille d'affichage réelle (~1200px de large max
 * dans les cards et l'en-tête d'article). Branché sur les deux points d'entrée qui
 * écrivent une image sur le disque 'public' : upload admin (JobArticleController,
 * PaidCourseController) et réhébergement d'image externe (ExternalImageRehoster).
 */
class ImageOptimizer
{
    private const MAX_WIDTH = 1200;
    private const JPEG_QUALITY = 82;
    private const PNG_COMPRESSION = 6; // 0 (aucune compression) à 9 (max) — 6 = bon compromis vitesse/poids
    private const SKIP_BELOW_BYTES = 150 * 1024; // pas la peine de retoucher une image déjà légère

    /**
     * Redimensionne et recompresse un fichier image en place.
     * Retourne true si le fichier a été remplacé par une version plus légère, false sinon
     * (déjà optimal, format non géré par GD, ou échec — l'appelant garde alors l'original).
     */
    public static function optimize(string $absolutePath, int $maxWidth = self::MAX_WIDTH): bool
    {
        if (!is_file($absolutePath) || !extension_loaded('gd')) {
            return false;
        }

        $info = @getimagesize($absolutePath);
        if ($info === false) {
            return false;
        }

        [$width, $height, $type] = $info;
        $originalSize = filesize($absolutePath);

        if ($originalSize < self::SKIP_BELOW_BYTES && $width <= $maxWidth) {
            return false;
        }

        $source = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($absolutePath),
            IMAGETYPE_PNG  => @imagecreatefrompng($absolutePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolutePath) : false,
            default        => false, // GIF/autres non touchés (animations, icônes)
        };

        if (!$source) {
            return false;
        }

        $targetWidth  = min($width, $maxWidth);
        $targetHeight = max(1, (int) round($height * ($targetWidth / $width)));

        $resized = imagecreatetruecolor($targetWidth, $targetHeight);

        if ($type === IMAGETYPE_PNG) {
            // Préserver la transparence éventuelle
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }

        imagecopyresampled($resized, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        $tmpPath = $absolutePath . '.optimizing.tmp';
        $ok = match ($type) {
            IMAGETYPE_JPEG => imagejpeg($resized, $tmpPath, self::JPEG_QUALITY),
            IMAGETYPE_PNG  => imagepng($resized, $tmpPath, self::PNG_COMPRESSION),
            IMAGETYPE_WEBP => imagewebp($resized, $tmpPath, self::JPEG_QUALITY),
            default        => false,
        };

        imagedestroy($source);
        imagedestroy($resized);

        if (!$ok || !is_file($tmpPath) || filesize($tmpPath) === 0) {
            @unlink($tmpPath);
            return false;
        }

        // Ne garder la version recompressée que si elle est réellement plus légère
        if (filesize($tmpPath) >= $originalSize) {
            @unlink($tmpPath);
            return false;
        }

        rename($tmpPath, $absolutePath);
        return true;
    }
}
