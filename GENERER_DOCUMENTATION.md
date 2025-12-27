# Génération de la Documentation PHPDoc

## Installation

La documentation PHPDoc a été ajoutée aux fichiers suivants :

### Contrôleurs
- ✅ `app/Http/Controllers/ForumController.php` - Documentation complète
- ✅ `app/Http/Controllers/MessageController.php` - Documentation complète
- ✅ `app/Http/Controllers/Admin/ForumCategoryController.php` - Documentation complète

### Modèles
- ✅ `app/Models/ForumTopic.php` - Documentation complète
- ✅ `app/Models/ForumCategory.php` - Documentation complète
- ✅ `app/Models/ForumReply.php` - Documentation complète
- ✅ `app/Models/ForumVote.php` - Documentation complète
- ✅ `app/Models/Conversation.php` - Documentation complète
- ✅ `app/Models/Message.php` - Documentation complète
- ✅ `app/Models/MessageAttachment.php` - Documentation complète

## Générer la Documentation

### Option 1 : Utiliser phpDocumentor via PHAR (recommandé)

```bash
# Télécharger phpDocumentor PHAR (sans dépendances Composer)
wget https://github.com/phpDocumentor/phpDocumentor/releases/download/v3.4.0/phpDocumentor.phar
chmod +x phpDocumentor.phar

# Générer la documentation
./phpDocumentor.phar -d app -t docs/api --template="clean"
```

### Option 2 : Utiliser phpDocumentor via Docker

```bash
docker run --rm -v $(pwd):/data phpdoc/phpdoc:3 -d app -t docs/api
```

### Option 2 : Utiliser le script fourni

```bash
php artisan docs:generate
```

### Option 3 : Utiliser la configuration XML

```bash
vendor/bin/phpdoc -c phpdoc.xml
```

## Structure de la Documentation

La documentation générée sera disponible dans le dossier `docs/api/` et contiendra :

- **HTML** : Documentation interactive dans `docs/api/html/`
- **XML** : Documentation structurée dans `docs/api/xml/`

## Accès à la Documentation

Une fois générée, la documentation sera accessible via :
- Fichiers HTML : Ouvrir `docs/api/html/index.html` dans un navigateur
- Intégration IDE : Les IDEs modernes (PhpStorm, VS Code) utilisent automatiquement les annotations PHPDoc

## Format de Documentation

Toutes les méthodes documentées incluent :

1. **Description** : Explication de ce que fait la méthode
2. **@param** : Description des paramètres avec types
3. **@return** : Type et description de la valeur de retour
4. **@throws** : Exceptions possibles
5. **@example** : Exemples d'utilisation

## Exemple de Documentation

```php
/**
 * Crée une réponse à un topic existant
 * 
 * Permet à un utilisateur authentifié de répondre à un topic.
 * Vérifie que le topic n'est pas verrouillé avant d'autoriser la réponse.
 * 
 * @param \Illuminate\Http\Request $request Requête HTTP contenant le contenu de la réponse
 * @param string $categorySlug Slug de la catégorie
 * @param string $topicSlug Slug du topic
 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse Redirection ou réponse JSON
 * @throws \Illuminate\Validation\ValidationException Si la validation échoue
 * 
 * @example
 * Route: POST /forum/general/mon-topic/reply
 * Données attendues :
 * - body (required|string|min:5)
 */
public function reply(Request $request, $categorySlug, $topicSlug)
{
    // ...
}
```

## Maintenance

Pour maintenir la documentation à jour :

1. Ajouter des annotations PHPDoc lors de la création de nouvelles méthodes
2. Mettre à jour la documentation lors de modifications importantes
3. Régénérer la documentation après chaque mise à jour majeure

## Intégration Continue

Pour automatiser la génération de la documentation :

```yaml
# .github/workflows/docs.yml
name: Generate Documentation
on:
  push:
    branches: [ main ]
jobs:
  docs:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Generate PHPDoc
        run: |
          composer install
          vendor/bin/phpdoc -d app -t docs/api
```

