#!/bin/bash

# Script de déploiement pour les optimisations de performance
# À exécuter sur le serveur après le transfert des fichiers via FTP

echo "=========================================="
echo "  DÉPLOIEMENT - OPTIMISATIONS PERFORMANCE"
echo "=========================================="
echo ""

# Couleurs pour les messages
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages
info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    error "Le fichier artisan n'est pas trouvé. Êtes-vous dans le répertoire du projet Laravel ?"
    exit 1
fi

info "Répertoire du projet détecté : $(pwd)"
echo ""

# Étape 1 : Vérifier les fichiers transférés
info "Vérification des fichiers transférés..."
echo ""

if [ ! -f "public/.htaccess" ]; then
    warn "public/.htaccess n'est pas trouvé"
else
    info "✓ public/.htaccess trouvé"
fi

if [ ! -f "resources/views/layouts/app.blade.php" ]; then
    warn "resources/views/layouts/app.blade.php n'est pas trouvé"
else
    info "✓ resources/views/layouts/app.blade.php trouvé"
fi

if [ ! -f "app/Helpers/ImageOptimizer.php" ]; then
    warn "app/Helpers/ImageOptimizer.php n'est pas trouvé"
else
    info "✓ app/Helpers/ImageOptimizer.php trouvé"
fi

if [ ! -f "composer.json" ]; then
    error "composer.json n'est pas trouvé"
    exit 1
else
    info "✓ composer.json trouvé"
fi

echo ""

# Étape 2 : Vérifier Composer
info "Vérification de Composer..."
if ! command -v composer &> /dev/null; then
    error "Composer n'est pas installé ou n'est pas dans le PATH"
    exit 1
fi
info "✓ Composer trouvé : $(composer --version | head -n 1)"
echo ""

# Étape 3 : Mettre à jour l'autoload
info "Mise à jour de l'autoload Composer..."
composer dump-autoload
if [ $? -eq 0 ]; then
    info "✓ Autoload mis à jour avec succès"
else
    error "Erreur lors de la mise à jour de l'autoload"
    exit 1
fi
echo ""

# Étape 4 : Vérifier les permissions
info "Vérification des permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null
chmod -R 755 app/Helpers 2>/dev/null
info "✓ Permissions vérifiées"
echo ""

# Étape 5 : Vider les caches
info "Vidage des caches Laravel..."
php artisan optimize:clear
if [ $? -eq 0 ]; then
    info "✓ Caches vidés avec succès"
else
    warn "Certains caches n'ont pas pu être vidés (normal si certains n'existent pas)"
fi
echo ""

# Étape 6 : Optimiser pour la production
info "Optimisation pour la production..."
php artisan config:cache
if [ $? -eq 0 ]; then
    info "✓ Cache de configuration créé"
else
    error "Erreur lors de la création du cache de configuration"
    exit 1
fi

php artisan route:cache
if [ $? -eq 0 ]; then
    info "✓ Cache de routes créé"
else
    warn "Erreur lors de la création du cache de routes (normal si aucune route n'est définie)"
fi

php artisan view:cache
if [ $? -eq 0 ]; then
    info "✓ Cache de vues créé"
else
    warn "Erreur lors de la création du cache de vues"
fi

echo ""

# Étape 7 : Vérification finale
info "Vérification finale..."
if [ -f "bootstrap/cache/config.php" ]; then
    info "✓ Cache de configuration présent"
fi

if [ -f "bootstrap/cache/routes-v7.php" ] || [ -f "bootstrap/cache/routes.php" ]; then
    info "✓ Cache de routes présent"
fi

echo ""

# Résumé
echo "=========================================="
info "DÉPLOIEMENT TERMINÉ AVEC SUCCÈS !"
echo "=========================================="
echo ""
info "Prochaines étapes :"
echo "  1. Tester le site : https://www.niangprogrammeur.com"
echo "  2. Vérifier la console du navigateur (F12)"
echo "  3. Tester avec PageSpeed Insights : https://pagespeed.web.dev/"
echo ""
info "Si vous rencontrez des problèmes :"
echo "  - Vérifiez les logs : tail -f storage/logs/laravel.log"
echo "  - Videz les caches : php artisan optimize:clear"
echo "  - Vérifiez les permissions : ls -la storage bootstrap/cache"
echo ""

