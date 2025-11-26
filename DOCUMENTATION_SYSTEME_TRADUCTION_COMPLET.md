# 📚 Documentation Complète du Système de Traduction

## 🎯 Vue d'ensemble

Le système de traduction de l'application a été entièrement refondu pour garantir un changement de langue **immédiat et fiable**. Il utilise une architecture en couches avec un service dédié, un middleware optimisé et des helpers globaux.

## 🏗️ Architecture

### 1. **LocaleService** (`app/Services/LocaleService.php`)

Service centralisé pour la gestion de toutes les opérations liées aux locales.

#### Fonctionnalités principales :

- **`getCurrentLocale()`** : Récupère la locale actuelle depuis la session, le cookie ou utilise la valeur par défaut
- **`setLocale($locale, $clearCache = true)`** : Définit la locale et vide automatiquement les caches si nécessaire
- **`createLocaleCookie($locale)`** : Crée un cookie sécurisé pour la locale
- **`clearTranslationCaches()`** : Vide tous les caches liés aux traductions
- **`getNoCacheHeaders($locale)`** : Génère des headers HTTP pour empêcher la mise en cache
- **`isValidLocale($locale)`** : Vérifie si une locale est valide
- **`getSupportedLocales()`** : Retourne toutes les locales supportées
- **`getDefaultLocale()`** : Retourne la locale par défaut

#### Configuration :

```php
private const SUPPORTED_LOCALES = ['fr', 'en'];
private const DEFAULT_LOCALE = 'fr';
private const COOKIE_LIFETIME = 60 * 24 * 365; // 1 an
```

### 2. **SetLocale Middleware** (`app/Http/Middleware/SetLocale.php`)

Middleware qui s'exécute sur chaque requête pour définir automatiquement la locale.

#### Fonctionnement :

1. Récupère la locale via `LocaleService::getCurrentLocale()`
2. Définit la locale dans l'application
3. Ajoute des headers anti-cache si la locale a changé
4. Ajoute un header `X-Locale` pour le débogage

#### Enregistrement :

Le middleware est enregistré dans `bootstrap/app.php` :

```php
$middleware->web(append: [
    \App\Http\Middleware\SetLocale::class,
]);
```

### 3. **PageController::setLocale()** (`app/Http/Controllers/PageController.php`)

Méthode optimisée pour changer la locale et rediriger l'utilisateur.

#### Fonctionnalités :

- Utilise `LocaleService` pour définir la locale
- Vide automatiquement les caches lors du changement
- Crée un cookie sécurisé
- Gère les redirections intelligentes :
  - Paramètre `redirect` dans l'URL
  - Referer HTTP
  - Fallback vers la page d'accueil
- Gère les environnements (local vs production)

### 4. **TranslationHelper** (`app/Helpers/TranslationHelper.php`)

Helpers globaux pour faciliter l'utilisation des traductions.

#### Fonctions disponibles :

- **`t($key, $replace = [], $locale = null)`** : Raccourci pour `trans()`
- **`current_locale()`** : Retourne la locale actuelle
- **`is_locale($locale)`** : Vérifie si la locale actuelle correspond
- **`locale_url($locale, $path = null)`** : Génère une URL avec changement de locale

#### Exemple d'utilisation :

```php
// Dans une vue Blade
{{ t('app.nav.home') }}
{{ current_locale() }}
@if(is_locale('fr'))
    <p>Version française</p>
@endif
<a href="{{ locale_url('en', '/about') }}">English</a>
```

## 📁 Structure des fichiers de traduction

Les fichiers de traduction sont stockés dans `lang/{locale}/` :

```
lang/
├── fr/
│   ├── app.php          # Traductions générales (FR)
│   ├── exercises.php    # Exercices (FR)
│   └── quiz.php         # Quiz (FR)
└── en/
    ├── app.php          # Traductions générales (EN)
    ├── exercises.php    # Exercices (EN)
    └── quiz.php         # Quiz (EN)
```

### Format des fichiers :

