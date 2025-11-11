# Système de Statistiques - NiangProgrammeur

## 📊 Vue d'ensemble

Le système de statistiques est inspiré de **Jetpack Analytics** de WordPress et permet de suivre en temps réel les visites sur votre plateforme de formation.

## ✨ Fonctionnalités

### 1. **Tracking Automatique**
- Enregistrement automatique de chaque visite
- Capture des informations : URL, titre de page, IP, User-Agent, Referer
- Exclusion automatique des routes admin et assets

### 2. **Filtres Dynamiques**
- **Par jour** : Statistiques d'aujourd'hui
- **Par mois** : Statistiques du mois en cours
- **Par année** : Statistiques de l'année en cours

### 3. **Métriques Disponibles**
- **Visites totales** : Nombre total de visites selon le filtre
- **Visiteurs uniques** : Basé sur les adresses IP distinctes
- **Pages vues** : Nombre de pages différentes consultées
- **Moyenne par jour** : Calcul automatique selon la période

### 4. **Visualisations**
- **Graphique linéaire** : Évolution des visites sur 30 jours (Chart.js)
- **Top 10 pages** : Pages les plus visitées avec nombre de visites
- **Design moderne** : Interface ultra-moderne avec animations

## 🚀 Utilisation

### Accéder aux statistiques
```
URL: http://localhost:8000/admin/statistics
```

### Filtrer les données
```
http://localhost:8000/admin/statistics?filter=day
http://localhost:8000/admin/statistics?filter=month
http://localhost:8000/admin/statistics?filter=year
```

## 🗄️ Structure de la base de données

### Table `statistics`
```sql
- id (bigint)
- page_url (string)
- page_title (string, nullable)
- ip_address (string, nullable)
- user_agent (string, nullable)
- referer (string, nullable)
- visit_date (date)
- created_at (timestamp)
- updated_at (timestamp)
```

### Index
- `visit_date` : Optimise les requêtes par date
- `page_url` : Optimise les requêtes par page

## 📈 Méthodes du Modèle

### Statistic::getByDay($date)
Retourne le nombre de visites pour un jour spécifique

### Statistic::getByMonth($year, $month)
Retourne le nombre de visites pour un mois spécifique

### Statistic::getByYear($year)
Retourne le nombre de visites pour une année spécifique

### Statistic::getTopPages($limit, $period)
Retourne les pages les plus visitées selon la période

### Statistic::getDailyStats($days)
Retourne les statistiques quotidiennes pour les X derniers jours

## 🔧 Configuration

### Middleware TrackVisit
Le middleware est automatiquement appliqué à toutes les routes web via `bootstrap/app.php`

### Exclusions
Par défaut, les routes suivantes ne sont PAS trackées :
- `/admin/*` (toutes les routes admin)
- `/css/*` (fichiers CSS)
- `/js/*` (fichiers JavaScript)
- `/images/*` (images)

## 📊 Données de test

Pour générer des données de test :
```bash
php artisan db:seed --class=StatisticsSeeder
```

Cela créera environ 5000-9000 visites réparties sur 60 jours.

## 🎨 Interface Admin

### Sidebar
- Icône : `fa-chart-bar`
- Position : 2ème élément (après Dashboard)
- Route : `admin.statistics`

### Composants
1. **Filtres** : Boutons Jour/Mois/Année
2. **Cards statistiques** : 4 métriques principales
3. **Graphique** : Chart.js avec gradient cyan
4. **Liste des pages** : Top 10 avec nombre de visites

## 🔐 Sécurité

- Vérification de session admin requise
- Données anonymisées (IP stockée mais non affichée)
- Pas d'accès public aux statistiques

## 📝 Notes

- Les statistiques sont mises à jour en temps réel
- Le graphique affiche toujours les 30 derniers jours
- Les calculs sont optimisés avec des index sur la base de données
- Compatible avec tous les navigateurs modernes

## 🚀 Améliorations futures possibles

- Export CSV/PDF des statistiques
- Comparaison entre périodes
- Statistiques par formation
- Carte géographique des visiteurs
- Analyse des sources de trafic
- Taux de rebond
- Durée moyenne de visite
