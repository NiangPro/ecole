#!/bin/bash

# 🚀 Script de Démarrage - NiangProgrammeur

PROJECT_DIR="/Users/macbook/Documents/NiangProgrammeur/site"

echo "=========================================="
echo "🚀 NiangProgrammeur - Démarrage"
echo "=========================================="
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ Erreur: Répertoire $PROJECT_DIR non trouvé!"
    exit 1
fi

cd "$PROJECT_DIR"

# Afficher les informations
echo "📁 Répertoire: $PROJECT_DIR"
echo "📦 Environnement: $(cat .env | grep APP_ENV | cut -d'=' -f2)"
echo ""

# Nettoyage rapide des caches
echo "🧹 Nettoyage des caches..."
php artisan cache:clear > /dev/null 2>&1
php artisan view:clear > /dev/null 2>&1

# Vérifier la BD
echo "🗄️  Vérification de la base de données..."
DB_EXISTS=$(mysql -u root -e "USE niangprogrammeur;" 2>&1)
if [ $? -eq 0 ]; then
    echo "✅ Base de données: OK"
    # Compter les records
    USERS=$(mysql -u root niangprogrammeur -e "SELECT COUNT(*) FROM users;" 2>/dev/null | tail -1)
    FORMATIONS=$(mysql -u root niangprogrammeur -e "SELECT COUNT(*) FROM formations;" 2>/dev/null | tail -1)
    echo "   - Utilisateurs: $USERS"
    echo "   - Formations: $FORMATIONS"
else
    echo "❌ Base de données: NON TROUVÉE"
    echo "   Exécutez: mysql -u root -e \"CREATE DATABASE niangprogrammeur;\""
    exit 1
fi

echo ""
echo "=========================================="
echo "✅ Tous les checks sont OK!"
echo "=========================================="
echo ""

# Démarrage du serveur
echo "🌐 Démarrage du serveur Laravel..."
echo "📍 URL: http://localhost:8000"
echo ""
echo "⚠️  Appuyez sur CTRL+C pour arrêter le serveur"
echo ""

php artisan serve --host=127.0.0.1 --port=8000
