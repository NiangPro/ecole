# Guide : Planification des Tâches sur Windows

## Problème
L'erreur `>> /dev/null 2>&1` se produit car `/dev/null` est un chemin Linux/unix qui n'existe pas sur Windows.

## Solution pour Windows

### Option 1 : Utiliser Task Scheduler (Recommandé)

1. **Ouvrir Task Scheduler** :
   - Appuyez sur `Win + R`
   - Tapez `taskschd.msc` et appuyez sur Entrée

2. **Créer une tâche de base** :
   - Cliquez sur "Créer une tâche de base" dans le panneau de droite
   - Nom : `Laravel Scheduler`
   - Description : `Exécute le scheduler Laravel toutes les minutes`

3. **Définir le déclencheur** :
   - Sélectionnez "Quand l'ordinateur démarre"
   - Cliquez sur "Suivant"
   - Cochez "Répéter la tâche indéfiniment"
   - Intervalle : `1 minute`
   - Durée : `Indéfiniment`

4. **Définir l'action** :
   - Action : `Démarrer un programme`
   - Programme/script : Chemin complet vers `php.exe` (ex: `C:\xampp\php\php.exe`)
   - Ajouter des arguments : `artisan schedule:run`
   - Démarrer dans : Chemin vers votre projet (ex: `C:\Users\Niang\OneDrive\Documents\Business\formation-laravel`)

5. **Terminer** :
   - Cliquez sur "Terminer"

### Option 2 : Script Batch pour Windows

Créez un fichier `run-scheduler.bat` dans le dossier racine du projet :

```batch
@echo off
cd /d "%~dp0"
php artisan schedule:run
```

Ensuite, créez une tâche planifiée qui exécute ce fichier `.bat` toutes les minutes.

### Option 3 : Utiliser `nul` au lieu de `/dev/null`

Pour Windows PowerShell, utilisez `nul` :

```powershell
php artisan schedule:run > nul 2>&1
```

Pour Command Prompt (cmd.exe) :

```cmd
php artisan schedule:run > nul 2>&1
```

### Option 3 : Exécuter manuellement (Pour les tests)

```powershell
cd "C:\Users\Niang\OneDrive\Documents\Business\formation-laravel"
php artisan schedule:run
```

## Vérification

Pour vérifier que les tâches sont bien planifiées :

```powershell
php artisan schedule:list
```

Cette commande affichera toutes les tâches planifiées et leurs prochaines exécutions.

## Notes importantes

- Les tâches planifiées dans `routes/console.php` utilisent `withoutOverlapping()` pour éviter les exécutions simultanées
- Assurez-vous que PHP est dans votre PATH, ou utilisez le chemin complet vers `php.exe`
- Pour la production, utilisez Task Scheduler Windows ou un service comme Supervisor (si disponible)

