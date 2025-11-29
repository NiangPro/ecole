# 📧 Guide Complet - Configuration Email SMTP

## 🎯 Introduction

Ce guide vous explique comment configurer l'envoi d'emails via SMTP dans votre application Laravel. Vous pouvez configurer l'email de deux manières :

1. **Via l'interface Admin** (Recommandé) - Configuration depuis `/admin/settings`
2. **Via le fichier `.env`** - Configuration manuelle

---

## 🚀 Méthode 1 : Configuration via l'Interface Admin (Recommandé)

### Étape 1 : Accéder à la configuration

1. Connectez-vous à votre interface admin : `http://votre-site.com/admin`
2. Allez dans **Paramètres** : `/admin/settings`
3. Faites défiler jusqu'à la section **"Configuration Email (SMTP)"**

### Étape 2 : Remplir les champs

Remplissez les champs suivants selon votre fournisseur d'email (voir les exemples ci-dessous).

### Étape 3 : Enregistrer

Cliquez sur **"Enregistrer les paramètres"** en bas de la page.

---

## 📋 Configuration par Fournisseur

### 🔵 Gmail (Google)

#### Prérequis
1. Avoir un compte Gmail
2. Activer la validation en 2 étapes sur votre compte Google
3. Générer un "Mot de passe d'application"

#### Générer un mot de passe d'application Gmail

1. Allez sur : https://myaccount.google.com/security
2. Activez la **Validation en 2 étapes** si ce n'est pas déjà fait
3. Allez dans **Mots de passe des applications**
4. Sélectionnez **Autre (nom personnalisé)** et entrez "Laravel App"
5. Cliquez sur **Générer**
6. **Copiez le mot de passe** (16 caractères) - vous ne pourrez plus le voir après !

#### Configuration dans l'interface Admin

```
Type de serveur mail: SMTP
Hôte SMTP: smtp.gmail.com
Port SMTP: 587
Chiffrement: TLS
Nom d'utilisateur: votre-email@gmail.com
Mot de passe: [Le mot de passe d'application de 16 caractères généré]
Adresse email expéditeur: votre-email@gmail.com
Nom de l'expéditeur: NiangProgrammeur
```

#### ⚠️ Important pour Gmail
- **NE PAS utiliser votre mot de passe Gmail normal**
- Utilisez **uniquement** un mot de passe d'application
- Le mot de passe d'application est différent de votre mot de passe Gmail

---

### 🟠 Outlook / Hotmail / Microsoft 365

#### Configuration dans l'interface Admin

```
Type de serveur mail: SMTP
Hôte SMTP: smtp-mail.outlook.com
Port SMTP: 587
Chiffrement: TLS
Nom d'utilisateur: votre-email@outlook.com (ou @hotmail.com, @live.com)
Mot de passe: [Votre mot de passe Outlook]
Adresse email expéditeur: votre-email@outlook.com
Nom de l'expéditeur: NiangProgrammeur
```

#### Alternative pour Microsoft 365 (Entreprise)

```
Hôte SMTP: smtp.office365.com
Port SMTP: 587
Chiffrement: TLS
```

---

### 🟢 Mailtrap (Pour les tests - GRATUIT)

Mailtrap est parfait pour tester l'envoi d'emails sans envoyer de vrais emails.

#### Créer un compte Mailtrap

1. Allez sur : https://mailtrap.io
2. Créez un compte gratuit
3. Créez une "Inbox" (boîte de réception de test)
4. Allez dans **SMTP Settings** > **Integrations** > **Laravel**

#### Configuration dans l'interface Admin

```
Type de serveur mail: SMTP
Hôte SMTP: smtp.mailtrap.io
Port SMTP: 2525
Chiffrement: TLS
Nom d'utilisateur: [Votre Username Mailtrap]
Mot de passe: [Votre Password Mailtrap]
Adresse email expéditeur: noreply@niangprogrammeur.com
Nom de l'expéditeur: NiangProgrammeur
```

#### Avantages de Mailtrap
- ✅ Gratuit jusqu'à 500 emails/mois
- ✅ Capture tous les emails envoyés
- ✅ Permet de tester sans envoyer de vrais emails
- ✅ Interface web pour voir les emails

---

