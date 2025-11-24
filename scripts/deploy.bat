@echo off
REM Script de déploiement pour Windows
REM Usage: deploy.bat

echo 🚀 Début du déploiement...

REM Vérifier si on est dans le bon répertoire
if not exist "artisan" (
    echo ❌ Erreur: Ce script doit être exécuté depuis la racine du projet Laravel
    exit /b 1
)

REM Sauvegarde de la base de données (si configurée)
echo 📦 Création d'une sauvegarde...
php artisan backup:run 2>nul || echo Sauvegarde ignorée (backup non configuré)

REM Si Git est utilisé
if exist ".git" (
    echo 📥 Récupération des modifications depuis Git...
    git pull origin main || git pull origin master
)

REM Installer/mettre à jour les dépendances
echo 📦 Installation des dépendances...
composer install --no-dev --optimize-autoloader --no-interaction

REM Nettoyer tous les caches
echo 🧹 Nettoyage des caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

REM Exécuter les migrations (si nécessaire)
echo 🗄️  Exécution des migrations...
php artisan migrate --force --no-interaction

REM Optimiser l'application pour la production
echo ⚡ Optimisation de l'application...
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

REM Redémarrer les workers de queue (si utilisés)
echo 🔄 Redémarrage des workers de queue...
php artisan queue:restart 2>nul || echo Workers de queue ignorés

echo ✅ Déploiement terminé avec succès !
echo 🌐 Votre site est maintenant à jour.

pause

