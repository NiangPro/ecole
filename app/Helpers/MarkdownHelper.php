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

if (!function_exists('format_course_chapter_content')) {
    /**
     * Convertit le contenu (semi-markdown généré par les auteurs de cours) en HTML riche :
     * titres, listes, gras/italique, séparateurs, encadrés (callouts) à préfixe emoji,
     * et bloc "QUIZ MODULE ..." transformé en quiz interactif.
     *
     * Suppose que les blocs de code ``` ``` ont déjà été convertis en HTML (.code-box)
     * en amont (voir ProfileController::processMarkdownContent).
     *
     * @param string $content
     * @return string
     */
    function format_course_chapter_content($content)
    {
        if (empty($content)) {
            return $content;
        }

        $blocks = [];
        $storeBlock = function ($html) use (&$blocks) {
            $token = '@@CCF_BLOCK_' . count($blocks) . '@@';
            $blocks[$token] = $html;
            return $token;
        };

        // 1) Mettre de côté les blocs déjà rendus en HTML (ex: .code-box) pour ne pas
        //    les altérer pendant le parsing ligne par ligne.
        $content = preg_replace_callback('/<div class="code-box".*?<\/div>/s', function ($m) use ($storeBlock) {
            return $storeBlock($m[0]);
        }, $content);

        // 2) Extraire le bloc quiz "--- \n QUIZ ... (n questions)" jusqu'à la fin du contenu.
        if (preg_match('/-{3,}\s*\n+\s*(QUIZ[^\n]*)\n+([\s\S]*)$/i', $content, $qm, PREG_OFFSET_CAPTURE)) {
            $fullMatchStart = $qm[0][1];
            $quizTitle = $qm[1][0];
            $quizBody = $qm[2][0];

            $quizHtml = ccf_render_quiz($quizTitle, $quizBody);
            $content = substr($content, 0, $fullMatchStart) . $storeBlock($quizHtml);
        }

        // 3) Convertir le reste (titres, listes, gras, italique, séparateurs, callouts, paragraphes).
        $content = ccf_convert_lines($content);

        // 4) Réinjecter les blocs mis de côté.
        return strtr($content, $blocks);
    }
}

if (!function_exists('ccf_inline_format')) {
    /**
     * Conversion inline : **gras** et *italique*.
     */
    function ccf_inline_format($text)
    {
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/(?<!\*)\*([^*\n]+?)\*(?!\*)/', '<em>$1</em>', $text);
        return $text;
    }
}

if (!function_exists('ccf_detect_emoji_callout')) {
    /**
     * Détecte une ligne commençant par un emoji (ex: "🎯 Objectif : ...") et retourne
     * ['icon' => ..., 'text' => ..., 'type' => ...] ou null si aucune correspondance.
     */
    function ccf_detect_emoji_callout($line)
    {
        $pattern = '/^([\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{2190}-\x{21FF}]+)\x{FE0F}?\s*(.+)$/u';
        if (!preg_match($pattern, $line, $m)) {
            return null;
        }

        $icon = $m[1];
        $text = trim($m[2]);

        $typeMap = [
            'objectif' => 'objective',
            'explication' => 'info',
            'exemple' => 'example',
            'astuce' => 'tip',
            'conseil' => 'tip',
            'erreur' => 'warning',
            'attention' => 'warning',
            'résumé' => 'summary',
            'resume' => 'summary',
            'exercice' => 'exercise',
            'correction' => 'success',
        ];

        $type = 'note';
        foreach ($typeMap as $keyword => $mappedType) {
            if (mb_stripos($text, $keyword) !== false) {
                $type = $mappedType;
                break;
            }
        }

        return ['icon' => $icon, 'text' => $text, 'type' => $type];
    }
}

