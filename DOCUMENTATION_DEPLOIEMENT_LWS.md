# 📚 Documentation Complète - Déploiement sur LWS

## 🎯 Vue d'ensemble

Ce guide vous explique comment mettre à jour votre site Laravel hébergé sur LWS (LWS Hosting) après avoir supprimé le système de traduction et remis tout le contenu en français.

---

## 📋 Prérequis

- Accès FTP/SFTP à votre hébergement LWS
- Accès SSH (si disponible)
- Client FTP (FileZilla, WinSCP, etc.)
- Connexion à la base de données (phpMyAdmin ou autre)

---

## 🔧 Étape 1 : Préparation locale

### 1.1 Vérifier les modifications

Assurez-vous que toutes les modifications suivantes ont été effectuées :

- ✅ Middleware `SetLocale` supprimé de `bootstrap/app.php`
- ✅ Route `/lang/{locale}` supprimée de `routes/web.php`
- ✅ Méthode `setLocale()` supprimée de `PageController`
- ✅ Sélecteur de langue supprimé de `resources/views/partials/navigation.blade.php`
- ✅ Tous les `trans()` remplacés par du texte français en dur
- ✅ Locale forcée à `'fr'` dans `config/app.php`
- ✅ **IMPORTANT** : `TranslationHelper.php` supprimé de `composer.json` (section `autoload.files`)

### 1.2 Vider les caches localement

```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan route:clear
composer dump-autoload  # CRITIQUE : Régénère l'autoload après suppression de TranslationHelper.php
```

### 1.3 Tester localement

1. Démarrer le serveur : `php artisan serve`
2. Tester toutes les pages principales
3. Vérifier qu'il n'y a plus de sélecteur de langue
4. Vérifier que tout le contenu est en français

---

## 📤 Étape 2 : Préparer les fichiers pour le déploiement

### 2.1 Fichiers à modifier/supprimer

**Fichiers à SUPPRIMER** :
- `app/Http/Middleware/SetLocale.php` (si encore présent)
- `app/Helpers/TranslationHelper.php` (si encore présent)

**Fichiers à MODIFIER OBLIGATOIREMENT** :
- `composer.json` : Supprimer `app/Helpers/TranslationHelper.php` de la section `autoload.files`

**Fichiers à MODIFIER** :
- `bootstrap/app.php`
- `routes/web.php`
- `app/Http/Controllers/PageController.php`
- `resources/views/partials/navigation.blade.php`
- Tous les fichiers de vues avec `trans()`

**Fichiers à CONSERVER** (mais non utilisés) :
- `lang/fr/app.php` (peut être conservé pour référence)
- `lang/en/app.php` (peut être supprimé ou conservé)

### 2.2 Créer une archive (optionnel)

Si vous voulez créer une sauvegarde avant déploiement :

```bash
# Créer une archive de tous les fichiers modifiés
tar -czf backup-avant-deploiement-$(date +%Y%m%d).tar.gz \
  bootstrap/app.php \
  routes/web.php \
  app/Http/Controllers/PageController.php \
  resources/views/partials/navigation.blade.php \
  resources/views/
```

---

## 🚀 Étape 3 : Déploiement sur LWS

### 3.1 Connexion FTP/SFTP

1. **Ouvrir votre client FTP** (FileZilla, WinSCP, etc.)
2. **Se connecter** avec vos identifiants LWS :
   - Hôte : `ftp.votre-domaine.com` ou l'adresse fournie par LWS
   - Port : `21` (FTP) ou `22` (SFTP)
   - Utilisateur : Votre identifiant FTP
   - Mot de passe : Votre mot de passe FTP

### 3.2 Structure des dossiers sur LWS

Sur LWS, votre site Laravel est généralement dans :
```
/home/votre-utilisateur/
├── public_html/          # Dossier public (point d'entrée)
│   ├── index.php         # Point d'entrée Laravel
│   ├── .htaccess         # Configuration Apache
│   └── assets/           # Assets publics
├── laravel/              # Dossier Laravel (si séparé)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── routes/
│   ├── resources/
│   └── ...
└── ...
```

**Note** : La structure peut varier selon votre configuration LWS.

### 3.3 Upload des fichiers modifiés

#### Option A : Upload sélectif (recommandé)

1. **composer.json** ⚠️ **CRITIQUE**
   - Local : `composer.json`
   - Serveur : `laravel/composer.json` (ou selon votre structure)
   - **Vérifier** que `TranslationHelper.php` n'est plus dans `autoload.files`

2. **bootstrap/app.php**
   - Local : `bootstrap/app.php`
   - Serveur : `laravel/bootstrap/app.php` (ou selon votre structure)

3. **routes/web.php**
   - Local : `routes/web.php`
   - Serveur : `laravel/routes/web.php`

4. **app/Http/Controllers/PageController.php**
   - Local : `app/Http/Controllers/PageController.php`
   - Serveur : `laravel/app/Http/Controllers/PageController.php`

5. **resources/views/partials/navigation.blade.php**
   - Local : `resources/views/partials/navigation.blade.php`
   - Serveur : `laravel/resources/views/partials/navigation.blade.php`

6. **Tous les fichiers de vues modifiés**
   - Uploader tous les fichiers `.blade.php` modifiés

#### Option B : Upload complet (si beaucoup de modifications)

1. **Créer une archive** de tous les fichiers modifiés
2. **Uploader l'archive** sur le serveur
3. **Extraire** l'archive sur le serveur (via SSH ou FTP)

### 3.4 Supprimer les fichiers inutiles

