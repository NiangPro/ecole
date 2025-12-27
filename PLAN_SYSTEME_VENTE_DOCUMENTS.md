# 📄 Plan de Réalisation - Système de Vente de Documents

**Date de création** : 2024-12-19  
**Projet** : NiangProgrammeur - Plateforme de Formation  
**Statut** : 📋 Planification

---

## 📋 Vue d'Ensemble

Système complet de vente de documents numériques avec gestion de catégories, panier d'achat, système de paiement et téléchargement sécurisé.

---

## 🎯 Objectifs

1. Permettre la vente de documents numériques (PDF, Word, Excel, etc.)
2. Organiser les documents par catégories
3. Gérer un panier d'achat
4. Intégrer le système de paiement existant
5. Sécuriser les téléchargements
6. Suivre les ventes et statistiques

---

## 🏗️ Architecture du Système

### 1. **Base de Données**

#### 1.1 Table `document_categories`
```sql
- id (bigint, primary key)
- name (string, 255) - Nom de la catégorie
- slug (string, 255, unique) - Slug pour URL
- description (text, nullable) - Description de la catégorie
- icon (string, nullable) - Icône FontAwesome
- image (string, nullable) - Image de la catégorie
- image_type (enum: internal/external) - Type d'image
- parent_id (bigint, nullable, foreign key) - Catégorie parente (sous-catégories)
- is_active (boolean, default: true)
- order (integer, default: 0) - Ordre d'affichage
- created_at, updated_at (timestamps)
```

#### 1.2 Table `documents`
```sql
- id (bigint, primary key)
- title (string, 255) - Titre du document
- slug (string, 255, unique) - Slug pour URL
- description (text, nullable) - Description
- excerpt (text, nullable) - Résumé court
- category_id (bigint, foreign key -> document_categories)
- file_path (string) - Chemin du fichier
- file_name (string) - Nom original du fichier
- file_size (bigint) - Taille en octets
- file_type (string) - Type MIME (application/pdf, etc.)
- file_extension (string) - Extension (.pdf, .docx, etc.)
- cover_image (string, nullable) - Image de couverture
- cover_type (enum: internal/external)
- price (decimal 10,2) - Prix en FCFA
- discount_price (decimal 10,2, nullable) - Prix réduit
- is_featured (boolean, default: false)
- is_active (boolean, default: true)
- status (enum: draft/published/archived)
- download_count (integer, default: 0) - Nombre de téléchargements
- sales_count (integer, default: 0) - Nombre de ventes
- views_count (integer, default: 0) - Nombre de vues
- author_id (bigint, foreign key -> users) - Auteur/Créateur
- tags (json, nullable) - Tags pour recherche
- meta_title (string, nullable) - SEO
- meta_description (text, nullable) - SEO
- meta_keywords (string, nullable) - SEO
- created_at, updated_at (timestamps)
- published_at (timestamp, nullable) - Date de publication
```

#### 1.3 Table `document_purchases`
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key -> users)
- document_id (bigint, foreign key -> documents)
- payment_id (bigint, foreign key -> payments, nullable)
- amount_paid (decimal 10,2) - Montant payé
- currency (string, default: 'XOF')
- status (enum: pending/completed/cancelled/failed)
- purchased_at (timestamp, nullable) - Date d'achat
- download_count (integer, default: 0) - Nombre de téléchargements
- download_limit (integer, default: 5) - Limite de téléchargements
- expires_at (timestamp, nullable) - Expiration du droit de téléchargement
- created_at, updated_at (timestamps)
```

#### 1.4 Table `document_carts`
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key -> users, nullable) - Null pour panier anonyme
- session_id (string, nullable) - Pour panier anonyme
- document_id (bigint, foreign key -> documents)
- quantity (integer, default: 1)
- price (decimal 10,2) - Prix au moment de l'ajout
- created_at, updated_at (timestamps)
```

#### 1.5 Table `document_downloads`
```sql
- id (bigint, primary key)
- purchase_id (bigint, foreign key -> document_purchases)
- user_id (bigint, foreign key -> users)
- document_id (bigint, foreign key -> documents)
- ip_address (string, nullable)
- user_agent (text, nullable)
- downloaded_at (timestamp)
- created_at, updated_at (timestamps)
```

---

## 📦 Modèles Eloquent

### 2.1 Modèle `DocumentCategory`
```php
- Relations:
  * documents() - HasMany Document
  * parent() - BelongsTo DocumentCategory (parent)
  * children() - HasMany DocumentCategory (enfants)
- Scopes:
  * active() - Catégories actives
  * ordered() - Tri par ordre
- Méthodes:
  * getFullPath() - Chemin complet avec parents
```