if (!function_exists('ccf_convert_lines')) {
    /**
     * Parseur ligne par ligne : titres ##/###/####, listes -, séparateurs ---,
     * callouts à préfixe emoji, HTML déjà présent, et paragraphes.
     */
    function ccf_convert_lines($content)
    {
        $content = str_replace(["\r\n", "\r"], "\n", $content);
        $lines = explode("\n", $content);

        $result = [];
        $inList = false;
        $inParagraph = [];

        $flushParagraph = function () use (&$result, &$inParagraph) {
            if (!empty($inParagraph)) {
                $result[] = '<p>' . ccf_inline_format(implode(' ', $inParagraph)) . '</p>';
                $inParagraph = [];
            }
        };
        $flushList = function () use (&$result, &$inList) {
            if ($inList) {
                $result[] = '</ul>';
                $inList = false;
            }
        };

        foreach ($lines as $rawLine) {
            $line = trim($rawLine);

            // Bloc déjà mis de côté (code, quiz) : on le laisse passer tel quel.
            if (preg_match('/^@@CCF_BLOCK_\d+@@$/', $line)) {
                $flushParagraph();
                $flushList();
                $result[] = $line;
                continue;
            }

            if ($line === '') {
                $flushParagraph();
                $flushList();
                continue;
            }

            // Séparateur ---
            if (preg_match('/^-{3,}$/', $line)) {
                $flushParagraph();
                $flushList();
                $result[] = '<hr class="chapter-divider">';
                continue;
            }

            // Titres ##, ###, ####
            if (preg_match('/^(#{2,4})\s+(.+)$/', $line, $m)) {
                $flushParagraph();
                $flushList();
                $level = strlen($m[1]);
                $result[] = "<h{$level}>" . ccf_inline_format(trim($m[2])) . "</h{$level}>";
                continue;
            }

            // Liste à puces - ou *
            if (preg_match('/^[\-\*]\s+(.+)$/', $line, $m)) {
                $flushParagraph();
                if (!$inList) {
                    $result[] = '<ul>';
                    $inList = true;
                }
                $result[] = '<li>' . ccf_inline_format(trim($m[1])) . '</li>';
                continue;
            }

            // HTML déjà présent sur sa propre ligne (ex: <img ...>, <iframe ...>)
            if (preg_match('/^<[a-zA-Z][^>]*>.*<\/[a-zA-Z]+>$|^<[a-zA-Z][^>]*\/?>$/', $line)) {
                $flushParagraph();
                $flushList();
                $result[] = $line;
                continue;
            }

            // Callout à préfixe emoji (🎯 Objectif :, 📖 Explication :, ...)
            $callout = ccf_detect_emoji_callout($line);
            if ($callout) {
                $flushParagraph();
                $flushList();
                $result[] = sprintf(
                    '<div class="content-callout callout-%s"><span class="callout-icon">%s</span><div class="callout-body">%s</div></div>',
                    $callout['type'],
                    $callout['icon'],
                    ccf_inline_format($callout['text'])
                );
                continue;
            }

            // Paragraphe normal (accumulé jusqu'à la ligne vide suivante)
            $flushList();
            $inParagraph[] = $line;
        }

        $flushParagraph();
        $flushList();

        return implode("\n", $result);
    }
}