```php
// lang/fr/app.php
return [
    'nav' => [
        'home' => 'Accueil',
        'formations' => 'Formations',
    ],
];
```

## 🔄 Flux de changement de langue

1. **Utilisateur clique sur le sélecteur de langue**
   - URL : `/lang/{locale}?redirect=/current-page`

2. **PageController::setLocale() est appelé**
   - Valide la locale
   - Appelle `LocaleService::setLocale()` qui :
     - Vide les caches (vues, config, application)
     - Définit la locale dans App, config et Lang
     - Sauvegarde dans la session
     - Marque la locale comme changée

3. **Cookie est créé**
   - Durée : 1 an
   - Sécurisé en production (Secure, HttpOnly, SameSite)

4. **Redirection**
   - Vers le paramètre `redirect` si fourni
   - Sinon vers le referer
   - Sinon vers la page d'accueil

5. **Middleware SetLocale s'exécute**
   - Récupère la locale depuis la session/cookie
   - Définit la locale dans l'application
   - Ajoute des headers anti-cache si nécessaire

6. **Page rendue avec la nouvelle langue**
   - Les traductions sont immédiatement disponibles
   - Pas de cache, tout est à jour

## 🚀 Utilisation dans les vues

### Méthode standard Laravel :

```blade
{{ trans('app.nav.home') }}
{{ __('app.nav.home') }}
@lang('app.nav.home')
```

### Avec paramètres de remplacement :

```blade
{{ trans('app.exercices.exercises_count', ['count' => 10]) }}
```

### Helpers personnalisés :

```blade
{{ t('app.nav.home') }}
{{ current_locale() }}
@if(is_locale('en'))
    <p>English version</p>
@endif
```

### Liens de changement de langue :

```blade
<a href="{{ locale_url('fr', request()->path()) }}">Français</a>
<a href="{{ locale_url('en', request()->path()) }}">English</a>
```

## 🔧 Gestion du cache

### Vidage automatique :

Le système vide automatiquement les caches lors d'un changement de langue :

- **Cache des vues** (`view:clear`)
- **Cache de configuration** (`config:clear`)
- **Cache de l'application** (`Cache::flush()`)

### Headers anti-cache :

Le middleware ajoute des headers HTTP pour empêcher la mise en cache :

```
Cache-Control: no-cache, no-store, must-revalidate, private, max-age=0
Pragma: no-cache
Expires: 0
ETag: [unique basé sur locale et temps]
X-Locale: fr
```

## 🍪 Gestion des cookies

### Configuration du cookie :

- **Nom** : `locale`
- **Durée** : 1 an (60 * 24 * 365 minutes)
- **Path** : `/` (disponible sur tout le site)
- **HttpOnly** : `true` (non accessible en JavaScript)
- **Secure** : `true` en production (HTTPS uniquement)
- **SameSite** : `None` en production, `Lax` en local

### Synchronisation session/cookie :

Le système synchronise automatiquement la session et le cookie :
- Si la session existe, elle est utilisée
- Sinon, le cookie est lu et synchronisé avec la session
- Si aucun des deux n'existe, la locale par défaut est utilisée

## 🌍 Langues supportées

Actuellement, l'application supporte :

- **Français (fr)** : Langue par défaut
- **Anglais (en)**

Pour ajouter une nouvelle langue :

1. Créer le dossier `lang/{locale}/`
2. Créer les fichiers de traduction nécessaires
3. Ajouter la locale dans `LocaleService::SUPPORTED_LOCALES`
4. Mettre à jour les sélecteurs de langue dans les vues

## 🐛 Débogage

### Vérifier la locale actuelle :

```php
// Dans un contrôleur ou une vue
dd(app()->getLocale());
dd(current_locale());
```

### Vérifier les headers HTTP :

Le header `X-Locale` indique la locale utilisée pour chaque requête.

### Vérifier les caches :

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

### Logs :

Les erreurs de cache sont loggées en mode debug :

```php
\Log::warning('Erreur lors du vidage du cache de traduction: ' . $e->getMessage());
```

## 📝 Bonnes pratiques

