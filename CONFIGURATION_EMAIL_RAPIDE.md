# ⚡ Configuration Email - Référence Rapide

## 🎯 Configuration Rapide par Fournisseur

### Gmail
```
Hôte: smtp.gmail.com
Port: 587
Chiffrement: TLS
Username: votre-email@gmail.com
Password: [Mot de passe d'application - 16 caractères]
```

**⚠️ Important :** Utilisez un **mot de passe d'application**, pas votre mot de passe Gmail normal.
👉 Générer : https://myaccount.google.com/apppasswords

---

### Outlook / Hotmail
```
Hôte: smtp-mail.outlook.com
Port: 587
Chiffrement: TLS
Username: votre-email@outlook.com
Password: [Votre mot de passe Outlook]
```

---

### Mailtrap (Tests)
```
Hôte: smtp.mailtrap.io
Port: 2525
Chiffrement: TLS
Username: [Votre username Mailtrap]
Password: [Votre password Mailtrap]
```

👉 Créer un compte : https://mailtrap.io

---

### OVH
```
Hôte: ssl0.ovh.net
Port: 587
Chiffrement: TLS
Username: votre-email@votre-domaine.com
Password: [Votre mot de passe OVH]
```

---

### Serveur Personnalisé (cPanel)
```
Hôte: mail.votre-domaine.com
Port: 587
Chiffrement: TLS
Username: votre-email@votre-domaine.com
Password: [Votre mot de passe email]
```

---

## 📍 Où Configurer ?

1. **Interface Admin** : `/admin/settings` → Section "Configuration Email (SMTP)"
2. **Fichier .env** : Ajoutez les variables `MAIL_*`

---

## ✅ Test Rapide

1. Allez dans `/admin/jobs/articles`
2. Cliquez sur "Envoyer par newsletter" sur un article
3. Vérifiez les logs : `storage/logs/laravel.log`

---

## 🐛 Problèmes Fréquents

| Erreur | Solution |
|--------|----------|
| Authentication failed | Vérifiez le mot de passe (mot de passe d'application pour Gmail) |
| Connection refused | Vérifiez l'hôte et le port |
| Timeout | Vérifiez le firewall/autorisez le port SMTP |

---

**📖 Guide Complet :** Voir `GUIDE_CONFIGURATION_EMAIL_SMTP.md`