### 🟣 Serveur Email Personnalisé (cPanel, OVH, etc.)

Si vous avez un hébergement web avec email (cPanel, OVH, O2Switch, etc.)

#### Configuration générale

```
Type de serveur mail: SMTP
Hôte SMTP: mail.votre-domaine.com (ou smtp.votre-domaine.com)
Port SMTP: 587 (ou 465)
Chiffrement: TLS (ou SSL pour le port 465)
Nom d'utilisateur: votre-email@votre-domaine.com
Mot de passe: [Votre mot de passe email]
Adresse email expéditeur: votre-email@votre-domaine.com
Nom de l'expéditeur: NiangProgrammeur
```

#### Exemples selon l'hébergeur

**OVH :**
```
Hôte SMTP: ssl0.ovh.net
Port SMTP: 587
Chiffrement: TLS
```

**O2Switch :**
```
Hôte SMTP: mail.o2switch.net
Port SMTP: 587
Chiffrement: TLS
```

**cPanel (général) :**
```
Hôte SMTP: mail.votre-domaine.com
Port SMTP: 587
Chiffrement: TLS
```

---

## 🔧 Méthode 2 : Configuration via le fichier `.env`

Si vous préférez configurer via le fichier `.env`, voici les variables à ajouter :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="NiangProgrammeur"
```

### ⚠️ Note importante

Si vous configurez via `.env` ET via l'interface admin :
- **L'interface admin a la priorité** si les champs sont remplis
- Si les champs de l'interface admin sont vides, les valeurs du `.env` sont utilisées

---

## ✅ Tester la Configuration

### Méthode 1 : Via l'interface Admin

1. Allez dans `/admin/jobs/articles`
2. Sélectionnez un article publié
3. Cliquez sur **"Envoyer par newsletter"**
4. Vérifiez les logs dans `storage/logs/laravel.log`

### Méthode 2 : Vérifier les logs

```bash
tail -f storage/logs/laravel.log
```

Recherchez les messages d'erreur ou de succès liés à l'envoi d'emails.

### Méthode 3 : Tester avec Mailtrap

Si vous utilisez Mailtrap, allez sur votre dashboard Mailtrap pour voir les emails capturés.

---

## 🐛 Dépannage - Problèmes Courants

### ❌ Erreur : "Connection refused" ou "Connection timeout"

**Causes possibles :**
- L'hôte SMTP est incorrect
- Le port est bloqué par le firewall
- Le serveur ne permet pas les connexions SMTP depuis votre IP

**Solutions :**
1. Vérifiez l'hôte SMTP (sans `http://` ou `https://`)
2. Essayez un autre port (587, 465, 2525)
3. Vérifiez que votre firewall/autorise les connexions sortantes sur le port SMTP
4. Contactez votre hébergeur si nécessaire

---

### ❌ Erreur : "Authentication failed" ou "Invalid credentials"

**Causes possibles :**
- Nom d'utilisateur ou mot de passe incorrect
- Pour Gmail : utilisation du mot de passe normal au lieu d'un mot de passe d'application
- Le compte email n'autorise pas les connexions SMTP

**Solutions :**
1. **Pour Gmail** : Utilisez un mot de passe d'application (voir section Gmail ci-dessus)
2. Vérifiez que le nom d'utilisateur est l'adresse email complète
3. Vérifiez que le mot de passe est correct (copier-coller pour éviter les erreurs)
4. Pour Gmail, activez "Accès aux applications moins sécurisées" (non recommandé) ou utilisez un mot de passe d'application

---

### ❌ Erreur : "Could not authenticate"

**Solutions :**
1. Vérifiez que le chiffrement correspond au port :
   - Port 587 → TLS
   - Port 465 → SSL
2. Vérifiez que le nom d'utilisateur est l'adresse email complète
3. Pour Gmail, utilisez un mot de passe d'application

---

### ❌ Les emails ne sont pas envoyés (pas d'erreur visible)

**Vérifications :**
1. Vérifiez les logs : `storage/logs/laravel.log`
2. Vérifiez que la configuration est bien enregistrée
3. Vérifiez que le mailer par défaut est bien "smtp"
4. Testez avec Mailtrap pour isoler le problème

---

### ❌ Erreur : "Swift_TransportException"

