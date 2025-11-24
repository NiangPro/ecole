# Guide de Mise à Jour du Site en Ligne

Ce guide vous explique comment mettre à jour votre site Laravel en production avec les nouvelles traductions et modifications.

## 📋 Prérequis

- Accès SSH/FTP à votre serveur
- Accès à la base de données (si nécessaire)
- Fichier `.env` de production configuré

---

## 🚀 Méthode 1 : Mise à jour via Git (Recommandé)

Si votre site utilise Git, c'est la méthode la plus propre et sécurisée.

### Étape 1 : Préparer les modifications localement

```bash
# Vérifier les modifications
git status

# Ajouter tous les fichiers modifiés
git add .

# Créer un commit
git commit -m "Ajout des traductions pour les quiz et exercices"

# Pousser vers le dépôt distant
git push origin main
```

### Étape 2 : Sur le serveur de production

```bash
# Se connecter au serveur via SSH
ssh utilisateur@votre-serveur.com

# Aller dans le répertoire du projet
cd /chemin/vers/votre/projet

# Récupérer les dernières modifications
git pull origin main

# Installer/mettre à jour les dépendances
composer install --no-dev --optimize-autoloader

# Nettoyer tous les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimiser l'application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Si vous avez des migrations
php artisan migrate --force

# Redémarrer les services si nécessaire (ex: queue workers)
php artisan queue:restart
```

---

## 📤 Méthode 2 : Mise à jour via FTP/SFTP

Si vous n'utilisez pas Git, vous pouvez transférer les fichiers manuellement.

### Étape 1 : Préparer les fichiers à transférer

**Fichiers à mettre à jour :**

1. **Fichiers de traduction (IMPORTANT) :**
   - `lang/fr/app.php`
   - `lang/en/app.php`
   - `lang/fr/exercises.php`
   - `lang/en/exercises.php`
   - `lang/fr/quiz.php`
   - `lang/en/quiz.php`

2. **Contrôleurs :**
   - `app/Http/Controllers/PageController.php`

3. **Vues :**
   - `resources/views/quiz.blade.php`
   - `resources/views/quiz-language.blade.php`
   - `resources/views/quiz-result.blade.php`
   - `resources/views/exercices-language.blade.php`
   - `resources/views/exercice-detail.blade.php`

### Étape 2 : Transférer les fichiers

Utilisez un client FTP (FileZilla, WinSCP, etc.) pour transférer les fichiers vers votre serveur.

**⚠️ Important :**
- Ne transférez PAS le fichier `.env`
- Ne transférez PAS le dossier `vendor/` (sera régénéré)
- Ne transférez PAS le dossier `node_modules/`

### Étape 3 : Sur le serveur (via SSH ou terminal)

```bash
# Aller dans le répertoire du projet
cd /chemin/vers/votre/projet

# Installer/mettre à jour les dépendances
composer install --no-dev --optimize-autoloader

# Nettoyer tous les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimiser l'application
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔧 Commandes Artisan Essentielles

### Nettoyage des caches (à faire après chaque mise à jour)

```bash
# Nettoyer tous les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

### Optimisation pour la production

```bash
# Optimiser l'application
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Vérifier les permissions

```bash
# Permissions pour les dossiers de stockage
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 📝 Checklist de Mise à Jour

Avant de mettre à jour le site en production :

- [ ] Tester toutes les fonctionnalités en local
- [ ] Vérifier que les traductions fonctionnent (FR/EN)
- [ ] Sauvegarder la base de données
- [ ] Sauvegarder le fichier `.env`
- [ ] Vérifier les permissions des fichiers
- [ ] Tester les quiz et exercices
- [ ] Vérifier que le cache est bien nettoyé

Après la mise à jour :

- [ ] Vérifier que le site fonctionne correctement
- [ ] Tester le changement de langue (FR/EN)
- [ ] Vérifier les quiz (questions traduites)
- [ ] Vérifier les exercices (instructions traduites)
- [ ] Vérifier les pages de résultats
- [ ] Vérifier les logs d'erreurs (`storage/logs/laravel.log`)

---

## 🗄️ Sauvegarde de la Base de Données

Avant toute mise à jour, sauvegardez votre base de données :

```bash
# MySQL/MariaDB
mysqldump -u utilisateur -p nom_base_de_donnees > backup_$(date +%Y%m%d_%H%M%S).sql

# Via Artisan (si configuré)
php artisan backup:run
```

---

## 🔍 Vérification Post-Déploiement

### 1. Vérifier les traductions

- Visiter `/quiz/html5` et vérifier que les questions sont traduites
- Visiter `/exercices/html5` et vérifier que les exercices sont traduits
- Changer la langue et vérifier que tout s'adapte

### 2. Vérifier les logs

```bash
# Voir les dernières erreurs
tail -f storage/logs/laravel.log
```

### 3. Vérifier les performances

- Tester la vitesse de chargement des pages
- Vérifier que le cache fonctionne
- Vérifier les requêtes à la base de données

---

## 🚨 En Cas de Problème

### Le site ne fonctionne plus après la mise à jour

1. **Restaurer la sauvegarde :**
   ```bash
   # Restaurer les fichiers
   git checkout HEAD~1  # Si vous utilisez Git
   
   # Ou restaurer depuis votre sauvegarde FTP
   ```

2. **Vérifier les logs :**
   ```bash
   tail -n 100 storage/logs/laravel.log
   ```

3. **Nettoyer les caches :**
   ```bash
   php artisan optimize:clear
   ```

4. **Vérifier les permissions :**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

### Les traductions ne s'affichent pas

1. **Vérifier que les fichiers de traduction sont bien présents :**
   ```bash
   ls -la lang/fr/
   ls -la lang/en/
   ```

2. **Nettoyer le cache de configuration :**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Vérifier la locale dans `.env` :**
   ```env
   APP_LOCALE=fr
   FALLBACK_LOCALE=en
   ```

---

## 📦 Script de Déploiement Automatique

Vous pouvez créer un script `deploy.sh` pour automatiser le processus :

```bash
#!/bin/bash

echo "🚀 Début du déploiement..."

# Aller dans le répertoire du projet
cd /chemin/vers/votre/projet

# Récupérer les modifications
git pull origin main

# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Nettoyer les caches
php artisan optimize:clear

# Optimiser
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Redémarrer les workers
php artisan queue:restart

echo "✅ Déploiement terminé !"
```

Rendre le script exécutable :
```bash
chmod +x deploy.sh
```

Utilisation :
```bash
./deploy.sh
```

---

## 🔐 Sécurité

- Ne jamais commiter le fichier `.env`
- Utiliser des mots de passe forts pour la base de données
- Activer HTTPS sur votre site
- Mettre à jour régulièrement les dépendances
- Surveiller les logs pour détecter les erreurs

---

## 📞 Support

Si vous rencontrez des problèmes lors de la mise à jour, vérifiez :
1. Les logs Laravel (`storage/logs/laravel.log`)
2. Les logs du serveur web (Apache/Nginx)
3. Les permissions des fichiers et dossiers
4. La configuration PHP (version, extensions)

---

**Dernière mise à jour :** $(date +%Y-%m-%d)

