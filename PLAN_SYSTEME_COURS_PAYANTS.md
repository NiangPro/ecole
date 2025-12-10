# 📚 PLAN DÉTAILLÉ - SYSTÈME DE GESTION DES COURS PAYANTS
## Route: `/admin/monetization/courses`

**Date de création**: 2025-01-27  
**Statut**: 🚀 En développement

---

## 🎯 OBJECTIFS

Créer un système complet de gestion des cours payants dans l'interface d'administration avec toutes les fonctionnalités CRUD et des fonctionnalités avancées.

---

## 📋 FONCTIONNALITÉS À IMPLÉMENTER

### 1. **LISTE DES COURS (Index)**
- ✅ Affichage de tous les cours payants avec pagination
- ✅ Filtres par statut (draft, published, archived)
- ✅ Recherche par titre, description, slug
- ✅ Filtres par prix (min/max)
- ✅ Filtres par date de création
- ✅ Statistiques globales (total, publiés, brouillons, revenus)
- ✅ Actions en masse (publier, archiver, supprimer)
- ✅ Tri par colonnes (titre, prix, ventes, date)
- ✅ Export CSV/Excel

### 2. **CRÉATION D'UN COURS (Create)**
- ✅ Formulaire complet avec validation
- ✅ Upload d'image
- ✅ Éditeur de contenu riche (WYSIWYG)
- ✅ Gestion des réductions (prix, dates)
- ✅ Champs "Ce que vous apprendrez" (liste)
- ✅ Champs "Prérequis" (liste)
- ✅ Prévisualisation avant sauvegarde

### 3. **MODIFICATION D'UN COURS (Edit)**
- ✅ Formulaire pré-rempli avec les données existantes
- ✅ Modification de tous les champs
- ✅ Gestion de l'image (changer/supprimer)
- ✅ Historique des modifications

### 4. **DÉTAILS D'UN COURS (Show)**
- ✅ Affichage complet des informations
- ✅ Statistiques de ventes
- ✅ Liste des achats (purchases)
- ✅ Graphiques de revenus
- ✅ Actions rapides (publier, archiver, supprimer)

### 5. **SUPPRESSION (Destroy)**
- ✅ Confirmation avant suppression
- ✅ Vérification des achats existants
- ✅ Option de suppression douce (soft delete)

### 6. **FONCTIONNALITÉS AVANCÉES**
- ✅ Export des données (CSV, Excel)
- ✅ Statistiques détaillées
- ✅ Actions en masse
- ✅ Duplication de cours
- ✅ Gestion des réductions automatiques

---

## 🗂️ STRUCTURE DES FICHIERS

```
app/Http/Controllers/Admin/
└── PaidCourseController.php (nouveau)

resources/views/admin/monetization/courses/
├── index.blade.php (liste)
├── create.blade.php (création)
├── edit.blade.php (modification)
└── show.blade.php (détails)

routes/web.php
└── Routes CRUD ajoutées
```

---

## 📊 MODÈLE DE DONNÉES

### Table: `paid_courses`
- id
- title
- slug (unique)
- description
- content
- image
- price
- currency
- discount_price
- discount_start
- discount_end
- status (draft, published, archived)
- duration_hours
- students_count
- rating
- reviews_count
- what_you_learn (JSON)
- requirements (JSON)
- timestamps

### Relations
- `hasMany` CoursePurchase
- `hasMany` Payment (via morphMany)

---

## 🔐 VALIDATION

### Règles de validation (Create/Update)
- `title`: required|string|max:255
- `slug`: required|string|max:255|unique:paid_courses,slug
- `description`: nullable|string|max:1000
- `content`: nullable|string
- `image`: nullable|image|max:2048
- `price`: required|numeric|min:0
- `currency`: required|string|size:3
- `discount_price`: nullable|numeric|min:0|lt:price
- `discount_start`: nullable|date
- `discount_end`: nullable|date|after:discount_start
- `status`: required|in:draft,published,archived
- `duration_hours`: nullable|integer|min:1
- `what_you_learn`: nullable|array
- `requirements`: nullable|array

---

## 🎨 INTERFACE UTILISATEUR

### Design
- Design moderne et responsive
- Dark mode compatible
- Animations fluides
- Tableaux interactifs
- Modals pour confirmations
- Toast notifications

### Composants
- Cards pour les cours
- Tableaux avec tri
- Formulaires avec validation en temps réel
- Upload d'images avec prévisualisation
- Éditeur de contenu riche
- Graphiques pour statistiques

---

## 📈 STATISTIQUES À AFFICHER

### Dashboard des cours
- Total de cours
- Cours publiés
- Cours en brouillon
- Cours archivés
- Revenus totaux
- Nombre total de ventes
- Revenus par mois
- Top 5 cours les plus vendus

### Par cours
- Nombre de ventes
- Revenus générés
- Taux de conversion
- Note moyenne
- Nombre d'avis

---

## 🔄 ACTIONS EN MASSE

- Publier plusieurs cours
- Archiver plusieurs cours
- Supprimer plusieurs cours
- Modifier le statut en masse
- Exporter les cours sélectionnés

---

## 📤 EXPORT

- Export CSV avec toutes les données
- Export Excel avec formatage
- Export des statistiques
- Export des ventes par cours

---

## 🚀 ÉTAPES D'IMPLÉMENTATION

1. ✅ Créer le plan détaillé
2. ⏳ Ajouter les routes CRUD
3. ⏳ Créer le contrôleur PaidCourseController
4. ⏳ Créer la vue index (liste)
5. ⏳ Créer la vue create (création)
6. ⏳ Créer la vue edit (modification)
7. ⏳ Créer la vue show (détails)
8. ⏳ Ajouter les fonctionnalités avancées
9. ⏳ Tests et optimisations

---

## 📝 NOTES IMPORTANTES

- Utiliser les mêmes patterns que DonationController
- Respecter les conventions Laravel
- Gérer les erreurs proprement
- Optimiser les requêtes (eager loading)
- Sécuriser les uploads d'images
- Valider tous les inputs
- Messages en français
- Commentaires en français

---

## ✅ CHECKLIST FINALE

- [ ] Routes créées et testées
- [ ] Contrôleur complet avec toutes les méthodes
- [ ] Vues créées et stylisées
- [ ] Validation complète
- [ ] Gestion des images
- [ ] Export fonctionnel
- [ ] Statistiques affichées
- [ ] Actions en masse opérationnelles
- [ ] Responsive design
- [ ] Dark mode compatible
- [ ] Tests effectués
- [ ] Documentation à jour

---

**FIN DU PLAN**