### 1. Utiliser les helpers globaux

Préférer `t()` à `trans()` pour la cohérence :

```blade
{{ t('app.nav.home') }}
```

### 2. Organiser les traductions

Grouper les traductions par fonctionnalité :

```php
// lang/fr/app.php
return [
    'nav' => [...],
    'formations' => [...],
    'exercices' => [...],
];
```

### 3. Utiliser des clés descriptives

```php
// ✅ Bon
'app.nav.home'
'app.formations.title'

// ❌ Mauvais
'app.text1'
'app.string2'
```

### 4. Gérer les paramètres

Utiliser des paramètres pour les textes dynamiques :

```php
// lang/fr/app.php
'exercises_count' => ':count exercices',

// Dans la vue
{{ trans('app.exercises_count', ['count' => 10]) }}
```

### 5. Fallback automatique

Laravel utilise automatiquement la locale par défaut si une traduction est manquante.

## 🔄 Migration depuis l'ancien système

L'ancien système utilisait directement `App::setLocale()` et vidait manuellement les caches. Le nouveau système :

- ✅ Centralise la logique dans `LocaleService`
- ✅ Vide automatiquement les caches
- ✅ Gère mieux les erreurs
- ✅ Fournit des helpers globaux
- ✅ Améliore les performances

Aucun changement n'est nécessaire dans les vues existantes, les fonctions `trans()`, `__()` et `@lang()` continuent de fonctionner.

## 📊 Performance

### Optimisations :

1. **Cache intelligent** : Les caches ne sont vidés que lors d'un changement de locale
2. **Headers HTTP** : Empêchent la mise en cache côté navigateur
3. **Service unique** : Une seule instance de `LocaleService` par requête
4. **Lazy loading** : Les traductions sont chargées à la demande

### Impact :

- ✅ Changement de langue instantané
- ✅ Pas de délai de propagation
- ✅ Pas de cache obsolète
- ✅ Performance optimale

## 🎓 Exemples complets

### Exemple 1 : Sélecteur de langue dans la navigation

```blade
<div class="language-selector">
    <a href="{{ locale_url('fr', request()->path()) }}" 
       class="{{ is_locale('fr') ? 'active' : '' }}">
        Français
    </a>
    <a href="{{ locale_url('en', request()->path()) }}" 
       class="{{ is_locale('en') ? 'active' : '' }}">
        English
    </a>
</div>
```

### Exemple 2 : Page avec traductions

```blade
@extends('layouts.app')

@section('title', t('app.formations.title'))

@section('content')
    <h1>{{ t('app.formations.title') }}</h1>
    <p>{{ t('app.formations.subtitle') }}</p>
    
    @foreach($formations as $formation)
        <div>
            <h2>{{ t("app.formations.{$formation->slug}.title") }}</h2>
            <p>{{ t("app.formations.{$formation->slug}.description") }}</p>
        </div>
    @endforeach
@endsection
```

### Exemple 3 : Utilisation dans un contrôleur

```php
public function index()
{
    $locale = app(LocaleService::class)->getCurrentLocale();
    
    $title = trans('app.formations.title', [], $locale);
    
    return view('formations.index', compact('title'));
}
```

## ✅ Checklist de déploiement

Avant de déployer en production :

- [ ] Vérifier que toutes les traductions sont complètes
- [ ] Tester le changement de langue sur toutes les pages
- [ ] Vérifier que les cookies fonctionnent en HTTPS
- [ ] Tester avec différents navigateurs
- [ ] Vérifier les headers HTTP
- [ ] Tester la redirection après changement de langue
- [ ] Vérifier que les caches sont bien vidés
- [ ] Tester en navigation privée (pas de cookies)

## 📞 Support

Pour toute question ou problème :

1. Vérifier les logs Laravel
2. Vérifier les headers HTTP (`X-Locale`)
3. Vérifier les cookies dans les DevTools
4. Vider manuellement les caches si nécessaire

---

**Dernière mise à jour** : {{ date('d/m/Y') }}

**Version** : 2.0.0

