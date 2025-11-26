# 📋 Guide d'Implémentation - Article Sponsorisé Sunu Code

## 🎯 Objectif

Créer et afficher un article sponsorisé pour la formation en développement web de Sunu Code sur la page d'accueil de niangprogrammeur.com.

## 📝 Étapes d'implémentation

### 1. Exécuter la migration (si pas déjà fait)

```bash
php artisan migrate
```

Cette commande ajoute le champ `is_sponsored` à la table `job_articles`.

### 2. Créer l'article sponsorisé

#### Option A : Via le Seeder (Recommandé)

```bash
php artisan db:seed --class=SponsoredArticleSeeder
```

#### Option B : Via l'interface d'administration

1. Connectez-vous à l'administration : `/admin`
2. Allez dans **Emplois > Articles**
3. Cliquez sur **Créer un article**
4. Remplissez les informations suivantes :
   - **Titre** : Formation Présentielle en Développement Web - Sunu Code
   - **Slug** : formation-presentielle-developpement-web-sunu-code
   - **Catégorie** : Opportunités professionnelles (ou autre catégorie appropriée)
   - **Extrait** : Devenez développeur web et créez des sites et applications modernes...
   - **Contenu** : (Voir le contenu complet dans le seeder)
   - **Image de couverture** : https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1200&h=630&fit=crop
   - **Type d'image** : Externe
   - **Statut** : Publié
   - **✅ Article sponsorisé** : Cocher cette case
   - **Date de publication** : Aujourd'hui
5. Cliquez sur **Enregistrer**

### 3. Vérifier l'affichage

L'article sponsorisé apparaîtra automatiquement dans la section "Articles Sponsorisés" sur la page d'accueil (`/`), à droite de la section "Catégories d'Articles".

## 🎨 Caractéristiques de l'article sponsorisé

- **Badge "Sponsorisé"** : Affiché automatiquement avec une étoile dorée
- **Design distinctif** : Fond doré/jaune pour se démarquer
- **Position privilégiée** : Affiché dans la sidebar droite de la page d'accueil
- **Responsive** : S'adapte à tous les écrans

## 📊 Structure de la section

La page d'accueil est maintenant divisée en deux parties avant "Dernières Opportunités d'Emploi" :

```
┌─────────────────────────────────────────────────────────┐
│  Section Catégories & Articles Sponsorisés              │
├──────────────────────────────┬──────────────────────────┤
│  Catégories d'Articles (2/3) │ Articles Sponsorisés (1/3)│
│  - Cards modernes            │ - Article Sunu Code      │
│  - Icônes                     │ - Badge "Sponsorisé"     │
│  - Nombre d'articles          │ - Image de couverture   │
│  - Badge "Nouveau"            │ - Titre et extrait      │
│                               │ - Métadonnées           │
└──────────────────────────────┴──────────────────────────┘
```

## 🔧 Personnalisation

### Modifier l'article sponsorisé

1. Allez dans **Emplois > Articles**
2. Recherchez "Sunu Code" ou le slug "formation-presentielle-developpement-web-sunu-code"
3. Cliquez sur **Modifier**
4. Modifiez les champs souhaités
5. Assurez-vous que **Article sponsorisé** est coché
6. Enregistrez

### Ajouter d'autres articles sponsorisés

1. Créez un nouvel article normal
2. Cochez la case **Article sponsorisé**
3. Publiez l'article
4. Il apparaîtra automatiquement dans la section (maximum 3 articles affichés)

### Modifier le nombre d'articles sponsorisés affichés

Modifiez le fichier `app/Http/Controllers/PageController.php` :

```php
// Ligne ~50, dans la méthode index()
->take(3)  // Changez 3 par le nombre souhaité
```

## 📱 Responsive Design

La section s'adapte automatiquement :
- **Desktop** : 2 colonnes (2/3 catégories, 1/3 sponsorisés)
- **Tablette** : 1 colonne (catégories au-dessus, sponsorisés en dessous)
- **Mobile** : 1 colonne avec cards optimisées

## 🎯 SEO et Métadonnées

L'article sponsorisé inclut :
- **Meta Title** : Optimisé pour le référencement
- **Meta Description** : Description attractive pour les moteurs de recherche
- **Meta Keywords** : Mots-clés pertinents
- **Score SEO** : 95/100
- **Score de lisibilité** : 90/100

## 🔍 Vérification

Pour vérifier que tout fonctionne :

1. **Page d'accueil** : `/`
   - Vérifiez que la section "Articles Sponsorisés" apparaît
   - Vérifiez que l'article Sunu Code est visible
   - Vérifiez le badge "Sponsorisé"

2. **Page de l'article** : `/emplois/article/formation-presentielle-developpement-web-sunu-code`
   - Vérifiez que l'article s'affiche correctement
   - Vérifiez les métadonnées SEO

3. **Administration** : `/admin/jobs/articles`
   - Vérifiez que l'article est marqué comme sponsorisé
   - Vérifiez que le statut est "Publié"

## 🐛 Dépannage

### L'article n'apparaît pas

1. Vérifiez que `is_sponsored = true` dans la base de données
2. Vérifiez que `status = 'published'`
3. Videz le cache : `php artisan cache:clear`
4. Videz le cache des vues : `php artisan view:clear`

### L'article apparaît mais sans badge

1. Vérifiez que le champ `is_sponsored` existe dans la table
2. Exécutez la migration : `php artisan migrate`

### Erreur de route pour les catégories

Si vous cliquez sur une catégorie et obtenez une erreur 404 :
1. Vérifiez que la route existe dans `routes/web.php`
2. Vérifiez que la vue `resources/views/emplois/category.blade.php` existe (à créer si nécessaire)

## 📞 Support

Pour toute question ou problème :
- Vérifiez les logs : `storage/logs/laravel.log`
- Vérifiez la console du navigateur pour les erreurs JavaScript
- Vérifiez la base de données directement

## ✅ Checklist de déploiement

- [ ] Migration exécutée (`is_sponsored` ajouté)
- [ ] Article sponsorisé créé (via seeder ou admin)
- [ ] Article marqué comme sponsorisé (`is_sponsored = true`)
- [ ] Article publié (`status = 'published'`)
- [ ] Cache vidé (`php artisan cache:clear`)
- [ ] Vérification sur la page d'accueil
- [ ] Vérification responsive (mobile, tablette, desktop)
- [ ] Vérification du lien vers l'article complet

## 🚀 Prochaines étapes

1. **Créer la vue category.blade.php** pour afficher les articles par catégorie
2. **Ajouter plus d'articles sponsorisés** pour enrichir la section
3. **Personnaliser les styles** si nécessaire
4. **Ajouter des statistiques** sur les clics des articles sponsorisés

---

**Date de création** : 26 novembre 2025  
**Dernière mise à jour** : 26 novembre 2025

