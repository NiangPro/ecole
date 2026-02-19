# Déploiement sur LWS - Affichage des images

## Problème
Sur LWS, les liens symboliques (`storage:link`) sont souvent désactivés. Les images dans `storage/app/public/` ne sont pas accessibles via `/storage/`.

## Solution : Commande `storage:publish`

Cette commande **copie** les fichiers de `storage/app/public/` vers `public/storage/` pour qu'Apache les serve directement.

### Étapes sur LWS

1. **Déployer le projet** (contenu de `public/` à la racine du site)

2. **Vérifier que le dossier storage existe** sur le serveur :
   - `storage/app/public/document-covers/`
   - `storage/app/public/job-covers/`
   
   Les images uploadées via l'admin sont stockées ici. Si vous avez créé des documents en local, **uploadez les images manuellement** sur le serveur dans ces dossiers.

3. **Exécuter la commande** (via Terminal LWS ou SSH) :
   ```bash
   php artisan storage:publish
   ```
   
   Pour forcer la copie de tous les fichiers :
   ```bash
   php artisan storage:publish --force
   ```

4. **Après chaque nouvel upload d'image** (document, article emploi), ré-exécuter :
   ```bash
   php artisan storage:publish
   ```

### Structure attendue sur le serveur
```
/votre-site/
├── app/
├── bootstrap/
├── config/
├── storage/
│   └── app/
│       └── public/
│           ├── document-covers/   ← Images des documents
│           └── job-covers/        ← Images des articles emplois
├── vendor/
└── public/  (ou contenu déployé à la racine htdocs)
    ├── index.php
    ├── .htaccess
    ├── storage/   ← Créé par storage:publish (fichiers copiés ici)
    │   ├── document-covers/
    │   └── job-covers/
    └── ...
```

### Vérification
Après `storage:publish`, testez une URL d'image directement :
`https://www.niangprogrammeur.com/storage/document-covers/NOM_FICHIER.jpg`