### 2.2 Modèle `Document`
```php
- Relations:
  * category() - BelongsTo DocumentCategory
  * author() - BelongsTo User
  * purchases() - HasMany DocumentPurchase
  * cartItems() - HasMany DocumentCart
- Scopes:
  * published() - Documents publiés
  * active() - Documents actifs
  * featured() - Documents en vedette
  * byCategory($categoryId) - Par catégorie
  * search($query) - Recherche
- Méthodes:
  * getCurrentPrice() - Prix actuel (avec réduction)
  * getFileUrl() - URL de téléchargement sécurisé
  * canBeDownloadedBy($user) - Vérifier droit de téléchargement
  * incrementViews() - Incrémenter les vues
```

### 2.3 Modèle `DocumentPurchase`
```php
- Relations:
  * user() - BelongsTo User
  * document() - BelongsTo Document
  * payment() - BelongsTo Payment
  * downloads() - HasMany DocumentDownload
- Scopes:
  * completed() - Achats complétés
  * pending() - Achats en attente
- Méthodes:
  * canDownload() - Vérifier si peut télécharger
  * incrementDownloadCount() - Incrémenter téléchargements
  * isExpired() - Vérifier expiration
```

### 2.4 Modèle `DocumentCart`
```php
- Relations:
  * user() - BelongsTo User (nullable)
  * document() - BelongsTo Document
- Scopes:
  * forUser($userId) - Panier d'un utilisateur
  * forSession($sessionId) - Panier anonyme
- Méthodes:
  * getTotal() - Total du panier
  * clear() - Vider le panier
```

---

## 🎨 Interfaces Utilisateur

### 3.1 **Frontend - Catalogue de Documents**

#### Page: `/documents`
- Liste des documents avec filtres
- Filtres par catégorie
- Recherche par titre/description
- Tri par prix, date, popularité
- Pagination
- Affichage en grille/liste

#### Page: `/documents/category/{slug}`
- Documents d'une catégorie
- Sous-catégories
- Filtres et tri

#### Page: `/documents/{slug}`
- Détails du document
- Aperçu (première page PDF si possible)
- Description complète
- Prix et bouton d'achat
- Documents similaires
- Avis/commentaires (optionnel)

#### Page: `/documents/cart`
- Panier d'achat
- Liste des documents
- Total
- Bouton de paiement
- Modification quantité/suppression

### 3.2 **Frontend - Espace Utilisateur**

#### Page: `/mes-documents`
- Liste des documents achetés
- Statut de chaque achat
- Bouton de téléchargement
- Historique des téléchargements
- Limite de téléchargements restants

### 3.3 **Backend - Administration**

#### Page: `/admin/documents`
- Liste des documents
- Filtres (statut, catégorie, auteur)
- Actions: créer, modifier, supprimer, publier
- Statistiques rapides

#### Page: `/admin/documents/create`
- Formulaire de création
- Upload de fichier
- Upload d'image de couverture
- Sélection de catégorie
- Prix et réduction
- SEO (meta tags)
- Prévisualisation

#### Page: `/admin/documents/{id}/edit`
- Formulaire d'édition
- Même structure que création

#### Page: `/admin/documents/categories`
- Gestion des catégorie
- Création/édition/suppression
- Ordre d'affichage (drag & drop)
- Catégories parentes/enfants

#### Page: `/admin/documents/sales`
- Statistiques de ventes
- Graphiques (ventes par période, catégorie)
- Top documents vendus
- Revenus totaux
- Export des données

#### Page: `/admin/documents/purchases`
- Liste des achats
- Filtres (statut, date, utilisateur)
- Détails d'un achat
- Gestion manuelle (valider/annuler)

---

## 🔧 Contrôleurs

### 4.1 `DocumentController` (Frontend)
```php
- index() - Liste des documents
- category($slug) - Documents par catégorie
- show($slug) - Détails d'un document
- search() - Recherche
- download($id) - Téléchargement sécurisé
```

### 4.2 `DocumentCartController` (Frontend)
```php
- index() - Afficher le panier
- add() - Ajouter au panier
- update() - Modifier quantité
- remove() - Retirer du panier
- clear() - Vider le panier
- getTotal() - API: Total du panier
```

### 4.3 `UserDocumentController` (Frontend)
```php
- index() - Mes documents achetés
- download($purchaseId) - Télécharger un document
- downloadHistory() - Historique des téléchargements
```

