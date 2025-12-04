# Génération du Sitemap - Documentation

## 📋 Quand le sitemap est généré ?

Le sitemap est généré **dynamiquement à la volée** (on-the-fly) à chaque fois qu'un utilisateur ou un moteur de recherche accède à :
- `https://niangprogrammeur.com/sitemap.xml` (sitemap index)
- `https://niangprogrammeur.com/sitemap-pages.xml` (pages statiques)
- `https://niangprogrammeur.com/sitemap-articles.xml` (articles dynamiques)

## ⚡ Système de Cache

Pour optimiser les performances, le sitemap utilise un système de cache :

### 1. **Sitemap Index** (`/sitemap.xml`)
- **Durée du cache** : 1 heure (3600 secondes)
- **Invalidation** : Automatique lors de la création/modification/suppression d'un article
- **Clé de cache** : `sitemap_index_{md5(baseUrl)}`

### 2. **Sitemap Pages** (`/sitemap-pages.xml`)
- **Durée du cache** : 6 heures (21600 secondes)
- **Raison** : Les pages statiques changent rarement
- **Clé de cache** : `sitemap_pages_{md5(baseUrl)}`

### 3. **Sitemap Articles** (`/sitemap-articles.xml`)
- **Durée du cache** : 1 heure (3600 secondes)
- **Invalidation** : Automatique lors de la création/modification/suppression d'un article
- **Clé de cache** : `sitemap_articles_{md5(baseUrl)}`

## 🔄 Invalidation Automatique du Cache

Le cache est automatiquement invalidé dans le modèle `JobArticle` lors des événements suivants :

1. **Création d'un article** (`static::created`)
2. **Modification d'un article** (`static::updated`)
3. **Suppression d'un article** (`static::deleted`)

Lors de ces événements, les caches suivants sont invalidés :
- `sitemap_articles_lastmod` (date de dernière modification)
- `sitemap_index_{md5(baseUrl)}` (sitemap index)
- `sitemap_articles_{md5(baseUrl)}` (sitemap articles)

## 📝 Processus de Génération

### Étape 1 : Requête HTTP
Quand un utilisateur ou un robot accède à `/sitemap.xml` :

1. Le contrôleur `SitemapController@index` est appelé
2. Il vérifie si le sitemap est en cache
3. Si oui, il retourne le cache
4. Si non, il génère le sitemap et le met en cache

### Étape 2 : Génération du Contenu

#### Sitemap Index
- Liste les sous-sitemaps (`sitemap-pages.xml` et `sitemap-articles.xml`)
- Inclut la date de dernière modification des articles

#### Sitemap Pages
- Pages statiques (accueil, à propos, contact, FAQ, etc.)
- Toutes les formations (15 formations)
- Toutes les pages d'exercices (15 langages)
- Toutes les pages de quiz (15 langages)
- Pages d'emplois principales
- Pages de catégories d'emplois

#### Sitemap Articles
- Tous les articles publiés (`status = 'published'`)
- Inclut les images (pour Google Images)
- Inclut les tags news (pour Google News - articles < 2 jours)
- Limite : 50 000 URLs (limite Google)

## 🚀 Avantages de ce Système

1. **Performance** : Le cache réduit la charge serveur
2. **Actualité** : Le cache est invalidé automatiquement quand le contenu change
3. **Flexibilité** : Le sitemap s'adapte automatiquement aux nouveaux articles
4. **SEO** : Les moteurs de recherche reçoivent toujours un sitemap à jour

## 📊 Headers HTTP

Le sitemap retourne les headers suivants :
- `Content-Type: application/xml; charset=utf-8`
- `Cache-Control: public, max-age={durée}` (selon le type de sitemap)

Ces headers permettent aux navigateurs et CDN de mettre en cache le sitemap côté client.

## 🔧 Commandes Artisan (Obsolètes)

⚠️ **Note** : La commande `php artisan sitemap:generate` existe mais n'est plus utilisée car le sitemap est maintenant généré dynamiquement. Cette commande générait un fichier statique dans `public/sitemap.xml`, mais ce fichier a été supprimé pour éviter les conflits avec le système dynamique.

## 📌 Résumé

- **Génération** : À la volée (on-the-fly) à chaque requête
- **Cache** : 1 heure pour index/articles, 6 heures pour pages
- **Invalidation** : Automatique lors de la création/modification/suppression d'articles
- **Avantage** : Toujours à jour, performant, et automatique

