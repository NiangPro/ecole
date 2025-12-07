# Tests et Qualité - Documentation

## Vue d'ensemble

Ce document décrit la suite de tests complète mise en place pour le projet Laravel, incluant les tests unitaires, d'intégration et E2E (End-to-End).

## Structure des Tests

### Tests Unitaires (`tests/Unit/`)

Les tests unitaires vérifient le comportement des composants individuels :

- **UserTest.php** : Tests pour le modèle User
  - Création d'utilisateur
  - Vérification du rôle admin
  - Filtrage des utilisateurs actifs
  - Relations avec badges, notifications, favoris
  - Progression des formations

- **BadgeTest.php** : Tests pour le modèle Badge
  - Création de badge
  - Attribution à un utilisateur
  - Recherche par code

- **NotificationTest.php** : Tests pour le modèle Notification
  - Création de notification
  - Marquage comme lu
  - Marquage de toutes les notifications
  - Comptage des notifications non lues

- **SecurityAuditTest.php** : Tests pour le modèle SecurityAudit
  - Création d'audit
  - Enregistrement d'événements
  - Sanitization des données sensibles
  - Filtrage par sévérité

- **FavoriteTest.php** : Tests pour le modèle Favorite
  - Création de favori
  - Gestion de plusieurs favoris

- **Services/** : Tests pour les services
  - RecaptchaServiceTest
  - BadgeServiceTest

### Tests d'Intégration (`tests/Feature/`)

Les tests d'intégration vérifient le comportement des fonctionnalités complètes :

- **AuthTest.php** : Tests d'authentification
  - Connexion utilisateur
  - Échec de connexion avec mauvais mot de passe
  - Déconnexion
  - Inscription

- **AdminAuthTest.php** : Tests d'authentification admin
  - Connexion admin
  - Blocage des utilisateurs non-admin
  - Accès au dashboard admin

- **FavoriteTest.php** : Tests des favoris
  - Ajout de favori
  - Retrait de favori
  - Vérification d'existence
  - Protection des routes authentifiées

- **NotificationTest.php** : Tests des notifications
  - Affichage des notifications non lues
  - Marquage comme lu
  - Marquage de toutes comme lues

- **SecurityAuditTest.php** : Tests de l'audit de sécurité
  - Accès admin aux audits
  - Blocage des non-admins
  - Filtrage par sévérité
  - Affichage des détails

### Tests E2E (`tests/Browser/`)

Les tests E2E utilisent Laravel Dusk pour tester l'application dans un navigateur réel :

- **HomePageTest.php** : Tests de la page d'accueil
  - Affichage correct
  - Navigation vers les formations
  - Fonctionnalité de recherche

- **AuthTest.php** : Tests d'authentification E2E
  - Connexion utilisateur
  - Connexion admin

## Configuration

### PHPUnit (`phpunit.xml`)

- Base de données en mémoire (SQLite) pour les tests
- Configuration du code coverage
- Exclusion des fichiers non testables (Console, Exceptions, Middleware)

### Laravel Dusk

- Installation automatique de ChromeDriver
- Configuration pour les tests de navigateur
- Screenshots et source code en cas d'échec

## Exécution des Tests

### Tous les tests
```bash
php artisan test
```

### Tests unitaires uniquement
```bash
php artisan test --testsuite=Unit
```

### Tests d'intégration uniquement
```bash
php artisan test --testsuite=Feature
```

### Tests E2E (Dusk)
```bash
php artisan dusk
```

### Avec code coverage
```bash
php artisan test --coverage
```

Le rapport de couverture sera généré dans :
- `coverage/html/index.html` (rapport HTML)
- `coverage/coverage.txt` (rapport texte)

## Objectif de Couverture

**Objectif : > 80% de couverture de code**

Les fichiers suivants sont prioritaires pour la couverture :
- `app/Models/` (tous les modèles)
- `app/Http/Controllers/` (tous les contrôleurs)
- `app/Services/` (tous les services)

## Factories

Toutes les factories nécessaires ont été créées :
- `UserFactory`
- `BadgeFactory`
- `SecurityAuditFactory`
- `FormationProgressFactory`
- `NotificationFactory`
- `FavoriteFactory`

## Bonnes Pratiques

1. **Isolation** : Chaque test est indépendant grâce à `RefreshDatabase`
2. **Nommage** : Les méthodes de test suivent la convention `test_nom_de_la_fonctionnalite`
3. **Arrange-Act-Assert** : Structure claire des tests
4. **Factories** : Utilisation des factories pour créer des données de test
5. **Assertions** : Utilisation d'assertions Laravel spécifiques

## Maintenance

- Exécuter les tests avant chaque commit
- Maintenir la couverture de code > 80%
- Ajouter des tests pour chaque nouvelle fonctionnalité
- Mettre à jour les tests lors de modifications de code

## Prochaines Étapes

- [ ] Ajouter des tests pour les contrôleurs manquants
- [ ] Ajouter des tests pour les middlewares
- [ ] Améliorer la couverture des services
- [ ] Ajouter des tests de performance
- [ ] Intégrer les tests dans CI/CD