### 4.4 `Admin/DocumentController` (Backend)
```php
- index() - Liste des documents
- create() - Formulaire de création
- store() - Enregistrer un document
- edit($id) - Formulaire d'édition
- update($id) - Mettre à jour
- destroy($id) - Supprimer
- publish($id) - Publier
- unpublish($id) - Dépublier
- statistics() - Statistiques
```

### 4.5 `Admin/DocumentCategoryController` (Backend)
```php
- index() - Liste des catégories
- create() - Formulaire de création
- store() - Enregistrer
- edit($id) - Formulaire d'édition
- update($id) - Mettre à jour
- destroy($id) - Supprimer
- reorder() - Réordonner (AJAX)
```

### 4.6 `Admin/DocumentPurchaseController` (Backend)
```php
- index() - Liste des achats
- show($id) - Détails d'un achat
- approve($id) - Approuver un achat
- cancel($id) - Annuler un achat
```

---

## 💳 Intégration Paiement

### 5.1 Extension du `PaymentController`
```php
- processDocumentPurchase() - Traiter achat de document(s)
- processCartCheckout() - Traiter paiement du panier
```

### 5.2 Modification du modèle `Payment`
- Ajouter support polymorphique pour `DocumentPurchase`
- Relation: `paymentable` (DocumentPurchase)

### 5.3 Workflow de Paiement
1. Utilisateur ajoute documents au panier
2. Clique sur "Payer"
3. Sélectionne méthode de paiement
4. Redirection vers passerelle (Wave/PayPal/Stripe)
5. Retour après paiement
6. Création de `DocumentPurchase` avec statut "completed"
7. Envoi email de confirmation avec lien de téléchargement
8. Accès au téléchargement dans "Mes documents"

---

## 🔒 Sécurité et Téléchargement

### 6.1 Middleware `EnsureDocumentAccess`
```php
- Vérifier que l'utilisateur a acheté le document
- Vérifier que le téléchargement n'a pas expiré
- Vérifier la limite de téléchargements
- Logger chaque téléchargement
```

### 6.2 Système de Téléchargement Sécurisé
```php
- Génération de token unique par téléchargement
- Validation du token (expiration, usage unique optionnel)
- Streaming sécurisé du fichier
- Headers de sécurité (X-Content-Type-Options, etc.)
- Limitation de débit (rate limiting)
- Logging des téléchargements (IP, user agent, timestamp)
```

### 6.3 Protection des Fichiers
- Stockage dans `storage/app/documents/` (hors public)
- Noms de fichiers hashés
- Vérification d'intégrité (checksum)
- Scan antivirus (optionnel)

---

## 📊 Statistiques et Rapports

### 7.1 Dashboard Admin
- Revenus totaux (jour/semaine/mois/année)
- Nombre de documents vendus
- Top 10 documents
- Top catégories
- Graphiques (Chart.js ou similaire)
- Taux de conversion

### 7.2 Rapports Utilisateur
- Documents achetés
- Dépenses totales
- Historique des téléchargements

---

## 🗂️ Gestion des Fichiers

### 8.1 Upload de Documents
- Validation: type, taille, extension
- Stockage: `storage/app/documents/{category_id}/{document_id}/`
- Génération de nom unique
- Compression si nécessaire
- Génération de thumbnail (première page PDF)

### 8.2 Upload d'Images de Couverture
- Validation: format, taille
- Redimensionnement automatique
- Stockage: `storage/app/document-covers/`
- Support interne/externe

---

## 🔍 Recherche et Filtres

### 9.1 Fonctionnalités de Recherche
- Recherche par titre
- Recherche par description
- Recherche par tags
- Recherche par catégorie
- Filtres: prix, date, popularité
- Tri: prix croissant/décroissant, date, vues, ventes

### 9.2 Indexation
- Utiliser Laravel Scout (optionnel)
- Indexation des documents pour recherche rapide

---

## 📧 Notifications et Emails

### 10.1 Emails Automatiques
- Confirmation d'achat
- Lien de téléchargement
- Rappel d'expiration (si applicable)
- Nouveau document dans catégorie suivie (optionnel)

### 10.2 Notifications In-App
- Nouveau document disponible
- Achat confirmé
- Téléchargement réussi

---

## 🧪 Tests

### 11.1 Tests Unitaires
- Modèles (Document, DocumentCategory, DocumentPurchase)
- Relations
- Scopes
- Méthodes métier

### 11.2 Tests d'Intégration
- Workflow d'achat complet
- Téléchargement sécurisé
- Panier d'achat
- Paiement