if (!function_exists('ccf_render_quiz')) {
    /**
     * Transforme un bloc "QUIZ MODULE X (n questions)" + questions en quiz interactif.
     */
    function ccf_render_quiz($titleLine, $body)
    {
        preg_match('/QUIZ\s*(?:MODULE\s*(\d+))?/i', $titleLine, $tm);
        $moduleNumber = $tm[1] ?? null;

        preg_match_all('/Q(\d+)\.\s*(.*?)(?=(?:\n\s*Q\d+\.)|\z)/s', trim($body), $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            // Repli sûr : on ne perd jamais le contenu, même si le format est inattendu.
            return '<div class="quiz-card-modern"><div class="quiz-modern-header"><i class="fas fa-question-circle"></i><h3>' . htmlspecialchars($titleLine) . '</h3></div><div class="quiz-modern-body">' . ccf_convert_lines($body) . '</div></div>';
        }

        $questionsHtml = '';
        $totalQuestions = count($matches);

        foreach ($matches as $m) {
            $qNumber = (int) $m[1];
            $rest = trim($m[2]);

            if (!preg_match('/(.*?)(\n?[A-D]\)|\n?→)/s', $rest, $qm)) {
                // Pas d'options détectables : on affiche la question telle quelle.
                $questionsHtml .= sprintf(
                    '<div class="quiz-question-card"><div class="quiz-question-head"><span class="quiz-question-number">%d</span><p class="quiz-question-text">%s</p></div></div>',
                    $qNumber,
                    ccf_inline_format($rest)
                );
                continue;
            }

            $question = trim($qm[1]);
            $optionsText = substr($rest, strlen($qm[1]));

            if (preg_match('/[A-D]\)/', $optionsText)) {
                // Choix multiple A) B) C) D)
                preg_match_all('/([A-D])\)\s*(.*?)(?=(?:\s*[A-D]\)|$))/s', $optionsText, $om, PREG_SET_ORDER);

                $optionsHtml = '';
                foreach ($om as $o) {
                    $text = trim($o[2]);
                    $isCorrect = mb_strpos($text, "\u{2705}") !== false;
                    $text = trim(str_replace("\u{2705}", '', $text));
                    $optionsHtml .= sprintf(
                        '<button type="button" class="quiz-option" data-correct="%s"><span class="quiz-option-letter">%s</span><span class="quiz-option-text">%s</span><span class="quiz-option-feedback"><i class="fas fa-check"></i><i class="fas fa-times"></i></span></button>',
                        $isCorrect ? '1' : '0',
                        $o[1],
                        ccf_inline_format($text)
                    );
                }

                $questionsHtml .= sprintf(
                    '<div class="quiz-question-card" data-quiz-question><div class="quiz-question-head"><span class="quiz-question-number">%d</span><p class="quiz-question-text">%s</p></div><div class="quiz-options">%s</div></div>',
                    $qNumber,
                    ccf_inline_format($question),
                    $optionsHtml
                );
            } elseif (mb_strpos($optionsText, '→') !== false) {
                // Vrai / Faux avec explication
                preg_match('/→\s*(.*?)\s*✅(.*)$/s', $optionsText, $tf);
                $answer = trim($tf[1] ?? '');
                $explanation = preg_replace('/^[\s\p{Pd}—–\-]+/u', '', trim($tf[2] ?? ''));

                $questionsHtml .= sprintf(
                    '<div class="quiz-question-card quiz-truefalse" data-quiz-truefalse data-answer="%s"><div class="quiz-question-head"><span class="quiz-question-number">%d</span><p class="quiz-question-text">%s</p></div>
                    <div class="quiz-tf-options">
                        <button type="button" class="quiz-tf-btn" data-value="vrai">Vrai</button>
                        <button type="button" class="quiz-tf-btn" data-value="faux">Faux</button>
                    </div>
                    <div class="quiz-tf-reveal"><strong>%s</strong> — %s</div>
                    </div>',
                    htmlspecialchars(mb_strtolower($answer)),
                    $qNumber,
                    ccf_inline_format($question),
                    htmlspecialchars($answer),
                    ccf_inline_format($explanation)
                );
            } else {
                $questionsHtml .= sprintf(
                    '<div class="quiz-question-card"><div class="quiz-question-head"><span class="quiz-question-number">%d</span><p class="quiz-question-text">%s</p></div><div class="quiz-question-raw">%s</div></div>',
                    $qNumber,
                    ccf_inline_format($question),
                    ccf_inline_format(trim($optionsText))
                );
            }
        }

        $titleLabel = $moduleNumber ? "Quiz — Module {$moduleNumber}" : 'Quiz';

        return sprintf(
            '<div class="quiz-card-modern">
                <div class="quiz-modern-header">
                    <span class="quiz-modern-icon"><i class="fas fa-brain"></i></span>
                    <div>
                        <h3>%s</h3>
                        <p>%d question%s — vérifiez vos connaissances</p>
                    </div>
                </div>
                <div class="quiz-modern-body">%s</div>
            </div>',
            htmlspecialchars($titleLabel),
            $totalQuestions,
            $totalQuestions > 1 ? 's' : '',
            $questionsHtml
        );
    }
}