Si les fichiers suivants existent encore sur le serveur, les supprimer :

- `app/Http/Middleware/SetLocale.php`
- `app/Helpers/TranslationHelper.php`

---

## 🔄 Étape 4 : Actions sur le serveur

### 4.1 Via SSH (si disponible)

Si vous avez accès SSH :

```bash
# Se connecter au serveur
ssh votre-utilisateur@votre-domaine.com

# Aller dans le dossier Laravel
cd ~/laravel  # ou le chemin de votre installation Laravel
# OU si votre site est directement dans public_html :
cd ~/public_html  # ou /htdocs/niangprogrammeur.com

# ⚠️ CRITIQUE : Régénérer l'autoload EN PREMIER (après modification de composer.json)
composer dump-autoload

# Vider les caches
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan route:clear

# Optimiser (optionnel, pour la production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**⚠️ IMPORTANT** : Si vous obtenez l'erreur `TranslationHelper.php not found`, c'est que `composer.json` n'a pas été mis à jour ou que `composer dump-autoload` n'a pas été exécuté.

### 4.2 Via FTP uniquement

Si vous n'avez pas accès SSH, vous pouvez :

1. **Supprimer manuellement les caches** :
   - `bootstrap/cache/config.php` (si existe)
   - `bootstrap/cache/routes-v7.php` (si existe)
   - `storage/framework/views/*` (vider le dossier)

2. **Vérifier les permissions** :
   - `storage/` : `755` ou `775`
   - `bootstrap/cache/` : `755` ou `775`

---

## ✅ Étape 5 : Vérifications

### 5.1 Vérifier le site

1. **Ouvrir votre site** : `https://www.niangprogrammeur.com`
2. **Vérifier** :
   - ✅ Pas de sélecteur de langue dans la navbar
   - ✅ Tout le contenu est en français
   - ✅ Toutes les pages se chargent correctement
   - ✅ Pas d'erreurs dans la console (F12)

### 5.2 Vérifier les routes

Tester les routes principales :
- `/` (Accueil)
- `/formations` (Formations)
- `/formations/html5` (Formation HTML5)
- `/exercices` (Exercices)
- `/quiz` (Quiz)
- `/contact` (Contact)

### 5.3 Vérifier les logs

Si des erreurs apparaissent, vérifier les logs :

**Via SSH** :
```bash
tail -f storage/logs/laravel.log
```

**Via FTP** :
- Télécharger `storage/logs/laravel.log`
- Vérifier les erreurs récentes

---

## 🐛 Résolution de problèmes

### Problème 1 : Erreur 500 - TranslationHelper.php not found

**Erreur typique** :
```
Fatal error: Failed opening required 'TranslationHelper.php'
```

**Solution** :
1. **Vérifier que `composer.json` a été uploadé** et que `TranslationHelper.php` n'est plus dans `autoload.files`
2. **Exécuter sur le serveur** (via SSH) :
   ```bash
   composer dump-autoload
   ```
3. Si pas d'accès SSH, supprimer manuellement le cache :
   - Supprimer `vendor/composer/autoload_files.php` (ou le régénérer)
   - Ou supprimer tout le dossier `vendor/` et réinstaller : `composer install`

### Problème 2 : Erreur 500 (générale)

**Solution** :
1. Vérifier les permissions des dossiers `storage/` et `bootstrap/cache/`
2. Vérifier le fichier `.env` (variables d'environnement)
3. Vérifier les logs : `storage/logs/laravel.log`

### Problème 2 : Page blanche

**Solution** :
1. Activer l'affichage des erreurs dans `.env` :
   ```env
   APP_DEBUG=true
   ```
2. Vérifier les logs
3. Vérifier que tous les fichiers ont été uploadés correctement

### Problème 3 : Sélecteur de langue encore visible

**Solution** :
1. Vider le cache du navigateur (Ctrl+F5)
2. Vérifier que `resources/views/partials/navigation.blade.php` a été uploadé
3. Vider les caches Laravel sur le serveur

### Problème 4 : Contenu en anglais

**Solution** :
1. Vérifier que tous les `trans()` ont été remplacés
2. Vérifier `config/app.php` : `'locale' => 'fr'`
3. Vider les caches

---

## 📝 Checklist de déploiement

- [ ] Tous les fichiers modifiés uploadés
- [ ] Fichiers inutiles supprimés (`SetLocale.php`, etc.)
- [ ] Caches vidés sur le serveur
- [ ] Permissions vérifiées (`storage/`, `bootstrap/cache/`)
- [ ] Site testé (toutes les pages principales)
- [ ] Sélecteur de langue absent
- [ ] Tout le contenu en français
- [ ] Pas d'erreurs dans les logs
- [ ] Performance vérifiée

---

## 🔐 Sécurité post-déploiement

### 1. Désactiver le mode debug

Dans `.env` sur le serveur :
```env
APP_DEBUG=false
APP_ENV=production
```

### 2. Optimiser pour la production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Vérifier les permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 📞 Support

Si vous rencontrez des problèmes :

1. **Vérifier les logs** : `storage/logs/laravel.log`
2. **Vérifier la documentation LWS** : https://www.lws.fr/
3. **Contacter le support LWS** si nécessaire

---

## 📅 Notes de version

**Date** : 2025-01-27
**Version** : 1.0
**Modifications** :
- Suppression du système de traduction
- Suppression du sélecteur de langue
- Remise de tout le contenu en français
- Simplification du code

---

**Bon déploiement ! 🚀**

