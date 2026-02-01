# Fichiers modifiés – Affichage des vues (paliers)

Les vues des articles (JobArticle) s'affichent partout avec les mêmes paliers fictifs (1,5 K vues, 2,1 K vues, …, 2,5 M vues) via l’accesseur `featured_display_views` du modèle `JobArticle`.

---

## 1. Modèle (logique des paliers)

| Fichier | Modification |
|---------|--------------|
| **`app/Models/JobArticle.php`** | Accesseur `getFeaturedDisplayViewsAttribute()` : retourne le libellé selon le nombre réel de vues (< 5 → 1,5 K ; ≥ 5 → 2,1 K ; > 10 → 2,5 K ; > 15 → 2,8 K ; > 20 → 3,2 K ; > 30 → 3,5 K ; > 40 → 3,8 K ; > 50 → 4,5 K ; > 100 → 10,1 K ; > 200 → 20 K ; > 500 → 1 M ; > 1000 → 2,5 M). |

---

## 2. Vues front – Page article et listes emplois

| Fichier | Modification |
|---------|--------------|
| **`resources/views/emplois/article.blade.php`** | 5 endroits : métadonnées de l’article (2×), sidebar « articles les plus vus » (2× pour `$topArticle`), bloc « Articles similaires » (1× pour `$related`). `number_format($article->views, …)` et `$related->views` remplacés par `$article->featured_display_views` / `$topArticle->featured_display_views` / `$related->featured_display_views`. |
| **`resources/views/emplois/featured-articles.blade.php`** | Affichage des vues dans chaque carte : `$article->views` → `$article->featured_display_views`. |
| **`resources/views/emplois/all-articles.blade.php`** | `number_format($article->views ?? 0)` → `$article->featured_display_views`. |
| **`resources/views/emplois/concours.blade.php`** | `$article->views` vues → `$article->featured_display_views`. |
| **`resources/views/emplois/offres.blade.php`** | `$article->views` → `$article->featured_display_views`. |
| **`resources/views/emplois/recent-articles.blade.php`** | `number_format($article->views)` vue(s) → `$article->featured_display_views`. |
| **`resources/views/emplois/index.blade.php`** | Liste des derniers articles : `$article->views` vues → `$article->featured_display_views`. |
| **`resources/views/emplois/bourses.blade.php`** | `$article->views` → `$article->featured_display_views`. |

---

## 3. Page d’accueil et recherche

| Fichier | Modification |
|---------|--------------|
| **`resources/views/index.blade.php`** | Section « Articles Vedettes » : déjà en `$article->featured_display_views`. Autre section (bloc articles avec 🔥) : remplacement du bloc conditionnel sur `$article->views` (1K / X.XK) par `$article->featured_display_views`. |
| **`resources/views/search.blade.php`** | Résultats articles : `@if($article->views > 0)` + `$article->views` vues remplacés par `$article->featured_display_views`. |

---

## 4. Admin

| Fichier | Modification |
|---------|--------------|
| **`resources/views/admin/jobs/articles/index.blade.php`** | Colonne vues du tableau : `$article->views` → `$article->featured_display_views`. |
| **`resources/views/admin/jobs/articles/show.blade.php`** | Métadonnées : `$article->views` vues → `$article->featured_display_views`. |
| **`resources/views/admin/dashboard.blade.php`** | Deux blocs : « Derniers articles » et « Articles les Plus Vus » : `number_format($article->views)` vues → `$article->featured_display_views`. |

---

## Récapitulatif

- **1 fichier modèle** : `app/Models/JobArticle.php`
- **12 fichiers de vues** : emplois (8), index (1), search (1), admin (3)

**Total : 13 fichiers modifiés.**

Partout où un article (JobArticle) affiche des vues, c’est désormais `featured_display_views` qui est utilisé (page article, listes emplois, page d’accueil, recherche, admin).
