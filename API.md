# 📡 Documentation API - NiangProgrammeur

Documentation complète de l'API REST de la plateforme NiangProgrammeur.

## 📋 Table des Matières

- [Authentification](#authentification)
- [Endpoints Publics](#endpoints-publics)
- [Endpoints Authentifiés](#endpoints-authentifiés)
- [Endpoints Admin](#endpoints-admin)
- [Codes de Réponse](#codes-de-réponse)
- [Gestion des Erreurs](#gestion-des-erreurs)
- [Rate Limiting](#rate-limiting)

## 🔐 Authentification

L'API utilise l'authentification Laravel standard avec sessions pour les utilisateurs authentifiés.

### Headers Requis

```
Accept: application/json
Content-Type: application/json
X-CSRF-TOKEN: {token} (pour les requêtes POST/PUT/DELETE)
```

### Obtenir le Token CSRF

```http
GET /sanctum/csrf-cookie
```

## 🌐 Endpoints Publics

### Formations

#### Liste des formations

```http
GET /formations
```

**Réponse :**
```json
{
  "formations": [
    {
      "slug": "html5",
      "title": "HTML5",
      "description": "...",
      "icon": "fab fa-html5"
    }
  ]
}
```

#### Détails d'une formation

```http
GET /formations/{slug}
```

**Paramètres :**
- `slug` (string) : Slug de la formation (html5, css3, javascript, etc.)

**Réponse :**
```json
{
  "formation": {
    "slug": "html5",
    "title": "HTML5",
    "sections": [...],
    "progress": {
      "completed_sections": [],
      "section_id": null
    }
  }
}
```

### Exercices

#### Liste des exercices par langage

```http
GET /exercices/{language}
```

**Paramètres :**
- `language` (string) : Langage (html, css, javascript, etc.)

**Réponse :**
```json
{
  "exercices": [
    {
      "id": 1,
      "title": "Exercice 1",
      "difficulty": "beginner",
      "completed": false
    }
  ]
}
```

#### Détails d'un exercice

```http
GET /exercices/{language}/{id}
```

**Paramètres :**
- `language` (string) : Langage
- `id` (integer) : ID de l'exercice

**Réponse :**
```json
{
  "exercice": {
    "id": 1,
    "title": "Exercice 1",
    "description": "...",
    "instructions": "...",
    "solution": "..."
  }
}
```

#### Soumettre un exercice

```http
POST /exercices/{language}/{id}/submit
```

**Body :**
```json
{
  "code": "console.log('Hello World');",
  "language": "javascript"
}
```

**Réponse :**
```json
{
  "success": true,
  "message": "Exercice complété avec succès",
  "result": {
    "output": "Hello World",
    "passed": true
  }
}
```

#### Exécuter du code

```http
POST /exercices/{language}/run
```

**Body :**
```json
{
  "code": "console.log('Hello');",
  "language": "javascript"
}
```

**Réponse :**
```json
{
  "output": "Hello",
  "error": null,
  "execution_time": 0.123
}
```

**Rate Limit :** 30 requêtes par minute

### Quiz

#### Liste des quiz par langage

```http
GET /quiz/{language}
```

**Paramètres :**
- `language` (string) : Langage

**Réponse :**
```json
{
  "quiz": {
    "language": "javascript",
    "questions": [
      {
        "id": 1,
        "question": "Qu'est-ce que JavaScript?",
        "options": ["...", "..."],
        "type": "multiple_choice"
      }
    ]
  }
}
```

#### Soumettre un quiz

```http
POST /quiz/{language}/submit
```

**Body :**
```json
{
  "answers": {
    "1": "option_a",
    "2": "option_b"
  }
}
```

**Réponse :**
```json
{
  "success": true,
  "score": 85,
  "total": 100,
  "percentage": 85,
  "results": [
    {
      "question_id": 1,
      "correct": true,
      "user_answer": "option_a",
      "correct_answer": "option_a"
    }
  ]
}
```

#### Résultats d'un quiz

```http
GET /quiz/{language}/result
```

**Réponse :**
```json
{
  "result": {
    "score": 85,
    "total": 100,
    "percentage": 85,
    "passed": true,
    "completed_at": "2025-01-27T10:00:00Z"
  }
}
```

### Commentaires

#### Créer un commentaire

```http
POST /comments
```

**Body :**
```json
{
  "commentable_type": "App\\Models\\JobArticle",
  "commentable_id": 1,
  "content": "Excellent article !",
  "name": "John Doe",
  "email": "john@example.com",
  "parent_id": null
}
```

**Réponse :**
```json
{
  "success": true,
  "message": "Commentaire en attente de modération",
  "comment": {
    "id": 1,
    "content": "Excellent article !",
    "status": "pending",
    "created_at": "2025-01-27T10:00:00Z"
  }
}
```

**Rate Limit :** 5 requêtes par 15 minutes

#### Liker un commentaire

```http
POST /comments/{id}/like
```

**Réponse :**
```json
{
  "success": true,
  "likes": 5
}
```

**Rate Limit :** 10 requêtes par minute

### Newsletter

#### S'abonner à la newsletter

```http
POST /newsletter/subscribe
```

**Body :**
```json
{
  "email": "user@example.com"
}
```

**Réponse :**
```json
{
  "success": true,
  "message": "Inscription réussie"
}
```

**Rate Limit :** 10 requêtes par minute

#### Se désabonner

```http
GET /newsletter/unsubscribe/{token}
```

**Paramètres :**
- `token` (string) : Token de désabonnement

**Réponse :**
```json
{
  "success": true,
  "message": "Désabonnement réussi"
}
```

### Contact

#### Envoyer un message

```http
POST /contact
```

**Body :**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "subject": "Question",
  "message": "Bonjour, j'ai une question..."
}
```

**Réponse :**
```json
{
  "success": true,
  "message": "Message envoyé avec succès"
}
```

**Rate Limit :** 5 requêtes par minute

### Recherche

#### Rechercher du contenu

```http
GET /search?q={query}&type={type}
```

**Paramètres :**
- `q` (string) : Terme de recherche
- `type` (string, optionnel) : Type de contenu (formations, exercices, quiz, articles)

**Réponse :**
```json
{
  "results": [
    {
      "type": "formation",
      "title": "HTML5",
      "slug": "html5",
      "excerpt": "..."
    }
  ],
  "total": 10
}
```

**Rate Limit :** 30 requêtes par minute

## 🔒 Endpoints Authentifiés

Tous les endpoints ci-dessous nécessitent une authentification via middleware `auth`.

### Progression des Formations

#### Mettre à jour la progression

```http
POST /api/formation-progress/update
```

**Body :**
```json
{
  "formation_slug": "html5",
  "section_id": "intro",
  "completed_sections": ["intro", "editors"]
}
```

**Réponse :**
```json
{
  "success": true,
  "progress": {
    "formation_slug": "html5",
    "section_id": "intro",
    "completed_sections": ["intro", "editors"],
    "updated_at": "2025-01-27T10:00:00Z"
  }
}
```

#### Obtenir la progression

```http
GET /api/formation-progress/{formationSlug}
```

**Réponse :**
```json
{
  "progress": {
    "formation_slug": "html5",
    "section_id": "intro",
    "completed_sections": ["intro", "editors"],
    "progress_percentage": 15
  }
}
```

### Favoris

#### Basculer un favori

```http
POST /api/favorites/toggle
```

**Body :**
```json
{
  "favorite_type": "formation",
  "favorite_slug": "html5",
  "favorite_name": "HTML5"
}
```

**Réponse :**
```json
{
  "success": true,
  "is_favorite": true,
  "message": "Ajouté aux favoris"
}
```

#### Vérifier si favori

```http
GET /api/favorites/check?type={type}&slug={slug}
```

**Paramètres :**
- `type` (string) : Type (formation, exercice, quiz)
- `slug` (string) : Slug de l'élément

**Réponse :**
```json
{
  "is_favorite": true
}
```

### Notifications

#### Obtenir les notifications non lues

```http
GET /api/notifications/unread
```

**Réponse :**
```json
{
  "notifications": [
    {
      "id": 1,
      "type": "badge_earned",
      "title": "Badge obtenu !",
      "message": "Vous avez obtenu le badge 'Débutant'",
      "is_read": false,
      "created_at": "2025-01-27T10:00:00Z"
    }
  ],
  "unread_count": 5
}
```

#### Marquer une notification comme lue

```http
POST /api/notifications/{id}/read
```

**Réponse :**
```json
{
  "success": true,
  "message": "Notification marquée comme lue"
}
```

#### Marquer toutes comme lues

```http
POST /api/notifications/read-all
```

**Réponse :**
```json
{
  "success": true,
  "message": "Toutes les notifications ont été marquées comme lues"
}
```

### Objectifs

#### Créer un objectif

```http
POST /dashboard/goals
```

**Body :**
```json
{
  "title": "Terminer HTML5",
  "description": "Compléter toutes les sections",
  "target_date": "2025-02-01",
  "target_value": 100,
  "current_value": 0
}
```

**Réponse :**
```json
{
  "success": true,
  "goal": {
    "id": 1,
    "title": "Terminer HTML5",
    "status": "in_progress",
    "progress_percentage": 0
  }
}
```

#### Mettre à jour un objectif

```http
PUT /dashboard/goals/{id}
```

**Body :**
```json
{
  "title": "Terminer HTML5 et CSS3",
  "target_date": "2025-02-15"
}
```

#### Supprimer un objectif

```http
DELETE /dashboard/goals/{id}
```

**Réponse :**
```json
{
  "success": true,
  "message": "Objectif supprimé"
}
```

#### Marquer un objectif comme complété

```http
POST /dashboard/goals/{id}/complete
```

**Réponse :**
```json
{
  "success": true,
  "goal": {
    "id": 1,
    "status": "completed",
    "completed_at": "2025-01-27T10:00:00Z"
  }
}
```

#### Mettre à jour la progression

```http
POST /dashboard/goals/{id}/progress
```

**Body :**
```json
{
  "current_value": 50
}
```

### Certificats

#### Générer un certificat

```http
POST /dashboard/certificates/generate/{formationSlug}
```

**Paramètres :**
- `formationSlug` (string) : Slug de la formation

**Réponse :**
```json
{
  "success": true,
  "certificate": {
    "id": 1,
    "formation_slug": "html5",
    "formation_name": "HTML5",
    "issued_at": "2025-01-27T10:00:00Z",
    "download_url": "/dashboard/certificates/1/download"
  }
}
```

#### Télécharger un certificat

```http
GET /dashboard/certificates/{id}/download
```

**Réponse :** Fichier PDF

## 👨‍💼 Endpoints Admin

Tous les endpoints ci-dessous nécessitent le middleware `admin`.

### Utilisateurs

#### Liste des utilisateurs

```http
GET /admin/users
```

**Paramètres de requête :**
- `page` (integer) : Numéro de page
- `per_page` (integer) : Éléments par page
- `search` (string) : Recherche par nom/email

**Réponse :**
```json
{
  "users": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user",
      "is_active": true,
      "created_at": "2025-01-27T10:00:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total": 100,
    "per_page": 15
  }
}
```

#### Créer un utilisateur

```http
POST /admin/users
```

**Body :**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "role": "user",
  "is_active": true
}
```

#### Mettre à jour un utilisateur

```http
PUT /admin/users/{id}
```

#### Supprimer un utilisateur

```http
DELETE /admin/users/{id}
```

### Articles d'Emploi

#### Liste des articles

```http
GET /admin/jobs/articles
```

#### Créer un article

```http
POST /admin/jobs/articles
```

**Body :**
```json
{
  "category_id": 1,
  "title": "Nouvelle offre d'emploi",
  "slug": "nouvelle-offre-emploi",
  "excerpt": "Description courte",
  "content": "Contenu complet...",
  "status": "published",
  "published_at": "2025-01-27T10:00:00Z"
}
```

#### Envoyer la newsletter pour un article

```http
POST /admin/jobs/articles/{id}/send-newsletter
```

**Réponse :**
```json
{
  "success": true,
  "message": "Newsletter envoyée à 150 abonnés"
}
```

### Commentaires

#### Liste des commentaires

```http
GET /admin/comments
```

**Paramètres de requête :**
- `status` (string) : pending, approved, rejected
- `page` (integer) : Numéro de page

#### Approuver un commentaire

```http
POST /admin/comments/{id}/approve
```

#### Rejeter un commentaire

```http
POST /admin/comments/{id}/reject
```

#### Supprimer un commentaire

```http
DELETE /admin/comments/{id}
```

### Backups

#### Liste des backups

```http
GET /admin/backups
```

**Réponse :**
```json
{
  "backups": [
    {
      "filename": "backup_2025-01-27_10-00-00.sql",
      "size": 5242880,
      "created_at": "2025-01-27T10:00:00Z"
    }
  ]
}
```

#### Créer un backup

```http
POST /admin/backups/create
```

**Réponse :**
```json
{
  "success": true,
  "filename": "backup_2025-01-27_10-00-00.sql",
  "message": "Backup créé avec succès"
}
```

#### Télécharger un backup

```http
GET /admin/backups/download/{filename}
```

#### Supprimer un backup

```http
DELETE /admin/backups/{filename}
```

### Audit de Sécurité

#### Liste des audits

```http
GET /admin/security-audit
```

**Réponse :**
```json
{
  "audits": [
    {
      "id": 1,
      "type": "failed_login",
      "severity": "medium",
      "description": "Tentative de connexion échouée",
      "ip_address": "192.168.1.1",
      "created_at": "2025-01-27T10:00:00Z"
    }
  ]
}
```

#### Détails d'un audit

```http
GET /admin/security-audit/{audit}
```

#### Exporter les audits en CSV

```http
GET /admin/security-audit/export/csv
```

**Réponse :** Fichier CSV

## 📊 Codes de Réponse

| Code | Signification |
|------|---------------|
| 200 | Succès |
| 201 | Créé avec succès |
| 204 | Succès sans contenu |
| 400 | Requête invalide |
| 401 | Non authentifié |
| 403 | Accès interdit |
| 404 | Ressource non trouvée |
| 422 | Erreur de validation |
| 429 | Trop de requêtes (Rate Limit) |
| 500 | Erreur serveur |

## ⚠️ Gestion des Erreurs

### Format d'Erreur Standard

```json
{
  "success": false,
  "message": "Message d'erreur",
  "errors": {
    "field": ["Erreur de validation"]
  }
}
```

### Exemples

#### Erreur de validation

```json
{
  "success": false,
  "message": "Les données fournies sont invalides",
  "errors": {
    "email": ["Le champ email est requis"],
    "password": ["Le mot de passe doit contenir au moins 8 caractères"]
  }
}
```

#### Erreur d'authentification

```json
{
  "success": false,
  "message": "Non authentifié",
  "error": "unauthenticated"
}
```

#### Erreur de permission

```json
{
  "success": false,
  "message": "Accès interdit",
  "error": "forbidden"
}
```

## 🚦 Rate Limiting

L'API applique des limites de taux pour protéger contre les abus :

| Endpoint | Limite |
|----------|--------|
| `/search` | 30 req/min |
| `/exercices/{language}/run` | 30 req/min |
| `/comments` | 5 req/15min |
| `/comments/{id}/like` | 10 req/min |
| `/newsletter/subscribe` | 10 req/min |
| `/contact` | 5 req/min |

### Réponse Rate Limit

```json
{
  "message": "Too Many Attempts.",
  "retry_after": 60
}
```

**Headers :**
```
X-RateLimit-Limit: 30
X-RateLimit-Remaining: 5
Retry-After: 60
```

---

**Dernière mise à jour** : 2025-01-27

**Version API** : 1.0.0

