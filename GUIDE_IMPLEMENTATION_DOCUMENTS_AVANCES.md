# Guide d'Implémentation - Fonctionnalités Avancées Documents

## ✅ État Actuel

### Migrations et Modèles (TERMINÉ)
- ✅ `document_reviews` - Système d'avis/commentaires
- ✅ `document_coupons` - Codes promo/réductions
- ✅ `document_wishlists` - Liste de souhaits
- ✅ `document_bundles` - Packs de documents
- ✅ `document_bundle_items` - Items des packs
- ✅ `is_free` ajouté à `documents` - Support documents gratuits

### Modèles Créés
- ✅ `DocumentReview` - Avis avec modération
- ✅ `DocumentCoupon` - Codes promo avec validation
- ✅ `DocumentWishlist` - Wishlist avec notifications
- ✅ `DocumentBundle` - Bundles avec calcul d'économies
- ✅ `DocumentBundleItem` - Items des bundles

### Modèle Document Amélioré
- ✅ Relations: reviews, approvedReviews, wishlists, bundles
- ✅ Méthodes: isFree(), getAverageRatingAttribute(), getReviewsCountAttribute()

## 📋 À Implémenter

### 1. Contrôleurs

#### DocumentReviewController
```php
- store() - Créer un avis
- approve() - Approuver un avis (admin)
- delete() - Supprimer un avis
```

#### DocumentWishlistController
```php
- index() - Page wishlist utilisateur
- add() - Ajouter à la wishlist
- remove() - Retirer de la wishlist
- toggle() - Toggle wishlist (AJAX)
```

#### DocumentCouponController
```php
- validate() - Valider un code promo (AJAX)
- apply() - Appliquer un code promo au panier
```

#### DocumentBundleController
```php
- index() - Liste des bundles
- show() - Détails d'un bundle
```

### 2. Modifications DocumentController

#### Méthode show() - Ajouter:
```php
// Récupérer les avis approuvés
$reviews = $document->approvedReviews()
    ->with('user')
    ->orderBy('created_at', 'desc')
    ->paginate(10);

// Vérifier si dans wishlist
$inWishlist = false;
if (auth()->check()) {
    $inWishlist = DocumentWishlist::isInWishlist(
        auth()->id(), 
        $document->id
    );
}

// Recommandations personnalisées
$recommendations = $this->getPersonalizedRecommendations($document);

return view('documents.show', compact(
    'document', 
    'relatedDocuments', 
    'userHasPurchased',
    'reviews',
    'inWishlist',
    'recommendations'
));
```

#### Nouvelle méthode downloadFree():
```php
public function downloadFree($id)
{
    $document = Document::findOrFail($id);
    
    if (!$document->isFree()) {
        abort(403, 'Ce document n\'est pas gratuit');
    }
    
    // Enregistrer le téléchargement
    $document->increment('download_count');
    
    // Télécharger directement
    return Storage::disk('local')->download(
        $document->file_path,
        $document->file_name
    );
}
```

#### Nouvelle méthode getPersonalizedRecommendations():
```php
private function getPersonalizedRecommendations(Document $document)
{
    $user = auth()->user();
    
    if (!$user) {
        // Recommandations basées sur la catégorie
        return Document::published()
            ->active()
            ->where('category_id', $document->category_id)
            ->where('id', '!=', $document->id)
            ->orderBy('sales_count', 'desc')
            ->take(6)
            ->get();
    }
    
    // Basées sur les achats précédents
    $purchasedCategories = DocumentPurchase::where('user_id', $user->id)
        ->where('status', 'completed')
        ->with('document.category')
        ->get()
        ->pluck('document.category_id')
        ->unique()
        ->filter();
    
    // Basées sur les catégories consultées
    $viewedCategories = Document::whereHas('purchases', function($q) use ($user) {
        // Logique pour catégories consultées
    })->pluck('category_id')->unique();
    
    $categories = $purchasedCategories->merge($viewedCategories)->unique();
    
    return Document::published()
        ->active()
        ->whereIn('category_id', $categories)
        ->where('id', '!=', $document->id)
        ->orderBy('sales_count', 'desc')
        ->take(6)
        ->get();
}
```

### 3. Routes à Ajouter