### 11.3 Tests Fonctionnels
- Interface utilisateur
- Interface admin
- Responsive design

---

## 📱 Responsive Design

### 12.1 Mobile First
- Catalogue adaptatif
- Panier mobile-friendly
- Téléchargement mobile
- Interface admin responsive

---

## 🚀 Phases d'Implémentation

### Phase 1: Base de Données et Modèles (3-4 jours)
- [ ] Créer migrations
- [ ] Créer modèles Eloquent
- [ ] Définir relations
- [ ] Créer seeders (catégories de test)
- [ ] Tests unitaires modèles

### Phase 2: Backend - Administration (4-5 jours)
- [ ] CRUD Documents
- [ ] CRUD Catégories
- [ ] Upload fichiers
- [ ] Upload images
- [ ] Gestion des achats
- [ ] Statistiques de base

### Phase 3: Frontend - Catalogue (3-4 jours)
- [ ] Page catalogue
- [ ] Page détails document
- [ ] Filtres et recherche
- [ ] Pagination
- [ ] Responsive design

### Phase 4: Panier et Paiement (3-4 jours)
- [ ] Système de panier
- [ ] Page panier
- [ ] Intégration paiement
- [ ] Confirmation d'achat
- [ ] Emails automatiques

### Phase 5: Téléchargement et Sécurité (2-3 jours)
- [ ] Middleware de sécurité
- [ ] Système de téléchargement
- [ ] Génération de tokens
- [ ] Logging téléchargements
- [ ] Limites et expiration

### Phase 6: Espace Utilisateur (2 jours)
- [ ] Page "Mes documents"
- [ ] Historique téléchargements
- [ ] Interface de téléchargement

### Phase 7: Statistiques et Rapports (2 jours)
- [ ] Dashboard admin
- [ ] Graphiques
- [ ] Export données
- [ ] Rapports utilisateur

### Phase 8: Optimisations et Tests (2-3 jours)
- [ ] Cache (Redis/Memcached)
- [ ] Optimisation requêtes
- [ ] Tests complets
- [ ] Corrections bugs
- [ ] Documentation

**Total estimé: 21-27 jours de développement**

---

## 📚 Technologies et Packages

### Packages Laravel Recommandés
- `spatie/laravel-permission` - Gestion permissions (si nécessaire)
- `spatie/laravel-medialibrary` - Gestion fichiers (optionnel)
- `intervention/image` - Manipulation images
- `barryvdh/laravel-dompdf` - Génération PDF (si nécessaire)
- `laravel/scout` - Recherche (optionnel)

### Frontend
- Chart.js - Graphiques statistiques
- Select2 - Sélecteurs avancés
- Dropzone.js - Upload drag & drop (optionnel)

---

## 🔐 Sécurité

### Mesures de Sécurité
- Validation stricte des uploads
- Scan antivirus (optionnel)
- Rate limiting sur téléchargements
- Tokens uniques pour téléchargements
- Vérification d'intégrité fichiers
- Logs de sécurité
- Protection CSRF
- Sanitization des inputs

---

## 📈 Métriques à Suivre

- Nombre de documents vendus
- Revenus totaux
- Taux de conversion (vues → achats)
- Documents les plus vendus
- Catégories les plus populaires
- Taux de téléchargement après achat
- Taux d'abandon panier

---

## 🎯 Fonctionnalités Futures (Phase 2)

- Système d'avis/commentaires
- Documents gratuits
- Abonnements avec accès illimité
- Codes promo/réductions
- Recommandations personnalisées
- Wishlist (liste de souhaits)
- Partage social
- Affiliés pour documents
- Documents en bundle (pack)
- Prévisualisation avancée (plus de pages)

---

## 📝 Notes Importantes

1. **Compatibilité**: Réutiliser le système de paiement existant (Wave, PayPal, Stripe)
2. **Performance**: Mettre en cache les listes de documents et catégories
3. **SEO**: Optimiser les URLs, meta tags, sitemap
4. **Accessibilité**: Respecter les standards WCAG
5. **Multilingue**: Support FR/EN comme le reste du site
6. **Backup**: Sauvegarder régulièrement les fichiers documents

---

## ✅ Checklist de Validation

- [ ] Tous les tests passent
- [ ] Sécurité validée
- [ ] Performance acceptable
- [ ] Responsive design validé
- [ ] Documentation complète
- [ ] Formation admin effectuée
- [ ] Backup système en place
- [ ] Monitoring configuré

---

**Fin du Plan**


