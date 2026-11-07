<?php

namespace App\Support;

/**
 * Variantes d'images redimensionnées, générées à la demande puis réutilisées (audit PERF-02).
 *
 * make() renvoie le chemin absolu d'une copie WebP de largeur ≤ $width, régénérée si l'original
 * est plus récent. En cas d'échec (GD absent, format non géré…), renvoie l'original : l'image
 * reste affichée, simplement plus lourde.
 */
class ImageVariant
{
    public static function make(string $source, int $width, string $cacheDir, ?int $height = null): string
    {
        if (! is_file($source)) {
            return $source;
        }

        $target = rtrim($cacheDir, '/') . '/' . md5($source) . '-' . $width . ($height ? 'x' . $height : '') . '.webp';
        if (is_file($target) && filemtime($target) >= filemtime($source)) {
            return $target;
        }

        try {
            $info = @getimagesize($source);
            if (! $info || ! function_exists('imagewebp')) {
                return $source;
            }
            [$w, $h, $type] = $info;
            if ($w <= $width && ($height === null || $h <= $height)) {
                return $source; // déjà assez petite
            }

            $img = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
                IMAGETYPE_PNG  => @imagecreatefrompng($source),
                IMAGETYPE_WEBP => @imagecreatefromwebp($source),
                IMAGETYPE_GIF  => @imagecreatefromgif($source),
                default        => false,
            };
            if (! $img) {
                return $source;
            }

            $ratio = min($width / $w, $height ? $height / $h : INF);
            $nw = max(1, (int) round($w * $ratio));
            $nh = max(1, (int) round($h * $ratio));
            $dst = imagecreatetruecolor($nw, $nh);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagecopyresampled($dst, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);

            if (! is_dir($cacheDir)) {
                @mkdir($cacheDir, 0755, true);
            }
            $ok = imagewebp($dst, $target, 82);
            imagedestroy($img);
            imagedestroy($dst);

            return $ok && is_file($target) ? $target : $source;
        } catch (\Throwable $e) {
            return $source;
        }
    }
}
