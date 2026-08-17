# 📡 Guide des Endpoints API - Access Informatique

## Table des matières
1. [Endpoints publics](#endpoints-publics)
2. [Endpoints admin protégés](#endpoints-admin)
3. [Diagrammes de flux](#diagrammes)
4. [Codes d'erreur HTTP](#codes-erreur)
5. [Exemples cURL](#exemples-curl)

---

## 🌐 Endpoints Publics

### GET `/api/contents?page=home`

**Description** : Récupère les textes dynamiques d'une page

**Paramètres** :
- `page` (string) : Identifiant de la page (home, about, contact, formation, hackathon)

**Réponse succès (200)** :
```json
[
  {
    "id": 1,
    "page": "home",
    "key_name": "hero.title",
    "label": "Titre principal",
    "value": "Des logiciels taillés pour vous.",
    "updated_at": "2024-01-15 10:30:45"
  },
  {
    "id": 2,
    "page": "home",
    "key_name": "hero.description",
    "label": "Description",
    "value": "Access Informatique...",
    "updated_at": "2024-01-15 10:30:45"
  }
]
```

**Réponse erreur (404)** :
```json
{
  "success": false,
  "error": "Page introuvable"
}
```

---

### GET `/api/solutions`

**Description** : Liste de toutes les solutions (logiciels)

**Paramètres** : Aucun

**Réponse succès (200)** :
```json
[
  {
    "id": 1,
    "slug": "solumed",
    "name": "SoluMed",
    "category": "Santé",
    "short_description": "Solution pour cliniques et hôpitaux",
    "hero_image": "/images/cvsolumed.png",
    "features": ["Dossiers patients", "Facturation", "..."],
    "created_at": "2024-01-10 15:20:00"
  },
  ...
]
```

---

### GET `/api/solutions/:slug`

**Description** : Détail complet d'une solution

**Paramètres** :
- `slug` (URL parameter) : Slug de la solution (ex: solumed)

**Réponse succès (200)** :
```json
{
  "id": 1,
  "slug": "solumed",
  "name": "SoluMed",
  "category": "Santé",
  "description": "Description longue...",
  "short_description": "...",
  "hero_image": "/images/...",
  "features": [
    {"title": "Dossiers patients", "description": "..."},
    {"title": "Facturation", "description": "..."}
  ],
  "use_cases": ["Cliniques", "Hôpitaux"],
  "pricing": "Sur devis",
  "created_at": "..."
}
```

---

### GET `/api/formations`

**Description** : Liste des formations

**Paramètres** : Aucun (ou `?slug=xxx` pour une formation spécifique)

**Réponse succès (200)** :
```json
[
  {
    "id": 1,
    "slug": "developpement-web-full-stack",
    "title": "Développement Web Full Stack",
    "category": "Développement",
    "duration": "6 mois",
    "level": "Débutant à intermédiaire",
    "price": "€1 200",
    "description": "Apprenez HTML, CSS, JavaScript...",
    "image_url": "/images/...",
    "skills": ["HTML", "CSS", "Vue.js", "Node.js"],
    "is_active": 1
  },
  ...
]
```

---

### GET `/api/formations/:slug`

**Description** : Détail complet d'une formation

**Paramètres** :
- `slug` (URL parameter) : Slug de la formation

**Réponse succès (200)** :
```json
{
  "id": 1,
  "slug": "developpement-web-full-stack",
  "title": "Développement Web Full Stack",
  "category": "Développement",
  "duration": "6 mois",
  "level": "Débutant à intermédiaire",
  "price": "€1 200",
  "description": "...",
  "image_url": "/images/...",
  "skills": ["HTML", "CSS", "Vue.js", "Node.js"],
  "modules": [
    {
      "id": 1,
      "title": "Fondations Web",
      "description": "HTML, CSS et responsive design",
      "duration": "3 semaines",
      "sort_order": 1
    }
  ],
  "benefits": [
    "Portfolio prêt à présenter",
    "Support technique hebdomadaire"
  ],
  "outcomes": [
    "Créer une application web complète",
    "Maîtriser Vue.js"
  ],
  "is_active": 1
}
```

---

### GET `/api/partners`

**Description** : Liste des partenaires

**Réponse succès (200)** :
```json
[
  {
    "id": 1,
    "slug": "centre-medical-lumiere",
    "name": "Centre Médical Lumière",
    "logo_url": "/images/partners/centre-medical.png",
    "website": "https://...",
    "description": "Clinique partenaire",
    "is_active": 1
  },
  ...
]
```

---

### POST `/api/forms/contact`

**Description** : Soumettre un formulaire de contact

**Corps JSON** :
```json
{
  "name": "Jean Dupont",
  "email": "jean@example.com",
  "phone": "+225 01 01 57 30 54",
  "subject": "Demande de devis",
  "message": "Je souhaiterais en savoir plus sur SoluMed..."
}
```

**Réponse succès (200)** :
```json
{
  "success": true,
  "message": "Message envoyé avec succès",
  "lead_id": 42
}
```

**Réponse erreur (422)** :
```json
{
  "success": false,
  "error": "Email invalide"
}
```

**Notes** :
- Email envoyé à `contact@accessinformatique.com`
- Enregistrement en DB dans `leads_contact`
- Rate limiting : 5 par IP par 10 minutes

---

### POST `/api/forms/inscription`

**Description** : Soumettre une demande d'inscription à une formation

**Corps JSON** :
```json
{
  "prenom": "Marie",
  "nom": "Martin",
  "email": "marie@example.com",
  "phone": "+225 07 07 26 18 58",
  "formation_requested": "Développement Web Full Stack",
  "niveau_experience": "Débutant",
  "motivation": "Je veux apprendre le web développement..."
}
```

**Réponse succès (200)** :
```json
{
  "success": true,
  "message": "Inscription enregistrée",
  "inscription_id": 85
}
```

---

## 🔒 Endpoints Admin (Protégés par JWT)

**Authentification requise** : Header `X-Admin-Token` avec token JWT

### POST `/api/admin/login`

**Description** : Authentification admin

**Corps JSON** :
```json
{
  "email": "admin@accessinformatique.com",
  "password": "MonMotDePasseSecurisé123!"
}
```

**Réponse succès (200)** :
```json
{
  "success": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "expiresIn": 3600,
  "admin": {
    "id": 5,
    "name": "Admin User",
    "email": "admin@accessinformatique.com",
    "role": "admin"
  }
}
```

**Réponse erreur (401)** :
```json
{
  "success": false,
  "error": "Identifiants incorrects."
}
```

**Notes** :
- Rate limiting : 5 tentatives par IP par 5 minutes
- Token valide 1 heure
- Stockage recommandé : localStorage (frontend) ou Cookie httpOnly (plus sécurisé)

---

### POST `/api/admin/logout`

**Description** : Déconnexion (côté frontend, c'est surtout un événement)

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Réponse succès (200)** :
```json
{
  "success": true,
  "message": "Déconnexion réussie"
}
```

---

### GET `/api/admin/contents`

**Description** : Liste tous les contenus dynamiques (groupés par page)

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Réponse succès (200)** :
```json
{
  "home": [
    {"id": 1, "page": "home", "key_name": "hero.title", "value": "..."},
    {"id": 2, "page": "home", "key_name": "hero.description", "value": "..."}
  ],
  "about": [
    {"id": 10, "page": "about", "key_name": "hero.title", "value": "..."}
  ],
  "contact": [
    {"id": 20, "page": "contact", "key_name": "form.title", "value": "..."}
  ]
}
```

---

### GET `/api/admin/contents?page=home`

**Description** : Contenus d'une seule page

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Paramètres** :
- `page` (string) : home, about, contact, formation, hackathon

**Réponse succès (200)** :
```json
[
  {"id": 1, "page": "home", "key_name": "hero.title", "label": "Titre", "value": "..."},
  {"id": 2, "page": "home", "key_name": "hero.description", "label": "Description", "value": "..."}
]
```

---

### PUT `/api/admin/contents?id=1`

**Description** : Modifier le contenu d'une clé

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Paramètres** :
- `id` (integer) : ID du contenu

**Corps JSON** :
```json
{
  "value": "Nouvelle valeur du contenu"
}
```

**Réponse succès (200)** :
```json
{
  "success": true,
  "message": "Contenu mis à jour avec succès.",
  "id": 1
}
```

**Réponse erreur (404)** :
```json
{
  "success": false,
  "error": "Contenu introuvable."
}
```

**Audit** : L'action est enregistrée dans `audit_logs` avec les valeurs avant/après

---

### GET `/api/admin/solutions`

**Description** : Liste des solutions (pour admin)

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Réponse succès (200)** :
```json
[
  {
    "id": 1,
    "slug": "solumed",
    "name": "SoluMed",
    "category": "Santé",
    "is_active": 1,
    "hero_image": "/images/...",
    "created_at": "2024-01-10 15:20:00"
  },
  ...
]
```

---

### POST `/api/admin/solutions`

**Description** : Créer une nouvelle solution

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Corps JSON** :
```json
{
  "name": "Musa",
  "slug": "musa",
  "category": "Gestion",
  "short_description": "Solution de gestion intégrée",
  "description": "Description complète...",
  "hero_image": "/images/musa.png",
  "features": ["Feature 1", "Feature 2"],
  "is_active": 1
}
```

**Réponse succès (201)** :
```json
{
  "success": true,
  "message": "Solution créée avec succès",
  "id": 7
}
```

---

### PUT `/api/admin/solutions?id=1`

**Description** : Modifier une solution

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Corps JSON** : Mêmes champs que POST

**Réponse succès (200)** :
```json
{
  "success": true,
  "message": "Solution mise à jour avec succès",
  "id": 1
}
```

---

### DELETE `/api/admin/solutions?id=1`

**Description** : Supprimer une solution

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Réponse succès (200)** :
```json
{
  "success": true,
  "message": "Solution supprimée avec succès"
}
```

**Notes** : La suppression est logique (soft delete) ou physique selon l'implémentation

---

### GET `/api/admin/formations`

**Description** : Liste des formations (pour admin)

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Réponse** : Array de formations avec tous les détails (modules, bénéfices, résultats)

---

### POST `/api/admin/formations`

**Description** : Créer une formation

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Corps JSON** :
```json
{
  "title": "Cybersécurité Avancée",
  "slug": "cybersecurite-avancee",
  "category": "Sécurité",
  "duration": "3 mois",
  "level": "Avancé",
  "price": "€1 500",
  "description": "...",
  "image_url": "/images/...",
  "modules": [
    {"title": "Module 1", "description": "...", "duration": "2 sem"}
  ],
  "benefits": ["Bénéfice 1"],
  "outcomes": ["Résultat 1"],
  "is_active": 1
}
```

---

### GET `/api/admin/stats`

**Description** : Statistiques du dashboard

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Réponse succès (200)** :
```json
{
  "counters": {
    "total_contacts": 145,
    "new_contacts": 8,
    "total_inscriptions": 92,
    "new_inscriptions": 5,
    "total_admins": 3
  },
  "last_contacts": [
    {
      "id": 1,
      "name": "Jean Dupont",
      "email": "jean@example.com",
      "subject": "Demande info",
      "status": "new",
      "created_at": "2024-01-15 10:30:45"
    }
  ],
  "last_inscriptions": [
    {
      "id": 1,
      "prenom": "Marie",
      "nom": "Martin",
      "formation_requested": "Web Dev",
      "status": "new",
      "created_at": "2024-01-15 09:15:20"
    }
  ]
}
```

---

### GET `/api/admin/leads/contact`

**Description** : Liste des messages de contact reçus

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Réponse succès (200)** :
```json
[
  {
    "id": 1,
    "name": "Jean Dupont",
    "email": "jean@example.com",
    "phone": "+225 01 01 57 30 54",
    "subject": "Demande de devis",
    "message": "Je souhaiterais...",
    "status": "new",
    "created_at": "2024-01-15 10:30:45"
  },
  ...
]
```

---

### GET `/api/admin/leads/inscriptions`

**Description** : Liste des demandes d'inscription

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Réponse succès (200)** :
```json
[
  {
    "id": 1,
    "prenom": "Marie",
    "nom": "Martin",
    "email": "marie@example.com",
    "phone": "+225 07 07 26 18 58",
    "formation_requested": "Développement Web",
    "niveau_experience": "Débutant",
    "motivation": "Je veux apprendre...",
    "status": "new",
    "created_at": "2024-01-15 09:15:20"
  },
  ...
]
```

---

### POST `/api/admin/upload`

**Description** : Upload une image

**Headers requis** :
```
X-Admin-Token: eyJ...
Content-Type: multipart/form-data
```

**Paramètres** :
- `file` (file) : Image à uploader
- `folder` (string) : solutions, formations, partners, etc.

**Réponse succès (200)** :
```json
{
  "success": true,
  "file_url": "/uploads/solutions/image_12345.png",
  "file_name": "image_12345.png"
}
```

---

### GET `/api/admin/audit-log`

**Description** : Journal d'audit des actions

**Headers requis** :
```
X-Admin-Token: eyJ...
```

**Paramètres** :
- `?page=1` (pagination)
- `?limit=50` (par défaut 50)

**Réponse succès (200)** :
```json
[
  {
    "id": 1,
    "admin_id": 5,
    "admin_name": "Admin User",
    "action": "update",
    "target_type": "content",
    "target_id": 1,
    "changes": {
      "page": "home",
      "key_name": "hero.title",
      "old": "Ancien titre",
      "new": "Nouveau titre"
    },
    "ip_address": "192.168.1.100",
    "created_at": "2024-01-15 10:30:45"
  },
  ...
]
```

---

## 📊 Diagrammes

### Flux d'authentification

```
┌─────────────────────────────────────────────────────────────┐
│ 1. Admin visite /admin/login                               │
└──────────────┬──────────────────────────────────────────────┘
               │
               ↓
        ┌──────────────────┐
        │ Soumet email     │
        │ + password       │
        └────────┬─────────┘
                 │
                 ↓
    ┌────────────────────────────┐
    │ POST /api/admin/login      │
    │ {email, password}          │
    └────────┬───────────────────┘
             │
             ↓
    ┌────────────────────────────────────┐
    │ Backend :                          │
    │ 1. Récupère admin par email        │
    │ 2. password_verify()               │
    │ 3. Génère JWT                      │
    │ 4. Retourne token + user info      │
    └────────┬───────────────────────────┘
             │
             ↓
    ┌──────────────────────────────┐
    │ Frontend stocke :             │
    │ localStorage['admin_token']   │
    │ localStorage['admin_user']    │
    └────────┬─────────────────────┘
             │
             ↓
    ┌────────────────────────────┐
    │ Router redirige            │
    │ vers /admin/dashboard      │
    └────────┬───────────────────┘
             │
             ↓
    ┌───────────────────────────────┐
    │ GET /api/admin/stats          │
    │ Header: X-Admin-Token: eyJ... │
    │                               │
    │ Backend vérifie le JWT        │
    │ et retourne les stats         │
    └───────────────────────────────┘
```

### Flux de modification de contenu

```
┌─────────────────────────────────────────────────────────┐
│ Admin accède à /admin/contents                          │
└──────────────┬──────────────────────────────────────────┘
               │
               ↓
    ┌──────────────────────────────────────────────┐
    │ GET /api/admin/contents                      │
    │ Récupère tous les contenus groupés par page  │
    └──────────────┬───────────────────────────────┘
                   │
                   ↓
    ┌────────────────────────────────────────────────────┐
    │ Frontend affiche les textareas                     │
    │ avec les valeurs actuelles                         │
    └────────────┬─────────────────────────────────────┘
                 │
                 ↓
    ┌──────────────────────────────────────────────────┐
    │ Admin modifie le texte dans textarea              │
    │ v-model → drafts[id]                             │
    └──────────────┬───────────────────────────────────┘
                   │ Click "Sauvegarder"
                   ↓
    ┌──────────────────────────────────────────────────┐
    │ PUT /api/admin/contents?id=1                      │
    │ Body: {value: "Nouvelle valeur"}                  │
    │ Header: X-Admin-Token: eyJ...                     │
    └──────────────┬───────────────────────────────────┘
                   │
                   ↓
    ┌──────────────────────────────────────────────────┐
    │ Backend :                                        │
    │ 1. Vérifie JWT                                   │
    │ 2. Vérifie rôle (admin)                         │
    │ 3. Valide que le contenu existe                 │
    │ 4. UPDATE contents SET value = ?                │
    │ 5. Enregistre l'action en audit_logs            │
    │ 6. Retourne succès                              │
    └──────────────┬───────────────────────────────────┘
                   │
                   ↓
    ┌──────────────────────────────────────────────────┐
    │ Frontend affiche "✓ Sauvé" pendant 2 sec         │
    │ Met à jour row.value = drafts[id]               │
    └──────────────┬───────────────────────────────────┘
                   │
                   ↓
    ┌──────────────────────────────────────────────────┐
    │ Prochain client visitant /accueil                │
    │ charge les contenus depuis l'API publique        │
    │ et affiche la NOUVELLE VALEUR                    │
    └──────────────────────────────────────────────────┘
```

### Flux de gestion d'erreur API

```
┌─────────────────────────────────────────┐
│ Frontend envoie requête                 │
└──────────────┬──────────────────────────┘
               │
               ↓
    ┌────────────────────────────────┐
    │ Axios interceptor request      │
    │ (ajoute token, .php, etc.)     │
    └────────────┬───────────────────┘
                 │
                 ↓
    ┌────────────────────────────────┐
    │ Requête HTTP envoyée           │
    └────────────┬───────────────────┘
                 │
       ┌─────────┴──────────┐
       │                    │
       ↓                    ↓
    ┌─────┐            ┌──────────────┐
    │ 200 │            │ 4xx / 5xx    │
    │ OK  │            │ Error        │
    └──┬──┘            └──────┬───────┘
       │                      │
       ↓                      ↓
  Succès                 ┌──────────────┐
       │                 │ 401          │
       │                 │ Unauthorized │
       │                 └──────┬───────┘
       │                        │
       │                        ↓
       │                  ┌─────────────────────┐
       │                  │ Interceptor réponse │
       │                  │ Efface token        │
       │                  │ Redirige /login     │
       │                  └─────────────────────┘
       │
       ↓
  ┌──────────────────┐
  │ Appel try/catch  │
  │ .then() ou await │
  └────────┬─────────┘
            │
      ┌─────┴──────────┐
      │                │
      ↓                ↓
   Succès           Erreur
   │                │
   ↓                ↓
 Mettre à       Afficher
 jour UI        message
```

---

## ⚠️ Codes d'erreur HTTP

| Code | Signification | Exemple |
|------|---------------|---------|
| 200 | OK - Requête réussie | Récupération de données |
| 201 | Created - Ressource créée | Création d'un contenu |
| 204 | No Content - Suppression réussie | DELETE réussi |
| 400 | Bad Request - Format invalide | JSON mal formé |
| 401 | Unauthorized - Authentification requise | Token manquant ou expiré |
| 403 | Forbidden - Permissions insuffisantes | Rôle inadequat |
| 404 | Not Found - Ressource inexistante | ID introuvable |
| 405 | Method Not Allowed | GET au lieu de POST |
| 422 | Unprocessable Entity - Validation échouée | Email invalide |
| 429 | Too Many Requests - Rate limit dépassé | Trop de login attempts |
| 500 | Internal Server Error - Erreur serveur | Bug PHP, PDO error |
| 503 | Service Unavailable - Serveur indisponible | Maintenance |

---

## 🔧 Exemples cURL

### Login

```bash
curl -X POST http://localhost/Access_informatique/backend/api/admin/login.php \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@accessinformatique.com","password":"password123"}'

# Réponse
{
  "success": true,
  "token": "eyJ...",
  "admin": {"id": 5, "email": "admin@..."}
}
```

### Récupérer les contenus d'une page

```bash
curl -X GET "http://localhost/Access_informatique/backend/api/contents.php?page=home" \
  -H "Accept: application/json"

# Réponse
[
  {"id": 1, "key_name": "hero.title", "value": "Des logiciels taillés..."},
  ...
]
```

### Modifier un contenu

```bash
TOKEN="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."

curl -X PUT "http://localhost/Access_informatique/backend/api/admin/contents.php?id=1" \
  -H "Content-Type: application/json" \
  -H "X-Admin-Token: $TOKEN" \
  -d '{"value":"Nouvelle valeur"}'

# Réponse
{
  "success": true,
  "message": "Contenu mis à jour avec succès.",
  "id": 1
}
```

### Créer une solution

```bash
TOKEN="eyJ..."

curl -X POST http://localhost/Access_informatique/backend/api/admin/solutions.php \
  -H "Content-Type: application/json" \
  -H "X-Admin-Token: $TOKEN" \
  -d '{
    "name": "Musa",
    "slug": "musa",
    "category": "Gestion",
    "short_description": "...",
    "is_active": 1
  }'

# Réponse
{
  "success": true,
  "message": "Solution créée avec succès",
  "id": 7
}
```

### Uploader une image

```bash
TOKEN="eyJ..."

curl -X POST http://localhost/Access_informatique/backend/api/admin/upload.php \
  -H "X-Admin-Token: $TOKEN" \
  -F "file=@/chemin/vers/image.png" \
  -F "folder=solutions"

# Réponse
{
  "success": true,
  "file_url": "/uploads/solutions/image_12345.png"
}
```

### Obtenir les statistiques

```bash
TOKEN="eyJ..."

curl -X GET http://localhost/Access_informatique/backend/api/admin/stats.php \
  -H "X-Admin-Token: $TOKEN" \
  -H "Accept: application/json"

# Réponse
{
  "counters": {
    "total_contacts": 145,
    "new_contacts": 8,
    "total_inscriptions": 92,
    "new_inscriptions": 5
  },
  "last_contacts": [...],
  "last_inscriptions": [...]
}
```

---

## 🔍 Debugging des requêtes API

### Dans le navigateur (DevTools)

```javascript
// Ouvrir la console (F12) et taper :

// Vérifier le token
console.log(localStorage.getItem('admin_token'))

// Faire une requête de test
fetch('/api/contents?page=home')
  .then(r => r.json())
  .then(d => console.log(d))

// Avec token
fetch('/api/admin/contents', {
  headers: { 'X-Admin-Token': localStorage.getItem('admin_token') }
})
  .then(r => r.json())
  .then(d => console.log(d))
```

### Avec Postman ou Insomnia

1. Crée une requête POST sur `/api/admin/login`
2. Body JSON : `{"email": "...", "password": "..."}`
3. Récupère le token de la réponse
4. Pour les requêtes admin, ajoute le header :
   - Key: `X-Admin-Token`
   - Value: `{token}`

### Avec Firefox Network Tab

1. Ouvre DevTools (F12)
2. Onglet "Network"
3. Exécute une action (login, save, etc.)
4. Clique sur la requête
5. Voir les détails :
   - Request Headers
   - Request Body
   - Response Headers
   - Response Body

---

Fin du guide API !

