#!/bin/bash

# Script de déploiement automatique pour Laravel
# Usage: ./deploy.sh

set -e  # Arrêter en cas d'erreur

echo "🚀 Début du déploiement..."

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Vérifier si on est dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Erreur: Ce script doit être exécuté depuis la racine du projet Laravel${NC}"
    exit 1
fi

# Sauvegarde de la base de données (si configurée)
echo -e "${YELLOW}📦 Création d'une sauvegarde...${NC}"
if [ -f ".env" ]; then
    php artisan backup:run 2>/dev/null || echo "Sauvegarde ignorée (backup non configuré)"
fi

# Si Git est utilisé
if [ -d ".git" ]; then
    echo -e "${YELLOW}📥 Récupération des modifications depuis Git...${NC}"
    git pull origin main || git pull origin master
fi

# Installer/mettre à jour les dépendances
echo -e "${YELLOW}📦 Installation des dépendances...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction

# Nettoyer tous les caches
echo -e "${YELLOW}🧹 Nettoyage des caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

# Exécuter les migrations (si nécessaire)
echo -e "${YELLOW}🗄️  Exécution des migrations...${NC}"
php artisan migrate --force --no-interaction

# Optimiser l'application pour la production
echo -e "${YELLOW}⚡ Optimisation de l'application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Redémarrer les workers de queue (si utilisés)
if php artisan queue:work --help &>/dev/null; then
    echo -e "${YELLOW}🔄 Redémarrage des workers de queue...${NC}"
    php artisan queue:restart
fi

# Vérifier les permissions
echo -e "${YELLOW}🔐 Vérification des permissions...${NC}"
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

echo -e "${GREEN}✅ Déploiement terminé avec succès !${NC}"
echo -e "${GREEN}🌐 Votre site est maintenant à jour.${NC}"

