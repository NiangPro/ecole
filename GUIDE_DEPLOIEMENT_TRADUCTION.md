# 🌍 Guide de Déploiement du Système de Traduction

## 📋 Vue d'ensemble

Ce guide explique comment déployer le système de traduction français/anglais sur le site en production (niangprogrammeur.com).

## ✅ Prérequis

- Accès FTP/SFTP au serveur de production
- Accès SSH au serveur (optionnel mais recommandé)
- Les fichiers de traduction doivent être présents sur le serveur

## 📁 Fichiers à Déployer

### 1. Fichiers de Traduction

Les fichiers suivants doivent être présents sur le serveur :

```
lang/
├── fr/
│   └── app.php
└── en/
    └── app.php
```

**Emplacement sur le serveur :** `/chemin/vers/votre/projet/lang/`

### 2. Fichiers Modifiés

#### Contrôleur
- `app/Http/Controllers/PageController.php`
  - Méthode `ensureLocale()` modifiée
  - Nouvelle méthode `setLanguage($locale)`

#### Routes
- `routes/web.php`
  - Nouvelle route : `Route::get('/language/{locale}', ...)`

#### Vues
- `resources/views/layouts/app.blade.php`
  - Widget de langue avec drapeau
  - Fonction JavaScript `toggleLanguage()`

- `resources/views/formations/all.blade.php`
  - Utilise les clés de traduction `trans('app.formations.*')`

## 🚀 Étapes de Déploiement

### Étape 1 : Vérifier les Fichiers de Traduction

Assurez-vous que les fichiers de traduction sont présents :

```bash
# Sur le serveur
ls -la lang/fr/app.php
ls -la lang/en/app.php
```

### Étape 2 : Uploader les Fichiers Modifiés

Via FTP/SFTP, uploader les fichiers suivants :

1. `app/Http/Controllers/PageController.php`
2. `routes/web.php`
3. `resources/views/layouts/app.blade.php`
4. `resources/views/formations/all.blade.php`
5. `lang/fr/app.php` (si modifié)
6. `lang/en/app.php` (si modifié)

### Étape 3 : Vider les Caches

**Important :** Vider tous les caches Laravel après le déploiement.

#### Via SSH (recommandé) :

```bash
cd /chemin/vers/votre/projet
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Via FTP + Interface Admin (si disponible) :

Si vous avez accès à une interface d'administration, utilisez la fonctionnalité de vidage de cache.

#### Via Terminal Laravel (si installé) :

```bash
php artisan optimize:clear
```

### Étape 4 : Vérifier les Permissions

Assurez-vous que les fichiers ont les bonnes permissions :

```bash
chmod -R 755 lang/
chmod 644 lang/fr/app.php
chmod 644 lang/en/app.php
```

### Étape 5 : Tester le Système

1. **Tester la page formations :**
   - Visiter : `https://niangprogrammeur.com/formations`
   - Vérifier que le contenu s'affiche en français

2. **Tester le changement de langue :**
   - Cliquer sur l'icône de drapeau 🇫🇷
   - Vérifier que la page se recharge en anglais
   - Vérifier que le drapeau change en 🇬🇧

3. **Vérifier la persistance :**
   - Recharger la page
   - Vérifier que la langue sélectionnée est conservée

## 🔧 Configuration Serveur

### Vérifier la Configuration PHP

Assurez-vous que les sessions PHP fonctionnent correctement :

```php
// Vérifier dans php.ini
session.save_handler = files
session.save_path = "/tmp" (ou un chemin valide)
```

### Vérifier les Permissions de Session

```bash
# Créer le dossier de sessions si nécessaire
mkdir -p /chemin/vers/sessions
chmod 777 /chemin/vers/sessions
```

## 🐛 Dépannage

### Problème : La langue ne change pas

**Solution :**
1. Vider tous les caches
2. Vérifier que la route `/language/{locale}` est accessible
3. Vérifier les logs Laravel : `storage/logs/laravel.log`

### Problème : Les traductions ne s'affichent pas

**Solution :**
1. Vérifier que les fichiers `lang/fr/app.php` et `lang/en/app.php` existent
2. Vérifier les permissions des fichiers
3. Vérifier la syntaxe PHP des fichiers de traduction

### Problème : Le drapeau ne s'affiche pas

**Solution :**
1. Vérifier que l'encodage UTF-8 est correct
2. Vérifier que le navigateur supporte les emojis
3. Vérifier que la condition `$showLanguageWidget` est vraie

## 📝 Checklist de Déploiement

- [ ] Fichiers de traduction uploadés (`lang/fr/app.php`, `lang/en/app.php`)
- [ ] Contrôleur modifié uploadé (`app/Http/Controllers/PageController.php`)
- [ ] Routes modifiées uploadées (`routes/web.php`)
- [ ] Vues modifiées uploadées (`resources/views/layouts/app.blade.php`, `resources/views/formations/all.blade.php`)
- [ ] Caches vidés (cache, config, route, view)
- [ ] Permissions vérifiées
- [ ] Test de la page formations en français
- [ ] Test du changement de langue
- [ ] Test de la persistance de la langue

## 🔄 Mise à Jour Future

Pour ajouter de nouvelles traductions :

1. Modifier `lang/fr/app.php` et `lang/en/app.php`
2. Uploader les fichiers modifiés
3. Vider les caches : `php artisan cache:clear && php artisan view:clear`

## 📞 Support

En cas de problème, vérifier :
- Les logs Laravel : `storage/logs/laravel.log`
- Les logs serveur (Apache/Nginx)
- La console du navigateur (F12) pour les erreurs JavaScript

## 🎯 Résultat Attendu

Après le déploiement :
- ✅ L'icône de langue affiche un drapeau (🇫🇷 ou 🇬🇧)
- ✅ Le clic sur le drapeau change la langue
- ✅ La langue est conservée lors de la navigation
- ✅ Tous les textes de la page formations sont traduits
- ✅ Le changement de langue fonctionne sur toutes les pages concernées

