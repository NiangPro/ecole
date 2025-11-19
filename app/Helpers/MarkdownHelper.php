<?php

if (!function_exists('markdown_to_html')) {
    /**
     * Convertit le Markdown basique en HTML
     * 
     * @param string $markdown
     * @return string
     */
    function markdown_to_html($markdown)
    {
        if (empty($markdown)) {
            return '';
        }

        // Si le contenu contient déjà des balises HTML, le retourner tel quel
        if (strip_tags($markdown) !== $markdown) {
            return $markdown;
        }

        $html = $markdown;
        
        // Normaliser les sauts de ligne (Windows/Mac/Unix)
        $html = str_replace(["\r\n", "\r"], "\n", $html);
        
        // Diviser en lignes pour traitement
        $lines = explode("\n", $html);
        $result = [];
        $inList = false;
        $inParagraph = false;
        
        foreach ($lines as $line) {
            $line = rtrim($line);
            
            // Ligne vide = fin de paragraphe/liste
            if (empty($line)) {
                if ($inList) {
                    $result[] = '</ul>';
                    $inList = false;
                }
                if ($inParagraph) {
                    $result[] = '</p>';
                    $inParagraph = false;
                }
                continue;
            }
            
            // Titre ##
            if (preg_match('/^##\s+(.+)$/', $line, $matches)) {
                if ($inList) {
                    $result[] = '</ul>';
                    $inList = false;
                }
                if ($inParagraph) {
                    $result[] = '</p>';
                    $inParagraph = false;
                }
                $result[] = '<h2>' . trim($matches[1]) . '</h2>';
                continue;
            }
            
            // Titre ###
            if (preg_match('/^###\s+(.+)$/', $line, $matches)) {
                if ($inList) {
                    $result[] = '</ul>';
                    $inList = false;
                }
                if ($inParagraph) {
                    $result[] = '</p>';
                    $inParagraph = false;
                }
                $result[] = '<h3>' . trim($matches[1]) . '</h3>';
                continue;
            }
            
            // Titre ####
            if (preg_match('/^####\s+(.+)$/', $line, $matches)) {
                if ($inList) {
                    $result[] = '</ul>';
                    $inList = false;
                }
                if ($inParagraph) {
                    $result[] = '</p>';
                    $inParagraph = false;
                }
                $result[] = '<h4>' . trim($matches[1]) . '</h4>';
                continue;
            }
            
            // Liste à puces - ou * (avec ou sans espace après le tiret)
            if (preg_match('/^[\-\*]\s+(.+)$/', $line, $matches)) {
                if ($inParagraph) {
                    $result[] = '</p>';
                    $inParagraph = false;
                }
                if (!$inList) {
                    $result[] = '<ul>';
                    $inList = true;
                }
                $content = trim($matches[1]);
                // Convertir le gras dans les listes
                $content = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $content);
                $result[] = '<li>' . $content . '</li>';
                continue;
            }
            
            // Paragraphe normal
            if ($inList) {
                $result[] = '</ul>';
                $inList = false;
            }
            if (!$inParagraph) {
                $result[] = '<p>';
                $inParagraph = true;
            } else {
                $result[] = ' ';
            }
            
            // Convertir le gras **texte**
            $line = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $line);
            
            // Convertir l'italique *texte* (mais pas dans les listes)
            $line = preg_replace('/(?<!\*)\*([^*]+?)\*(?!\*)/', '<em>$1</em>', $line);
            
            $result[] = $line;
        }
        
        // Fermer les balises ouvertes
        if ($inList) {
            $result[] = '</ul>';
        }
        if ($inParagraph) {
            $result[] = '</p>';
        }
        
        $html = implode('', $result);
        
        // Nettoyer les paragraphes vides
        $html = preg_replace('/<p>\s*<\/p>/', '', $html);
        
        // Nettoyer les espaces multiples dans les paragraphes (mais garder les sauts de ligne entre éléments)
        $html = preg_replace('/(<p>)\s+/', '$1', $html);
        $html = preg_replace('/\s+(<\/p>)/', '$1', $html);
        
        return trim($html);
    }
}
