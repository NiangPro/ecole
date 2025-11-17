# Configuration reCAPTCHA v3

## 1. Obtenir les clés reCAPTCHA

1. Aller sur https://www.google.com/recaptcha/admin/create
2. Remplir le formulaire :
   - **Label** : NiangProgrammeur
   - **Type de reCAPTCHA** : reCAPTCHA v3
   - **Domaines** : Votre domaine (ex: niangprogrammeur.com, www.niangprogrammeur.com, localhost pour le développement)
3. Accepter les conditions et cliquer sur "Envoyer"
4. Copier les deux clés :
   - **Clé de site** (Site Key)
   - **Clé secrète** (Secret Key)

## 2. Configuration dans Laravel

Ajouter les clés dans votre fichier `.env` :

```env
RECAPTCHA_SITE_KEY=votre_clé_site_ici
RECAPTCHA_SECRET_KEY=votre_clé_secrète_ici
```

## 3. Test en développement

Si vous n'avez pas encore de clés reCAPTCHA ou si vous testez en local, laissez les variables vides dans `.env`. Le système fonctionnera sans reCAPTCHA (seul le Honeypot sera actif).

## 4. Fonctionnement

- **reCAPTCHA v3** : Invisible pour l'utilisateur, vérifie automatiquement si l'utilisateur est un bot ou un humain
- **Score** : reCAPTCHA v3 donne un score entre 0.0 (bot) et 1.0 (humain)
- **Seuil** : Le système accepte les scores >= 0.5
- **Honeypot** : Champ invisible qui détecte les bots qui remplissent tous les champs
- **Temps de remplissage** : Détecte les soumissions trop rapides (< 2 secondes)

## 5. Formulaires protégés

- Formulaire de commentaires (`/comments/store`)
- Formulaire de contact (`/contact`)
- Toutes les réponses aux commentaires

## 6. En cas d'erreur

Si reCAPTCHA échoue, un message d'erreur s'affiche et l'utilisateur peut réessayer. Si les clés ne sont pas configurées, le système fonctionne sans reCAPTCHA mais avec le Honeypot et le rate limiting.

