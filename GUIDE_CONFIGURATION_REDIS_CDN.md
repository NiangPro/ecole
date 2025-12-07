# Guide de Configuration Redis et CDN pour niangprogrammeur.com

## Configuration Redis

### Option 1 : Redis sur le serveur (VPS/Dedicated)

Si vous avez un serveur VPS ou dédié, vous pouvez installer Redis directement :

**Installation Redis (Ubuntu/Debian) :**
```bash
sudo apt update
sudo apt install redis-server
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

**Configuration dans `.env` :**
```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CACHE_DB=1
```

**Sécuriser Redis (recommandé en production) :**
```bash
# Éditer /etc/redis/redis.conf
sudo nano /etc/redis/redis.conf

# Ajouter un mot de passe
requirepass votre_mot_de_passe_securise

# Ne permettre que les connexions locales
bind 127.0.0.1

# Redémarrer Redis
sudo systemctl restart redis-server
```

**Configuration avec mot de passe dans `.env` :**
```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=votre_mot_de_passe_securise
REDIS_PORT=6379
REDIS_CACHE_DB=1
```

### Option 2 : Redis Cloud (Service géré)

Si vous utilisez un service géré comme Redis Cloud, Upstash, ou AWS ElastiCache :

**Exemple avec Redis Cloud :**
```env
CACHE_STORE=redis
REDIS_HOST=redis-12345.c123.us-east-1-1.ec2.cloud.redislabs.com
REDIS_PASSWORD=votre_mot_de_passe_redis_cloud
REDIS_PORT=12345
REDIS_CACHE_DB=0
```

**Exemple avec Upstash (gratuit jusqu'à 10K requêtes/jour) :**
```env
CACHE_STORE=redis
REDIS_HOST=usw1-xxx.upstash.io
REDIS_PASSWORD=votre_mot_de_passe_upstash
REDIS_PORT=6379
REDIS_CACHE_DB=0
```

### Option 3 : Hébergement partagé (sans Redis)

Si votre hébergeur ne supporte pas Redis, utilisez la base de données :

```env
CACHE_STORE=database
```

Le système utilisera automatiquement la base de données comme cache.

### Vérification de la connexion Redis

**Tester la connexion :**
```bash
# Depuis le serveur
redis-cli ping
# Devrait répondre : PONG

