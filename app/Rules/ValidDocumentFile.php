<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDocumentFile implements ValidationRule
{
    protected $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar'];
    protected $allowedMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'application/zip',
        'application/x-zip-compressed',
        'application/x-rar-compressed',
        'application/octet-stream', // Pour certains fichiers RAR
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value || !$value->isValid()) {
            $fail('Le fichier n\'est pas valide.');
            return;
        }

        $extension = strtolower($value->getClientOriginalExtension());
        $mimeType = $value->getMimeType();

        // Vérifier l'extension
        if (!in_array($extension, $this->allowedExtensions)) {
            $fail('Le fichier doit être de type : pdf, doc, docx, xls, xlsx, ppt, pptx, txt, zip, rar.');
            return;
        }

        // Vérifier le type MIME (mais être plus flexible pour les PDFs)
        if (in_array($mimeType, $this->allowedMimeTypes)) {
            return;
        }

        // Pour les PDFs, accepter aussi si l'extension est correcte
        // car certains serveurs peuvent retourner des types MIME différents
        if ($extension === 'pdf' && str_contains($mimeType, 'pdf')) {
            return;
        }

        // Pour les fichiers texte, être plus flexible
        if ($extension === 'txt' && (str_contains($mimeType, 'text') || str_contains($mimeType, 'plain'))) {
            return;
        }

        $fail('Le fichier doit être de type : pdf, doc, docx, xls, xlsx, ppt, pptx, txt, zip, rar.');
    }
}
