# Optimisations performances

## Déjà en place

- **Tracking des visites** : exécuté **après** l’envoi de la réponse (ne bloque plus la page).
- **GeoIP** : résultat mis en cache **24 h par IP** (moins d’appels à l’API externe).
- **Page d’accueil** : données mises en cache **15 min** (`homepage_view_fr` / `homepage_view_en`).
- **Navigation** : catégories en cache **30 min** (`navigation_job_categories`).
- **Paramètres mail** : en cache **1 h** (`site_settings_mail`).

## Réglages .env recommandés (pour moins de lenteur)

À adapter dans `.env` selon l’environnement.

| Variable      | En local (si pas besoin du détail des erreurs) | En production |
|---------------|-------------------------------------------------|---------------|
| `APP_DEBUG`   | `false` (ou `true` uniquement pour débugger)    | `false`       |
| `LOG_LEVEL`   | `warning` ou `error`                            | `warning` ou `error` |
| `SESSION_DRIVER` | `file` (plus rapide que `database`)         | `file` ou `redis` |

Exemple pour un site plus réactif en local :

```env
APP_DEBUG=false
LOG_LEVEL=warning
SESSION_DRIVER=file
```

## En production : cacher config et routes

Après chaque déploiement (ou changement de config/routes) :

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

En cas de modification de `.env` ou des routes, refaire les commandes ci‑dessus (ou `php artisan config:clear` puis `php artisan config:cache`).

## Si le site reste lent

- Vérifier que **MySQL/MariaDB** a des index sur les colonnes utilisées dans les `WHERE` / `ORDER BY` (ex. `job_articles.published_at`, `job_articles.status`, `statistics.visit_date`).
- Vérifier l’hébergement (CPU, RAM, disque).
- En production : activer **OPcache** (PHP) et un cache HTTP (Varnish, Cloudflare, etc.) si possible.
