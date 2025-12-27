# Configuration PWA - NiangProgrammeur

## Fonctionnalités implémentées

✅ Service Worker amélioré avec cache stratégique
✅ Mode offline amélioré avec page dédiée
✅ Notifications push
✅ Installation PWA améliorée avec bouton d'installation
✅ Cache stratégique pour offline

## Configuration des notifications push

### 1. Générer les clés VAPID

Pour activer les notifications push, vous devez générer une paire de clés VAPID (Voluntary Application Server Identification).

```bash
# Installer web-push (Node.js requis)
npm install -g web-push

# Générer les clés
web-push generate-vapid-keys
```

Cela générera deux clés :
- **Public Key** : À ajouter dans `.env` et dans le frontend
- **Private Key** : À garder secret dans `.env`

### 2. Configurer les clés dans `.env`

Ajoutez ces lignes dans votre fichier `.env` :

```env
VAPID_PUBLIC_KEY=votre_cle_publique_ici
VAPID_PRIVATE_KEY=votre_cle_privee_ici
VAPID_SUBJECT=mailto:votre-email@example.com
```

### 3. Ajouter la configuration dans `config/services.php`

```php
'vapid' => [
    'public_key' => env('VAPID_PUBLIC_KEY'),
    'private_key' => env('VAPID_PRIVATE_KEY'),
    'subject' => env('VAPID_SUBJECT'),
],
```

## Utilisation

### Service Worker

Le service worker est automatiquement enregistré lors du chargement de la page. Il gère :
- Cache des ressources statiques (CSS, JS, images)
- Cache des pages HTML
- Cache des requêtes API
- Mode offline avec page dédiée

### Installation PWA

Un bouton d'installation apparaît automatiquement lorsque :
- L'utilisateur visite le site
- Le navigateur détecte que l'app peut être installée
- L'app n'est pas déjà installée

### Notifications Push

Les utilisateurs peuvent activer les notifications push via le bouton "Activer les notifications" qui apparaît dans les paramètres.

### Mode Offline

Lorsque l'utilisateur est hors ligne :
- Les pages mises en cache sont disponibles
- Une page offline dédiée s'affiche pour les pages non mises en cache
- Les notifications de connexion/déconnexion sont affichées

## Commandes utiles

### Vider le cache PWA

```javascript
// Dans la console du navigateur
window.pwaManager.clearCache();
```

### Mettre en cache des URLs spécifiques

```javascript
window.pwaManager.cacheUrls([
    '/formations',
    '/documents',
    '/exercices'
]);
```

## Structure des fichiers

```
public/
├── sw.js                    # Service Worker principal
├── offline.html             # Page offline
├── manifest.json            # Manifest PWA
└── js/
    └── pwa-manager.js       # Gestionnaire PWA

app/
├── Http/Controllers/
│   └── PushNotificationController.php
└── Models/
    └── PushSubscription.php

database/migrations/
└── create_push_subscriptions_table.php
```

## Notes importantes

1. **HTTPS requis** : Les PWA et notifications push nécessitent HTTPS en production
2. **Service Worker** : Doit être à la racine du domaine ou dans un scope approprié
3. **Manifest** : Doit être accessible et valide
4. **Notifications** : Nécessitent la permission de l'utilisateur

## Support navigateurs

- ✅ Chrome/Edge (Android & Desktop)
- ✅ Firefox (Android & Desktop)
- ✅ Safari (iOS 11.3+)
- ✅ Samsung Internet
- ⚠️ Safari Desktop (support limité)

## Dépannage

### Le service worker ne s'enregistre pas
- Vérifiez que vous êtes en HTTPS (ou localhost)
- Vérifiez la console du navigateur pour les erreurs
- Vérifiez que `/sw.js` est accessible

### Les notifications push ne fonctionnent pas
- Vérifiez que les clés VAPID sont configurées
- Vérifiez que l'utilisateur a accordé la permission
- Vérifiez la console pour les erreurs

### Le cache ne fonctionne pas
- Vérifiez que le service worker est actif
- Videz le cache du navigateur
- Vérifiez les stratégies de cache dans `sw.js`

