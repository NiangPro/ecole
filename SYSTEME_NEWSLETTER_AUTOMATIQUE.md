# 📧 Système d'Envoi Manuel de Newsletter

## 📋 Description

Ce système permet d'envoyer manuellement un email à tous les abonnés actifs de la newsletter lorsqu'un article est publié, via des boutons dans l'interface admin.

## 🚀 Fonctionnement

### Envoi manuel

L'envoi se fait manuellement via des boutons dans l'interface admin :

1. **Liste des articles** (`/admin/jobs/articles`) : Bouton "Envoyer par newsletter" pour chaque article publié
2. **Détails d'un article** (`/admin/jobs/articles/{id}`) : Bouton "Envoyer par newsletter" en haut de la page

### Composants du système

#### 1. **Mailable** (`app/Mail/NewsletterArticleMail.php`)
- Classe responsable de la création de l'email
- Génère le sujet et le contenu de l'email
- Inclut le lien de désinscription

#### 2. **Job** (`app/Jobs/SendNewsletterArticleJob.php`)
- Traite l'envoi des emails en arrière-plan
- Envoie un email à chaque abonné actif
- Gère les erreurs et les logs
- Ajoute un délai entre chaque envoi pour éviter la surcharge

#### 3. **Template Email** (`resources/views/emails/newsletter/article.blade.php`)
- Design responsive et moderne
- Affiche l'image de couverture, le titre, l'extrait
- Bouton "Lire l'article complet"
- Lien de désinscription

#### 4. **Modèle JobArticle** (`app/Models/JobArticle.php`)
- Événements `created` et `updated` qui déclenchent l'envoi
- Vérifie que l'article est publié avant d'envoyer

## ⚙️ Configuration

### 1. Configuration Email

Assurez-vous que votre fichier `.env` contient les paramètres d'email :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@niangprogrammeur.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Configuration Queue

Le système utilise les queues Laravel pour l'envoi en arrière-plan.

**Option 1 : Queue synchrone (pour les tests)**
```env
QUEUE_CONNECTION=sync
```

**Option 2 : Queue database (recommandé pour la production)**
```env
QUEUE_CONNECTION=database
```

Puis exécutez le worker :
```bash
php artisan queue:work
```

**Option 3 : Queue Redis (pour les gros volumes)**
```env
QUEUE_CONNECTION=redis
```

## 📝 Utilisation

### Envoi manuel de la newsletter

**Méthode 1 : Depuis la liste des articles**
1. Allez sur `/admin/jobs/articles`
2. Trouvez l'article publié que vous souhaitez envoyer
3. Cliquez sur le bouton vert avec l'icône d'avion (📧) dans la colonne Actions
4. Confirmez l'envoi
5. L'email sera envoyé à tous les abonnés actifs

**Méthode 2 : Depuis les détails d'un article**
1. Allez sur `/admin/jobs/articles/{id}`
2. Cliquez sur le bouton "Envoyer par newsletter" en haut de la page
3. Confirmez l'envoi
4. L'email sera envoyé à tous les abonnés actifs

**Note** : Seuls les articles avec le statut "Publié" peuvent être envoyés par newsletter.

### Vérification des envois

Les logs sont enregistrés dans `storage/logs/laravel.log` :
- Succès : `Newsletter envoyée avec succès à X abonné(s)`
- Erreurs : `Erreur lors de l'envoi de l'email à [email]`

## 🔧 Personnalisation

### Modifier le template email

Éditez le fichier `resources/views/emails/newsletter/article.blade.php`

### Modifier le sujet de l'email

Éditez la méthode `build()` dans `app/Mail/NewsletterArticleMail.php` :
```php
return $this->subject('📰 Nouvel article : ' . $this->article->title)
```

### Modifier le délai entre les envois

Dans `app/Jobs/SendNewsletterArticleJob.php`, modifiez :
```php
usleep(100000); // 0.1 seconde (100000 microsecondes)
```

## ⚠️ Notes importantes

1. **Performance** : Pour un grand nombre d'abonnés, utilisez une queue (database ou redis)
2. **Limites SMTP** : Vérifiez les limites de votre fournisseur SMTP
3. **Rate Limiting** : Le délai entre chaque envoi évite la surcharge
4. **Erreurs** : Les erreurs sont loggées mais n'interrompent pas l'envoi aux autres abonnés

## 🐛 Dépannage

### Les emails ne sont pas envoyés

1. Vérifiez la configuration email dans `.env`
2. Vérifiez que le worker de queue tourne : `php artisan queue:work`
3. Consultez les logs : `storage/logs/laravel.log`
4. Testez l'envoi manuel : `php artisan tinker` puis `Mail::to('test@example.com')->send(new \App\Mail\NewsletterArticleMail($article, 'test@example.com'));`

### Les emails sont envoyés mais pas reçus

1. Vérifiez le dossier spam
2. Vérifiez les logs SMTP
3. Testez avec un service comme Mailtrap

## 📊 Statistiques

Pour voir combien d'abonnés recevront l'email :
- Consultez `/admin/newsletter`
- Filtrez par "Actifs"

