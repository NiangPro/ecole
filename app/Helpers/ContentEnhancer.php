<?php

namespace App\Helpers;

use Illuminate\Support\Str;

/**
 * ContentEnhancer : Génère du contenu expert unique pour combattre le thin content
 * 
 * Stratégie SEO :
 * - Génère des paragraphes 100% uniques basés sur le titre et la localisation
 * - Injecte "Conseils de l'Expert NiangProgrammeur" pour enrichir le contenu
 * - Améliore le SEO score et la profondeur du contenu
 */
class ContentEnhancer
{
    private const EXPERT_INTRO = [
        "💡 Conseil d'Expert NiangProgrammeur :",
        "📌 Astuce Professionnelle :",
        "🎯 Recommandation du Décodeur de Carrière :",
        "⚡ Point clé à retenir :",
        "🏆 Best Practice Sénégalaise :",
    ];

    private const LOCATION_CONTEXT = [
        'dakar' => [
            'Hub économique du Sénégal',
            'Centre des opportunités professionnelles',
            'Siège des grandes entreprises sénégalaises',
            'Marché du travail dynamique et compétitif',
            'Zone de concentration des recruteurs',
        ],
        'teletravaill' => [
            'Flexibilité géographique croissante',
            'Tendance post-COVID des entreprises',
            'Opportunité pour les talents dispersés',
            'Réduction des coûts de déplacement',
            'Productivité accrue et meilleur équilibre travail-vie',
        ],
        'senegal' => [
            'Marché de l\'emploi en pleine croissance',
            'Secteurs porteurs : Tech, Services, Finance',
            'Compétences numériques très demandées',
            'Opportunités dans l\'Afrique de l\'Ouest',
            'Réseau professionnel continental en expansion',
        ],
        'international' => [
            'Marché global de l\'emploi',
            'Compétences transversales valorisées',
            'Réseautage international indispensable',
            'Certifications reconnues mondialement',
            'Mobilité professionnelle croissante',
        ],
    ];

    private const CAREER_STRATEGIES = [
        'offre' => [
            'personnalisez votre candidature en mettant l\'accent sur les compétences demandées.',
            'incluez des projets concrets qui démontrent votre valeur ajoutée dès le premier regard.',
            'préparez des questions intelligentes pour l\'entretien : les recruteurs sénégalais valorisent l\'initiative.',
            'recherchez le profil du manager en ligne (LinkedIn, etc.) pour adapter votre discours.',
            'soignez votre lettre de motivation : c\'est votre différentiateur dans un marché compétitif.',
            'adaptez votre CV au format attendu localement — une page suffit pour les postes juniors.',
            'relancez le recruteur après 7 jours sans réponse : la persévérance est très appréciée au Sénégal.',
        ],
        'concours' => [
            'planifiez votre préparation au moins 2 mois avant l\'examen pour couvrir tout le programme.',
            'résolvez les épreuves des années précédentes régulièrement — c\'est la méthode la plus efficace.',
            'identifiez vos points faibles et intensifiez leur révision en priorité absolue.',
            'formez un groupe d\'étude de 3 à 5 personnes pour confronter vos connaissances.',
            'dormez 7-8 heures la veille du concours : la concentration vaut toutes les révisions de dernière minute.',
            'lisez attentivement les barèmes de notation avant de commencer votre préparation.',
            'constituez votre dossier administratif bien en avance pour éviter les dépôts tardifs.',
        ],
        'bourses' => [
            'constituez un dossier complet et bien présenté — les formulaires incomplets sont systématiquement rejetés.',
            'rédigez une lettre de motivation personnalisée et convaincante, en évitant les formules génériques.',
            'demandez des lettres de recommandation à des professeurs ou employeurs qui vous connaissent bien.',
            'respectez scrupuleusement les délais de candidature — les dossiers tardifs ne sont pas traités.',
            'mettez en avant votre impact potentiel sur votre communauté et vos ambitions à long terme.',
            'faites relire votre dossier par un pair ou un mentor avant soumission finale.',
            'préparez-vous à un entretien oral même si non mentionné — certains organismes l\'ajoutent à la dernière minute.',
        ],
        'administratif' => [
            'rassemblez toutes les pièces en original ET en copie certifiée conforme avant de vous déplacer.',
            'vérifiez les horaires d\'ouverture du service concerné sur le site officiel ou par téléphone la veille.',
            'conservez un accusé de réception ou récépissé pour chaque dépôt de dossier.',
            'renseignez-vous sur les délais de traitement pour anticiper vos démarches administratives.',
            'préparez une carte d\'identité nationale ou un passeport valide — c\'est indispensable pour toute démarche.',
            'anticipez les frais de timbre fiscal ou de légalisation souvent requis par les administrations sénégalaises.',
            'notez le numéro de dossier ou de référence dès l\'enregistrement de votre demande.',
        ],
    ];

