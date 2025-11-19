<?php

namespace App\Services;

class SanitizationService
{
    /**
     * Sanitize une chaîne de caractères pour éviter XSS
     *
     * @param string|null $input
     * @param bool $allowHtml Si true, nettoie mais permet certains tags HTML
     * @return string
     */
    public static function sanitizeString(?string $input, bool $allowHtml = false): string
    {
        if (empty($input)) {
            return '';
        }

        // Nettoyer les espaces en début/fin
        $input = trim($input);

        if ($allowHtml) {
            // Permettre certains tags HTML mais nettoyer les attributs dangereux
            $allowedTags = '<p><br><strong><em><u><a><ul><ol><li><h1><h2><h3><h4><h5><h6>';
            $input = strip_tags($input, $allowedTags);
            
            // Nettoyer les attributs dangereux des balises autorisées
            $input = preg_replace_callback(
                '/<a\s+([^>]*)>/i',
                function ($matches) {
                    $attrs = $matches[1];
                    // Garder seulement href et title, nettoyer href
                    if (preg_match('/href=["\']([^"\']*)["\']/', $attrs, $hrefMatch)) {
                        $href = filter_var($hrefMatch[1], FILTER_SANITIZE_URL);
                        if (!filter_var($href, FILTER_VALIDATE_URL) && !str_starts_with($href, '/')) {
                            $href = '#';
                        }
                        return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">';
                    }
                    return '<a>';
                },
                $input
            );
        } else {
            // Pas de HTML, échapper tous les caractères spéciaux
            $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $input;
    }

    /**
     * Sanitize un email
     *
     * @param string|null $email
     * @return string
     */
    public static function sanitizeEmail(?string $email): string
    {
        if (empty($email)) {
            return '';
        }

        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        return $email;
    }

    /**
     * Sanitize une URL
     *
     * @param string|null $url
     * @return string
     */
    public static function sanitizeUrl(?string $url): string
    {
        if (empty($url)) {
            return '';
        }

        $url = trim($url);
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        // Vérifier que c'est une URL valide
        if (!filter_var($url, FILTER_VALIDATE_URL) && !str_starts_with($url, '/')) {
            return '';
        }

        return $url;
    }

    /**
     * Sanitize un numéro de téléphone
     *
     * @param string|null $phone
     * @return string
     */
    public static function sanitizePhone(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        // Garder seulement les chiffres, espaces, +, -, (, )
        $phone = preg_replace('/[^0-9+\-() ]/', '', $phone);
        $phone = trim($phone);
        
        return $phone;
    }

    /**
     * Sanitize un nom (pas de HTML, pas de caractères spéciaux dangereux)
     *
     * @param string|null $name
     * @return string
     */
    public static function sanitizeName(?string $name): string
    {
        if (empty($name)) {
            return '';
        }

        $name = trim($name);
        // Permettre lettres, espaces, tirets, apostrophes (pour noms composés)
        $name = preg_replace('/[^a-zA-ZÀ-ÿ\s\-\']/u', '', $name);
        // Limiter les espaces multiples
        $name = preg_replace('/\s+/', ' ', $name);
        
        return $name;
    }

    /**
     * Sanitize le contenu d'un commentaire ou message
     *
     * @param string|null $content
     * @return string
     */
    public static function sanitizeContent(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        $content = trim($content);
        
        // Échapper HTML pour éviter XSS
        $content = htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Limiter les retours à la ligne multiples
        $content = preg_replace('/\n{3,}/', "\n\n", $content);
        
        return $content;
    }

    /**
     * Sanitize un tableau de données
     *
     * @param array $data
     * @param array $rules Règles de sanitization ['field' => 'method']
     * @return array
     */
    public static function sanitizeArray(array $data, array $rules): array
    {
        $sanitized = [];

        foreach ($rules as $field => $method) {
            if (isset($data[$field])) {
                if (method_exists(self::class, $method)) {
                    $sanitized[$field] = self::$method($data[$field]);
                } else {
                    $sanitized[$field] = self::sanitizeString($data[$field]);
                }
            }
        }

        return $sanitized;
    }
}

