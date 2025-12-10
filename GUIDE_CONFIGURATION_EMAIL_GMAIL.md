# Guide Détaillé : Configuration Email SMTP Gmail pour Laravel

## 📋 Table des matières
1. [Prérequis](#prérequis)
2. [Étape 1 : Créer un mot de passe d'application Gmail](#étape-1--créer-un-mot-de-passe-dapplication-gmail)
3. [Étape 2 : Configuration du fichier .env](#étape-2--configuration-du-fichier-env)
4. [Étape 3 : Vérification de config/mail.php](#étape-3--vérification-de-configmailphp)
5. [Étape 4 : Test de la configuration](#étape-4--test-de-la-configuration)
6. [Étape 5 : Configuration pour l'envoi d'emails](#étape-5--configuration-pour-lenvoi-demails)
7. [Dépannage](#dépannage)
8. [Sécurité et bonnes pratiques](#sécurité-et-bonnes-pratiques)

---

## Prérequis

- Un compte Gmail actif
- Laravel installé et configuré
- Accès au fichier `.env` de votre projet
- Accès à la console Google (pour créer un mot de passe d'application)

---

## Étape 1 : Créer un mot de passe d'application Gmail

### 1.1 Activer la validation en deux étapes

**⚠️ IMPORTANT :** Vous devez d'abord activer la validation en deux étapes sur votre compte Gmail pour pouvoir créer un mot de passe d'application.

1. Allez sur [myaccount.google.com](https://myaccount.google.com)
2. Cliquez sur **Sécurité** dans le menu de gauche
3. Dans la section **Connexion à Google**, cliquez sur **Validation en deux étapes**
4. Suivez les instructions pour activer la validation en deux étapes

### 1.2 Créer un mot de passe d'application

1. Une fois la validation en deux étapes activée, retournez sur la page **Sécurité**
2. Dans la section **Connexion à Google**, vous verrez maintenant **Mots de passe des applications**
3. Cliquez sur **Mots de passe des applications**
4. Sélectionnez **Application** : `Autre (nom personnalisé)`
5. Entrez un nom descriptif, par exemple : `Laravel Formation App`
6. Cliquez sur **Générer**
7. **⚠️ IMPORTANT :** Copiez immédiatement le mot de passe généré (16 caractères sans espaces). Vous ne pourrez plus le voir après !

**Exemple de mot de passe généré :** `abcd efgh ijkl mnop` (vous devez l'utiliser sans espaces : `abcdefghijklmnop`)

---

## Étape 2 : Configuration du fichier .env

Ouvrez le fichier `.env` à la racine de votre projet Laravel et modifiez les paramètres suivants :

```env
# Configuration Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=niangprogrammeur@gmail.com
MAIL_PASSWORD=hzhvryibaojkjfyp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Explication des paramètres :

- **MAIL_MAILER** : `smtp` pour utiliser le protocole SMTP
- **MAIL_HOST** : `smtp.gmail.com` (serveur SMTP de Gmail)
- **MAIL_PORT** : 
  - `587` pour TLS (recommandé)
  - `465` pour SSL (alternative)
- **MAIL_USERNAME** : Votre adresse email Gmail complète (ex: `monemail@gmail.com`)
- **MAIL_PASSWORD** : Le mot de passe d'application de 16 caractères généré à l'étape 1 (sans espaces)
- **MAIL_ENCRYPTION** : 
  - `tls` pour le port 587 (recommandé)
  - `ssl` pour le port 465
- **MAIL_FROM_ADDRESS** : L'adresse email qui apparaîtra comme expéditeur
- **MAIL_FROM_NAME** : Le nom qui apparaîtra comme expéditeur (utilise le nom de l'application par défaut)

### Exemple complet :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=formation.laravel@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=formation.laravel@gmail.com
MAIL_FROM_NAME="NiangProgrammeur"
```

---

## Étape 3 : Vérification de config/mail.php

Vérifiez que le fichier `config/mail.php` est correctement configuré. Par défaut, Laravel lit les valeurs depuis le fichier `.env`, mais vous pouvez vérifier la structure :

```php
'mailers' => [
    'smtp' => [
        'transport' => 'smtp',
        'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
        'port' => env('MAIL_PORT', 2525),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'timeout' => null,
        'local_domain' => env('MAIL_EHLO_DOMAIN'),
    ],
],

'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
    'name' => env('MAIL_FROM_NAME', 'Example'),
],
```

**Note :** Normalement, vous n'avez pas besoin de modifier ce fichier si vous utilisez le `.env`.

---

## Étape 4 : Test de la configuration

### 4.1 Vider le cache de configuration

Après avoir modifié le fichier `.env`, videz le cache de configuration Laravel :

```bash
php artisan config:clear
php artisan cache:clear
```

### 4.2 Tester avec Tinker

Ouvrez la console Laravel Tinker :

```bash
php artisan tinker
```

Puis exécutez cette commande pour envoyer un email de test :

```php
Mail::raw('Ceci est un email de test depuis Laravel avec Gmail SMTP', function ($message) {
    $message->to('votre-email-de-test@gmail.com')
            ->subject('Test Email Laravel Gmail');
});
```

Si vous recevez l'email, la configuration est correcte ! ✅

### 4.3 Tester avec une route de test (développement uniquement)

Créez une route de test temporaire dans `routes/web.php` :

```php
Route::get('/test-email', function () {
    try {
        Mail::raw('Ceci est un email de test depuis Laravel avec Gmail SMTP', function ($message) {
            $message->to('votre-email-de-test@gmail.com')
                    ->subject('Test Email Laravel Gmail');
        });
        
        return 'Email envoyé avec succès !';
    } catch (\Exception $e) {
        return 'Erreur : ' . $e->getMessage();
    }
});
```

Visitez `http://127.0.0.1:8000/test-email` dans votre navigateur.

**⚠️ IMPORTANT :** Supprimez cette route après les tests en production !

---

## Étape 5 : Configuration pour l'envoi d'emails

### 5.1 Créer une classe Mailable

Créez une classe Mailable pour vos emails :

```bash
php artisan make:mail TestMail
```

Modifiez le fichier `app/Mail/TestMail.php` :

```php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Test Email depuis Laravel')
                    ->view('emails.test')
                    ->with(['data' => $this->data]);
    }
}
```

### 5.2 Créer la vue email

Créez le fichier `resources/views/emails/test.blade.php` :

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Email</title>
</head>
<body>
    <h1>Bonjour !</h1>
    <p>Ceci est un email de test depuis Laravel avec Gmail SMTP.</p>
    <p>Données reçues : {{ $data }}</p>
    <p>Date : {{ now()->format('d/m/Y H:i:s') }}</p>
</body>
</html>
```

### 5.3 Envoyer l'email

Dans votre contrôleur ou route :

```php
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

Mail::to('destinataire@gmail.com')->send(new TestMail('Données de test'));
```

---

## Dépannage

### Problème 1 : "Authentication failed"

**Causes possibles :**
- Le mot de passe d'application est incorrect
- La validation en deux étapes n'est pas activée
- Le mot de passe contient des espaces

**Solutions :**
1. Vérifiez que vous utilisez le mot de passe d'application (16 caractères), pas votre mot de passe Gmail
2. Vérifiez qu'il n'y a pas d'espaces dans le mot de passe dans le `.env`
3. Recréez un nouveau mot de passe d'application
4. Videz le cache : `php artisan config:clear`

### Problème 2 : "Connection timeout"

**Causes possibles :**
- Problème de port ou d'encryption
- Firewall bloquant la connexion

**Solutions :**
1. Essayez le port `465` avec `ssl` au lieu de `587` avec `tls`
2. Vérifiez que votre firewall/autorouteur permet les connexions sortantes sur les ports 587 ou 465

### Problème 3 : "Could not authenticate"

**Causes possibles :**
- Compte Gmail avec "Accès moins sécurisé des applications" désactivé
- Mot de passe d'application expiré ou révoqué

**Solutions :**
1. Vérifiez que la validation en deux étapes est activée
2. Recréez un nouveau mot de passe d'application
3. Vérifiez que le compte Gmail n'est pas verrouillé

### Problème 4 : Emails non reçus

**Causes possibles :**
- Email dans les spams
- Limite de quota Gmail atteinte
- Adresse email incorrecte

**Solutions :**
1. Vérifiez le dossier spam/courrier indésirable
2. Gmail limite à 500 emails/jour pour les comptes gratuits
3. Vérifiez l'adresse email du destinataire
4. Utilisez `Mail::failures()` pour voir les échecs

### Problème 5 : "Swift_TransportException"

**Solutions :**
1. Vérifiez que toutes les variables dans `.env` sont correctes
2. Videz le cache : `php artisan config:clear`
3. Vérifiez que les guillemets ne sont pas nécessaires dans `.env` (sauf pour les valeurs avec espaces)

---

## Sécurité et bonnes pratiques

### 1. Ne jamais commiter le fichier .env

Assurez-vous que `.env` est dans `.gitignore` :

```gitignore
.env
.env.backup
.env.production
```

### 2. Utiliser des variables d'environnement

Ne jamais hardcoder les identifiants dans le code. Toujours utiliser `env()` ou les fichiers de configuration.

### 3. Limites Gmail

- **Comptes gratuits** : 500 emails/jour
- **Google Workspace** : 2000 emails/jour
- Pour des volumes plus importants, utilisez un service dédié (SendGrid, Mailgun, etc.)

### 4. Utiliser les queues pour les emails

Pour améliorer les performances, utilisez les queues Laravel :

```php
// Au lieu de
Mail::to($user)->send(new WelcomeMail($user));

// Utilisez
Mail::to($user)->queue(new WelcomeMail($user));
```

Configurez la queue dans `.env` :

```env
QUEUE_CONNECTION=database
```

Puis exécutez le worker :

```bash
php artisan queue:work
```

### 5. Logs des emails en développement

En développement, vous pouvez logger les emails au lieu de les envoyer. Dans `config/mail.php` :

```php
'mailers' => [
    'log' => [
        'transport' => 'log',
        'channel' => env('MAIL_LOG_CHANNEL'),
    ],
],
```

Puis dans `.env` :

```env
MAIL_MAILER=log
```

Les emails seront enregistrés dans `storage/logs/laravel.log`.

---

## Configuration alternative : Port 465 avec SSL

Si le port 587 avec TLS ne fonctionne pas, essayez cette configuration :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-application
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Vérification finale

### Checklist de configuration

- [ ] Validation en deux étapes activée sur Gmail
- [ ] Mot de passe d'application créé et copié
- [ ] Fichier `.env` configuré avec toutes les variables
- [ ] Cache Laravel vidé (`php artisan config:clear`)
- [ ] Email de test envoyé et reçu
- [ ] Fichier `.env` ajouté à `.gitignore`

---

## Commandes utiles

```bash
# Vider le cache de configuration
php artisan config:clear

# Vider tout le cache
php artisan cache:clear

# Voir la configuration actuelle
php artisan config:show mail

# Tester l'envoi d'email
php artisan tinker
# Puis : Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));
```

---

## Support et ressources

- [Documentation Laravel Mail](https://laravel.com/docs/mail)
- [Documentation Gmail SMTP](https://support.google.com/mail/answer/7126229)
- [Créer un mot de passe d'application Google](https://support.google.com/accounts/answer/185833)

---

**Note :** Ce guide est spécifique à Gmail. Pour d'autres fournisseurs (Outlook, Yahoo, etc.), les paramètres SMTP seront différents.

