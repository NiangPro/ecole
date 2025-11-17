<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobArticle;
use App\Models\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ConcoursArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'concours')->first();
        
        if (!$category) {
            $this->command->error('Catégorie "Concours" introuvable. Exécutez d\'abord ConcoursCategorySeeder.');
            return;
        }

        $articles = [
            [
                'title' => 'Concours de Recrutement de la Fonction Publique Sénégalaise 2025',
                'slug' => 'concours-fonction-publique-senegal-2025',
                'excerpt' => 'Découvrez toutes les informations sur les concours de recrutement de la fonction publique sénégalaise en 2025. Dates, conditions, et procédures d\'inscription.',
                'content' => 'Le Sénégal organise chaque année des concours de recrutement pour la fonction publique. Ces concours permettent d\'intégrer différents corps de métiers dans l\'administration publique sénégalaise.

## Types de Concours

### Concours Directs
Les concours directs sont ouverts aux candidats titulaires d\'un diplôme de niveau requis. Ils permettent d\'accéder directement à un poste dans la fonction publique.

### Concours Professionnels
Les concours professionnels sont réservés aux agents déjà en fonction qui souhaitent évoluer dans leur carrière ou changer de corps.

## Conditions Générales d\'Admission

- Être de nationalité sénégalaise
- Être âgé de 18 ans au moins
- Jouir de ses droits civiques
- Être de bonne moralité
- Remplir les conditions d\'aptitude physique requises
- Posséder le diplôme ou le titre requis pour le concours

## Procédure d\'Inscription

1. **Consultation des avis de concours** : Les avis sont publiés dans les journaux officiels et sur les sites web des ministères concernés
2. **Téléchargement du dossier** : Le dossier d\'inscription est généralement disponible en ligne
3. **Composition du dossier** : 
   - Formulaire d\'inscription dûment rempli
   - Photocopies certifiées conformes des diplômes
   - Extrait d\'acte de naissance
   - Certificat de nationalité
   - Casier judiciaire (bulletin n°3)
   - Photocopies de la carte nationale d\'identité
   - Photos d\'identité
4. **Dépôt du dossier** : Le dossier doit être déposé dans les délais impartis

## Épreuves des Concours

Les concours comprennent généralement :
- **Épreuves écrites** : Culture générale, spécialité, langue
- **Épreuves orales** : Entretien avec le jury, exposé sur un sujet
- **Épreuves pratiques** : Pour certains concours techniques

## Préparation aux Concours

Pour réussir les concours de la fonction publique, il est recommandé de :
- S\'informer régulièrement sur les dates et conditions
- Préparer les épreuves écrites et orales
- Suivre l\'actualité nationale et internationale
- S\'entraîner avec les annales des années précédentes
- Participer à des sessions de préparation si disponibles

## Calendrier 2025

Les concours sont généralement organisés tout au long de l\'année. Restez informé en consultant régulièrement :
- Le Journal Officiel du Sénégal
- Les sites web des ministères
- Les centres d\'information et d\'orientation

## Conseils pour Réussir

1. **Organisation** : Établissez un planning de révision
2. **Régularité** : Travaillez de manière constante
3. **Documentation** : Rassemblez tous les documents nécessaires à l\'avance
4. **Entraînement** : Pratiquez avec les sujets des années précédentes
5. **Confiance** : Croyez en vos capacités et restez motivé

Bon courage à tous les candidats !',
                'cover_image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1200&h=630&fit=crop',
                'cover_type' => 'external',
                'category_id' => $category->id,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(2),
                'meta_title' => 'Concours Fonction Publique Sénégal 2025 - Guide Complet',
                'meta_description' => 'Tout savoir sur les concours de recrutement de la fonction publique sénégalaise en 2025. Dates, conditions, procédures d\'inscription et conseils pour réussir.',
                'meta_keywords' => ['concours', 'fonction publique', 'sénégal', 'recrutement', '2025', 'administration'],
            ],
            [
                'title' => 'Concours d\'Entrée à l\'École Nationale d\'Administration (ENA) du Sénégal 2025',
                'slug' => 'concours-ena-senegal-2025',
                'excerpt' => 'Informations complètes sur le concours d\'entrée à l\'ENA du Sénégal. Formation d\'excellence pour les futurs cadres de l\'administration sénégalaise.',
                'content' => 'L\'École Nationale d\'Administration (ENA) du Sénégal est l\'institution de référence pour la formation des hauts fonctionnaires. Le concours d\'entrée est très sélectif et exige une préparation rigoureuse.

## Présentation de l\'ENA

L\'ENA forme les cadres supérieurs de l\'administration publique sénégalaise. Les diplômés occupent des postes de responsabilité dans les ministères, les institutions publiques et les organisations internationales.

## Conditions d\'Admission

- Être de nationalité sénégalaise
- Être âgé de 30 ans au plus au 31 décembre de l\'année du concours
- Être titulaire d\'un diplôme de niveau Master ou équivalent
- Avoir une expérience professionnelle d\'au moins 2 ans (pour certains concours)

## Types de Concours

### Concours Externe
Ouvert aux candidats titulaires d\'un diplôme de niveau Master, sans condition d\'âge pour certains profils.

### Concours Interne
Réservé aux fonctionnaires en activité depuis au moins 4 ans.

### Troisième Concours
Ouvert aux candidats justifiant d\'une expérience professionnelle dans le secteur privé.

## Épreuves du Concours

### Épreuves d\'Admissibilité
1. **Dissertation de culture générale** (4h, coefficient 3)
2. **Épreuve de spécialité** selon le profil (4h, coefficient 4)
3. **Langue vivante** : Anglais, Espagnol ou Arabe (3h, coefficient 2)
4. **Épreuve à option** : Droit, Économie, ou Gestion (3h, coefficient 2)

### Épreuves d\'Admission
1. **Entretien avec le jury** (30 min, coefficient 5)
2. **Épreuve orale de langue** (20 min, coefficient 2)
3. **Épreuve de spécialité orale** (30 min, coefficient 3)

## Programme de Formation

La formation à l\'ENA dure 2 ans et comprend :
- **Première année** : Formation théorique et stages en administration
- **Deuxième année** : Spécialisation et stage à l\'étranger

## Débouchés

Les diplômés de l\'ENA accèdent à des postes de :
- Directeur de cabinet ministériel
- Directeur d\'administration centrale
- Ambassadeur
- Préfet
- Directeur d\'établissement public
- Expert dans les organisations internationales

## Préparation au Concours

### Conseils pour Réussir

1. **Maîtrise de la culture générale** : Suivez l\'actualité nationale et internationale
2. **Renforcement de la spécialité** : Approfondissez vos connaissances dans votre domaine
3. **Pratique des langues** : Travaillez régulièrement l\'anglais et une autre langue
4. **Entraînement aux épreuves** : Utilisez les annales des années précédentes
5. **Préparation à l\'oral** : Entraînez-vous à l\'expression orale et à la gestion du stress

## Calendrier 2025

- **Publication de l\'avis** : Février-Mars 2025
- **Inscriptions** : Mars-Avril 2025
- **Épreuves écrites** : Mai-Juin 2025
- **Résultats d\'admissibilité** : Juillet 2025
- **Épreuves orales** : Août 2025
- **Résultats finaux** : Septembre 2025

## Ressources Utiles

- Site officiel de l\'ENA du Sénégal
- Annales des concours précédents
- Centres de préparation agréés
- Bibliothèques spécialisées

Bon courage dans votre préparation !',
                'cover_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200&h=630&fit=crop',
                'cover_type' => 'external',
                'category_id' => $category->id,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(5),
                'meta_title' => 'Concours ENA Sénégal 2025 - Guide Complet d\'Admission',
                'meta_description' => 'Tout savoir sur le concours d\'entrée à l\'École Nationale d\'Administration du Sénégal. Conditions, épreuves, préparation et débouchés.',
                'meta_keywords' => ['ENA', 'concours', 'sénégal', 'administration', 'formation', 'cadres'],
            ],
            [
                'title' => 'Concours de Recrutement dans les Entreprises Publiques du Sénégal 2025',
                'slug' => 'concours-entreprises-publiques-senegal-2025',
                'excerpt' => 'Découvrez les opportunités de concours dans les entreprises publiques sénégalaises : SDE, SDEEC, SENELEC, APS, et bien d\'autres.',
                'content' => 'Les entreprises publiques sénégalaises organisent régulièrement des concours de recrutement pour pourvoir leurs postes. Ces concours offrent des opportunités de carrière intéressantes dans différents secteurs.

## Entreprises Publiques Concernées

### Secteur de l\'Énergie
- **SENELEC** (Société Nationale d\'Électricité du Sénégal)
- **SDE** (Sénégalaise des Eaux)
- **SDEEC** (Sénégalaise des Eaux de l\'Électricité et de l\'Énergie)

### Secteur des Transports
- **APIX** (Agence de Promotion des Investissements et des Grands Travaux)
- **AIBD** (Aéroport International Blaise Diagne)
- **Transrail**

### Secteur des Télécommunications
- **SONATEL** (Société Nationale des Télécommunications)

### Secteur Financier
- **Caisse de Dépôts et de Consignations (CDC)**
- **Banque de l\'Habitat du Sénégal (BHS)**

### Autres Secteurs
- **APS** (Agence de Presse Sénégalaise)
- **RTS** (Radiodiffusion Télévision Sénégalaise)
- **SENELEC Distribution**

## Types de Postes Recrutés

### Postes Techniques
- Ingénieurs (électricité, eau, télécommunications)
- Techniciens supérieurs
- Agents techniques

### Postes Administratifs
- Comptables
- Secrétaires de direction
- Gestionnaires de ressources humaines
- Juristes

### Postes Commerciaux
- Chargés de clientèle
- Agents commerciaux
- Responsables marketing

## Conditions Générales

- Être de nationalité sénégalaise
- Remplir les conditions d\'âge requises (généralement 18-35 ans)
- Posséder le diplôme requis pour le poste
- Être en bonne santé physique
- Jouir de ses droits civiques

## Processus de Recrutement

### 1. Publication de l\'Avis
Les avis de concours sont publiés dans :
- Les journaux nationaux
- Les sites web des entreprises
- Les réseaux sociaux officiels
- Les centres d\'information

### 2. Inscription
- Téléchargement du dossier d\'inscription
- Composition du dossier avec tous les documents requis
- Dépôt du dossier dans les délais

### 3. Sélection
- Présélection sur dossier
- Épreuves écrites (culture générale, spécialité, langue)
- Épreuves pratiques (pour les postes techniques)
- Entretien avec le jury

### 4. Admission
- Publication des résultats
- Vérification des documents
- Intégration dans l\'entreprise

## Avantages des Entreprises Publiques

- **Sécurité de l\'emploi** : Statut de fonctionnaire ou contrat stable
- **Rémunération attractive** : Salaires compétitifs avec primes
- **Avantages sociaux** : Mutuelle, retraite, congés
- **Évolution de carrière** : Possibilités d\'avancement
- **Formation continue** : Programmes de développement professionnel

## Préparation aux Concours

### Pour les Épreuves Écrites
1. **Culture générale** : Suivez l\'actualité nationale et internationale
2. **Spécialité** : Maîtrisez les connaissances techniques de votre domaine
3. **Langues** : Travaillez l\'anglais et le français
4. **Tests psychotechniques** : Entraînez-vous aux tests de logique

### Pour les Épreuves Orales
1. **Présentation personnelle** : Préparez votre présentation
2. **Connaissances de l\'entreprise** : Informez-vous sur l\'entreprise
3. **Motivation** : Exprimez clairement vos motivations
4. **Gestion du stress** : Entraînez-vous à parler en public

## Calendrier 2025

Les concours sont organisés tout au long de l\'année selon les besoins des entreprises. Restez informé en :
- Consultan régulièrement les sites web des entreprises
- Suivant les publications dans les journaux
- S\'inscrivant aux alertes email si disponibles

## Conseils pour Réussir

1. **Anticipation** : Préparez votre dossier à l\'avance
2. **Rigueur** : Respectez scrupuleusement les délais
3. **Préparation** : Entraînez-vous régulièrement
4. **Information** : Restez informé sur les entreprises
5. **Persévérance** : Ne vous découragez pas en cas d\'échec

Bon courage dans vos démarches !',
                'cover_image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1200&h=630&fit=crop',
                'cover_type' => 'external',
                'category_id' => $category->id,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(8),
                'meta_title' => 'Concours Entreprises Publiques Sénégal 2025 - Opportunités',
                'meta_description' => 'Découvrez les concours de recrutement dans les entreprises publiques sénégalaises. SENELEC, SDE, SONATEL, et autres. Guide complet 2025.',
                'meta_keywords' => ['concours', 'entreprises publiques', 'sénégal', 'SENELEC', 'SDE', 'SONATEL', 'recrutement'],
            ],
            [
                'title' => 'Concours d\'Entrée dans les Forces de Sécurité du Sénégal 2025',
                'slug' => 'concours-forces-securite-senegal-2025',
                'excerpt' => 'Informations sur les concours de recrutement dans la Police, la Gendarmerie et les Douanes sénégalaises. Conditions, épreuves et carrières.',
                'content' => 'Les forces de sécurité du Sénégal recrutent régulièrement par concours. Ces métiers offrent des opportunités de carrière dans la protection des citoyens et la sécurité nationale.

## Corps de Sécurité Concernés

### Police Nationale
La Police Nationale assure la sécurité publique, le maintien de l\'ordre et la protection des personnes et des biens.

### Gendarmerie Nationale
La Gendarmerie est une force militaire chargée de la sécurité publique, notamment en zone rurale.

### Douanes Sénégalaises
Les Douanes sont chargées de la surveillance des frontières et de la collecte des droits et taxes.

### Sapeurs-Pompiers
Les sapeurs-pompiers assurent la sécurité civile, les secours d\'urgence et la lutte contre les incendies.

## Types de Concours

### Concours de Commissaire de Police
- Niveau requis : Master ou équivalent
- Formation : École Nationale de Police
- Débouchés : Postes de commandement

### Concours d\'Officier de Police
- Niveau requis : Licence ou équivalent
- Formation : École de Police
- Débouchés : Postes d\'encadrement

### Concours de Gardien de la Paix
- Niveau requis : Baccalauréat
- Formation : Centre de formation
- Débouchés : Postes d\'exécution

### Concours de Gendarme
- Niveau requis : Baccalauréat minimum
- Formation : École de Gendarmerie
- Conditions : Être apte physiquement et médicalement

### Concours des Douanes
- Niveau requis : Variable selon le poste
- Formation : École Nationale des Douanes
- Spécialités : Contrôle, vérification, informatique

## Conditions Générales d\'Admission

### Conditions de Nationalité
- Être de nationalité sénégalaise
- Jouir de ses droits civiques

### Conditions d\'Âge
- Police : Généralement 18-30 ans
- Gendarmerie : 18-25 ans pour le concours direct
- Douanes : Variable selon le poste

### Conditions Physiques
- Taille minimale requise (variable selon le corps)
- Bonne acuité visuelle
- Aptitude médicale complète
- Condition physique excellente

### Conditions de Formation
- Diplôme requis selon le niveau du concours
- Maîtrise du français
- Connaissances de base en informatique

## Épreuves des Concours

### Épreuves Physiques
- Course de vitesse (100m, 400m)
- Course d\'endurance (1000m, 3000m)
- Saut en hauteur ou en longueur
- Pompes, tractions, abdominaux
- Natation (pour certains corps)

### Épreuves Écrites
- Culture générale
- Français
- Mathématiques
- Tests psychotechniques
- Épreuve de spécialité (selon le poste)

### Épreuves Orales
- Entretien avec le jury
- Tests psychologiques
- Épreuve de langue (anglais)

## Processus de Sélection

1. **Inscription** : Dépôt du dossier d\'inscription
2. **Présélection** : Vérification des dossiers
3. **Épreuves physiques** : Tests d\'aptitude physique
4. **Épreuves écrites** : Concours écrit
5. **Épreuves orales** : Entretien et tests
6. **Visite médicale** : Examen médical complet
7. **Formation** : Intégration dans l\'école de formation

## Formation

### Durée de Formation
- Commissaire : 2 ans
- Officier : 18 mois
- Gardien/Gendarme : 12-18 mois
- Agent des Douanes : 6-12 mois

### Contenu de la Formation
- Formation théorique (droit, procédures, techniques)
- Formation pratique (terrain, stages)
- Formation physique et sportive
- Formation aux valeurs républicaines

## Carrières et Débouchés

### Évolution de Carrière
- Avancement par ancienneté
- Avancement au choix
- Concours internes pour évoluer
- Spécialisations possibles

### Spécialisations
- Police judiciaire
- Police de la route
- Police scientifique
- Cybercriminalité
- Relations publiques
- Formation

## Avantages

- **Sécurité de l\'emploi** : Statut de fonctionnaire
- **Rémunération** : Salaire de base + primes
- **Retraite** : Pension de retraite
- **Santé** : Couverture médicale
- **Logement** : Possibilité de logement de fonction
- **Formation continue** : Développement professionnel

## Préparation aux Concours

### Préparation Physique
1. **Endurance** : Course régulière (3-4 fois par semaine)
2. **Force** : Musculation, pompes, tractions
3. **Vitesse** : Sprint, exercices de vitesse
4. **Souplesse** : Étirements, yoga

### Préparation Intellectuelle
1. **Culture générale** : Actualité, histoire, géographie
2. **Français** : Orthographe, grammaire, rédaction
3. **Mathématiques** : Calcul, logique
4. **Tests psychotechniques** : Entraînement régulier

### Préparation Mentale
1. **Gestion du stress** : Techniques de relaxation
2. **Confiance en soi** : Visualisation positive
3. **Motivation** : Rappel des objectifs
4. **Persévérance** : Ne pas abandonner

## Calendrier 2025

Les concours sont organisés tout au long de l\'année. Consultez régulièrement :
- Les sites web officiels des forces de sécurité
- Le Journal Officiel
- Les centres d\'information

Bon courage dans votre préparation !',
                'cover_image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=1200&h=630&fit=crop',
                'cover_type' => 'external',
                'category_id' => $category->id,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(12),
                'meta_title' => 'Concours Forces de Sécurité Sénégal 2025 - Police, Gendarmerie, Douanes',
                'meta_description' => 'Guide complet des concours de recrutement dans les forces de sécurité sénégalaises. Police, Gendarmerie, Douanes. Conditions et préparation.',
                'meta_keywords' => ['concours', 'police', 'gendarmerie', 'douanes', 'sénégal', 'sécurité', 'recrutement'],
            ],
            [
                'title' => 'Concours d\'Entrée dans les Écoles de Formation Sanitaire du Sénégal 2025',
                'slug' => 'concours-ecoles-sante-senegal-2025',
                'excerpt' => 'Informations sur les concours d\'entrée dans les écoles de formation sanitaire : médecine, pharmacie, soins infirmiers, et autres professions de santé.',
                'content' => 'Le secteur de la santé au Sénégal offre de nombreuses opportunités de formation et de carrière. Les concours d\'entrée dans les écoles de formation sanitaire sont très prisés.

## Écoles de Formation Sanitaire

### Faculté de Médecine, de Pharmacie et d\'Odontostomatologie (FMPOS)
Formation des médecins, pharmaciens et dentistes.

### École Nationale de Développement Sanitaire et Social (ENDSS)
Formation des agents de santé communautaire et des travailleurs sociaux.

### École Nationale des Infirmiers d\'État (ENI)
Formation des infirmiers et infirmières d\'État.

### École Nationale des Sages-Femmes (ENSF)
Formation des sages-femmes.

### Institut de Formation en Soins Infirmiers (IFSI)
Formation des infirmiers et infirmières.

### École Nationale de Santé Publique (ENSP)
Formation des cadres de santé publique.

## Types de Concours

### Concours de Médecine
- **Niveau requis** : Baccalauréat scientifique (Série S)
- **Durée de formation** : 7 ans (médecine générale) ou 9-12 ans (spécialisation)
- **Épreuves** : Sciences (Maths, Physique, Chimie, SVT), Français, Anglais

### Concours de Pharmacie
- **Niveau requis** : Baccalauréat scientifique
- **Durée de formation** : 6 ans
- **Épreuves** : Sciences, Français, Anglais

### Concours d\'Odontologie
- **Niveau requis** : Baccalauréat scientifique
- **Durée de formation** : 6 ans
- **Épreuves** : Sciences, Français, Anglais

### Concours d\'Infirmier d\'État
- **Niveau requis** : Baccalauréat (toutes séries)
- **Durée de formation** : 3 ans
- **Épreuves** : Culture générale, Sciences, Français

### Concours de Sage-Femme
- **Niveau requis** : Baccalauréat (série S recommandée)
- **Durée de formation** : 4 ans
- **Épreuves** : Sciences, Culture générale, Français

## Conditions d\'Admission

### Conditions Générales
- Être de nationalité sénégalaise (ou avoir la nationalité d\'un pays de la CEDEAO)
- Être âgé de 17 ans au moins et 25 ans au plus (variable selon l\'école)
- Être titulaire du Baccalauréat ou équivalent
- Être en bonne santé physique et mentale
- Ne pas avoir de casier judiciaire

### Conditions Spécifiques
- **Médecine/Pharmacie/Odontologie** : Baccalauréat série S avec mention
- **Infirmier/Sage-Femme** : Baccalauréat toutes séries acceptées
- **Santé Publique** : Niveau Master pour certains programmes

## Épreuves des Concours

### Épreuves Écrites
1. **Sciences** : Mathématiques, Physique, Chimie, Sciences de la Vie et de la Terre
2. **Français** : Dissertation, commentaire de texte, grammaire
3. **Anglais** : Compréhension, expression écrite
4. **Culture générale** : Actualité, histoire, géographie

### Épreuves Orales
- Entretien avec le jury
- Tests psychologiques
- Épreuve de langue

## Processus de Sélection

1. **Inscription** : Dépôt du dossier avec tous les documents
2. **Présélection** : Vérification des dossiers
3. **Épreuves écrites** : Concours écrit
4. **Résultats d\'admissibilité** : Publication des résultats
5. **Épreuves orales** : Pour les candidats admissibles
6. **Résultats finaux** : Liste des admis
7. **Inscription administrative** : Inscription dans l\'école

## Programmes de Formation

### Médecine
- **Cycle 1** (2 ans) : Sciences fondamentales
- **Cycle 2** (3 ans) : Sciences médicales
- **Cycle 3** (2 ans) : Internat et spécialisation

### Pharmacie
- **Cycle 1** (2 ans) : Sciences fondamentales
- **Cycle 2** (2 ans) : Sciences pharmaceutiques
- **Cycle 3** (2 ans) : Stages et spécialisation

### Infirmier d\'État
- **1ère année** : Sciences fondamentales et initiation
- **2ème année** : Stages cliniques
- **3ème année** : Perfectionnement et mémoire

## Débouchés Professionnels

### Médecins
- Hôpitaux publics et privés
- Cabinets médicaux
- Organisations internationales
- Recherche médicale

### Pharmaciens
- Pharmacies d\'officine
- Hôpitaux
- Industrie pharmaceutique
- Recherche

### Infirmiers
- Hôpitaux et centres de santé
- Soins à domicile
- Établissements scolaires
- Entreprises

### Sages-Femmes
- Maternités
- Centres de santé
- Cliniques privées
- Consultations prénatales

## Préparation aux Concours

### Pour les Épreuves Scientifiques
1. **Révision complète** : Revoyez tout le programme du lycée
2. **Exercices** : Entraînez-vous régulièrement
3. **Annales** : Travaillez les sujets des années précédentes
4. **Groupes de travail** : Étudiez en groupe pour échanger

### Pour les Épreuves de Français
1. **Lecture** : Lisez régulièrement (presse, littérature)
2. **Rédaction** : Entraînez-vous à rédiger
3. **Grammaire** : Maîtrisez les règles de base
4. **Vocabulaire** : Enrichissez votre vocabulaire

### Pour l\'Entretien
1. **Motivation** : Préparez vos motivations
2. **Connaissances** : Informez-vous sur le métier
3. **Expression** : Travaillez votre expression orale
4. **Confiance** : Restez naturel et confiant

## Calendrier 2025

- **Publication des avis** : Mars-Avril 2025
- **Inscriptions** : Avril-Mai 2025
- **Épreuves écrites** : Juin-Juillet 2025
- **Résultats d\'admissibilité** : Août 2025
- **Épreuves orales** : Septembre 2025
- **Résultats finaux** : Octobre 2025
- **Rentrée** : Novembre 2025

## Conseils pour Réussir

1. **Organisation** : Établissez un planning de révision
2. **Régularité** : Travaillez quotidiennement
3. **Équilibre** : Mangez bien, dormez suffisamment
4. **Motivation** : Gardez vos objectifs en tête
5. **Persévérance** : Ne vous découragez pas

Bon courage dans votre préparation !',
                'cover_image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=1200&h=630&fit=crop',
                'cover_type' => 'external',
                'category_id' => $category->id,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(15),
                'meta_title' => 'Concours Écoles de Santé Sénégal 2025 - Médecine, Pharmacie, Infirmier',
                'meta_description' => 'Guide complet des concours d\'entrée dans les écoles de formation sanitaire du Sénégal. Médecine, pharmacie, soins infirmiers. Conditions et préparation.',
                'meta_keywords' => ['concours', 'médecine', 'pharmacie', 'infirmier', 'santé', 'sénégal', 'formation'],
            ],
        ];

        foreach ($articles as $articleData) {
            // Convertir les mots-clés en JSON si c'est un array
            if (isset($articleData['meta_keywords']) && is_array($articleData['meta_keywords'])) {
                $articleData['meta_keywords'] = json_encode($articleData['meta_keywords']);
            }

            // Générer le slug s'il n'existe pas
            if (empty($articleData['slug'])) {
                $articleData['slug'] = Str::slug($articleData['title']);
            }

            // Calculer les scores SEO et lisibilité (simplifiés)
            $articleData['seo_score'] = 85;
            $articleData['readability_score'] = 80;

            JobArticle::updateOrCreate(
                ['slug' => $articleData['slug']],
                $articleData
            );
        }

        $this->command->info('5 articles de concours créés avec succès !');
    }
}

