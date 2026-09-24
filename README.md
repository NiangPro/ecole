# 🚀 NiangProgrammeur - Plateforme de Formation Gratuite

Plateforme de formation gratuite en développement web avec système de badges, certificats, quiz et exercices interactifs.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)
![License](https://img.shields.io/badge/License-Proprietary-red?style=flat-square)

## 📋 Table des Matières

- [Fonctionnalités](#-fonctionnalités)
- [Technologies](#-technologies)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Structure du Projet](#-structure-du-projet)
- [Tests](#-tests)
- [Déploiement](#-déploiement)
- [Contribution](#-contribution)
- [Support](#-support)
- [Licence](#-licence)

## ✨ Fonctionnalités

### 🎓 Formations
- ✅ **15 Formations complètes** : HTML5, CSS3, JavaScript, PHP, Bootstrap, Python, Java, SQL, C, Git, WordPress, IA, C++, C#, Dart
- ✅ **Suivi de progression** avec sauvegarde automatique
- ✅ **Navigation fluide** entre les sections
- ✅ **Système de favoris** pour marquer les formations préférées

### 💪 Exercices Interactifs
- ✅ **Exercices pratiques** pour chaque langage
- ✅ **Éditeur de code** intégré avec exécution en temps réel
- ✅ **Validation automatique** des solutions
- ✅ **Suivi des exercices complétés**

### 🧪 Quiz
- ✅ **Quiz par langage** avec questions variées
- ✅ **Résultats détaillés** avec corrections
- ✅ **Historique des scores**
- ✅ **Système de notation**

### 🏆 Gamification
- ✅ **Système de badges** (15+ badges disponibles)
- ✅ **Certificats de complétion** téléchargeables en PDF
- ✅ **Objectifs personnalisés** avec suivi de progression
- ✅ **Statistiques détaillées**

### 👤 Dashboard Utilisateur
- ✅ **Vue d'ensemble** avec statistiques
- ✅ **Progression des formations**
- ✅ **Historique des activités**
- ✅ **Gestion des objectifs**
- ✅ **Paramètres personnalisables**

### 🔐 Administration
- ✅ **Panel admin sécurisé** avec authentification
- ✅ **Gestion des utilisateurs**
- ✅ **Gestion des articles d'emploi**
- ✅ **Modération des commentaires**
- ✅ **Audit de sécurité**
- ✅ **Backups automatiques**
- ✅ **Statistiques avancées**

### 📰 Articles d'Emploi
- ✅ **Articles par catégories** (Offres, Bourses, Concours, etc.)
- ✅ **Système de commentaires** avec modération
- ✅ **Newsletter automatique** pour nouveaux articles
- ✅ **SEO optimisé** avec scores de qualité

### 🌐 Multilingue & Accessibilité
- ✅ **Français/Anglais** avec changement dynamique
- ✅ **Mode sombre/clair** avec préférence sauvegardée
- ✅ **PWA (Progressive Web App)** installable
- ✅ **Responsive design** mobile-first
- ✅ **Accessibilité WCAG** optimisée

### 🔍 SEO & Performance
- ✅ **Sitemap XML** dynamique
- ✅ **Soumission automatique** à Bing
- ✅ **Optimisation des images** (WebP, lazy loading)
- ✅ **Cache Redis** pour performances optimales
- ✅ **Compression des assets** (Gzip/Brotli)
- ✅ **CDN** pour assets statiques

## 🛠️ Technologies

### Backend
- **Framework** : Laravel 12.x
- **PHP** : 8.2+
- **Base de données** : MySQL 8.0+ / SQLite 3.x
- **Cache** : Redis (optionnel)
- **Queue** : Database / Redis

### Frontend
- **Templates** : Blade
- **CSS** : Tailwind CSS 4.x
- **JavaScript** : Vanilla JS (ES6+)
- **Build Tool** : Vite 7.x
- **Icons** : Font Awesome 6.x

### Outils de Développement
- **Tests** : PHPUnit 11.x, Laravel Dusk 8.x
- **Code Quality** : Laravel Pint
- **Linting** : ESLint (optionnel)

## 📦 Prérequis

- PHP >= 8.2 avec extensions : BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- Composer >= 2.0
- Node.js >= 18.x et NPM >= 9.x
- MySQL >= 8.0 ou SQLite >= 3.x
- Redis (optionnel, pour le cache)
- Git

## 🚀 Installation

### 1. Cloner le projet

```bash
git clone https://github.com/votre-repo/formation-laravel.git
cd formation-laravel
```

### 2. Installer les dépendances

```bash
# Dépendances PHP
composer install

# Dépendances Node.js
npm install
```

### 3. Configuration de l'environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configurer la base de données

Éditez le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=formation_laravel
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrations et Seeders

```bash
# Créer les tables
php artisan migrate

# Remplir avec des données de test (optionnel)
php artisan db:seed
```

### 6. Compiler les assets

```bash
# Mode développement (avec hot reload)
npm run dev

# Mode production (minifié)
npm run build
```

### 7. Lancer le serveur

```bash
# Serveur de développement
php artisan serve

# Ou avec toutes les commandes en parallèle
composer run dev
```

Le site sera accessible sur : **http://localhost:8000**

## ⚙️ Configuration

### Variables d'Environnement Importantes

```env
# Application
APP_NAME="NiangProgrammeur"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=formation_laravel

# Cache Redis (optionnel)
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# CDN (optionnel)
CDN_URL=https://cdn.example.com

# Mail (pour newsletter)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null

# Bing Webmaster Tools (optionnel)
BING_API_KEY=your_api_key
```

### Configuration Redis

Pour activer Redis comme cache :

1. Installer Redis sur votre système
2. Configurer dans `.env` :
```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### Configuration CDN

Pour utiliser un CDN pour les assets statiques :

1. Configurer votre CDN (Cloudflare, AWS CloudFront, etc.)
2. Ajouter dans `.env` :
```env
CDN_URL=https://cdn.votre-domaine.com
```

## 💻 Utilisation

### Commandes Artisan Utiles

```bash
# Optimiser les images en WebP
php artisan images:optimize

# Précharger le cache
php artisan cache:warmup

# Générer le sitemap
php artisan sitemap:generate

# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Lancer les tests
php artisan test

# Lancer les tests E2E (nécessite Laravel Dusk)
php artisan dusk
```

### Commandes Composer Utiles

```bash
# Générer le changelog automatiquement
composer changelog

# Lancer l'environnement de développement complet
composer dev

# Lancer les tests
composer test
```

### Accès Admin

1. Créer un utilisateur admin via tinker :
```bash
php artisan tinker
```
```php
$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
    'is_active' => true
]);
```

2. Se connecter via : `/admin/login`

## 📁 Structure du Projet

```
formation-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Contrôleurs
│   │   │   ├── Admin/          # Contrôleurs admin
│   │   │   └── Auth/           # Authentification
│   │   └── Middleware/         # Middlewares personnalisés
│   ├── Models/                 # Modèles Eloquent
│   ├── Services/               # Services métier
│   └── Helpers/                 # Helpers
├── bootstrap/                   # Bootstrap de l'application
├── config/                      # Fichiers de configuration
├── database/
│   ├── migrations/             # Migrations
│   ├── seeders/                 # Seeders
│   └── factories/               # Factories
├── public/                      # Assets publics
│   ├── css/                     # Styles CSS
│   ├── js/                      # JavaScript
│   ├── images/                  # Images
│   └── manifest.json            # PWA Manifest
├── resources/
│   ├── views/                   # Vues Blade
│   │   ├── layouts/             # Layouts
│   │   ├── partials/            # Partials
│   │   ├── formations/         # Pages formations
│   │   ├── dashboard/           # Dashboard utilisateur
│   │   └── admin/               # Panel admin
│   ├── lang/                    # Traductions (FR/EN)
│   └── js/                      # Sources JavaScript
├── routes/
│   ├── web.php                  # Routes web
│   └── console.php              # Commandes console
├── storage/                      # Fichiers de stockage
│   ├── app/                     # Fichiers uploadés
│   ├── logs/                    # Logs
│   └── framework/              # Cache framework
├── tests/                        # Tests
│   ├── Unit/                    # Tests unitaires
│   ├── Feature/                 # Tests d'intégration
│   └── Browser/                 # Tests E2E (Dusk)
├── .env.example                 # Exemple de configuration
├── composer.json                 # Dépendances PHP
├── package.json                  # Dépendances Node.js
└── README.md                     # Ce fichier
```

## 🧪 Tests

### Lancer les Tests

```bash
# Tous les tests
php artisan test

# Tests unitaires uniquement
php artisan test --testsuite=Unit

# Tests d'intégration uniquement
php artisan test --testsuite=Feature

# Avec couverture de code
php artisan test --coverage

# Tests E2E (nécessite Chrome/Chromium)
php artisan dusk
```

### Structure des Tests

- **Unit** : Tests unitaires des modèles, services, helpers
- **Feature** : Tests d'intégration des contrôleurs, routes, middlewares
- **Browser** : Tests E2E avec Laravel Dusk

### Couverture de Code

Le projet vise une couverture de code > 80%. Voir les rapports dans `tests/coverage/`.

## 🚢 Déploiement

### Préparation Production

```bash
# Optimiser l'autoloader
composer install --optimize-autoloader --no-dev

# Compiler les assets
npm run build

# Optimiser le cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimiser les images
php artisan images:optimize

# Précharger le cache
php artisan cache:warmup
```

### Variables d'Environnement Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Utiliser HTTPS
FORCE_HTTPS=true
```

### Serveur Web

Configurer Nginx ou Apache pour pointer vers `public/` comme racine.

Voir [INSTALLATION.md](INSTALLATION.md) pour plus de détails.

## 🤝 Contribution

Voir [CONTRIBUTING.md](CONTRIBUTING.md) pour les guidelines de contribution.

## 📞 Support

- **Email** : NiangProgrammeur@gmail.com
- **Téléphone** : +221 78 312 36 57
- **Site Web** : https://niangprogrammeur.com

## 📝 Licence

Propriétaire - NiangProgrammeur © 2025

Tous droits réservés. Ce projet est la propriété exclusive de NiangProgrammeur.

---

**Dernière mise à jour** : 2025-01-27

**Version** : 1.0.0
