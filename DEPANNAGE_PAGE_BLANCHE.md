# Dépannage - Page Blanche après Optimisation

## Diagnostic Rapide

Si la page d'accueil affiche une page blanche après avoir lancé les commandes d'optimisation, suivez ces étapes :

## Étape 1 : Vérifier les Logs d'Erreur

```bash
# Se connecter au serveur
ssh votre-utilisateur@niangprogrammeur.com

# Vérifier les logs Laravel
tail -n 50 storage/logs/laravel.log

# Vérifier les logs PHP
tail -n 50 /var/log/php-fpm/error.log
# ou
tail -n 50 /var/log/apache2/error.log
# ou
tail -n 50 /var/log/nginx/error.log
```

## Étape 2 : Vider TOUS les Caches

```bash
# Vider tous les caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

# Si le problème persiste, supprimer manuellement
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*
```

## Étape 3 : Désactiver Temporairement le Middleware de Compression

Le middleware `CompressResponse` peut causer des problèmes si Brotli/Gzip ne sont pas disponibles.

**Modifier `bootstrap/app.php` :**

```php
->withMiddleware(function (Middleware $middleware): void {
    // Middleware pour forcer www en production
    $middleware->web(prepend: [
        \App\Http\Middleware\ForceWwwRedirect::class,
        \App\Http\Middleware\EnhancedCsrfProtection::class,
    ]);
    
    $middleware->web(append: [
        \App\Http\Middleware\TrackVisit::class,
        // DÉSACTIVER TEMPORAIREMENT
        // \App\Http\Middleware\CompressResponse::class,
    ]);
    
    // ...
})
```

Puis :
```bash
php artisan config:clear
php artisan route:clear
```

## Étape 4 : Vérifier les Permissions

```bash
# Vérifier les permissions
ls -la storage/logs/
ls -la storage/framework/cache/
ls -la storage/framework/views/

# Corriger les permissions si nécessaire
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Étape 5 : Vérifier les Extensions PHP

```bash
# Vérifier que les extensions nécessaires sont installées
php -m | grep -E "gd|imagick|redis|mbstring|openssl"

# Si GD n'est pas installé (pour WebP)
sudo apt install php-gd
sudo systemctl restart php-fpm
# ou
sudo systemctl restart apache2
```

## Étape 6 : Activer le Mode Debug Temporairement

**Dans `.env` :**
```env
APP_DEBUG=true
APP_ENV=local
```

Cela affichera les erreurs au lieu d'une page blanche.

**⚠️ IMPORTANT :** Remettre `APP_DEBUG=false` après résolution du problème !

## Étape 7 : Vérifier la Mémoire PHP

```bash
# Vérifier la limite de mémoire
php -i | grep memory_limit

# Si trop faible, augmenter dans php.ini
memory_limit = 256M
```

## Solutions Spécifiques

### Solution 1 : Problème avec CompressResponse

Si le problème vient du middleware de compression :

**Option A : Désactiver complètement**
Voir Étape 3 ci-dessus.

**Option B : Corriger le middleware**

Modifier `app/Http/Middleware/CompressResponse.php` :

```php
public function handle(Request $request, Closure $next): Response
{
    $response = $next($request);

    // Ne compresser qu'en production et pour les réponses HTML/CSS/JS
    if (app()->environment('production') && $this->shouldCompress($response)) {
        // Vérifier que les fonctions sont disponibles
        if (!function_exists('gzencode') && !function_exists('brotli_compress')) {
            return $response; // Retourner sans compression
        }
        
        $content = $response->getContent();
        
        // Vérifier que le contenu n'est pas vide
        if (empty($content)) {
            return $response;
        }
        
        // Vérifier si le client supporte Brotli
        if ($this->supportsBrotli($request) && function_exists('brotli_compress')) {
            $compressed = @brotli_compress($content, 6);
            if ($compressed !== false && !empty($compressed)) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'br');
                $response->headers->set('Vary', 'Accept-Encoding');
                return $response;
            }
        }
        
        // Sinon, vérifier si le client supporte Gzip
        if ($this->supportsGzip($request) && function_exists('gzencode')) {
            $compressed = @gzencode($content, 6);
            if ($compressed !== false && !empty($compressed)) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->set('Vary', 'Accept-Encoding');
                return $response;
            }
        }
    }

    return $response;
}
```

### Solution 2 : Problème avec ImageOptimizer

Si le problème vient de la conversion WebP :

**Désactiver temporairement la conversion automatique :**

Modifier `app/Helpers/ImageOptimizer.php`, dans la méthode `attributes()` :

```php
// Commenter temporairement cette partie
/*
// Support WebP : générer une version WebP si disponible
$webpSrc = self::getWebpVersion($src);
if ($webpSrc && !isset($options['no_webp'])) {
    $attributes[] = 'srcset="' . htmlspecialchars($webpSrc, ENT_QUOTES, 'UTF-8') . '"';
    $attributes[] = 'type="image/webp"';
}
*/
```

### Solution 3 : Problème avec Redis

Si Redis n'est pas disponible :

**Dans `.env` :**
```env
# Utiliser la base de données au lieu de Redis
CACHE_STORE=database
```

Puis :
```bash
php artisan config:clear
php artisan cache:clear
```

## Commandes de Récupération Complètes

```bash
# 1. Vider tous les caches
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

# 4. Activer le debug temporairement
# (Modifier .env : APP_DEBUG=true)

# 5. Vérifier les logs
tail -f storage/logs/laravel.log

# 6. Tester l'application
php artisan route:list
php artisan config:cache
```

## Vérification Rapide

```bash
# Test 1 : Vérifier que Laravel fonctionne
php artisan --version

# Test 2 : Vérifier les routes
php artisan route:list

# Test 3 : Vérifier la configuration
php artisan config:show app.name

# Test 4 : Tester une route spécifique
php artisan tinker
>>> \Route::getRoutes()->getByName('home');
```

## Si Rien ne Fonctionne

1. **Restaurer depuis un backup** si vous en avez un
2. **Vérifier les fichiers modifiés récemment** :
   ```bash
   git status
   git diff
   ```
3. **Désactiver toutes les optimisations** :
   - Désactiver CompressResponse
   - Désactiver WebP dans ImageOptimizer
   - Utiliser `CACHE_STORE=database`
4. **Contacter le support de l'hébergeur** avec les logs d'erreur

## Prévention Future

1. **Toujours tester en local** avant de déployer
2. **Faire un backup** avant les optimisations
3. **Activer APP_DEBUG temporairement** pour voir les erreurs
4. **Vérifier les logs** après chaque déploiement

