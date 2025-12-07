# 🤝 Guide de Contribution - NiangProgrammeur

Merci de votre intérêt pour contribuer à NiangProgrammeur ! Ce document fournit les guidelines pour contribuer au projet.

## 📋 Table des Matières

- [Code de Conduite](#code-de-conduite)
- [Comment Contribuer](#comment-contribuer)
- [Processus de Développement](#processus-de-développement)
- [Standards de Code](#standards-de-code)
- [Tests](#tests)
- [Documentation](#documentation)
- [Pull Requests](#pull-requests)
- [Rapport de Bugs](#rapport-de-bugs)
- [Suggestions de Fonctionnalités](#suggestions-de-fonctionnalités)

## 📜 Code de Conduite

En participant à ce projet, vous acceptez de respecter notre code de conduite :

- **Respect** : Traitez tous les contributeurs avec respect
- **Ouverture** : Accueillez les nouvelles idées et suggestions
- **Collaboration** : Travaillez ensemble pour améliorer le projet
- **Professionnalisme** : Maintenez un ton professionnel dans toutes les communications

## 🚀 Comment Contribuer

### 1. Fork et Clone

```bash
# Fork le projet sur GitHub
# Puis clonez votre fork
git clone https://github.com/votre-username/formation-laravel.git
cd formation-laravel
```

### 2. Créer une Branche

```bash
# Créer une branche pour votre fonctionnalité/correction
git checkout -b feature/ma-fonctionnalite
# ou
git checkout -b fix/mon-bug
```

### 3. Configuration de l'Environnement

```bash
# Installer les dépendances
composer install
npm install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Créer la base de données
php artisan migrate
php artisan db:seed
```

### 4. Développer

- Suivez les [Standards de Code](#standards-de-code)
- Écrivez des [Tests](#tests) pour votre code
- Mettez à jour la [Documentation](#documentation) si nécessaire

### 5. Commit

```bash
# Ajouter vos changements
git add .

# Commit avec un message descriptif
git commit -m "feat: ajouter fonctionnalité X"
# ou
git commit -m "fix: corriger bug Y"
```

**Convention de Commit :**
- `feat:` Nouvelle fonctionnalité
- `fix:` Correction de bug
- `docs:` Documentation
- `style:` Formatage, point-virgule manquant, etc.
- `refactor:` Refactoring du code
- `test:` Ajout/modification de tests
- `chore:` Maintenance, dépendances, etc.

### 6. Push et Pull Request

```bash
# Pousser vers votre fork
git push origin feature/ma-fonctionnalite

# Créer une Pull Request sur GitHub
```

## 🔄 Processus de Développement

### Workflow Git

1. **Main** : Branche principale, toujours stable
2. **Develop** : Branche de développement (si applicable)
3. **Feature branches** : `feature/nom-fonctionnalite`
4. **Fix branches** : `fix/nom-bug`
5. **Hotfix branches** : `hotfix/nom-urgence`

### Étapes de Développement

1. **Planifier** : Créer une issue ou discuter de la fonctionnalité
2. **Développer** : Coder la fonctionnalité/correction
3. **Tester** : Écrire et exécuter les tests
4. **Documenter** : Mettre à jour la documentation
5. **Review** : Soumettre une PR et attendre la review
6. **Merge** : Après approbation, merge dans main

## 📝 Standards de Code

### PHP (Laravel)

#### Style de Code

- Suivre [PSR-12](https://www.php-fig.org/psr/psr-12/)
- Utiliser Laravel Pint pour le formatage automatique

```bash
# Formater le code
php artisan pint
```

#### Conventions de Nommage

- **Classes** : `PascalCase` (ex: `UserController`)
- **Méthodes** : `camelCase` (ex: `getUserData`)
- **Variables** : `camelCase` (ex: `$userName`)
- **Constantes** : `UPPER_SNAKE_CASE` (ex: `MAX_USERS`)
- **Tables** : `snake_case` pluriel (ex: `user_badges`)
- **Routes** : `kebab-case` (ex: `/dashboard/overview`)

#### Structure des Contrôleurs

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ]);

        $user = User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès');
    }
}
```

#### Commentaires

- Utiliser le français pour les commentaires
- Documenter les méthodes publiques avec PHPDoc
- Expliquer le "pourquoi", pas le "quoi"

```php
/**
 * Calculer le score de progression d'une formation
 * 
 * @param string $formationSlug Slug de la formation
 * @param User $user Utilisateur
 * @return int Score entre 0 et 100
 */
public function calculateProgress(string $formationSlug, User $user): int
{
    // Logique de calcul...
}
```

### JavaScript

#### Style de Code

- Utiliser ES6+ (let/const, arrow functions, etc.)
- Suivre les conventions ESLint (si configuré)

#### Conventions

```javascript
// Variables et fonctions : camelCase
const userName = 'John';
function getUserData() {}

// Constantes : UPPER_SNAKE_CASE
const MAX_RETRIES = 3;

// Classes : PascalCase
class UserManager {
    constructor() {}
}
```

#### Commentaires

```javascript
/**
 * Gérer l'installation PWA
 * @class PWAManager
 */
class PWAManager {
    /**
     * Installer l'application PWA
     * @returns {Promise<void>}
     */
    async installPWA() {
        // Logique d'installation...
    }
}
```

### CSS

- Utiliser Tailwind CSS pour le styling
- Éviter les styles inline sauf nécessité
- Organiser les styles par composant

```css
/* Utiliser Tailwind classes dans le HTML */
/* Pour styles personnalisés, utiliser des classes utilitaires */
.btn-primary {
    @apply bg-blue-500 text-white px-4 py-2 rounded;
}
```

## 🧪 Tests

### Écrire des Tests

#### Tests Unitaires

```php
<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }
}
```

#### Tests d'Intégration

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.layout');
    }
}
```

### Exécuter les Tests

```bash
# Tous les tests
php artisan test

# Tests unitaires uniquement
php artisan test --testsuite=Unit

# Tests d'intégration uniquement
php artisan test --testsuite=Feature

# Tests E2E (Dusk)
php artisan dusk

# Avec couverture
php artisan test --coverage
```

### Couverture de Code

- Viser une couverture > 80%
- Tester les cas limites
- Tester les erreurs

## 📚 Documentation

### Mettre à Jour la Documentation

Si vous ajoutez/modifiez :

- **Fonctionnalités** : Mettre à jour `README.md`
- **API** : Mettre à jour `API.md`
- **Routes** : Documenter dans les contrôleurs
- **Modèles** : Documenter les relations dans les modèles

### Commentaires de Code

- Documenter les méthodes publiques
- Expliquer la logique complexe
- Ajouter des exemples si nécessaire

## 🔀 Pull Requests

### Avant de Soumettre

- [ ] Code formaté avec Laravel Pint
- [ ] Tests écrits et passants
- [ ] Documentation mise à jour
- [ ] Pas de conflits avec main
- [ ] Messages de commit clairs

### Template de PR

```markdown
## Description
Brève description des changements

## Type de changement
- [ ] Nouvelle fonctionnalité
- [ ] Correction de bug
- [ ] Amélioration de performance
- [ ] Documentation
- [ ] Refactoring

## Tests
- [ ] Tests unitaires ajoutés/modifiés
- [ ] Tests d'intégration ajoutés/modifiés
- [ ] Tous les tests passent

## Checklist
- [ ] Code formaté
- [ ] Documentation mise à jour
- [ ] Pas de warnings/erreurs
- [ ] Compatible avec les versions précédentes
```

### Review Process

1. **Automated Checks** : Les tests doivent passer
2. **Code Review** : Au moins un reviewer doit approuver
3. **Discussion** : Répondre aux commentaires
4. **Merge** : Après approbation, merge par un mainteneur

## 🐛 Rapport de Bugs

### Template d'Issue

```markdown
**Description du bug**
Description claire du problème

**Étapes pour reproduire**
1. Aller à '...'
2. Cliquer sur '...'
3. Voir l'erreur

**Comportement attendu**
Ce qui devrait se passer

**Comportement actuel**
Ce qui se passe réellement

**Screenshots**
Si applicable

**Environnement**
- OS: [ex: Windows 10]
- Navigateur: [ex: Chrome 120]
- Version PHP: [ex: 8.2.12]
- Version Laravel: [ex: 12.37.0]

**Logs**
```
Erreurs pertinentes des logs
```
```

## 💡 Suggestions de Fonctionnalités

### Template d'Issue

```markdown
**Description**
Description claire de la fonctionnalité proposée

**Problème résolu**
Quel problème cette fonctionnalité résout-elle ?

**Solution proposée**
Comment cette fonctionnalité devrait fonctionner ?

**Alternatives considérées**
Autres solutions envisagées

**Contexte additionnel**
Screenshots, mockups, etc.
```

## 📞 Questions ?

- **Email** : NiangProgrammeur@gmail.com
- **Issues GitHub** : Pour les bugs et suggestions
- **Discussions GitHub** : Pour les questions générales

## 🙏 Remerciements

Merci de contribuer à NiangProgrammeur ! Chaque contribution, grande ou petite, est appréciée.

---

**Dernière mise à jour** : 2025-01-27

