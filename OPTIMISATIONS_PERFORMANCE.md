# Optimisations Performance - Documentation

## Vue d'ensemble

Ce document décrit toutes les optimisations de performance implémentées pour améliorer les temps de chargement et l'expérience utilisateur.

## 1. Optimisation des Images

### WebP Support

Le système convertit automatiquement les images JPG/PNG en WebP pour réduire la taille des fichiers de 25-35%.

**Fonctionnalités :**
- Conversion automatique à la volée via `ImageOptimizer`
- Support des balises `<picture>` avec fallback
- Lazy loading par défaut
- Dimensions explicites pour éviter le CLS (Cumulative Layout Shift)

**Utilisation :**
```php
// Dans les vues Blade
{!! \App\Helpers\ImageOptimizer::img('images/hero.jpg', 'Description', [
    'width' => 1200,
    'height' => 600,
    'priority' => true, // Pour les images au-dessus de la ligne de flottaison
]) !!}

// Avec picture et sources multiples
{!! \App\Helpers\ImageOptimizer::picture('images/hero.jpg', 'Description', [
    [
        'srcset' => 'images/hero-mobile.jpg 1x, images/hero-mobile@2x.jpg 2x',
        'media' => '(max-width: 768px)',
    ],
]) !!}
```

**Commande Artisan :**
```bash
# Convertir toutes les images en WebP
php artisan images:optimize

# Avec options
php artisan images:optimize --path=public/uploads --quality=90 --force
```

### Lazy Loading

Toutes les images utilisent le lazy loading par défaut (sauf celles marquées avec `priority: true`).

**Avantages :**
- Réduction du temps de chargement initial
- Économie de bande passante
- Amélioration du Core Web Vitals

## 2. Mise en Cache Avancée (Redis)

### Configuration

Redis est configuré comme cache par défaut si disponible, sinon fallback sur la base de données.

**Configuration dans `.env` :**
```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CACHE_DB=1
```

### Utilisation

Le cache est utilisé automatiquement pour :
- Articles récents (cache 1h)
- Catégories (cache 2h)
- Statistiques (cache 1h)
- Articles populaires (cache 30min)

**Commande de préchargement :**
```bash
php artisan cache:warmup
```

**Exemple dans le code :**
```php
$articles = Cache::remember('homepage.latest_jobs', 3600, function () {
    return JobArticle::where('status', 'published')
        ->orderBy('published_at', 'desc')
        ->take(9)
        ->get();
});
```

### Avantages Redis

- **Performance** : Accès ultra-rapide aux données
- **Scalabilité** : Support de la réplication et du clustering
- **Persistance** : Données conservées après redémarrage
- **Expiration automatique** : Gestion automatique des TTL

## 3. Compression des Assets (CSS/JS)

### Compression Gzip/Brotli

La compression est gérée par le serveur web (Nginx/Apache) qui est plus efficace que la compression au niveau de l'application.

**Configuration serveur web :**

**Nginx :**
```nginx
gzip on;
gzip_vary on;
gzip_proxied any;
gzip_comp_level 6;
gzip_types text/plain text/css text/xml text/javascript application/json application/javascript;
```

**Apache (.htaccess) :**
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

### Minification Vite

Vite minifie automatiquement les assets en production :

**Configuration (`vite.config.js`) :**
- Minification Terser pour JS
- Minification CSS
- Suppression des `console.log` en production
- Tree shaking (suppression du code mort)
- Hash dans les noms de fichiers pour le cache busting

**Build :**
```bash
npm run build
```

## 4. CDN pour les Assets Statiques

### Configuration

Le CDN est configurable via la variable d'environnement `CDN_URL`.

**Configuration dans `.env` :**
```env
CDN_URL=https://cdn.example.com
```

### Utilisation

Le helper `CdnHelper` gère automatiquement les URLs CDN :

```php
// Assets CSS/JS
{!! \App\Helpers\CdnHelper::css('css/app.css') !!}
{!! \App\Helpers\CdnHelper::js('js/app.js') !!}

// Images avec optimisation WebP
{!! \App\Helpers\CdnHelper::image('images/hero.jpg', true) !!}

// Assets génériques
{!! \App\Helpers\CdnHelper::asset('fonts/roboto.woff2') !!}
```

### Fonctionnalités

- **Cache busting automatique** : Ajout d'un hash basé sur le contenu du fichier
- **Fallback local** : Utilise les assets locaux si CDN non configuré
- **Support WebP** : Détection automatique des versions WebP

## Commandes Artisan

### Optimisation des images
```bash
php artisan images:optimize [--path=public/images] [--quality=85] [--force]
```

### Préchargement du cache
```bash
php artisan cache:warmup
```

### Nettoyage du cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Métriques de Performance

### Objectifs

- **First Contentful Paint (FCP)** : < 1.8s
- **Largest Contentful Paint (LCP)** : < 2.5s
- **Time to Interactive (TTI)** : < 3.8s
- **Cumulative Layout Shift (CLS)** : < 0.1
- **Total Blocking Time (TBT)** : < 200ms

### Outils de Mesure

- Google PageSpeed Insights
- Lighthouse (Chrome DevTools)
- WebPageTest
- GTmetrix

## Bonnes Pratiques

1. **Images** :
   - Utiliser WebP quand possible
   - Spécifier width/height pour éviter le CLS
   - Lazy loading pour les images hors viewport
   - Priority pour les images critiques

2. **Cache** :
   - Utiliser Redis en production
   - Précharger le cache au déploiement
   - Invalider le cache après les mises à jour

3. **Assets** :
   - Minifier en production
   - Utiliser un CDN pour les assets statiques
   - Activer la compression Gzip/Brotli

4. **Monitoring** :
   - Surveiller les métriques Core Web Vitals
   - Analyser les temps de réponse
   - Optimiser les requêtes lentes

## Prochaines Étapes

- [ ] Implémenter le service worker pour le cache offline
- [ ] Ajouter le preloading des ressources critiques
- [ ] Optimiser les polices (font-display: swap)
- [ ] Implémenter le code splitting avancé
- [ ] Ajouter le monitoring des performances en temps réel

