<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Redimensionne une image uploadée (recadrage centré façon object-fit:cover),
     * la convertit en WebP et la stocke. Retourne le chemin relatif sur le disque,
     * comme le ferait UploadedFile::store().
     */
    public static function storeResized(
        UploadedFile $file,
        string $directory,
        int $width,
        int $height,
        string $disk = 'public',
        int $quality = 82
    ): string {
        $info = @getimagesize($file->getRealPath());

        $srcImage = match ($info[2] ?? null) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($file->getRealPath()),
            IMAGETYPE_PNG => imagecreatefrompng($file->getRealPath()),
            IMAGETYPE_WEBP => imagecreatefromwebp($file->getRealPath()),
            IMAGETYPE_GIF => imagecreatefromgif($file->getRealPath()),
            default => false,
        };

        // Format non supporté par GD (ex: SVG) : on stocke le fichier tel quel.
        if (!$srcImage) {
            return $file->store($directory, $disk);
        }

        [$srcWidth, $srcHeight] = $info;

        // Recadrage centré façon object-fit:cover pour remplir exactement la cible
        // sans déformer l'image.
        $srcRatio = $srcWidth / $srcHeight;
        $dstRatio = $width / $height;

        if ($srcRatio > $dstRatio) {
            $cropHeight = $srcHeight;
            $cropWidth = (int) round($srcHeight * $dstRatio);
        } else {
            $cropWidth = $srcWidth;
            $cropHeight = (int) round($srcWidth / $dstRatio);
        }
        $cropX = (int) round(($srcWidth - $cropWidth) / 2);
        $cropY = (int) round(($srcHeight - $cropHeight) / 2);

        $dstImage = imagecreatetruecolor($width, $height);
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);

        imagecopyresampled(
            $dstImage,
            $srcImage,
            0,
            0,
            $cropX,
            $cropY,
            $width,
            $height,
            $cropWidth,
            $cropHeight
        );

        ob_start();
        imagewebp($dstImage, null, $quality);
        $contents = ob_get_clean();

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        $relativePath = trim($directory, '/') . '/' . time() . '_' . Str::random(10) . '.webp';
        Storage::disk($disk)->put($relativePath, $contents);

        return $relativePath;
    }
}