```php
// Routes avis
Route::post('/documents/{document}/reviews', [DocumentReviewController::class, 'store'])
    ->middleware('auth')
    ->name('documents.reviews.store');
Route::post('/admin/reviews/{review}/approve', [DocumentReviewController::class, 'approve'])
    ->middleware(['auth', 'admin'])
    ->name('admin.reviews.approve');

// Routes wishlist
Route::get('/dashboard/wishlist', [DocumentWishlistController::class, 'index'])
    ->middleware('auth')
    ->name('wishlist.index');
Route::post('/documents/{document}/wishlist', [DocumentWishlistController::class, 'toggle'])
    ->middleware('auth')
    ->name('wishlist.toggle');

// Routes coupons
Route::post('/coupons/validate', [DocumentCouponController::class, 'validate'])
    ->name('coupons.validate');

// Routes bundles
Route::get('/bundles', [DocumentBundleController::class, 'index'])
    ->name('bundles.index');
Route::get('/bundles/{slug}', [DocumentBundleController::class, 'show'])
    ->name('bundles.show');

// Route téléchargement gratuit
Route::get('/documents/{id}/download-free', [DocumentController::class, 'downloadFree'])
    ->name('documents.download-free');
```

### 4. Vues à Modifier/Créer

#### resources/views/documents/show.blade.php
Ajouter:
- Section avis avec formulaire
- Bouton wishlist (AJAX)
- Boutons partage social (Facebook, Twitter, WhatsApp, LinkedIn)
- Section recommandations personnalisées
- Badge "Gratuit" si is_free
- Bouton téléchargement direct si gratuit

#### resources/views/dashboard/wishlist.blade.php (NOUVEAU)
- Liste des documents en wishlist
- Boutons retirer
- Notifications de réduction

#### resources/views/documents/bundles/index.blade.php (NOUVEAU)
- Liste des bundles disponibles
- Prix et économies

#### resources/views/documents/bundles/show.blade.php (NOUVEAU)
- Détails du bundle
- Liste des documents inclus
- Prix total vs prix bundle

### 5. Modifications Checkout

Dans `DocumentCartController@processCheckout`:
```php
// Appliquer code promo si fourni
if ($request->filled('coupon_code')) {
    $coupon = DocumentCoupon::where('code', $request->coupon_code)
        ->active()
        ->first();
    
    if ($coupon && $coupon->isValid()) {
        // Calculer réduction pour chaque item
        foreach ($cartItems as $item) {
            if ($coupon->canBeUsedFor($item->document)) {
                $discount = $coupon->calculateDiscount($item->price);
                $item->price -= $discount;
                $coupon->apply();
            }
        }
    }
}
```

### 6. Partage Social

Ajouter dans `resources/views/documents/show.blade.php`:
```blade
<div class="social-share">
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
       target="_blank" class="share-btn facebook">
        <i class="fab fa-facebook"></i> Facebook
    </a>
    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($document->title) }}" 
       target="_blank" class="share-btn twitter">
        <i class="fab fa-twitter"></i> Twitter
    </a>
    <a href="https://wa.me/?text={{ urlencode($document->title . ' - ' . url()->current()) }}" 
       target="_blank" class="share-btn whatsapp">
        <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
       target="_blank" class="share-btn linkedin">
        <i class="fab fa-linkedin"></i> LinkedIn
    </a>
</div>
```

### 7. Liens de Parrainage

Ajouter dans `User` model:
```php
public function getReferralLinkAttribute(): string
{
    return route('documents.index', ['ref' => $this->id]);
}
```

### 8. Interface Admin

Créer:
- `resources/views/admin/documents/reviews/index.blade.php` - Liste avis à modérer
- `resources/views/admin/documents/coupons/index.blade.php` - Gestion codes promo
- `resources/views/admin/documents/bundles/index.blade.php` - Gestion bundles

## 🚀 Ordre d'Implémentation Recommandé

1. **Documents gratuits** (le plus simple)
   - Modifier `DocumentController@show` pour afficher badge gratuit
   - Créer méthode `downloadFree`
   - Ajouter route et bouton

2. **Wishlist** (simple)
   - Créer `DocumentWishlistController`
   - Ajouter routes
   - Créer vue wishlist
   - Ajouter bouton toggle dans vue document

3. **Avis/Commentaires** (moyen)
   - Créer `DocumentReviewController`
   - Ajouter section avis dans vue document
   - Créer interface admin modération

4. **Codes promo** (moyen)
   - Créer `DocumentCouponController`
   - Modifier checkout pour appliquer codes
   - Ajouter champ code promo dans checkout

5. **Partage social** (simple)
   - Ajouter boutons dans vue document

6. **Recommandations** (moyen)
   - Implémenter logique dans `DocumentController`
   - Afficher section recommandations

7. **Bundles** (complexe)
   - Créer `DocumentBundleController`
   - Créer vues bundles
   - Intégrer dans checkout

## 📝 Notes

- Tous les modèles et migrations sont prêts
- Les relations sont configurées
- Il reste à créer les contrôleurs, routes et vues
- L'ordre d'implémentation peut être adapté selon les priorités

