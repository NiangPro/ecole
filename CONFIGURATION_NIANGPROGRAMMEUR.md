# Configuration Redis et CDN pour niangprogrammeur.com

## Configuration Recommandée

### Option 1 : Configuration Complète (VPS avec Redis + Cloudflare CDN)

Si vous avez un VPS (serveur dédié), voici la configuration optimale :

**Dans votre fichier `.env` sur le serveur :**

```env
# ============================================
# CONFIGURATION CACHE REDIS
# ============================================
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CACHE_DB=1

# ============================================
# CONFIGURATION CDN
# ============================================
CDN_URL=https://niangprogrammeur.com
```

**Note :** Avec Cloudflare, vous pouvez utiliser directement votre domaine principal car Cloudflare sert automatiquement les assets depuis son CDN.

### Option 2 : Configuration avec Redis Cloud (Gratuit)

Si vous n'avez pas de VPS mais voulez utiliser Redis :

**1. Créer un compte gratuit sur Upstash :**
   - Aller sur https://upstash.com
   - Créer un compte (gratuit jusqu'à 10K requêtes/jour)
   - Créer une base Redis
   - Copier les informations de connexion

**2. Configuration dans `.env` :**

```env
# ============================================
# CONFIGURATION CACHE REDIS (Upstash)
# ============================================
CACHE_STORE=redis
REDIS_HOST=votre-host.upstash.io
REDIS_PASSWORD=votre-mot-de-passe-upstash
REDIS_PORT=6379
REDIS_CACHE_DB=0

# ============================================
# CONFIGURATION CDN (Cloudflare)
# ============================================
CDN_URL=https://niangprogrammeur.com
```

### Option 3 : Configuration Minimale (Sans Redis)

Si vous ne voulez pas configurer Redis pour l'instant :

```env
# ============================================
# CONFIGURATION CACHE (Base de données)
# ============================================
CACHE_STORE=database

# ============================================
# CONFIGURATION CDN (Cloudflare)
# ============================================
CDN_URL=https://niangprogrammeur.com
```

## Étapes de Configuration Détaillées

### Étape 1 : Configurer Cloudflare CDN (Gratuit)

1. **Créer un compte Cloudflare :**
   - Aller sur https://www.cloudflare.com
   - Cliquer sur "Sign Up" (gratuit)

2. **Ajouter votre domaine :**
   - Dans le dashboard, cliquer sur "Add a Site"
   - Entrer : `niangprogrammeur.com`
   - Choisir le plan "Free"

3. **Changer les DNS :**
   - Cloudflare vous donnera 2 serveurs DNS (ex: `ns1.cloudflare.com` et `ns2.cloudflare.com`)
   - Aller chez votre registrar (là où vous avez acheté le domaine)
   - Remplacer les serveurs DNS par ceux de Cloudflare
   - Attendre 24-48h pour la propagation

4. **Activer les optimisations :**
   - Dans Cloudflare, aller dans "Speed" → "Optimization"
   - Activer "Auto Minify" (CSS, JavaScript, HTML)
   - Activer "Brotli"
   - Dans "Caching", configurer :
     - Browser Cache TTL : 1 month
     - Edge Cache TTL : 1 month

5. **Configuration dans `.env` :**
   ```env
   CDN_URL=https://niangprogrammeur.com
   ```

### Étape 2 : Configurer Redis (Optionnel mais Recommandé)

#### Si vous avez un VPS :

**Installation Redis :**
```bash
# Se connecter à votre serveur via SSH
ssh root@votre-serveur

# Installer Redis (Ubuntu/Debian)
sudo apt update
sudo apt install redis-server -y

# Démarrer Redis
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Vérifier que Redis fonctionne
redis-cli ping
# Devrait répondre : PONG
```

**Sécuriser Redis (Important en production) :**
```bash
# Éditer la configuration Redis
sudo nano /etc/redis/redis.conf

# Trouver et modifier :
requirepass votre_mot_de_passe_securise_ici
bind 127.0.0.1

# Redémarrer Redis
sudo systemctl restart redis-server

# Tester avec le mot de passe
redis-cli -a votre_mot_de_passe_securise_ici ping
```

**Configuration dans `.env` :**
```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=votre_mot_de_passe_securise_ici
REDIS_PORT=6379
REDIS_CACHE_DB=1
```

#### Si vous n'avez pas de VPS :

**Utiliser Upstash (Gratuit) :**

1. Aller sur https://upstash.com
2. Créer un compte gratuit
3. Créer une nouvelle base Redis
4. Choisir la région la plus proche (ex: `eu-west-1` pour l'Europe)
5. Copier les informations de connexion :
   - Endpoint (REDIS_HOST)
   - Port (généralement 6379)
   - Password (REDIS_PASSWORD)

**Configuration dans `.env` :**
```env
CACHE_STORE=redis
REDIS_HOST=votre-endpoint.upstash.io
REDIS_PASSWORD=votre-password-upstash
REDIS_PORT=6379
REDIS_CACHE_DB=0
```

### Étape 3 : Vérifier la Configuration

**1. Vider le cache de configuration :**
```bash
php artisan config:clear
```

**2. Tester Redis :**
```bash
php artisan tinker
>>> Cache::put('test', 'ok', 60);
>>> Cache::get('test');
# Devrait retourner : "ok"
>>> exit
```

**3. Précharger le cache :**
```bash
php artisan cache:warmup
```

**4. Optimiser les images :**
```bash
php artisan images:optimize
```

**5. Optimiser l'application :**
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Configuration Finale Recommandée

**Pour niangprogrammeur.com avec VPS + Cloudflare :**

```env
# ============================================
# APPLICATION
# ============================================
APP_NAME="NiangProgrammeur"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://niangprogrammeur.com

# ============================================
# CACHE REDIS
# ============================================
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=votre_mot_de_passe_redis_securise
REDIS_PORT=6379
REDIS_CACHE_DB=1

# ============================================
# CDN CLOUDFLARE
# ============================================
CDN_URL=https://niangprogrammeur.com
```

**Pour niangprogrammeur.com sans VPS (Hébergement partagé) :**

```env
# ============================================
# APPLICATION
# ============================================
APP_NAME="NiangProgrammeur"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://niangprogrammeur.com

# ============================================
# CACHE (Base de données)
# ============================================
CACHE_STORE=database

# ============================================
# CDN CLOUDFLARE
# ============================================
CDN_URL=https://niangprogrammeur.com
```

## Commandes de Déploiement

Après avoir configuré votre `.env`, exécutez ces commandes sur votre serveur :

```bash
# 1. Vider tous les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 2. Précharger le cache
php artisan cache:warmup

# 3. Optimiser les images en WebP
php artisan images:optimize

# 4. Optimiser l'application
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Vérification

### Vérifier que Redis fonctionne :

```bash
# Depuis le serveur
redis-cli ping
# Devrait répondre : PONG

# Depuis Laravel
php artisan tinker
>>> Cache::store('redis')->put('test', 'ok', 60);
>>> Cache::store('redis')->get('test');
# Devrait retourner : "ok"
```

### Vérifier que le CDN fonctionne :

1. Ouvrir https://niangprogrammeur.com dans votre navigateur
2. Ouvrir les DevTools (F12)
3. Aller dans l'onglet "Network"
4. Recharger la page
5. Vérifier les requêtes CSS/JS/Images :
   - Elles devraient venir de Cloudflare (vérifier les headers `CF-Cache-Status`)

### Vérifier la compression :

```bash
# Tester avec curl
curl -H "Accept-Encoding: br" -I https://niangprogrammeur.com
# Devrait montrer : Content-Encoding: br
```

## Support et Dépannage

### Problème : Redis ne se connecte pas

**Solution :**
```bash
# Vérifier que Redis est démarré
sudo systemctl status redis-server

# Vérifier la connexion
redis-cli ping

# Vérifier les logs
tail -f /var/log/redis/redis-server.log
```

### Problème : CDN ne fonctionne pas

**Solution :**
1. Vérifier que Cloudflare est actif sur votre domaine
2. Vérifier que les DNS pointent vers Cloudflare
3. Vérifier que `CDN_URL` est correct dans `.env`
4. Vider le cache : `php artisan config:clear`

### Problème : Les images WebP ne s'affichent pas

**Solution :**
```bash
# Convertir les images en WebP
php artisan images:optimize

# Vérifier que l'extension GD est installée
php -m | grep gd
```

## Résumé

- **CDN** : Cloudflare (gratuit, facile à configurer)
- **Redis** : Optionnel mais recommandé pour les performances
- **Configuration** : Ajouter les variables dans `.env`
- **Déploiement** : Exécuter les commandes d'optimisation

Pour toute question, consultez `GUIDE_CONFIGURATION_REDIS_CDN.md` pour plus de détails.