    /**
     * Déduit le type de contenu à partir du slug de la catégorie.
     * Utilité : les contrôleurs appellent cette méthode pour éviter la duplication de logique.
     */
    public static function getContentTypeFromSlug(?string $categorySlug): string
    {
        if (!$categorySlug) return 'offre';

        if (str_contains($categorySlug, 'concours')) return 'concours';
        if (str_contains($categorySlug, 'bourse'))   return 'bourses';
        if (str_contains($categorySlug, 'admin') || str_contains($categorySlug, 'document')) return 'administratif';

        return 'offre';
    }

    /**
     * Génère un paragraphe expert 100 % unique basé sur le titre, la localisation et le type.
     *
     * La combinatoire (intro × stratégie × contexte × keywords) produit >500 variantes
     * distinctes sans aucune dépendance à une IA externe.
     *
     * @param string      $title    Titre de l'offre, du concours ou de la ressource
     * @param string|null $location Localisation (ex: Dakar, Télétravail, Sénégal)
     * @param string|null $type     Type de contenu (offre, concours, bourses, administratif)
     * @return string Paragraphe unique généré
     */
    public static function generateExpertAdvice(
        string $title,
        ?string $location = null,
        ?string $type = 'offre'
    ): string {
        // Seed déterministe basé sur le titre pour stabiliser le texte entre les rechargements
        $seed = crc32(strtolower($title));
        srand($seed);

        $introList   = self::EXPERT_INTRO;
        $intro       = $introList[$seed % count($introList)];

        $strategy    = self::getStrategiesForType($type);
        $tip         = $strategy[$seed % count($strategy)];

        $locationCtx = self::getLocationContext($location);
        $contexte    = $locationCtx[($seed >> 4) % count($locationCtx)];

        $keywords    = self::extractKeywords($title);
        $kw          = !empty($keywords)
            ? ' autour de « ' . implode(' et ', array_slice($keywords, 0, 2)) . ' »'
            : '';

        // Restaurer le générateur aléatoire PHP après usage du seed
        srand();

        return sprintf(
            '%s Pour maximiser vos chances%s dans ce contexte de %s, il est essentiel de %s '
            . 'La communauté NiangProgrammeur vous accompagne : préparation méthodique et maîtrise '
            . 'du marché local sont les deux piliers du succès professionnel en Afrique de l\'Ouest.',
            $intro,
            $kw,
            $contexte,
            lcfirst($tip)
        );
    }

    /**
     * Génère plusieurs paragraphes experts (pour créer du contenu riche)
     */
    public static function generateMultipleExpertSections(
        string $title,
        ?string $location = null,
        ?string $type = 'offre',
        int $count = 1
    ): string {
        $sections = [];
        
        for ($i = 0; $i < $count; $i++) {
            $sections[] = self::generateExpertAdvice($title, $location, $type);
        }

        return implode("\n\n", $sections);
    }

    /**
     * Injecte un encadré "Conseil Expert" HTML dans le contenu
     * Position : après le 3ème paragraphe ou à la fin
     */
    public static function injectExpertBoxInContent(
        string $content,
        string $title,
        ?string $location = null,
        ?string $type = 'offre'
    ): string {
        $expertText = self::generateExpertAdvice($title, $location, $type);
        
        // Créer l'encadré HTML avec styles Vert Canard/Turquoise
        $expertBox = self::buildExpertHTMLBox($expertText, $type);

        // Compter les paragraphes
        $paragraphs = explode('</p>', $content);
        $insertionPoint = min(3, count($paragraphs) - 1); // Après 3e paragraphe ou fin

        if ($insertionPoint > 0) {
            array_splice($paragraphs, $insertionPoint, 0, [$expertBox]);
            return implode('</p>', $paragraphs);
        }

        return $content . $expertBox;
    }

    /**
     * Génère l'HTML de l'encadré expert
     */
    private static function buildExpertHTMLBox(string $text, string $type = 'offre'): string {
        $icon = match($type) {
            'concours' => '🏆',
            'bourses' => '🎓',
            default => '💡',
        };

        return <<<HTML
            <div class="expert-advice-box" style="
                background: linear-gradient(135deg, rgba(5, 150, 105, 0.08), rgba(8, 145, 178, 0.08));
                border-left: 5px solid #059669;
                border-radius: 8px;
                padding: 1.5rem;
                margin: 1.5rem 0;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            ">
                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <span style="font-size: 1.5rem; flex-shrink: 0;">$icon</span>
                    <div>
                        <strong style="color: #059669; display: block; margin-bottom: 0.5rem; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            Conseil de l'Expert NiangProgrammeur
                        </strong>
                        <p style="margin: 0; color: #475569; line-height: 1.6; font-size: 0.95rem;">
                            $text
                        </p>
                    </div>
                </div>
            </div>
        HTML;
    }

