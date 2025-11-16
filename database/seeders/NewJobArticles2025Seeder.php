<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobArticle;
use App\Models\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewJobArticles2025Seeder extends Seeder
{
    public function run()
    {
        $categories = [
            'offres-emploi' => Category::where('slug', 'offres-emploi')->first(),
            'conseils-carriere' => Category::where('slug', 'conseils-carriere')->first(),
            'opportunites-professionnelles' => Category::where('slug', 'opportunites-professionnelles')->first(),
            'bourses-etudes' => Category::where('slug', 'bourses-etudes')->first(),
        ];

        $articles = [
            [
                'title' => 'Opportunités Tech au Sénégal 2025 : Métiers en Forte Demande',
                'category' => 'opportunites-professionnelles',
                'cover_image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Découvrez les métiers tech les plus recherchés au Sénégal en 2025. Développement web, cybersécurité, intelligence artificielle...',
                'content' => "Le secteur technologique au Sénégal connaît une croissance exponentielle en 2025. Les entreprises cherchent activement des talents dans plusieurs domaines clés.

## Développement Web et Mobile
Les développeurs full-stack et spécialisés en React, Vue.js, et Laravel sont particulièrement recherchés. Les startups locales et internationales recrutent massivement pour leurs projets digitaux.

## Cybersécurité
Avec la digitalisation accélérée des entreprises, les experts en cybersécurité sont devenus indispensables. Les postes de Security Analyst et Ethical Hacker offrent des salaires attractifs.

## Intelligence Artificielle
L'IA transforme tous les secteurs. Les data scientists et machine learning engineers sont très demandés, avec des opportunités dans la finance, la santé, et l'agriculture.

## DevOps et Cloud
L'adoption du cloud computing explose. Les profils AWS, Azure, et DevOps sont recherchés par les grandes entreprises pour moderniser leur infrastructure.

## Blockchain et Crypto
Le Sénégal émerge comme un hub blockchain en Afrique de l'Ouest. Les développeurs blockchain et experts en crypto-monnaies ont de belles opportunités.

Pour se positionner sur ces postes, il est essentiel de se former continuellement et de construire un portfolio solide. Les formations certifiantes et les projets open-source sont des atouts majeurs.",
                'meta_keywords' => ['tech sénégal', 'emploi informatique', 'développeur web', 'cybersécurité', 'IA Sénégal', 'blockchain'],
            ],
            [
                'title' => 'Télétravail au Sénégal : Guide Complet 2025',
                'category' => 'conseils-carriere',
                'cover_image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Comment trouver et réussir dans le télétravail depuis le Sénégal. Conseils pratiques, plateformes et opportunités internationales.',
                'content' => "Le télétravail révolutionne le marché de l'emploi au Sénégal en 2025. De plus en plus de Sénégalais travaillent pour des entreprises internationales depuis Dakar.

## Avantages du Télétravail
- Flexibilité horaire et géographique
- Accès au marché international avec salaires compétitifs
- Équilibre vie professionnelle/personnelle amélioré
- Réduction des coûts de transport

## Plateformes Populaires
Les plateformes comme Upwork, Fiverr, Toptal, et Remote.com connectent les talents sénégalais aux entreprises mondiales. Les profils développeurs, designers, et marketeurs digitaux sont particulièrement recherchés.

## Secteurs Porteurs
- Développement logiciel et web
- Design graphique et UI/UX
- Marketing digital et SEO
- Rédaction et traduction
- Support client multilingue

## Conseils pour Réussir
1. Investir dans une connexion internet stable (fibre recommandée)
2. Créer un espace de travail dédié et professionnel
3. Développer vos compétences en communication interculturelle
4. Construire votre réputation en ligne (testimonials, portfolio)
5. Maîtriser les outils collaboratifs (Slack, Zoom, Trello)

## Fiscalité et Légalité
Les travailleurs indépendants doivent s'enregistrer comme auto-entrepreneur et déclarer leurs revenus. Renseignez-vous sur les accords de double imposition si vous travaillez pour des entreprises étrangères.

Le télétravail ouvre des horizons professionnels inédits pour les Sénégalais, permettant de gagner des salaires internationaux tout en restant au pays.",
                'meta_keywords' => ['télétravail sénégal', 'remote work', 'freelance', 'travail à distance', 'emploi international'],
            ],
            [
                'title' => 'Bourses Études 2025 : Financement pour Masters et Doctorats',
                'category' => 'bourses-etudes',
                'cover_image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Liste actualisée des bourses d\'études disponibles en 2025 pour les étudiants sénégalais. Masters, doctorats, et formations à l\'étranger.',
                'content' => "2025 offre de nombreuses opportunités de bourses d'études pour les étudiants sénégalais souhaitant poursuivre leurs études à l'étranger.

## Bourses Gouvernementales
- Bourse d'Excellence du Sénégal : Pour les meilleurs étudiants
- Bourses de l'AUF (Agence Universitaire de la Francophonie)
- Bourses de la CEDEAO pour la mobilité régionale

## Bourses Internationales
- Programme Erasmus+ pour l'Europe
- Bourses Fulbright pour les États-Unis
- Bourses Chevening (Royaume-Uni)
- Bourses Eiffel (France)
- Bourses VLIR-UOS (Belgique)

## Bourses Privées
- MasterCard Foundation Scholars Program
- Fondation Orange pour le numérique
- Bourses Total Energies
- Fondation L'Oréal pour les femmes scientifiques

## Critères d'Éligibilité
Les critères varient mais incluent généralement :
- Excellence académique (mention Très Bien recommandée)
- Niveau de langue requis (TOEFL, IELTS, DELF)
- Lettre de motivation convaincante
- Projet professionnel clair
- Engagement communautaire (valeur ajoutée)

## Calendrier des Candidatures
- Janvier-Mars : Dépôt des dossiers pour la plupart des bourses
- Avril-Juin : Entretiens et sélections
- Juillet-Septembre : Notifications et préparatifs

## Conseils pour Postuler
1. Commencez tôt (6-12 mois à l'avance)
2. Préparez vos documents (diplômes, relevés, lettres de recommandation)
3. Personnalisez chaque candidature
4. Mettez en avant vos projets et réalisations
5. Pratiquez pour les entretiens en anglais/français

Ces bourses couvrent généralement les frais de scolarité, l'hébergement, et une allocation mensuelle.",
                'meta_keywords' => ['bourses études', 'master', 'doctorat', 'bourses internationales', 'études à l\'étranger'],
            ],
            [
                'title' => 'Entrepreneuriat Digital au Sénégal : Tendances 2025',
                'category' => 'opportunites-professionnelles',
                'cover_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Les secteurs les plus prometteurs pour créer une startup au Sénégal en 2025. Fintech, Agritech, Edtech...',
                'content' => "L'écosystème entrepreneurial sénégalais est en plein essor en 2025, avec un soutien gouvernemental fort et un accès croissant au financement.

## Secteurs à Fort Potentiel

### Fintech
Le mobile money et les services bancaires digitaux explosent. Les opportunités incluent :
- Solutions de paiement mobile innovantes
- Micro-crédit digital
- Plateformes d'épargne et d'investissement

### Agritech
Le Sénégal est un pays agricole avec un énorme potentiel digital :
- Applications de conseil agricole
- Marchés en ligne pour produits agricoles
- IoT pour la gestion des cultures

### Edtech
L'éducation en ligne et les solutions d'apprentissage sont en demande :
- Plateformes de formation professionnelle
- Applications éducatives mobiles
- Solutions de e-learning pour entreprises

### E-commerce et Logistique
Le commerce en ligne se développe rapidement :
- Marketplaces locales spécialisées
- Solutions de livraison last-mile
- Plateformes B2B

## Ressources et Financements
- DER (Fonds d'Investissement Stratégique du Sénégal)
- Orange Digital Center
- CTIC Dakar (incubateur tech)
- Seedstars et concours de pitch

## Avantages au Sénégal
- Coût de la vie abordable
- Main-d'œuvre qualifiée
- Accès au marché ouest-africain (350M+ habitants)
- Support gouvernemental (Plan Sénégal Émergent)

## Étapes pour Démarrer
1. Identifier un problème réel à résoudre
2. Valider votre idée avec des clients potentiels
3. Créer un MVP (Minimum Viable Product)
4. Chercher un co-fondateur complémentaire
5. Postuler aux incubateurs et accélérateurs
6. Lever des fonds (bootstrap, angels, VC)

L'entrepreneuriat digital au Sénégal n'a jamais été aussi accessible qu'en 2025.",
                'meta_keywords' => ['startup sénégal', 'entrepreneuriat', 'fintech', 'agritech', 'innovation'],
            ],
            [
                'title' => 'CV et Lettre de Motivation 2025 : Règles d\'Or',
                'category' => 'conseils-carriere',
                'cover_image' => 'https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Comment créer un CV moderne et une lettre de motivation percutante en 2025. Formats ATS, mots-clés, design.',
                'content' => "En 2025, les règles du recrutement ont évolué. Votre CV et lettre de motivation doivent s'adapter aux nouvelles pratiques.

## CV Moderne 2025

### Format ATS-Friendly
Les systèmes de tracking de candidatures (ATS) scannent automatiquement votre CV. Utilisez :
- Format Word ou PDF simple (pas d'images complexes)
- Sections claires (Expérience, Formation, Compétences)
- Mots-clés pertinents du secteur
- Polices lisibles (Arial, Calibri, Times)

### Structure Optimale
1. **En-tête** : Nom, titre professionnel, contacts (email pro, LinkedIn, ville)
2. **Résumé** : 3-4 lignes résumant votre valeur ajoutée
3. **Expérience** : Format anti-chronologique, actions et résultats quantifiés
4. **Formation** : Diplômes récents en premier
5. **Compétences** : Techniques et soft skills

### Exemples de Formulations
❌ Ancien : \"Responsable de projet\"
✅ Moderne : \"Géré un projet de 50K€ avec 10 collaborateurs, livré 2 semaines en avance\"

### CV en Ligne
- LinkedIn optimisé et à jour
- Portfolio/GitHub pour les tech
- Site personnel pour renforcer votre marque

## Lettre de Motivation Efficace

### Structure en 3 Parties
1. **Accroche** : Pourquoi cette entreprise vous intéresse (recherche préalable obligatoire)
2. **Corps** : Vos compétences alignées avec leurs besoins (exemples concrets)
3. **Conclusion** : Enthousiasme et prochaine étape (entretien)

### Personnalisation
Chaque lettre doit être unique. Mentionnez :
- Projets spécifiques de l'entreprise
- Culture d'entreprise qui vous attire
- Défis du secteur qu'ils affrontent

### Longueur
- 1 page maximum (250-400 mots)
- Paragraphes courts et aérés
- Ton professionnel mais authentique

## Erreurs à Éviter
- Fautes d'orthographe (utilisez Grammarly ou Antidote)
- Informations mensongères
- CV trop long (2 pages max pour 10+ ans d'expérience)
- Oublier de mettre à jour LinkedIn
- Copier-coller des templates sans personnalisation

Un CV et une lettre bien préparés doublent vos chances d'obtenir un entretien.",
                'meta_keywords' => ['CV', 'lettre motivation', 'recrutement', 'candidature', 'emploi'],
            ],
            [
                'title' => 'Secteur Bancaire Sénégal 2025 : Recrutement et Carrières',
                'category' => 'offres-emploi',
                'cover_image' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Les opportunités d\'emploi dans le secteur bancaire au Sénégal en 2025. Postes, compétences requises, salaires.',
                'content' => "Le secteur bancaire sénégalais se digitalise rapidement, créant de nouvelles opportunités professionnelles en 2025.

## Banques Actives au Sénégal
- Société Générale Sénégal
- Attijariwafa Bank
- Ecobank
- Banque Atlantique
- CBAO (Crédit Lyonnais)
- Bank of Africa

## Postes en Demande

### Digital Banking
Les banques recrutent massivement pour :
- Product Managers bancaires digitaux
- Développeurs d'applications bancaires
- UX/UI Designers pour interfaces bancaires
- Data Analysts pour analyse client

### Gestion de Clientèle
- Conseillers en gestion de patrimoine
- Chargés d'affaires entreprises
- Relationship Managers
- Spécialistes crédit

### Conformité et Risque
- Compliance Officers
- Risk Analysts
- Contrôleurs internes
- Spécialistes lutte anti-blanchiment

### Finance et Trésorerie
- Trésoriers
- Analystes financiers
- Controllers
- Gestionnaires de portefeuille

## Profils Recherchés
- Formation : Finance, Économie, Gestion, Informatique
- Compétences digitales (obligatoires maintenant)
- Maîtrise des outils bancaires (T24, Flexcube)
- Certifications : CFA, FRM (atout majeur)

## Salaires Indicatifs
- Conseiller bancaire junior : 200 000 - 350 000 FCFA/mois
- Chargé d'affaires : 400 000 - 700 000 FCFA/mois
- Digital Product Manager : 600 000 - 1 200 000 FCFA/mois
- Directeur d'agence : 800 000 - 1 500 000 FCFA/mois

## Évolution de Carrière
Le secteur offre une progression claire :
1. Conseiller (0-2 ans)
2. Chargé d'affaires (2-5 ans)
3. Chef d'agence adjoint (5-8 ans)
4. Directeur d'agence (8+ ans)

## Avantages du Secteur
- Stabilité de l'emploi
- Avantages sociaux attractifs
- Formation continue
- Opportunités internationales (réseau bancaire)

Le secteur bancaire reste un pilier de l'emploi au Sénégal avec des perspectives d'évolution intéressantes.",
                'meta_keywords' => ['banque sénégal', 'emploi bancaire', 'finance', 'banque digitale', 'carrière bancaire'],
            ],
            [
                'title' => 'Métiers du Marketing Digital 2025 : Compétences Clés',
                'category' => 'conseils-carriere',
                'cover_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Les compétences essentielles pour réussir dans le marketing digital en 2025. SEO, réseaux sociaux, email marketing...',
                'content' => "Le marketing digital évolue rapidement. Voici les compétences incontournables pour 2025.

## Compétences Techniques Essentielles

### SEO et Content Marketing
- Maîtrise des algorithmes Google (RankBrain, BERT, Core Updates)
- Recherche de mots-clés et intent
- Rédaction optimisée SEO
- Audit technique de sites web

### Publicité Digitale
- Google Ads (Search, Display, Shopping)
- Facebook et Instagram Ads
- LinkedIn Ads pour le B2B
- TikTok Ads (en forte croissance)
- Optimisation des campagnes (ROAS)

### Analytics et Data
- Google Analytics 4 (GA4) et migration Universal Analytics
- Tableaux de bord personnalisés
- Interprétation des métriques business
- A/B testing et expérimentation

### E-mail Marketing
- Plateformes (Mailchimp, Sendinblue, Klaviyo)
- Segmentation avancée
- Automatisation (drip campaigns)
- Personnalisation à l'échelle

## Compétences Soft
- Créativité et storytelling
- Capacité d'analyse
- Adaptabilité (le digital change vite)
- Collaboration cross-fonctionnelle

## Outils à Maîtriser
- **Analytics** : Google Analytics, Adobe Analytics
- **Publicité** : Google Ads Editor, Facebook Ads Manager
- **SEO** : Ahrefs, SEMrush, Screaming Frog
- **Social Media** : Hootsuite, Buffer, Later
- **Email** : Mailchimp, ActiveCampaign
- **Design** : Canva, Figma (basics)

## Certifications Valorantes
- Google Analytics Individual Qualification
- Google Ads Certifications
- Facebook Blueprint
- HubSpot Content Marketing
- Coursera Digital Marketing Specialization

## Tendances 2025
1. **Marketing automatisé par IA** : ChatGPT pour le contenu, outils d'optimisation IA
2. **Video-first** : YouTube Shorts, TikTok, Reels
3. **Privacité renforcée** : Post-cookies, first-party data
4. **Influence marketing** : Micro et nano-influenceurs
5. **Sustainability marketing** : Marques éco-responsables

## Salaire Moyen au Sénégal
- Community Manager junior : 150 000 - 300 000 FCFA/mois
- Digital Marketer : 300 000 - 600 000 FCFA/mois
- Growth Marketing Manager : 500 000 - 1 000 000 FCFA/mois
- Head of Marketing : 800 000 - 1 500 000 FCFA/mois

Le marketing digital offre des opportunités multiples pour ceux qui s'adaptent rapidement aux nouvelles technologies.",
                'meta_keywords' => ['marketing digital', 'SEO', 'publicité', 'social media', 'growth marketing'],
            ],
            [
                'title' => 'Concours Fonction Publique Sénégal 2025 : Guide Complet',
                'category' => 'offres-emploi',
                'cover_image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Comment se préparer aux concours de la fonction publique au Sénégal en 2025. Inscriptions, épreuves, conseils.',
                'content' => "Les concours de la fonction publique sénégalaise offrent des opportunités de carrière stables et prestigieuses.

## Types de Concours

### Concours Directs
- Inspecteur des Impôts et Domaines
- Agent de Trésor Public
- Inspecteur du Travail
- Agent des Douanes
- Personnel de Santé (médecins, infirmiers)
- Éducation (professeurs, administrateurs)

### Concours Professionnels
Pour les agents déjà en poste souhaitant évoluer :
- Promotion interne
- Concours de passage d'échelle
- Mutation et avancement

## Calendrier 2025
- **Janvier-Mars** : Publication des avis de concours
- **Avril-Juin** : Inscriptions et dépôt des dossiers
- **Juillet-Septembre** : Épreuves écrites
- **Octobre-Décembre** : Oraux et résultats

## Inscription
Les dossiers sont déposés au ministère concerné ou via la plateforme dédiée :
- Diplômes requis (varie selon le poste)
- Casier judiciaire (bulletin n°3)
- Certificat de nationalité
- Copies des diplômes certifiées conformes
- Photographies d'identité

## Épreuves Typiques

### Écrites
- Culture générale (dissertation)
- Français (grammaire, orthographe)
- Spécialité (selon le poste)
- Mathématiques/Logique (selon le poste)
- QCM de culture administrative

### Oraux
- Entretien avec le jury
- Mise en situation professionnelle
- Questions de spécialité

## Préparation Efficace

### Ressources
- Programmes officiels des années précédentes
- Annales de concours
- Manuels de culture générale
- Préparation avec instituts spécialisés

### Stratégie
1. **6 mois à l'avance** : Identifier le concours ciblé
2. **Planification** : 3-4 heures de révision par jour
3. **Annales** : S'entraîner sur les sujets précédents
4. **Actualité** : Suivre l'actualité nationale et internationale
5. **Simulation** : S'exercer en conditions d'examen

## Atouts pour Réussir
- Excellente maîtrise du français écrit et oral
- Culture générale solide (histoire, géo, actualité)
- Connaissance du fonctionnement de l'État
- Capacité de synthèse et d'analyse
- Maîtrise du stress

## Avantages de la Fonction Publique
- Emploi stable (statut de fonctionnaire)
- Évolution de carrière prévisible
- Avantages sociaux complets
- Retraite assurée
- Prestige social

Les concours de la fonction publique attirent de nombreux candidats. Une préparation rigoureuse est essentielle pour réussir.",
                'meta_keywords' => ['concours', 'fonction publique', 'emploi public', 'concours sénégal'],
            ],
            [
                'title' => 'Secteur Énergétique Sénégal 2025 : Opportunités Énergies Renouvelables',
                'category' => 'opportunites-professionnelles',
                'cover_image' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Les opportunités dans le secteur énergétique, notamment les énergies renouvelables au Sénégal en 2025.',
                'content' => "Le Sénégal investit massivement dans les énergies renouvelables, créant des milliers d'opportunités professionnelles.

## Plan Sénégal Émergent - Énergie
Le gouvernement vise 30% d'énergies renouvelables d'ici 2030, avec des projets majeurs en cours.

## Projets Majeurs
- **Parc éolien de Taiba Ndiaye** : 158,7 MW (opérationnel)
- **Centrale solaire de Bokhol** : 20 MW
- **Projets solaires** : Kahone, Diass, Thiès
- **Centrale hydroélectrique** : Projets en développement

## Secteurs Porteurs

### Ingénierie
- Ingénieurs en énergies renouvelables
- Ingénieurs électriques spécialisés
- Ingénieurs en génie civil (infrastructure)
- Project Managers énergie

### Exploitation et Maintenance
- Techniciens de maintenance éolienne
- Techniciens solaires
- Opérateurs de centrales
- Contrôleurs qualité

### Commercial et Business Development
- Business Developers énergie
- Responsables partenariats
- Chargés d'affaires projets
- Account Managers

### Finance et Juridique
- Analystes financiers énergie
- Juristes spécialisés PPP (Partenariats Public-Privé)
- Contrôleurs de gestion
- Risk Managers

## Entreprises Actives
- Senelec (compagnie nationale)
- Lekela Power
- Engie (via filiales)
- Total Energies (solaire)
- GreenWish Partners

## Compétences Requises
- Formation technique (électricité, mécanique, génie civil)
- Connaissance des énergies renouvelables
- Maîtrise des normes et standards internationaux
- Langues (français, anglais)
- Certifications professionnelles (atout)

## Salaires Indicatifs
- Technicien : 250 000 - 450 000 FCFA/mois
- Ingénieur junior : 500 000 - 800 000 FCFA/mois
- Ingénieur senior : 800 000 - 1 500 000 FCFA/mois
- Project Manager : 1 000 000 - 2 000 000 FCFA/mois

## Formation
- École Supérieure Polytechnique (ESP)
- Institut Supérieur de Formation Professionnelle
- Certifications internationales (IRENA, GIZ)

## Perspectives
Le secteur énergétique offre une stabilité et une croissance à long terme, avec des projets d'envergure pour les 10 prochaines années.

C'est un secteur d'avenir au Sénégal, combinant impact environnemental et opportunités professionnelles attractives.",
                'meta_keywords' => ['énergie renouvelable', 'solaire', 'éolien', 'secteur énergétique', 'emploi énergie'],
            ],
            [
                'title' => 'Candidature Spontanée Efficace : Techniques 2025',
                'category' => 'candidature-spontanee',
                'cover_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Comment rédiger et envoyer une candidature spontanée qui attire l\'attention des recruteurs en 2025.',
                'content' => "La candidature spontanée reste une méthode efficace pour décrocher un emploi, à condition de bien la préparer.

## Pourquoi Candidater Spontanément ?
- Vous pouvez cibler précisément les entreprises qui vous intéressent
- Moins de concurrence qu'aux offres publiées
- Démonstration de proactivité appréciée
- Opportunité de créer votre propre poste

## Identification des Entreprises Cibles

### Critères de Sélection
- Secteur d'activité aligné avec vos compétences
- Culture d'entreprise qui vous correspond
- Taille adaptée à votre profil (startup vs grande entreprise)
- Potentiel de croissance
- Localisation géographique

### Recherche
- Réseaux sociaux (LinkedIn, Facebook)
- Annuaires professionnels (CCI, APIX)
- Salons professionnels et événements sectoriels
- Plateformes : Jobberman, Emploi.sn, Careerjet

## Rédaction de la Candidature

### Email de Candidature
**Objet** : Claire et accrocheuse
Exemple : \"Candidature spontanée - [Votre Titre] - [Secteur d'activité]\"

**Structure** :
1. **Accroche** (1 paragraphe) : Pourquoi cette entreprise vous intéresse spécifiquement
2. **Valeur ajoutée** (2-3 paragraphes) : Vos compétences et comment elles bénéficient à l'entreprise
3. **Appel à l'action** : Suggérer un entretien

### Contenu à Inclure
- Recherche préalable sur l'entreprise (projets, valeurs, actualité)
- Compétences alignées avec leurs besoins
- Exemples concrets de réalisations
- Propositions de valeur (idées de projets, améliorations)

### Pièces Jointes
- CV moderne et personnalisé
- Lettre de motivation adaptée
- Portfolio (si applicable)
- Certifications pertinentes

## Canaux de Transmission

### Email Direct
Cibler le responsable RH ou le directeur du service visé (via LinkedIn)

### LinkedIn
- Message personnalisé + demande de connexion
- Partager du contenu pertinent pour montrer votre expertise
- Commenter les posts de l'entreprise intelligemment

### Candidature sur Site Web
Si l'entreprise a une section \"Candidature spontanée\" sur son site

### Réseau Personnel
Utiliser vos contacts pour une recommandation interne

## Timing Optimal
- Mardi à jeudi matin (éviter lundi matin et vendredi après-midi)
- Périodes de recrutement actif (janvier-mars, septembre-novembre)
- Après événements sectoriels où vous avez rencontré l'entreprise

## Suivi
- Relance polie après 10-15 jours si pas de réponse
- Restez professionnel même en cas de refus
- Gardez le contact pour des opportunités futures

## Taux de Réussite
Les candidatures spontanées bien préparées ont un taux de réponse de 15-25%, avec 5-10% d'entretiens.

L'effort supplémentaire de personnalisation paie : une candidature spontanée ciblée vaut 10 candidatures génériques.",
                'meta_keywords' => ['candidature spontanée', 'spontanée', 'candidature', 'recherche emploi', 'prospection'],
            ],
            [
                'title' => 'Métiers de la Santé au Sénégal 2025 : Formation et Recrutement',
                'category' => 'offres-emploi',
                'cover_image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=1200&h=630&fit=crop&auto=format',
                'excerpt' => 'Les opportunités dans le secteur de la santé au Sénégal. Formation, concours, et carrières médicales en 2025.',
                'content' => "Le secteur de la santé au Sénégal recrute activement, avec des besoins en personnel qualifié dans tous les domaines.

## Formations Médicales

### Médecine
- **FMPOS** (Faculté de Médecine, de Pharmacie et d'Odontostomatologie)
- **UCAD** (Université Cheikh Anta Diop)
- Durée : 7 ans (externat, internat)
- Spécialisations : Cardiologie, Pédiatrie, Chirurgie, etc.

### Pharmacie
- FMPOS - Département Pharmacie
- Durée : 5 ans
- Spécialisations : Pharmacie clinique, Industrie pharmaceutique

### Infirmier(e)
- Écoles d'infirmiers d'État
- **ISFPS** (Instituts Supérieurs de Formation en Profession de Santé)
- Durée : 3 ans

### Sage-femme
- Écoles de sages-femmes
- Durée : 4 ans

## Postes Disponibles

### Secteur Public
- Médecins généralistes et spécialistes (concours)
- Pharmaciens hospitaliers
- Infirmiers d'État
- Sages-femmes
- Techniciens de laboratoire
- Personnel administratif santé

### Secteur Privé
- Cliniques privées (médecins, infirmiers)
- Pharmacies privées
- Laboratoires d'analyses
- Centres de santé communautaires

### ONG et International
- Médecins Sans Frontières (MSF)
- Plan International
- Croix-Rouge
- ONU (OMS, UNICEF)

## Salaires Indicatifs

### Secteur Public
- Interne médecine : 200 000 - 350 000 FCFA/mois
- Médecin généraliste débutant : 500 000 - 700 000 FCFA/mois
- Spécialiste : 800 000 - 1 500 000 FCFA/mois
- Infirmier d'État : 250 000 - 400 000 FCFA/mois

### Secteur Privé
- Médecin généraliste : 700 000 - 1 200 000 FCFA/mois
- Spécialiste : 1 200 000 - 3 000 000 FCFA/mois
- Infirmier privé : 200 000 - 350 000 FCFA/mois

## Concours Public
- Concours annuel du Ministère de la Santé
- Épreuves écrites et orales
- Affectation selon classement et besoins

## Compétences Requises
- Excellentes bases scientifiques
- Qualités humaines (empathie, patience)
- Résistance au stress
- Formation continue obligatoire
- Maîtrise des outils numériques (télémédecine en développement)

## Spécialités en Demande
- Médecine d'urgence
- Pédiatrie
- Gynécologie-obstétrique
- Santé publique
- Télémédecine (nouveau)

## Évolution
- Internat → Résidanat → Spécialisation
- Formation continue (DU, DIU)
- Recherche universitaire
- Direction d'établissements

Le secteur de la santé offre une vocation et une stabilité de carrière, avec un impact social fort.",
                'meta_keywords' => ['santé', 'médecine', 'infirmier', 'pharmacien', 'emploi santé sénégal'],
            ],
        ];

        foreach ($articles as $articleData) {
            $category = $categories[$articleData['category']] ?? $categories['offres-emploi'];
            
            $article = JobArticle::create([
                'category_id' => $category->id,
                'title' => $articleData['title'],
                'slug' => Str::slug($articleData['title']),
                'excerpt' => $articleData['excerpt'],
                'content' => $articleData['content'],
                'cover_image' => $articleData['cover_image'],
                'cover_type' => 'external',
                'meta_title' => $articleData['title'] . ' | NiangProgrammeur',
                'meta_description' => $articleData['excerpt'],
                'meta_keywords' => $articleData['meta_keywords'],
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(rand(1, 30)),
                'views' => rand(50, 500),
                'seo_score' => rand(75, 95),
                'readability_score' => rand(70, 90),
            ]);

            echo "Article créé : {$article->title}\n";
        }

        echo "\n✅ 10 nouveaux articles créés avec succès !\n";
    }
}

