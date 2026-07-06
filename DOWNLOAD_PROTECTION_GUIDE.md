# 🛡️ GUIDE: Protection contre les Téléchargements en Masse

## 📋 Résumé des Protections Implémentées

### 1. **Middleware DownloadRateLimiting**
Limite stricte sur les téléchargements pour empêcher le scraping en masse:

- **10 téléchargements/heure** par utilisateur/IP
- **50 téléchargements/jour** par utilisateur/IP
- **3 téléchargements/heure** du même fichier
- **Détection bot**: 5 téléchargements en <60 secondes = blocage 24h

### 2. **Table de Logs (download_logs)**
Enregistre tous les téléchargements avec:
- IP address
- User-Agent
- Hash identifiant unique
- Raison du blocage (si applicable)
- Timestamp

### 3. **Détection de Patterns Bot**
Détecte automatiquement:
- Burst rapides (multiple downloads en secondes)
- Même fichier téléchargé 3+ fois par heure
- Trop de téléchargements différents en peu de temps

## 🚀 Installation

### Étape 1: Appliquer la Migration
```bash
php artisan migrate
```

Cela crée la table `download_logs` pour tracker tous les téléchargements.

### Étape 2: Vérifier les Routes
Les routes suivantes sont maintenant protégées:

✅ `/epreuves/{id}/telecharger` - Téléchargement épreuves
✅ `/epreuves/corrige/telecharger/{token}` - Corrigés payants
✅ `/epreuves/pay/telecharger/{token}` - Épreuves payantes
✅ `/documents/download/{token}` - Documents
✅ `/documents/{id}/download-free` - Documents gratuits
✅ `/dashboard/certificates/{id}/download` - Certificats

### Étape 3: Effacer le Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:clear
```

## 📊 Monitoring

### Afficher les Activités Suspectes (dernières 24h)
```bash
php artisan downloads:check-suspicious
```

### Afficher pour une période différente
```bash
php artisan downloads:check-suspicious --hours=48
```

## 🔧 Configuration

Pour ajuster les limites, modifiez dans:
`app/Http/Middleware/DownloadRateLimiting.php`

```php
private const DOWNLOAD_LIMITS = [
    'per_hour' => 10,           // Modifier ce nombre
    'per_day' => 50,
    'per_file_hour' => 3,
    'burst_threshold' => 5,
    'burst_window' => 60,
];
```

## 📈 Réponses HTTP

### ✅ Téléchargement Autorisé
Status: `200 OK`
Headers:
```
X-Download-Limit: 10
X-Download-Remaining: 9
X-Download-Reset: 3600
```

### ❌ Limite Horaire Dépassée
Status: `429 Too Many Requests`
Response:
```json
{
  "message": "Limite de téléchargement horaire dépassée (10/heure). Réessayez plus tard.",
  "error": "download_limit_exceeded",
  "status": 429
}
```

### ⚠️ Activité Bot Détectée
Status: `429 Too Many Requests`
Blocage automatique pour **24 heures**

Response:
```json
{
  "message": "Activité suspecte détectée. Réessayez plus tard.",
  "error": "download_limit_exceeded",
  "status": 429
}
```

## 📝 Logging

Tous les événements de sécurité sont loggés dans:
- **File**: `storage/logs/laravel.log`
- **Table**: `security_audits` (si existante)

Exemple de log:
```
[2026-06-18 19:30:45] local.WARNING: Download security: suspicious activity
  identifier: "hash_identifiant_client"
  reason: "download_limit_hour_exceeded"
  ip: "192.168.1.100"
  user_id: 5
  file_id: 123
```

## 🔐 Sécurité Supplémentaire

### Vérification de l'Authentification
Le middleware détecte si l'utilisateur est authentifié:
- ✅ Utilisateurs authentifiés: limite par user_id + IP
- ✅ Visiteurs anonymes: limite par IP uniquement

### Proxy-Aware
Fonctionne correctement avec:
- Cloudflare (header `CF-Connecting-IP`)
- Reverse proxy (header `X-Forwarded-For`)

### Stockage Cache
Utilise Laravel Cache (configurable dans `.env`):
- **File**: Stockage local (parfait pour serveur unique)
- **Redis**: Recommandé pour multi-serveur
- **Memcached**: Alternative possible

## 🧪 Tests

### Tester manuellement
```bash
# Simulation 10 téléchargements rapides
for i in {1..12}; do
  curl -I https://www.niangprogrammeur.com/epreuves/1/telecharger
  echo "Request $i"
done

# Dès la 11e requête = réponse 429
```

## 📊 Statistiques à Suivre

### Requête de Monitoring
```bash
# Activités bloquées (dernières 24h)
SELECT COUNT(*) as blocked_count, SUM(is_suspicious) as suspicious
FROM download_logs
WHERE blocked = 1 OR is_suspicious = 1
AND created_at >= NOW() - INTERVAL 1 DAY;
```

### Top 10 IPs Suspectes
```bash
SELECT identifier_hash, COUNT(*) as count, MAX(created_at) as last_attempt
FROM download_logs
WHERE is_suspicious = 1
GROUP BY identifier_hash
ORDER BY count DESC
LIMIT 10;
```

## 🚨 Troubleshooting

### Erreur: "Limite de téléchargement horaire dépassée"
**Causes possibles:**
1. Utilisateur a téléchargé 10 fichiers en 1h
2. Même IP + plusieurs utilisateurs
3. Bot/scraper détecté

**Solution:**
- Attendre 1 heure
- Utiliser VPN si limites partagées
- Contact support si limitation injustifiée

### Erreur: "Activité suspecte détectée"
**Causes possibles:**
1. 5+ téléchargements en <60 secondes
2. Même fichier téléchargé 3+ fois/heure
3. Pattern bot détecté

**Solution:**
- Attendre 24 heures
- Contacter l'administrateur

## 📞 Support Admin

### Débloquer un utilisateur (si faux positif)
```bash
# Dans tinker:
php artisan tinker
> Cache::forget('dl_blocked:' . hash('sha256', 'ip|user_id'))
```

### Exporter les logs de téléchargement
```bash
php artisan downloads:export-logs --days=30
```

## ✅ Checklist Post-Implémentation

- [x] Migration appliquée
- [x] Routes mises à jour avec middleware
- [x] Configuration cachée
- [x] Tests manuels effectués
- [x] Logs vérifiés
- [x] Monitoring en place

---

**Statut**: ✅ Production Ready
**Dernière mise à jour**: 2026-06-18
**Responsable**: Copilot Assistant
