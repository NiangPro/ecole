<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Contrôleur de secours pour servir les fichiers du storage
 * quand le lien symbolique (storage:link) n'est pas disponible.
 * Utile sur les hébergements partagés comme LWS qui désactivent les symlinks.
 */
class StorageFileController extends Controller
{
    /**
     * Sert un fichier depuis storage/app/public lorsque le lien symbolique
     * public/storage n'existe pas (hébergement LWS, etc.).
     */
    public function serve(Request $request, string $path): BinaryFileResponse
    {
        // Sécurité : empêcher la traversée de répertoires
        if (str_contains($path, '..') || str_starts_with($path, '/')) {
            abort(404);
        }

        $storagePublic = storage_path('app/public');
        $fullPath = $storagePublic . '/' . $path;

        // Fallback : si le fichier n'est pas dans storage/app/public, essayer public/storage (après storage:publish)
        if (!file_exists($fullPath) || !is_file($fullPath)) {
            $publicStorage = public_path('storage/' . $path);
            if (file_exists($publicStorage) && is_file($publicStorage)) {
                $fullPath = $publicStorage;
            } else {
                abort(404);
            }
        }

        // Vérifier que le fichier est bien dans storage/app/public ou public/storage (sécurité path traversal)
        $realFull = realpath($fullPath);
        $realStorage = realpath($storagePublic);
        $realPublicStorage = realpath(public_path('storage'));
        if ($realFull) {
            $realFullNorm = str_replace('\\', '/', $realFull);
            $allowed1 = $realStorage && str_starts_with($realFullNorm, rtrim(str_replace('\\', '/', $realStorage), '/') . '/');
            $allowed2 = $realPublicStorage && str_starts_with($realFullNorm, rtrim(str_replace('\\', '/', $realPublicStorage), '/') . '/');
            if (!$allowed1 && !$allowed2) {
                abort(404);
            }
        }

        $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
