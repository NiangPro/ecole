# Guide pour vider tous les caches Laravel

## Commande rapide (recommandée)
```bash
php artisan optimize:clear
```

Cette commande vide automatiquement :
- Configuration cache
- Application cache
- Compiled files
- Events cache
- Routes cache
- Views cache

## Commandes individuelles

Si vous voulez vider un cache spécifique :

```bash
# Cache de l'application
php artisan cache:clear

# Cache de configuration
php artisan config:clear

# Cache des routes
php artisan route:clear

# Cache des vues
php artisan view:clear
```

## Scripts Windows

### PowerShell
```powershell
.\clear-cache.ps1
```

### Batch
Double-cliquez sur `clear-cache.bat`

## Important

**Après chaque modification de :**
- Vues (fichiers .blade.php) → `php artisan view:clear`
- Routes (routes/web.php) → `php artisan route:clear`
- Configuration (config/*.php) → `php artisan config:clear`
- Code PHP → `php artisan optimize:clear`

**Pensez aussi à :**
- Vider le cache du navigateur (Ctrl + F5 pour un hard refresh)
- Vérifier que le serveur de développement est bien redémarré si nécessaire



