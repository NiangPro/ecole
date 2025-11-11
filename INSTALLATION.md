# Installation du Projet DevFormation Laravel

## Prérequis
- PHP >= 8.1
- Composer
- Node.js et NPM (optionnel)

## Installation

1. **Cloner le projet**
```bash
cd c:\Users\Niang\OneDrive\Documents\Business\formation-laravel
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer l'environnement**
Le fichier `.env` a déjà été créé. Si ce n'est pas le cas :
```bash
copy .env.example .env
php artisan key:generate
```

4. **Lancer le serveur de développement**
```bash
php artisan serve
```

Le site sera accessible sur : http://localhost:8000

## Structure du Projet

### Routes
Toutes les routes sont définies dans `routes/web.php` :
- `/` - Page d'accueil
- `/about` - Page À propos
- `/faq` - FAQ
- `/legal` - Mentions légales
- `/privacy-policy` - Politique de confidentialité
- `/terms` - Conditions d'utilisation

### Contrôleurs
Le contrôleur principal est `PageController` dans `app/Http/Controllers/PageController.php`

### Vues Blade
Les vues sont dans `resources/views/` :
- `layouts/app.blade.php` - Layout principal
- `partials/navigation.blade.php` - Navigation
- `partials/footer.blade.php` - Footer
- `index.blade.php` - Page d'accueil
- `about.blade.php` - À propos
- `faq.blade.php` - FAQ
- `legal.blade.php` - Mentions légales
- `privacy-policy.blade.php` - Politique de confidentialité
- `terms.blade.php` - Conditions d'utilisation

### Assets
Les fichiers CSS, JS et images sont dans `public/` :
- `public/css/` - Fichiers CSS
- `public/js/` - Fichiers JavaScript
- `public/images/` - Images (logo, etc.)

## Fonctionnalités

✅ Design ultra-moderne avec animations 3D
✅ Navigation responsive avec menu mobile
✅ Pages complètes (Accueil, À propos, FAQ, Mentions légales)
✅ Architecture Laravel MVC
✅ Templates Blade réutilisables
✅ Assets optimisés

## Contact

Email: NiangProgrammeur@gmail.com
Téléphone: +221 78 312 36 57