# Avec mot de passe
redis-cli -a votre_mot_de_passe ping
```

**Tester depuis Laravel :**
```bash
php artisan tinker
>>> Cache::put('test', 'value', 60);
>>> Cache::get('test');
# Devrait retourner : "value"
```

## Configuration CDN

### Option 1 : Cloudflare (Recommandé - Gratuit)

Cloudflare est gratuit et très performant pour les sites web.

**Étapes :**

1. **Créer un compte Cloudflare** : https://www.cloudflare.com
2. **Ajouter votre domaine** niangprogrammeur.com
3. **Changer les DNS** de votre domaine vers Cloudflare
4. **Activer le CDN** dans le dashboard Cloudflare

**Configuration dans `.env` :**
```env
CDN_URL=https://cdn.niangprogrammeur.com
```

**Ou utiliser le sous-domaine Cloudflare :**
```env
CDN_URL=https://niangprogrammeur.com
```

Cloudflare servira automatiquement les assets statiques depuis son CDN.

**Configuration avancée Cloudflare :**
- Activer "Auto Minify" (CSS, JavaScript, HTML)
- Activer "Brotli" compression
- Activer "Rocket Loader" (optionnel)
- Configurer les règles de cache (Cache Everything pour `/css`, `/js`, `/images`)

### Option 2 : AWS CloudFront

Si vous utilisez AWS :

**Étapes :**

1. **Créer un bucket S3** pour les assets statiques
2. **Créer une distribution CloudFront** pointant vers le bucket S3
3. **Configurer les permissions** et les CORS

**Configuration dans `.env` :**
```env
CDN_URL=https://d1234567890.cloudfront.net
```

### Option 3 : CDN personnalisé (VPS)

Si vous avez un VPS et voulez créer votre propre CDN :

**Avec Nginx :**
```nginx
# /etc/nginx/sites-available/cdn.niangprogrammeur.com
server {
    listen 80;
    listen [::]:80;
    server_name cdn.niangprogrammeur.com;

    root /var/www/niangprogrammeur.com/public;
    index index.html;

    location / {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|ttf|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
}
```

**Configuration dans `.env` :**
```env
CDN_URL=https://cdn.niangprogrammeur.com
```

### Option 4 : Sans CDN (Fallback)

Si vous ne configurez pas de CDN, le système utilisera les assets locaux :

```env
# Laisser vide ou ne pas définir
CDN_URL=
```

Les assets seront servis depuis votre serveur principal.

## Configuration complète pour niangprogrammeur.com

### Exemple avec Redis local + Cloudflare

**`.env` :**
```env
# Application
APP_NAME="NiangProgrammeur"
APP_ENV=production
APP_URL=https://niangprogrammeur.com

# Cache Redis
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=votre_mot_de_passe_redis
REDIS_PORT=6379
REDIS_CACHE_DB=1

# CDN Cloudflare
CDN_URL=https://niangprogrammeur.com
```

### Exemple avec Redis Cloud + Cloudflare

**`.env` :**
```env
# Application
APP_NAME="NiangProgrammeur"
APP_ENV=production
APP_URL=https://niangprogrammeur.com

# Cache Redis (Upstash gratuit)
CACHE_STORE=redis
REDIS_HOST=usw1-xxx.upstash.io
REDIS_PASSWORD=votre_mot_de_passe_upstash
REDIS_PORT=6379
REDIS_CACHE_DB=0

# CDN Cloudflare
CDN_URL=https://niangprogrammeur.com
```

### Exemple sans Redis (Base de données)

**`.env` :**
```env
# Application
APP_NAME="NiangProgrammeur"
APP_ENV=production
APP_URL=https://niangprogrammeur.com

# Cache Base de données
CACHE_STORE=database

# CDN Cloudflare
CDN_URL=https://niangprogrammeur.com
```

## Commandes de déploiement

Après configuration, exécutez :

```bash
# Vider le cache de configuration
php artisan config:clear

# Précharger le cache
php artisan cache:warmup

# Optimiser les images en WebP
php artisan images:optimize

# Optimiser l'application
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Vérification

### Vérifier Redis

```bash
# Test de connexion
php artisan tinker
>>> Cache::store('redis')->put('test', 'ok', 60);
>>> Cache::store('redis')->get('test');
# Devrait retourner : "ok"
```

### Vérifier le CDN

1. **Inspecter les requêtes réseau** dans les DevTools du navigateur
2. **Vérifier les headers** `X-CDN` ou `CF-Cache-Status` (Cloudflare)
3. **Tester la vitesse** avec PageSpeed Insights

### Vérifier la compression

```bash
# Tester avec curl
curl -H "Accept-Encoding: br" -I https://niangprogrammeur.com
# Devrait montrer : Content-Encoding: br

curl -H "Accept-Encoding: gzip" -I https://niangprogrammeur.com
# Devrait montrer : Content-Encoding: gzip
```

## Recommandations pour niangprogrammeur.com

### Configuration recommandée (Production)

1. **Redis** : Utiliser Redis local si VPS, sinon Redis Cloud (Upstash gratuit)
2. **CDN** : Cloudflare (gratuit, excellent pour les sites web)
3. **Compression** : Activée automatiquement par le middleware
4. **Images** : Convertir en WebP avec `php artisan images:optimize`

### Configuration minimale (Démarrage)

Si vous débutez et n'avez pas encore de VPS :

```env
# Cache avec base de données (fonctionne partout)
CACHE_STORE=database

# Pas de CDN pour l'instant
CDN_URL=
```

Vous pourrez ajouter Redis et CDN plus tard sans changer le code.

## Support

Si vous avez des questions ou des problèmes :
- Vérifiez les logs : `storage/logs/laravel.log`
- Testez Redis : `redis-cli ping`
- Vérifiez les permissions des fichiers
- Assurez-vous que les extensions PHP nécessaires sont installées (redis, gd)

