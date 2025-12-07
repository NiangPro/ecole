# Commandes de Récupération Urgente - Page Blanche

## ⚠️ ACTION IMMÉDIATE

Si la page d'accueil affiche une page blanche, exécutez ces commandes **IMMÉDIATEMENT** :

```bash
# 1. Vider TOUS les caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

# 2. Supprimer les fichiers de cache manuellement
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*

# 3. Vérifier les permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 4. Reconstruire le cache de configuration
php artisan config:cache
```

## 🔍 Diagnostic

### Vérifier les logs d'erreur :

```bash
# Logs Laravel
tail -n 100 storage/logs/laravel.log

# Logs PHP
tail -n 100 /var/log/php-fpm/error.log
# ou selon votre serveur web
tail -n 100 /var/log/apache2/error.log
tail -n 100 /var/log/nginx/error.log
```

### Activer le mode debug temporairement :

**Dans `.env` :**
```env
APP_DEBUG=true
APP_ENV=local
```

⚠️ **IMPORTANT :** Remettre `APP_DEBUG=false` après résolution !

## 🛠️ Solutions Rapides

### Solution 1 : Désactiver le Middleware de Compression

**Modifier `bootstrap/app.php` :**

Trouver cette ligne :
```php
\App\Http\Middleware\CompressResponse::class,
```

Et la commenter :
```php
// \App\Http\Middleware\CompressResponse::class,
```

Puis :
```bash
php artisan config:clear
php artisan route:clear
```

### Solution 2 : Utiliser la Base de Données au lieu de Redis

**Dans `.env` :**
```env
CACHE_STORE=database
```

Puis :
```bash
php artisan config:clear
php artisan cache:clear
```

### Solution 3 : Vérifier les Extensions PHP

```bash
# Vérifier que GD est installé (pour WebP)
php -m | grep gd

# Si absent, installer
sudo apt install php-gd
sudo systemctl restart php-fpm
```

## ✅ Vérification

Après avoir appliqué les solutions :

```bash
# Tester que Laravel fonctionne
php artisan --version

# Vérifier les routes
php artisan route:list | head -20

# Tester l'application
curl -I https://niangprogrammeur.com
```

## 📞 Si Rien ne Fonctionne

1. **Restaurer depuis un backup** si disponible
2. **Vérifier les fichiers modifiés** : `git status`
3. **Contacter le support** avec les logs d'erreur

