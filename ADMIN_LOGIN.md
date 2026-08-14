# 🔐 Compte Administrateur — Résolution du problème de connexion

## ✅ Problème Résolu

Le compte administrateur n'existait pas en base de données. **Il a été créé automatiquement.**

## 👤 Identifiants de Connexion

```
Email:      admin@accessinformatique.com
Mot de passe: Admin@Access2024!
```

## 🚀 Comment se connecter

1. **Accédez à l'espace admin:**
   ```
   http://localhost:5173/admin/login
   ```

2. **Entrez les identifiants:**
   - Email: `admin@accessinformatique.com`
   - Mot de passe: `Admin@Access2024!`

3. **Cliquez sur "Se connecter"**

## 📊 Accès au Dashboard Admin

Une fois connecté, vous pouvez accéder à:
- **Dashboard** → Statistiques et vue d'ensemble
- **Contenus** → Modifier les textes du site
- **Solutions** → Gérer les logiciels
- **Formations** → Gérer les formations
- **Leads (Contact)** → Voir les messages de contact
- **Leads (Inscriptions)** → Voir les inscriptions aux formations
- **Partenaires** → Gérer les partenaires
- **Administrateurs** → Gérer les comptes admin

## 🔄 Comment réinitialiser le mot de passe

Si vous oubliez le mot de passe, vous pouvez le réinitialiser avec:

```bash
php test_admin_account.php
```

Puis répondez "y" à la question pour réinitialiser.

## 📝 Sécurité

- ✅ Les mots de passe sont hashés en bcrypt (coût 12)
- ✅ Rate limiting: 5 tentatives par IP toutes les 5 minutes
- ✅ Les requêtes sont loggées pour le debugging
- ✅ Les tokens JWT expirent après 1 heure (configurable)

## 📂 Fichiers de référence

- **Login API:** [backend/api/admin/login.php](backend/api/admin/login.php)
- **Authentification:** [backend/includes/Auth.php](backend/includes/Auth.php)
- **Configuration:** [backend/.env](backend/.env)

