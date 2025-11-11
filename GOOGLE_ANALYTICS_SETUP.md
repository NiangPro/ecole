# Configuration Google Analytics

## 📊 Étapes pour installer Google Analytics

### 1. Créer un compte Google Analytics

1. Allez sur [Google Analytics](https://analytics.google.com/)
2. Connectez-vous avec votre compte Google
3. Cliquez sur "Commencer à mesurer"

### 2. Configurer la propriété

1. **Nom du compte** : NiangProgrammeur
2. **Nom de la propriété** : Site NiangProgrammeur
3. **Fuseau horaire** : GMT+0 (Sénégal)
4. **Devise** : XOF (Franc CFA)

### 3. Configurer le flux de données

1. Sélectionnez **"Web"**
2. **URL du site web** : Votre domaine (ex: https://niangprogrammeur.com)
3. **Nom du flux** : Site Web Principal
4. Cliquez sur "Créer un flux"

### 4. Récupérer l'ID de mesure

1. Dans la page du flux de données, vous verrez **"ID de mesure"**
2. Format : `G-XXXXXXXXXX`
3. Copiez cet ID

### 5. Ajouter l'ID dans Laravel

1. Ouvrez votre fichier `.env`
2. Ajoutez la ligne :
   ```
   GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
   ```
3. Remplacez `G-XXXXXXXXXX` par votre vrai ID

### 6. Vérifier l'installation

1. Retournez sur Google Analytics
2. Allez dans **Rapports > Temps réel**
3. Visitez votre site
4. Vous devriez voir votre visite en temps réel

## ✅ Fonctionnalités déjà implémentées

- ✅ Code Google Analytics intégré dans le layout
- ✅ Gestion du consentement cookies (RGPD)
- ✅ Anonymisation IP si cookies refusés
- ✅ Configuration via fichier .env

## 🔒 Conformité RGPD

Le code Analytics respecte le consentement cookies :
- **Accepté** : Tracking complet
- **Refusé** : Anonymisation IP + pas de stockage
- **Non défini** : Pas de tracking

## 📈 Métriques suivies

Une fois configuré, vous pourrez voir :
- Nombre de visiteurs
- Pages visitées
- Durée des sessions
- Taux de rebond
- Localisation géographique
- Appareils utilisés
- Sources de trafic

## 🎯 Objectifs recommandés

Configurez ces objectifs dans GA4 :
1. **Soumission formulaire contact**
2. **Visite page formation** (durée > 2 min)
3. **Visite de 3+ pages**
4. **Retour visiteur** (2+ sessions)

## 📝 Notes importantes

- L'ID commence toujours par `G-` (GA4)
- Les anciennes propriétés UA (Universal Analytics) ne sont plus supportées
- Les données apparaissent avec 24-48h de délai pour les rapports complets
- Le temps réel est instantané

## 🔗 Liens utiles

- [Google Analytics](https://analytics.google.com/)
- [Documentation GA4](https://support.google.com/analytics/answer/10089681)
- [Guide RGPD et Analytics](https://support.google.com/analytics/answer/9019185)
