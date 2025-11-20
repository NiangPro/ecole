# Génération d'Articles depuis le Web

## Fonctionnalité

Le système de génération d'articles recherche automatiquement les articles les plus récents sur le web, les reformule en tenant compte du SEO et de la visibilité, et génère des articles avec des images illustratives.

## Configuration des Clés API

Pour utiliser pleinement cette fonctionnalité, vous devez configurer les clés API suivantes dans votre fichier `.env` :

### 1. NewsAPI (Optionnel - Non utilisé par défaut)
**Note :** Le système utilise maintenant principalement le scraping des sites sénégalais. NewsAPI n'est plus nécessaire mais peut être utilisé en complément si configuré.

```env
NEWS_API_KEY=votre_cle_newsapi
```

**Comment obtenir une clé :**
1. Allez sur https://newsapi.org/
2. Créez un compte gratuit
3. Récupérez votre clé API dans le dashboard

**Limite gratuite :** 100 requêtes/jour

### 2. Unsplash API (Optionnel mais recommandé)
Permet de récupérer des images illustratives de qualité.

```env
UNSPLASH_API_KEY=votre_cle_unsplash
```

**Comment obtenir une clé :**
1. Allez sur https://unsplash.com/developers
2. Créez une application
3. Récupérez votre Access Key

**Limite gratuite :** 50 requêtes/heure

### 3. OpenAI API (Optionnel)
Permet d'améliorer la reformulation des articles avec l'IA.

```env
OPENAI_API_KEY=votre_cle_openai
```

**Comment obtenir une clé :**
1. Allez sur https://platform.openai.com/
2. Créez un compte
3. Générez une clé API

**Note :** Cette clé est payante mais améliore significativement la qualité de la reformulation.

## Sites Sources

Le système recherche automatiquement des articles sur les sites sénégalais suivants :

1. **https://directiondesbourses.sn/** - Direction des Bourses (Bourses d'études)
2. **https://concoursn.com/** - Concoursn (Concours et recrutements)
3. **https://www.sgee-sn.org/** - SGEE (Service de Gestion des Etudiants Sénégalais à l'Étranger)
4. **https://www.emploidakar.com/** - Emploi Dakar (Offres d'emploi)
5. **https://www.emploisenegal.com/** - Emplois Sénégal (Offres d'emploi)
6. **https://www.guichetjeunesse.sn/** - Guichet Jeunesse (Emploi et opportunités)
7. **https://senegalservices.sn/** - Sénégal Services (Services et emplois)
8. **https://samabac.sn/** - SAMABAC (Concours)
9. **https://guindima.sn/** - Guindima (Emplois)

Le système utilise du **web scraping** pour extraire le contenu de ces sites de manière respectueuse (avec pauses entre les requêtes, User-Agent approprié, etc.).

## Fonctionnement sans Clés API

Le système fonctionne même sans clés API en utilisant :
- **Web scraping** des sites sénégalais listés ci-dessus (gratuit, pas de clé nécessaire)
- **Google News RSS** en dernier recours si les sites principaux ne retournent pas assez de contenu
- **Images placeholder** d'Unsplash si la clé n'est pas configurée

## Utilisation

1. Allez sur `/admin/jobs/seeder`
2. Spécifiez le nombre d'articles à créer (1-50)
3. Optionnellement, sélectionnez une catégorie
4. Définissez le nombre de jours dans le passé pour la date de publication
5. Cliquez sur "Générer les Articles"

## Processus de Génération

1. **Recherche d'articles** : Le système recherche les articles les plus récents sur les sites sénégalais spécialisés (bourses, concours, emplois)
   - Scraping respectueux avec pauses de 2 secondes entre chaque site
   - Extraction intelligente du contenu (titres, descriptions, textes)
   - Filtrage des éléments non pertinents (menus, navigation, etc.)
2. **Reformulation** : Chaque article est reformulé avec :
   - Optimisation SEO (mots-clés, meta descriptions, structure)
   - Optimisation de la lisibilité
   - Adaptation au contexte Sénégal
   - Ajout de structure HTML (titres H2, paragraphes)
3. **Génération d'images** : Une image illustrative est récupérée pour chaque article
   - Priorité à Unsplash API si configurée
   - Fallback vers images placeholder
4. **Calcul des scores** : Scores SEO et de lisibilité sont calculés automatiquement
5. **Création** : Les articles sont créés dans la base de données avec le statut "publié"

## Scores SEO et Visibilité

- **Score SEO** (0-100) : Basé sur :
  - Longueur et optimisation du titre
  - Meta description optimisée
  - Présence de mots-clés
  - Longueur du contenu
  - Structure avec titres H2/H3
  - Présence d'images

- **Score de Lisibilité** (0-100) : Basé sur :
  - Longueur moyenne des phrases
  - Complexité des mots
  - Structure du contenu

## Notes Importantes

- Le processus peut prendre quelques secondes par article
- Les articles sont créés avec le statut "publié" par défaut
- Les dates de publication sont aléatoirement réparties sur les X jours spécifiés
- Les images sont stockées en externe (type: external)

