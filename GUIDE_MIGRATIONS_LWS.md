# Guide : Exécuter les migrations sur LWS

## Problème résolu
Une migration a été créée pour créer la table `advertisements` qui manquait sur le serveur.

## Fichier de migration créé
- `database/migrations/2025_11_17_204900_create_advertisements_table.php`

## Comment exécuter les migrations sur LWS

### Option 1 : Via SSH (si disponible)

1. Connectez-vous en SSH à votre serveur LWS
2. Naviguez vers le dossier du projet :
   ```bash
   cd /htdocs/niangprogrammeur.com
   ```
3. Exécutez les migrations :
   ```bash
   php artisan migrate --force
   ```

### Option 2 : Via phpMyAdmin (si SSH non disponible)

1. Connectez-vous à phpMyAdmin dans le panneau LWS
2. Sélectionnez la base de données `codaf2689015_23qyy0`
3. Allez dans l'onglet "SQL"
4. Exécutez cette requête SQL pour créer la table `advertisements` :

```sql
CREATE TABLE IF NOT EXISTS `advertisements` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `placement` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `clicks` int(11) NOT NULL DEFAULT 0,
  `impressions` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advertisements_is_active_index` (`is_active`),
  KEY `advertisements_placement_index` (`placement`),
  KEY `advertisements_order_index_index` (`order_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Option 3 : Créer la table sessions (si elle n'existe pas)

Si vous avez aussi une erreur concernant la table `sessions`, exécutez cette requête SQL dans phpMyAdmin :

```sql
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Vérification

Après avoir créé la table, testez votre site :
- `https://www.niangprogrammeur.com`

L'erreur "Table 'advertisements' doesn't exist" devrait être résolue.

## Toutes les migrations à exécuter

Si vous avez accès SSH, exécutez toutes les migrations en une fois :

```bash
cd /htdocs/niangprogrammeur.com
php artisan migrate --force
```

Cela créera toutes les tables nécessaires :
- users
- cache
- jobs
- adsense_settings
- statistics
- site_settings
- contact_messages
- exercises
- newsletters
- job_categories
- job_articles
- ads
- sessions
- comments
- formation_progress
- admin_logs
- **advertisements** (nouvelle table)

## Notes importantes

- Le flag `--force` est nécessaire en production
- Assurez-vous que le fichier `.env` contient les bonnes informations de base de données
- Sauvegardez votre base de données avant d'exécuter les migrations

