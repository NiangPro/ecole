# 🚀 NiangProgrammeur - Plateforme de Formation Gratuite

Plateforme de formation gratuite en développement web avec système de badges, certificats, quiz et exercices.

## 📋 Fonctionnalités

- ✅ **15 Formations** : HTML5, CSS3, JavaScript, PHP, Bootstrap, Python, Java, SQL, C, Git, WordPress, IA, C++, C#, Dart
- ✅ **Système d'exercices** avec suivi de progression
- ✅ **Système de quiz** avec résultats détaillés
- ✅ **Dashboard utilisateur** complet avec statistiques
- ✅ **Système de badges** et certificats
- ✅ **Panel admin** sécurisé
- ✅ **Articles d'emploi** avec catégories
- ✅ **Newsletter** automatique
- ✅ **SEO optimisé** (Sitemap, Bing Submission)
- ✅ **Mode sombre/clair**
- ✅ **Multilingue** (Français/Anglais)

## 🛠️ Technologies

- **Backend** : Laravel 10+
- **Frontend** : Blade Templates, Tailwind CSS, JavaScript
- **Base de données** : MySQL/SQLite
- **Authentification** : Laravel Auth

## 📦 Installation

Voir le fichier [INSTALLATION.md](INSTALLATION.md) pour les instructions complètes.

### Démarrage rapide

```bash
# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Lancer le serveur
php artisan serve
```

## 📚 Documentation

- [INSTALLATION.md](INSTALLATION.md) - Guide d'installation
- [GENERATION_SITEMAP.md](GENERATION_SITEMAP.md) - Génération du sitemap
- [DEPLOIEMENT_BADGES_PRODUCTION.md](DEPLOIEMENT_BADGES_PRODUCTION.md) - Déploiement badges
- [GUIDE_ACCEPTATION_ADSENSE.md](GUIDE_ACCEPTATION_ADSENSE.md) - Guide AdSense
- [CONFIGURATION_SEO.md](CONFIGURATION_SEO.md) - Configuration SEO
- [INTEGRATION_BING_API.md](INTEGRATION_BING_API.md) - Intégration Bing

## 🔐 Accès Admin

Seuls les administrateurs peuvent accéder au panel admin via `/admin/login`.

## 📊 Structure du Projet

```
app/
├── Http/Controllers/    # Contrôleurs
├── Models/              # Modèles Eloquent
├── Services/            # Services métier
└── Middleware/          # Middlewares

resources/
├── views/               # Vues Blade
├── lang/                # Traductions (FR/EN)
└── js/                  # JavaScript

database/
├── migrations/          # Migrations
└── seeders/             # Seeders
```

## 🚀 Prochaines Étapes

Voir [ANALYSE_GLOBALE_PROJET.md](ANALYSE_GLOBALE_PROJET.md) pour les prochaines étapes recommandées.

## 📝 Licence

Propriétaire - NiangProgrammeur

---

**Dernière mise à jour** : 2025-01-27

