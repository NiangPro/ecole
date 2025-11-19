# 🔐 Configuration de l'Authentification Admin Sécurisée

## ✅ Modifications Apportées

### 1. Système d'Authentification Laravel Standard
- ✅ Remplacement du système de session basique par Laravel Auth
- ✅ Utilisation du modèle `User` avec hashage de mot de passe
- ✅ Middleware `AdminAuth` pour protéger les routes
- ✅ Rate limiting pour prévenir les attaques brute force
- ✅ Logging des tentatives de connexion

### 2. Sécurité Renforcée
- ✅ Mot de passe hashé avec bcrypt
- ✅ Protection contre les attaques brute force (5 tentatives max)
- ✅ Vérification du rôle admin
- ✅ Vérification du statut actif du compte
- ✅ Régénération de session après connexion

### 3. Configuration Requise

#### Variables d'Environnement (.env)

Ajoutez ces variables dans votre fichier `.env` :

```env
# Configuration Admin
ADMIN_EMAIL=admin@niangprogrammeur.com
ADMIN_PASSWORD=Admin@2025
ADMIN_NAME=Administrateur
```

**⚠️ IMPORTANT :** Changez le mot de passe par défaut en production !

#### Création de l'Utilisateur Admin

Exécutez le seeder pour créer l'utilisateur admin :

```bash
php artisan db:seed --class=AdminUserSeeder
```

Ou pour exécuter tous les seeders :

```bash
php artisan db:seed
```

Le seeder va :
- Créer l'utilisateur admin s'il n'existe pas
- Utiliser les credentials depuis `.env` ou valeurs par défaut
- Hasher automatiquement le mot de passe
- Définir le rôle `admin`
- Activer le compte

## 📝 Utilisation

### Connexion Admin

1. Accédez à `/admin/login`
2. Utilisez les credentials configurés dans `.env`
3. Le système vérifie automatiquement :
   - Que l'utilisateur existe
   - Que le mot de passe est correct
   - Que l'utilisateur a le rôle `admin`
   - Que le compte est actif

### Protection des Routes

Toutes les routes admin sont maintenant protégées par le middleware `admin` :

```php
Route::middleware(['admin'])->group(function () {
    // Routes admin protégées
});
```

Le middleware vérifie automatiquement :
- Authentification
- Rôle admin
- Statut actif

### Modification du Mot de Passe

L'admin peut modifier son mot de passe depuis `/admin/profile` :
- Vérification du mot de passe actuel
- Hashage automatique du nouveau mot de passe
- Mise à jour sécurisée

## 🔒 Sécurité

### Rate Limiting
- Maximum 5 tentatives de connexion par email/IP
- Blocage de 5 minutes après échec
- Réinitialisation automatique après succès

### Logging
Toutes les tentatives de connexion sont loggées :
- Connexions réussies (avec user_id, email, IP)
- Tentatives échouées (avec email, IP)
- Déconnexions

### Session Security
- Régénération de session après connexion
- Invalidation complète lors de la déconnexion
- Protection CSRF activée

## 🚀 Migration depuis l'Ancien Système

Si vous aviez déjà un compte admin avec l'ancien système :

1. **Exécutez le seeder** pour créer le nouvel utilisateur :
   ```bash
   php artisan db:seed --class=AdminUserSeeder
   ```

2. **Vérifiez que l'utilisateur existe** :
   ```bash
   php artisan tinker
   >>> User::where('role', 'admin')->first();
   ```

3. **Testez la connexion** avec les nouveaux credentials

4. **Supprimez l'ancien code** (déjà fait automatiquement)

## ⚠️ Notes Importantes

1. **Changez le mot de passe par défaut** en production
2. **Ne commitez jamais** le fichier `.env`
3. **Utilisez des mots de passe forts** (min 12 caractères, mixte)
4. **Activez 2FA** si possible (amélioration future)
5. **Surveillez les logs** pour détecter les tentatives suspectes

## 📊 Améliorations Futures

- [ ] Two-Factor Authentication (2FA)
- [ ] Notifications email pour connexions suspectes
- [ ] Historique des connexions
- [ ] Gestion de plusieurs admins
- [ ] Permissions granulaires

## 🐛 Dépannage

### Problème : "Accès refusé"
- Vérifiez que l'utilisateur a le rôle `admin` : `$user->role === 'admin'`
- Vérifiez que le compte est actif : `$user->is_active === true`

### Problème : "Trop de tentatives"
- Attendez 5 minutes
- Ou réinitialisez le rate limiter : `php artisan tinker` puis `RateLimiter::clear('email|ip')`

### Problème : Seeder ne fonctionne pas
- Vérifiez que la table `users` existe : `php artisan migrate`
- Vérifiez les variables `.env`
- Vérifiez les logs : `storage/logs/laravel.log`

