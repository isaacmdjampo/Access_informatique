# Access Informatique — Guide de démarrage complet

## Prérequis

| Outil | Version minimum | Vérification |
|-------|-----------------|-------------|
| PHP | 8.0+ | `php -v` |
| MySQL | 8.0+ | phpMyAdmin ou `mysql --version` |
| Composer | 2.x | `composer --version` |
| Node.js | 20+ | `node -v` |
| WAMP | 3.x | — |

---

## 1. Base de données

### Créer la base dans phpMyAdmin

```sql
CREATE DATABASE access_informatique
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

### Importer le schéma et les données

```bash
# Via phpMyAdmin : importer les deux fichiers dans l'ordre
# OU via la ligne de commande WAMP :

mysql -u root access_informatique < database/schema.sql
mysql -u root access_informatique < database/seeds.sql
```

### Créer le compte administrateur

```bash
php database/setup_admin.php
```

> **Identifiants par défaut :**
> - Email : `admin@accessinformatique.com`
> - Mot de passe : `Admin@Access2024!`
>
> **SUPPRIMER** `database/setup_admin.php` après exécution.

---

## 2. Backend PHP

### Copier et configurer le fichier .env

```bash
# Le fichier backend/.env existe déjà — éditer les valeurs réelles :
```

```ini
APP_ENV=development
APP_URL=http://localhost/Access_Informatique/backend

DB_HOST=127.0.0.1
DB_NAME=access_informatique
DB_USER=root
DB_PASS=              # Votre mot de passe MySQL WAMP (vide par défaut)

JWT_SECRET=REMPLACER_PAR_UNE_CHAINE_ALEATOIRE_LONGUE_EN_PRODUCTION

MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_application_gmail
MAIL_FROM=noreply@accessinformatique.com
MAIL_FROM_NAME=Access Informatique
MAIL_ADMIN=admin@accessinformatique.com

