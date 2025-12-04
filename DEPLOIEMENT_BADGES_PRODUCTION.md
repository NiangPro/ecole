# 🚀 Guide de Déploiement - Système de Badges en Production

## ⚠️ Différences entre Local et Production

Lors du déploiement sur l'hébergeur, il est **ESSENTIEL** d'exécuter les migrations et seeders pour que le système de badges fonctionne correctement.

## 📋 Étapes de Déploiement

### 1. Exécuter les Migrations

Les tables suivantes doivent être créées dans la base de données de production :

```bash
php artisan migrate
```

**Tables créées :**
- `badges` - Définition des badges disponibles
- `user_badges` - Badges obtenus par les utilisateurs  
- `certificates` - Certificats de complétion des formations

### 2. Exécuter le Seeder des Badges

**IMPORTANT** : Le seeder `BadgeSeeder` doit être exécuté pour créer les badges dans la base de données.

```bash
php artisan db:seed --class=BadgeSeeder
```

Ou si le `DatabaseSeeder` inclut le `BadgeSeeder` :

```bash
php artisan db:seed
```

**Badges créés (12 badges) :**
- 3 badges spéciaux (Premier Pas, Premier Exercice, Premier Quiz)
- 2 badges de formations (Étudiant Assidu, Expert en Formations)
- 3 badges d'exercices (Débutant, Pratiquant, Maître du Code)
- 2 badges de quiz (Quiz Master, Grand Maître des Quiz)
- 2 badges de streak (Semaine Parfaite, Mois Parfait)

### 3. Vérifier le DatabaseSeeder

Assurez-vous que le `DatabaseSeeder` inclut le `BadgeSeeder` :

```php
// database/seeders/DatabaseSeeder.php
public function run(): void
{
    $this->call([
        BadgeSeeder::class,
        // ... autres seeders
    ]);
}
```

### 4. Vérifier les Permissions

Assurez-vous que les permissions sont correctes pour :
- Le dossier `storage/` (pour les certificats PDF)
- Le dossier `bootstrap/cache/`
- Le dossier `storage/framework/`

```bash
chmod -R 775 storage bootstrap/cache
```

### 5. Vider les Caches

Après le déploiement, videz tous les caches :

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### 6. Vérifier la Configuration

Assurez-vous que les variables d'environnement sont correctement configurées dans `.env` :

```env
APP_ENV=production
APP_URL=https://www.niangprogrammeur.com
APP_DEBUG=false
```

## 🔍 Vérifications Post-Déploiement

### Vérifier que les badges existent

Connectez-vous à la base de données et vérifiez :

```sql
SELECT COUNT(*) FROM badges;
-- Doit retourner 12
```

### Vérifier la page /dashboard/badges

1. Connectez-vous avec un compte utilisateur
2. Accédez à `/dashboard/badges`
3. Vérifiez que les badges s'affichent correctement
4. Vérifiez que les badges obtenus sont marqués comme "Obtenu"

## 🐛 Problèmes Courants

### Problème 1 : Page vide ou erreur

**Cause** : Les badges n'existent pas dans la base de données

**Solution** : Exécuter le seeder
```bash
php artisan db:seed --class=BadgeSeeder
```

### Problème 2 : Erreur "Table 'badges' doesn't exist"

**Cause** : Les migrations n'ont pas été exécutées

**Solution** : Exécuter les migrations
```bash
php artisan migrate
```

### Problème 3 : Badges non attribués automatiquement

**Cause** : Le service `BadgeService` n'est pas appelé

**Solution** : Vérifier que le `BadgeController` appelle `checkAndAwardBadges()`

### Problème 4 : Différences d'affichage

**Cause** : Cache non vidé ou fichiers de traduction manquants

**Solution** : 
```bash
php artisan cache:clear
php artisan view:clear
```

## 📝 Checklist de Déploiement

- [ ] Migrations exécutées (`php artisan migrate`)
- [ ] Seeder BadgeSeeder exécuté (`php artisan db:seed --class=BadgeSeeder`)
- [ ] Permissions correctes sur `storage/` et `bootstrap/cache/`
- [ ] Caches vidés (`php artisan cache:clear`, `php artisan view:clear`)
- [ ] Variables d'environnement configurées (`.env`)
- [ ] Page `/dashboard/badges` testée et fonctionnelle
- [ ] Badges s'affichent correctement
- [ ] Badges obtenus sont marqués comme "Obtenu"

## 🔄 Commandes Rapides pour Production

```bash
# 1. Migrations
php artisan migrate --force

# 2. Seeder des badges
php artisan db:seed --class=BadgeSeeder --force

# 3. Vider les caches
php artisan optimize:clear

# 4. Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📌 Notes Importantes

1. **Le seeder utilise `updateOrCreate()`** : Il est sûr de l'exécuter plusieurs fois, il ne créera pas de doublons
2. **Les badges sont créés avec `is_active = true`** par défaut
3. **L'ordre des badges** est défini par le champ `order` dans le seeder
4. **Les couleurs** sont définies en hexadécimal dans le seeder

## 🎯 Résultat Attendu

Après le déploiement, la page `/dashboard/badges` doit afficher :
- Tous les badges disponibles (12 badges)
- Les badges groupés par type (special, formation, exercise, quiz, streak)
- Les badges obtenus marqués avec une date d'obtention
- Les badges non obtenus affichés en grisé avec un cadenas