**Solutions :**
1. Vérifiez tous les paramètres SMTP
2. Vérifiez que le serveur SMTP est accessible
3. Vérifiez les logs pour plus de détails

---

## 📊 Tableau Récapitulatif des Ports et Chiffrements

| Fournisseur | Hôte SMTP | Port | Chiffrement |
|------------|-----------|------|-------------|
| Gmail | smtp.gmail.com | 587 | TLS |
| Gmail (SSL) | smtp.gmail.com | 465 | SSL |
| Outlook | smtp-mail.outlook.com | 587 | TLS |
| Microsoft 365 | smtp.office365.com | 587 | TLS |
| Mailtrap | smtp.mailtrap.io | 2525 | TLS |
| OVH | ssl0.ovh.net | 587 | TLS |
| cPanel | mail.votre-domaine.com | 587 | TLS |

---

## 🔒 Sécurité

### Bonnes Pratiques

1. **Ne partagez jamais vos identifiants SMTP**
2. **Utilisez des mots de passe d'application** pour Gmail
3. **Changez régulièrement les mots de passe**
4. **Utilisez TLS/SSL** pour chiffrer les connexions
5. **Limitez l'accès** à la page de configuration admin

### Stockage des Mots de Passe

- Les mots de passe sont stockés dans la base de données (table `site_settings`)
- Ils sont chiffrés dans la base de données
- Seuls les administrateurs peuvent accéder à cette configuration

---

## 📝 Exemples de Configuration Complète

### Exemple 1 : Gmail pour Production

```
Type de serveur mail: SMTP
Hôte SMTP: smtp.gmail.com
Port SMTP: 587
Chiffrement: TLS
Nom d'utilisateur: contact@niangprogrammeur.com
Mot de passe: xxxx xxxx xxxx xxxx (mot de passe d'application)
Adresse email expéditeur: contact@niangprogrammeur.com
Nom de l'expéditeur: NiangProgrammeur
```

### Exemple 2 : Mailtrap pour Développement

```
Type de serveur mail: SMTP
Hôte SMTP: smtp.mailtrap.io
Port SMTP: 2525
Chiffrement: TLS
Nom d'utilisateur: abc123def456
Mot de passe: xyz789uvw012
Adresse email expéditeur: noreply@niangprogrammeur.com
Nom de l'expéditeur: NiangProgrammeur (Test)
```

### Exemple 3 : Serveur OVH

```
Type de serveur mail: SMTP
Hôte SMTP: ssl0.ovh.net
Port SMTP: 587
Chiffrement: TLS
Nom d'utilisateur: contact@niangprogrammeur.com
Mot de passe: VotreMotDePasseOVH
Adresse email expéditeur: contact@niangprogrammeur.com
Nom de l'expéditeur: NiangProgrammeur
```

---

## 🎓 Conseils Pro

### Pour la Production

1. **Utilisez un service d'email professionnel** (SendGrid, Mailgun, Amazon SES)
2. **Configurez SPF et DKIM** pour améliorer la délivrabilité
3. **Surveillez les taux de rebond** et les plaintes
4. **Utilisez un sous-domaine** pour les emails (ex: `mail.niangprogrammeur.com`)

### Pour le Développement

1. **Utilisez Mailtrap** pour tester sans envoyer de vrais emails
2. **Vérifiez les logs** régulièrement
3. **Testez avec différents fournisseurs** d'email

---

## 📞 Support

Si vous rencontrez des problèmes :

1. Vérifiez les logs : `storage/logs/laravel.log`
2. Testez avec Mailtrap pour isoler le problème
3. Vérifiez la documentation de votre fournisseur d'email
4. Contactez votre hébergeur si le problème persiste

---

## ✅ Checklist de Configuration

Avant de considérer la configuration terminée, vérifiez :

- [ ] Tous les champs sont remplis correctement
- [ ] Le port et le chiffrement correspondent
- [ ] Le nom d'utilisateur est l'adresse email complète
- [ ] Le mot de passe est correct (mot de passe d'application pour Gmail)
- [ ] Les paramètres sont enregistrés
- [ ] Un test d'envoi a été effectué avec succès
- [ ] Les logs ne montrent pas d'erreurs

---

**Dernière mise à jour :** Novembre 2025

