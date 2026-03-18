# Guide Complet d'Intégration d'Images dans les Documents LWS

## Table des Matières

1. [Vue d'ensemble du système](#vue-densemble)
2. [Architecture des images](#architecture)
3. [Configuration requise](#configuration)
4. [Installation étape par étape](#installation)
5. [Gestion des images de couverture](#gestion-couverture)
6. [Dépannage et solutions](#depannage)
7. [Bonnes pratiques](#bonnes-pratiques)

---

## Vue d'ensemble du système {#vue-densemble}

Le système LWS (Laravel Web School) gère deux types d'images pour les documents :

### 1. Images de couverture de documents
- **Type interne** : Uploadées et stockées localement
- **Type externe** : URLs externes (CDN, hébergement tiers)

### 2. Images système (avatars, logos, etc.)
- Gérées via le système de fichiers Laravel

---

## Architecture des images {#architecture}

### Structure des répertoires

```
storage/
├── app/
│   ├── public/
│   │   └── document-covers/     # Images de couverture uploadées
│   └── private/
│       └── documents/           # Fichiers PDF/documents
public/
├── storage/                      # Lien symbolique (créé par storage:link)
└── images/                       # Images système
```

### Flux de traitement des images

1. **Upload interne** → `storage/app/public/document-covers/`
2. **Accès fallback** → `/storage/{path}` via `StorageFileController`
3. **URL signée** → `/document-cover/{id}` pour sécurité

---

## Configuration requise {#configuration}

### 1. Permissions des répertoires

```bash
# Créer les répertoires nécessaires
mkdir -p storage/app/public/document-covers
mkdir -p storage/app/private/documents

# Permissions correctes (755 pour répertoires, 644 pour fichiers)
chmod -R 755 storage/app/public
chmod -R 755 storage/app/private
```

### 2. Configuration Laravel

Vérifiez votre `config/filesystems.php` :

```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app/private'),
        'serve' => true,
    ],
],
```

### 3. Variables d'environnement

Dans votre `.env` :

```env
FILESYSTEM_DISK=local
APP_URL=https://votredomaine.com
```

---

## Installation étape par étape {#installation}

### Étape 1 : Créer les répertoires

```bash
# Dans votre projet Laravel
php artisan storage:link
```

### Étape 2 : Configurer les routes

Les routes essentielles sont déjà dans `routes/web.php` :

```php
// Fallback storage pour LWS (prioritaire)
Route::get('/storage/{path}', [\App\Http\Controllers\StorageFileController::class, 'serve'])
    ->where('path', '.*')
    ->name('storage.serve');

// URL signée pour images de couverture
Route::get('/document-cover/{id}', [\App\Http\Controllers\Admin\DocumentController::class, 'serveCover'])
    ->middleware('signed')
    ->name('document.cover.signed');
```

### Étape 3 : Vérifier les contrôleurs

Assurez-vous que ces contrôleurs existent :
- `StorageFileController` (fallback LWS)
- `DocumentController::serveCover()` (images de couverture)

### Étape 4 : Tester le système

```bash
# Test d'upload d'image
php artisan tinker
>>> $path = Storage::disk('public')->put('document-covers/test.jpg', file_get_contents('https://via.placeholder.com/300'));
>>> echo $path;
```

---

## Gestion des images de couverture {#gestion-couverture}

### Upload interne (recommandé pour LWS)

Dans le formulaire de création/édition :

```php
// Dans DocumentController::store()
if ($validated['cover_type'] === 'internal' && $request->hasFile('cover_image_file')) {
    $image = $request->file('cover_image_file');
    $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
    $imagePath = $image->storeAs('document-covers', $imageName, 'public');
    $validated['cover_image'] = $imagePath;
}
```

### Affichage des images

Dans les vues Blade :

```blade
<!-- Pour images internes avec URL signée -->
@if($document->cover_type === 'internal')
    <img src="{{ URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $document->id]) }}" 
         alt="{{ $document->title }}" class="w-full h-auto">
@else
    <!-- Pour URLs externes -->
    <img src="{{ $document->cover_image }}" alt="{{ $document->title }}">
@endif
```

### Fallback automatique

Si l'URL signée échoue, le système utilise `/storage/{path}` :

```php
// Dans StorageFileController
public function serve(Request $request, string $path): BinaryFileResponse
{
    $storagePublic = storage_path('app/public');
    $fullPath = $storagePublic . '/' . $path;
    
    // Vérifications de sécurité...
    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000, immutable',
    ]);
}
```

---

## Dépannage et solutions {#depannage}

### Problème 1 : Images 404

**Symptôme** : Les images uploadées s'affichent en erreur 404

**Causes possibles** :
- Lien symbolique manquant : `php artisan storage:link`
- Permissions incorrectes sur les répertoires
- Route fallback non configurée

**Solutions** :
```bash
# Recréer le lien symbolique
php artisan storage:link

# Vérifier les permissions
chmod -R 755 storage/app/public
chown -R www-data:www-data storage/app/public  # Sur Ubuntu/Debian
```

### Problème 2 : Upload échoue

**Symptôme** : Erreur lors de l'upload d'images

**Vérifications** :
```php
// Dans DocumentController, ajouter du debug
dd($request->hasFile('cover_image_file'), $request->file('cover_image_file'));
```

**Solutions** :
- Vérifier `enctype="multipart/form-data"` dans le formulaire
- Confirmer la taille maximale dans `php.ini`
- Valider les permissions du répertoire `document-covers`

### Problème 3 : Images externes ne s'affichent pas

**Symptôme** : Les URLs externes ne fonctionnent pas

**Solutions** :
```php
// Validation de l'URL dans DocumentController
$url = trim($request->input('cover_image_url', ''));
if (!empty($url) && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'))) {
    $validated['cover_image'] = $url;
}
```

### Problème 4 : Performance LWS

**Symptôme** : Chargement lent des images

**Optimisations** :
```php
// Dans serveCover(), ajouter cache
return response()->file($fullPath, [
    'Content-Type' => $mimeType,
    'Cache-Control' => 'public, max-age=31536000, immutable',
    'ETag' => md5_file($fullPath),
]);
```

---

## Bonnes pratiques {#bonnes-pratiques}

### 1. Sécurité

- Toujours utiliser des URLs signées pour les images internes
- Valider les types MIME des fichiers uploadés
- Limiter la taille des images (max 2-5MB)

### 2. Performance

- Compresser les images avant upload
- Utiliser des formats WebP quand possible
- Implémenter un cache CDN si disponible

### 3. Maintenance

```php
// Commande de nettoyage des images orphelines
php artisan make:command CleanupOrphanImages

// Dans la commande
$usedImages = Document::where('cover_type', 'internal')
    ->pluck('cover_image')
    ->filter();

$allImages = Storage::disk('public')->allFiles('document-covers');
$orphanImages = collect($allImages)->diff($usedImages);

foreach ($orphanImages as $orphan) {
    Storage::disk('public')->delete($orphan);
}
```

### 4. Backup

```bash
# Inclure les images dans votre backup
tar -czf backup-images-$(date +%Y%m%d).tar.gz storage/app/public/document-covers/
```

---

## Checklist de déploiement sur nouveau projet

### Pré-déploiement

- [ ] Créer les répertoires `storage/app/public/document-covers`
- [ ] Configurer les permissions (755)
- [ ] Exécuter `php artisan storage:link`
- [ ] Tester l'upload d'une image de test

### Post-déploiement

- [ ] Vérifier l'accès aux images via `/storage/`
- [ ] Tester les URLs signées `/document-cover/{id}`
- [ ] Configurer le cache navigateur (1 an)
- [ ] Monitorer les erreurs 404 dans les logs

### Monitoring

```php
// Dans app/Providers/EventServiceProvider
protected $listen = [
    'Illuminate\Foundation\Events\RequestHandled' => [
        'App\Listeners\LogImage404',
    ],
];
```

---

## Résumé technique

Le système d'images LWS utilise une approche à double niveau :

1. **Niveau primaire** : URLs signées via `serveCover()` pour sécurité
2. **Niveau fallback** : Route `/storage/{path}` pour compatibilité LWS

Cette architecture garantit que les images fonctionnent même sur les hébergements partagés LWS qui limitent les liens symboliques et les fonctionnalités avancées.

Pour configurer un nouveau projet, suivez simplement la checklist de déploiement et adaptez les chemins selon votre structure.
