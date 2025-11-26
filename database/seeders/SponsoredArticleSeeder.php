<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\JobArticle;
use Carbon\Carbon;

class SponsoredArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer la catégorie "Opportunités professionnelles" ou créer une catégorie "Formations"
        $category = Category::where('slug', 'opportunites-professionnelles')->first();
        
        if (!$category) {
            // Si la catégorie n'existe pas, utiliser la première catégorie active
            $category = Category::where('is_active', true)->first();
        }
        
        if (!$category) {
            throw new \Exception('Aucune catégorie active trouvée. Veuillez créer des catégories d\'abord.');
        }

        // Article sponsorisé Sunu Code - Formation Développement Web
        $article = [
            'category_id' => $category->id,
            'title' => 'Formation Présentielle en Développement Web - Sunu Code',
            'slug' => 'formation-presentielle-developpement-web-sunu-code',
            'excerpt' => 'Devenez développeur web et créez des sites et applications modernes. Formation complète de 5 mois à Dakar avec HTML, CSS, JavaScript, PHP, Laravel et les technologies web les plus demandées.',
            'content' => '# Formation Présentielle en Développement Web - Sunu Code

## 🎯 Description du programme

Formation complète en développement web pour créer des sites et applications web modernes. Apprenez HTML, CSS, JavaScript, PHP, Laravel et les technologies web les plus demandées.

### 📋 Détails de la formation

- **Durée** : 5 mois
- **Lieu** : Dakar, Sénégal
- **Fréquence** : 3 séances par semaine
- **Prix** : 25 000 FCFA par mois + 25 000 FCFA d\'inscription
- **Places disponibles** : Inscription ouverte

## 💼 Débouchés professionnels

À l\'issue de cette formation, vous pourrez exercer les métiers suivants :

- **Développeur Web** : Création de sites web et applications web complètes
- **Développeur Frontend** : Interface utilisateur moderne et responsive
- **Développeur Backend** : Gestion des serveurs et bases de données
- **Freelance** : Travailler en indépendant sur des projets variés
- **Entrepreneur digital** : Créer votre propre entreprise dans le numérique

## 🏆 Certification & Validation

Certificat de Développement Web délivré par Sunu Code à l\'issue de la formation.

## 📞 Contact

- **Téléphone** : +221 77 123 45 67
- **Email** : contact@sunucode.com
- **Site web** : [https://sunucode.com](https://sunucode.com)

## 🌟 Pourquoi choisir Sunu Code ?

Rejoignez des milliers d\'étudiants qui ont transformé leur carrière avec nos formations :

- **1000+** Étudiants formés par an
- **95%** Taux de réussite
- **85%** Taux d\'insertion professionnelle

## 📚 Programme de la formation

### Module 1 : Fondamentaux du Web
- HTML5 et structure sémantique
- CSS3 et design responsive
- Introduction au JavaScript

### Module 2 : JavaScript Avancé
- Programmation orientée objet
- Manipulation du DOM
- Frameworks modernes

### Module 3 : Backend avec PHP
- PHP et programmation serveur
- Bases de données MySQL
- Architecture MVC

### Module 4 : Framework Laravel
- Installation et configuration
- Routes et contrôleurs
- Authentification et sécurité

### Module 5 : Projet Final
- Développement d\'une application complète
- Déploiement en production
- Présentation du projet

## 🎓 Prérequis

- Motivation et passion pour le développement web
- Aucun prérequis technique nécessaire
- Ordinateur portable recommandé

## 💡 Avantages de la formation

✅ Formation pratique avec projets réels  
✅ Suivi personnalisé par des formateurs expérimentés  
✅ Certificat reconnu  
✅ Accès à une communauté active  
✅ Support après la formation  
✅ Opportunités de stage et d\'emploi

## 📅 Inscription

Les inscriptions sont ouvertes. Contactez-nous dès maintenant pour réserver votre place !

**Demandez des informations** : [Formulaire de contact](https://sunucode.com/formations-presentielles/developpement-web)

---

*Article sponsorisé par Sunu Code - Centre de formation spécialisé dans les formations accélérées en informatique et numérique en Afrique.*',
            'cover_image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1200&h=630&fit=crop',
            'cover_type' => 'external',
            'meta_title' => 'Formation Développement Web Présentielle - Sunu Code Dakar',
            'meta_description' => 'Formation complète en développement web à Dakar. Apprenez HTML, CSS, JavaScript, PHP, Laravel. 5 mois, 3 séances/semaine. Certificat délivré. Inscription ouverte.',
            'meta_keywords' => ['formation', 'développement web', 'Dakar', 'Sénégal', 'Sunu Code', 'HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'formation présentielle'],
            'status' => 'published',
            'is_sponsored' => true,
            'published_at' => Carbon::now(),
            'seo_score' => 95,
            'readability_score' => 90,
            'views' => 0,
        ];

        JobArticle::updateOrCreate(
            ['slug' => $article['slug']],
            $article
        );

        $this->command->info('✅ Article sponsorisé Sunu Code créé avec succès !');
    }
}