    /**
     * Extrait les mots-clés du titre
     */
    private static function extractKeywords(string $title): array {
        // Supprimer les accents et convertir en minuscules
        $cleaned = Str::ascii(strtolower($title));
        
        // Mots vides communs (stopwords)
        $stopwords = [
            'et', 'ou', 'un', 'une', 'des', 'le', 'la', 'les', 'de', 'du', 'en', 
            'pour', 'par', 'a', 'au', 'aux', 'est', 'son', 'son', 'sa', 'ses'
        ];

        // Récupérer les mots de plus de 3 caractères
        $words = array_filter(
            str_word_count($cleaned, 1, 'àâäæèéêëìîïòôöœùûüçñ'),
            fn($word) => !in_array($word, $stopwords) && strlen($word) > 3
        );

        return array_slice(array_unique($words), 0, 3);
    }

    /**
     * Récupère les stratégies pour un type de contenu
     */
    private static function getStrategiesForType(string $type): array {
        return self::CAREER_STRATEGIES[$type]
            ?? self::CAREER_STRATEGIES['offre'];
    }

    /**
     * Normalise et récupère le contexte de localisation
     */
    private static function getLocationContext(?string $location): array {
        if (!$location) {
            return self::LOCATION_CONTEXT['senegal'];
        }

        $normalized = strtolower(Str::ascii($location));

        foreach (self::LOCATION_CONTEXT as $key => $contexts) {
            if (str_contains($normalized, $key) || str_contains($key, substr($normalized, 0, 4))) {
                return $contexts;
            }
        }

        return self::LOCATION_CONTEXT['senegal']; // fallback par défaut
    }

    /**
     * Génère une FAQ riche basée sur le titre et la localisation
     * Utile pour la Tâche 3 (Schema FAQ)
     */
    public static function generateFAQs(
        string $title,
        ?string $location = null,
        ?string $type = 'offre'
    ): array {
        $keywords = self::extractKeywords($title);
        $keywordPhrase = !empty($keywords) ? implode(' ', array_slice($keywords, 0, 2)) : 'cette opportunité';
        
        $faqTemplates = match($type) {
            'concours' => [
                "Quels sont les documents requis pour le concours?" => "Consultez la liste complète des pièces à fournir sur le site de l'organisme. Généralement, vous aurez besoin d'une pièce d'identité valide, de relevés scolaires, et de certificats de stage.",
                "Comment bien préparer le concours?" => "Étudiez les annales des années précédentes, formez un groupe d'étude avec vos pairs, et suivez les cours spécialisés disponibles sur NiangProgrammeur.",
                "Quel est le coût de participation?" => "Consultez les frais de candidature auprès de l'organisme responsable. De nombreux concours du Sénégal sont gratuits ou peu onéreux.",
            ],
            'bourses' => [
                "Suis-je éligible à cette bourse?" => "Vérifiez les critères d'éligibilité spécifiques. Généralement, vous devez être citoyen sénégalais ou ressortissant d'un pays de la CEDEAO, et avoir des résultats scolaires satisfaisants.",
                "Comment candidater?" => "Suivez les instructions du site de l'organisme. Préparez un dossier complet avec lettre de motivation, relevés académiques, et lettres de recommandation.",
                "Quand est la date limite de candidature?" => "Consultez le calendrier officiel. Les délais varient selon l'organisme de bourse.",
            ],
            default => [
                "Comment candidater pour $keywordPhrase?" => "Préparez votre CV, lettre de motivation et pièces justificatives. Consultez l'offre pour connaître les modalités exactes de candidature.",
                "Quelles sont les compétences requises?" => "Lisez attentivement la description de l'offre. Les compétences clés y sont détaillées. Développez-les via les cours de NiangProgrammeur.",
                "Quel est le processus de recrutement?" => "Généralement : candidature → examen des dossiers → entretiens → offre. Préarez-vous à chaque étape.",
            ]
        };

        $faqs = [];
        foreach ($faqTemplates as $question => $answer) {
            $faqs[] = [
                'question' => $question,
                'answer' => $answer,
            ];
        }

        return $faqs;
    }
}