FRONTEND_URL=http://localhost:5173
UPLOAD_URL=http://localhost/Access_Informatique/backend/uploads
```

### Installer les dépendances PHP (PHPMailer)

```bash
cd backend
composer install
```

### Vérifier le RewriteBase dans backend/.htaccess

Si votre projet n'est pas à la racine de WAMP (`/`), adapter la ligne :

```apache
# backend/.htaccess — ligne à adapter selon votre installation
RewriteBase /Access_Informatique/backend/
```

### Tester l'API

Ouvrir dans le navigateur :
```
http://localhost/Access_Informatique/backend/api/solutions
```
→ Doit retourner un tableau JSON de solutions.

---

## 3. Frontend Vue.js (développement)

### Configurer l'URL de l'API

Le fichier `.env.local` est déjà créé à la racine du projet :

```ini
VITE_API_URL=http://localhost/Access_Informatique/backend/api
```

### Installer les dépendances et démarrer

```bash
# Depuis la racine du projet (c:\wamp64\www\Access_Informatique\)
npm install
npm run dev
```

Le frontend démarre sur : **http://localhost:5173**

### Accès au dashboard admin

```
http://localhost:5173/admin/login
```

---

## 4. Configuration des emails (Gmail)

Pour que les emails fonctionnent via Gmail :

1. Activer la **validation en 2 étapes** sur votre compte Google
2. Créer un **mot de passe d'application** :
   - Google Account → Sécurité → Mots de passe des applications
   - Nommer : "Access Informatique WAMP"
   - Copier le mot de passe de 16 caractères dans `MAIL_PASSWORD`

---

## 5. Déploiement en production (Apache)

### Frontend

```bash
npm run build
# Copier le contenu de dist/ sur le serveur web
# Y déposer le public/.htaccess (pour Vue Router history mode)
cp public/.htaccess dist/.htaccess
```

### Backend

- Copier le dossier `backend/` sur le serveur
- Mettre `APP_ENV=production` dans `.env`
- Changer `JWT_SECRET` par une chaîne aléatoire de 64+ caractères
- S'assurer que `mod_rewrite` est activé sur Apache
- Adapter `RewriteBase` dans `backend/.htaccess`

---

## 6. Récapitulatif des URLs

| Service | URL |
|---------|-----|
| Frontend (dev) | http://localhost:5173 |
| Admin (dev) | http://localhost:5173/admin/login |
| API publique | http://localhost/Access_Informatique/backend/api/solutions |
| API admin | http://localhost/Access_Informatique/backend/api/admin/stats |

---

## 7. Checklist de validation

### API publique
- [ ] `GET /api/solutions` → liste les 6 solutions avec leurs tags
- [ ] `GET /api/solutions/solumed` → détail complet avec modules et avantages
- [ ] `GET /api/formations` → liste les 6 formations avec skills
- [ ] `GET /api/formations/developpement-web-full-stack` → détail complet
- [ ] `GET /api/partners` → liste les 10 partenaires
- [ ] `GET /api/hackathon` → thèmes, timeline, récompenses
- [ ] `GET /api/contents?page=home` → textes de l'accueil

### Formulaires
- [ ] Remplir et soumettre le formulaire Contact → email reçu + message de succès
- [ ] Remplir et soumettre le formulaire Inscription → email reçu + message de succès

### Frontend
- [ ] Page accueil : partenaires chargés depuis l'API
- [ ] Page solutions : liste chargée depuis l'API, filtres fonctionnels
- [ ] Page solution détail : données complètes depuis l'API
- [ ] Page formations : recherche fonctionnelle, données depuis l'API
- [ ] Page hackathon : thèmes et programme depuis l'API

### Dashboard admin
- [ ] Login → accès refusé avec mauvais identifiants
- [ ] Login → accès accordé avec bons identifiants
- [ ] Dashboard : compteurs affichés
- [ ] Textes : modification d'un texte → visible sur le site
- [ ] Solutions : création d'une solution → visible sur `/solutions`
- [ ] Solutions : upload image → URL retournée correctement
- [ ] Leads contact : message du formulaire visible
- [ ] Leads inscriptions : inscription du formulaire visible
- [ ] Export CSV : téléchargement fonctionnel

### Sécurité
- [ ] `GET /backend/includes/config.php` → 403 Forbidden
- [ ] `GET /backend/vendor/` → 403 Forbidden
- [ ] `GET /backend/.env` → 403 Forbidden
- [ ] 5+ tentatives de connexion depuis la même IP → 429 Too Many Requests
- [ ] Appel API admin sans token → 401 Unauthorized

---

## 8. Structure des fichiers créés

```
Access_Informatique/
├── .env.local                          ← URL API pour Vite
├── .gitignore                          ← mis à jour
├── public/
│   └── .htaccess                       ← Vue Router history mode (Apache)
│
├── src/
│   ├── services/
│   │   └── api.js                      ← Instance Axios
│   ├── stores/
│   │   ├── content.js                  ← Cache textes Pinia
│   │   └── admin.js                    ← Auth admin Pinia
│   ├── composables/
│   │   └── useContent.js               ← Helper t(key, fallback)
│   └── views/admin/
│       ├── AdminLogin.vue
│       ├── AdminLayout.vue
│       ├── AdminDashboard.vue
│       ├── AdminContents.vue
│       ├── AdminSolutions.vue
│       ├── AdminFormations.vue
│       ├── AdminLeadsContact.vue
│       ├── AdminLeadsInscriptions.vue
│       └── AdminPartners.vue
│
├── backend/
│   ├── .env                            ← Configurer avant démarrage
│   ├── .htaccess                       ← Routing + sécurité
│   ├── composer.json
│   ├── uploads/                        ← Images uploadées (non versionné)
│   ├── includes/
│   │   ├── config.php
│   │   ├── db.php
│   │   ├── Response.php
│   │   ├── Auth.php                    ← JWT HS256
│   │   ├── Mailer.php                  ← PHPMailer wrapper
│   │   └── RateLimit.php               ← Anti-brute force
│   └── api/
│       ├── contents/index.php
│       ├── solutions/index.php
│       ├── formations/index.php
│       ├── partners/index.php
│       ├── hackathon/index.php
│       ├── forms/
│       │   ├── contact.php
│       │   └── inscription.php
│       └── admin/
│           ├── login.php               ← + rate limiting
│           ├── logout.php
│           ├── stats.php
│           ├── upload.php
│           ├── contents/index.php
│           ├── solutions/index.php
│           ├── formations/index.php
│           ├── partners/index.php
│           └── leads/
│               ├── contact.php
│               ├── inscriptions.php
│               └── export.php
│
└── database/
    ├── schema.sql                      ← 16 tables
    ├── seeds.sql                       ← Toutes les données initiales
    └── setup_admin.php                 ← À exécuter 1x puis supprimer
```
