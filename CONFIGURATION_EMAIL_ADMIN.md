# 📧 Configuration Email depuis l'Interface Admin

## 📋 Description

Ce système permet de configurer tous les paramètres d'email directement depuis l'interface admin, sans avoir à modifier le fichier `.env`.

## 🚀 Accès à la configuration

1. Connectez-vous à l'interface admin
2. Allez dans **Paramètres** (`/admin/settings`)
3. Faites défiler jusqu'à la section **"Configuration Email (SMTP)"**

## ⚙️ Paramètres configurables

### 1. Type de serveur mail
- **SMTP** : Serveur SMTP standard (recommandé)
- **Sendmail** : Utilise sendmail du serveur
- **Mailgun** : Service Mailgun
- **Amazon SES** : Amazon Simple Email Service
- **Utiliser .env** : Utilise les valeurs du fichier .env si vide

### 2. Hôte SMTP
- Exemples : `smtp.gmail.com`, `smtp.mailtrap.io`, `mail.niangprogrammeur.com`

### 3. Port SMTP
- **587** : Port standard avec TLS (recommandé)
- **465** : Port avec SSL
- **25** : Port standard (souvent bloqué)
- **2525** : Port alternatif (Mailtrap, etc.)

### 4. Chiffrement
- **TLS** : Transport Layer Security (recommandé pour le port 587)
- **SSL** : Secure Sockets Layer (pour le port 465)
- **Aucun** : Pas de chiffrement (non recommandé)

### 5. Nom d'utilisateur SMTP
- Votre adresse email ou nom d'utilisateur SMTP

### 6. Mot de passe SMTP
- Mot de passe de votre compte email ou mot de passe d'application
- **Note** : Le mot de passe est stocké de manière sécurisée dans la base de données

### 7. Adresse email expéditeur
- L'adresse qui apparaîtra comme expéditeur dans les emails
- Exemple : `noreply@niangprogrammeur.com`

### 8. Nom de l'expéditeur
- Le nom qui apparaîtra comme expéditeur
- Exemple : `NiangProgrammeur`

## 🔄 Priorité de configuration

1. **Si les champs sont remplis** : Les valeurs de la base de données sont utilisées
2. **Si les champs sont vides** : Les valeurs du fichier `.env` sont utilisées

## 📝 Exemples de configuration

### Gmail
```
Type de serveur mail: SMTP
Hôte SMTP: smtp.gmail.com
Port SMTP: 587
Chiffrement: TLS
Nom d'utilisateur: votre-email@gmail.com
Mot de passe: [Mot de passe d'application Gmail]
Adresse email expéditeur: votre-email@gmail.com
Nom de l'expéditeur: NiangProgrammeur
```

### Mailtrap (pour les tests)
```
Type de serveur mail: SMTP
Hôte SMTP: smtp.mailtrap.io
Port SMTP: 2525
Chiffrement: TLS
Nom d'utilisateur: [Votre username Mailtrap]
Mot de passe: [Votre password Mailtrap]
Adresse email expéditeur: noreply@niangprogrammeur.com
Nom de l'expéditeur: NiangProgrammeur
```

### Serveur personnalisé
```
Type de serveur mail: SMTP
Hôte SMTP: mail.niangprogrammeur.com
Port SMTP: 587
Chiffrement: TLS
Nom d'utilisateur: noreply@niangprogrammeur.com
Mot de passe: [Votre mot de passe]
Adresse email expéditeur: noreply@niangprogrammeur.com
Nom de l'expéditeur: NiangProgrammeur
```

## ✅ Test de la configuration

1. Enregistrez les paramètres
2. Allez dans `/admin/jobs/articles`
3. Sélectionnez un article publié
4. Cliquez sur le bouton "Envoyer par newsletter"
5. Vérifiez les logs dans `storage/logs/laravel.log` pour voir si l'envoi a réussi

## 🔒 Sécurité

- Les mots de passe sont stockés dans la base de données
- L'accès à cette page est protégé par le middleware admin
- Les valeurs sensibles ne sont pas affichées dans les logs

## 🐛 Dépannage

### Les emails ne sont pas envoyés

1. Vérifiez que tous les champs sont correctement remplis
2. Vérifiez les logs : `storage/logs/laravel.log`
3. Testez avec un service comme Mailtrap
4. Vérifiez que le port n'est pas bloqué par le firewall
5. Pour Gmail, utilisez un "Mot de passe d'application" et non votre mot de passe principal

### Erreur "Connection refused"

- Vérifiez que l'hôte SMTP est correct
- Vérifiez que le port est correct
- Vérifiez que le serveur autorise les connexions depuis votre IP

### Erreur "Authentication failed"

- Vérifiez le nom d'utilisateur et le mot de passe
- Pour Gmail, utilisez un mot de passe d'application
- Vérifiez que le compte email autorise les connexions SMTP

## 📊 Migration

La migration a été exécutée automatiquement. Les colonnes suivantes ont été ajoutées à la table `site_settings` :
- `mail_mailer`
- `mail_host`
- `mail_port`
- `mail_username`
- `mail_password`
- `mail_encryption`
- `mail_from_address`
- `mail_from_name`

## 🔄 Mise à jour de la configuration

La configuration est automatiquement rechargée :
- Au démarrage de l'application (via `AppServiceProvider`)
- Après chaque mise à jour des paramètres (via `AdminController`)
- Avant chaque envoi d'email (via `SendNewsletterArticleJob`)

