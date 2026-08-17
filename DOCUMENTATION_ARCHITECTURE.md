# 📚 Documentation Complète de l'Architecture Access Informatique

**Table des matières**
1. [Architecture Globale](#architecture-globale)
2. [Backend - Structure et Flux](#backend)
3. [Frontend - Structure et Flux](#frontend)
4. [Communication Frontend/Backend](#communication)
5. [Exemple Complet : Modification d'un Contenu](#exemple-complet)
6. [Bonnes Pratiques & Points d'Attention](#bonnes-pratiques)

---

## 📐 Architecture Globale

### Vue d'ensemble schématique

```
┌─────────────────────────────────────────────────────────────────────┐
│                         UTILISATEUR FINAL                           │
│                    (Navigateur Web - Frontend)                      │
└──────────────────────────────┬──────────────────────────────────────┘
                               │
                               ↓
                    ┌──────────────────────┐
                    │   VUE.JS (Frontend)  │
                    │  - Pages (Accueil,   │
                    │    Apropos, etc)     │
                    │  - Composants        │
                    │  - Stores (Pinia)    │
                    │  - Services (Axios)  │
                    └──────────┬───────────┘
                               │
                    (Requête HTTP/JSON)
                               │
                               ↓
        ┌──────────────────────────────────────────────────────┐
        │            BACKEND PHP (API REST)                   │
        │     (/api, /api/admin endpoints)                    │
        │                                                      │
        │  Endpoints organisés par domaine:                   │
        │  - /api/contents (textes dynamiques)               │
        │  - /api/admin/contents (gestion admin)             │
        │  - /api/solutions, /formations, /partners         │
        │  - /api/forms/contact, /inscription               │
        │                                                      │
        │  Middleware & Services:                            │
        │  - Auth.php (JWT)                                  │
        │  - RoleCheck.php (permissions)                     │
        │  - Response.php (réponses JSON)                    │
        │  - Mailer.php (emails)                             │
        │  - RateLimit.php (throttling)                      │
        └──────────────────┬───────────────────────────────────┘
                           │
                           ↓
        ┌──────────────────────────────────────────────────────┐
        │         BASE DE DONNÉES (MySQL/PlanetHosted)        │
        │                                                      │
        │  Tables principales:                               │
        │  - admins (utilisateurs admin + password)          │
        │  - contents (textes dynamiques par page)           │
        │  - solutions (logiciels proposés)                  │
        │  - formations (catalogue de formations)           │
        │  - partners (partenaires)                          │
        │  - leads_contact (messages de contact)            │
        │  - leads_inscriptions (demandes inscription)      │
        │  - audit_logs (historique des actions)            │
        └──────────────────────────────────────────────────────┘
```

### Flux de données général

```
┌─────────────────────────────────────────────────────────────────┐
│ LECTURE (User navigateur)                                       │
├─────────────────────────────────────────────────────────────────┤
│ 1. Utilisateur va sur /                                         │
│ 2. Vue charge les données via API publique                      │
│ 3. Affichage des contenus                                       │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ MODIFICATION (Admin depuis dashboard)                           │
├─────────────────────────────────────────────────────────────────┤
│ 1. Admin se connecte (JWT token généré)                         │
│ 2. Admin modifie un contenu dans AdminContents.vue             │
│ 3. Click "Sauvegarder" → API PUT /api/admin/contents?id=5      │
│ 4. Backend vérifie JWT, met à jour DB, log l'action           │
│ 5. Frontend reçoit confirmation                                │
│ 6. Store est mis à jour (optionnel pour refresh)              │
│ 7. Lors du prochain appel du client à la page, données         │
│    chargées depuis /api/contents (version à jour)             │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔌 BACKEND - Structure et Flux en Détail

### 1️⃣ Organisation des fichiers

```
backend/
├── api/                          # Endpoints publics (pas d'auth)
│   ├── contents.php             # GET /api/contents?page=home
│   ├── formations.php           # GET /api/formations
│   ├── solutions.php            # GET /api/solutions
│   ├── partners.php             # GET /api/partners
│   ├── contents/
│   │   └── index.php            # GET /api/contents/
│   ├── formations/
│   │   └── index.php            # GET /api/formations/, ?slug=xxx
│   ├── forms/
│   │   ├── contact.php          # POST /api/forms/contact
│   │   └── inscription.php      # POST /api/forms/inscription
│   └── admin/                   # Endpoints protégés (JWT requis)
│       ├── login.php            # POST /api/admin/login
│       ├── logout.php           # POST /api/admin/logout
│       ├── contents.php         # GET/PUT /api/admin/contents
│       ├── solutions.php        # GET/POST/PUT/DELETE
│       ├── formations.php       # GET/POST/PUT/DELETE
│       ├── partners.php         # GET/POST/PUT/DELETE
│       ├── admins.php           # GET/POST/PUT/DELETE
│       ├── stats.php            # GET /api/admin/stats
│       ├── audit-log.php        # GET /api/admin/audit-log
│       └── upload.php           # POST /api/admin/upload
│
├── includes/                     # Services & middlewares
│   ├── config.php               # Chargement .env et constantes
│   ├── db.php                   # Singleton PDO (connexion DB)
│   ├── Auth.php                 # JWT encode/decode
│   ├── Response.php             # Utilitaires réponses JSON
│   ├── RoleCheck.php            # Vérification rôles & audit log
│   ├── RateLimit.php            # Throttling par IP
│   └── Mailer.php               # Envoi d'emails
│
├── database/
│   ├── schema.sql               # Structure tables
│   └── seeds.sql                # Données initiales
│
└── .env                         # Variables d'environnement
```

### 2️⃣ Flux d'une requête API

**Exemple : GET /api/admin/contents?page=home**

```
┌─────────────────────────────────────┐
│ 1. Requête HTTP reçue par Apache    │
└──────────────┬──────────────────────┘
               │ GET /api/admin/contents?page=home
               │ Headers: {X-Admin-Token: "eyJ0..."}
               ↓
┌────────────────────────────────────────────────────┐
│ 2. Routage par Apache (.htaccess)                  │
│    Redirection vers le fichier PHP                  │
└──────────────┬─────────────────────────────────────┘
               │
               ↓
┌───────────────────────────────────────────────────────────┐
│ 3. backend/api/admin/contents/index.php s'exécute        │
│                                                           │
│    require_once 'config.php'      → Charge les vars env │
│    require_once 'db.php'          → Singleton DB         │
│    require_once 'Response.php'    → Utilitaires JSON    │
│    require_once 'Auth.php'        → JWT utilities        │
│    require_once 'RoleCheck.php'   → Permissions         │
└──────────────┬────────────────────────────────────────────┘
               │
               ↓
┌─────────────────────────────────────────────────────────┐
│ 4. Middleware CORS                                      │
│    cors_headers() — Autorise requête cross-origin       │
└──────────────┬──────────────────────────────────────────┘
               │
               ↓
┌──────────────────────────────────────────────────────────┐
│ 5. Vérification de l'authentification                   │
│    $payload = require_auth()                            │
│                                                         │
│    - Lit le header X-Admin-Token                       │
│    - Décode le JWT                                     │
│    - Vérifie la signature avec JWT_SECRET             │
│    - Retourne le payload ou erreur 401                │
└──────────────┬───────────────────────────────────────────┘
               │
               ↓
┌──────────────────────────────────────────────────────────┐
│ 6. Vérification du rôle                                 │
│    require_role('admin', $current_admin_id)            │
│                                                         │
│    - Récupère le rôle de l'admin en DB                │
│    - Vérifie la hiérarchie (superadmin > admin > editor)│
│    - Lance erreur 403 si permissions insuffisantes    │
└──────────────┬───────────────────────────────────────────┘
               │
               ↓
┌──────────────────────────────────────────────────────────┐
│ 7. Routing basé sur la méthode HTTP                     │
│    match($_SERVER['REQUEST_METHOD']) {                  │
│      'GET'  => handle_get(),                           │
│      'PUT'  => handle_put(),                           │
│    }                                                    │
└──────────────┬───────────────────────────────────────────┘
               │
               ↓
┌──────────────────────────────────────────────────────────┐
│ 8. handle_get()                                         │
│    $db = get_db()  → Récupère le singleton PDO        │
│                                                         │
│    $page = $_GET['page']  → Récupère 'home'           │
│                                                         │
│    $stmt = $db->prepare(                              │
│      'SELECT id, page, key_name, label, value, ...'   │
│      'FROM contents WHERE page = ?'                    │
│    )                                                   │
│    $stmt->execute([$page])  → Requête préparée!       │
│    $rows = $stmt->fetchAll()                          │
└──────────────┬───────────────────────────────────────────┘
               │
               ↓
┌──────────────────────────────────────────────────────────┐
│ 9. Réponse formatée en JSON                            │
│    json_response($rows)                                 │
│                                                         │
│    - Définit header Content-Type: application/json    │
│    - Encode les données en JSON                        │
│    - Envoie le statut HTTP 200                         │
│    - Exit (arrête l'exécution)                         │
└──────────────┬───────────────────────────────────────────┘
               │
               ↓
┌────────────────────────────────────────────────────────┐
│ 10. Réponse HTTP retourne au client                   │
│                                                        │
│ HTTP/1.1 200 OK                                       │
│ Content-Type: application/json                        │
│                                                        │
│ [                                                      │
│   {"id": 1, "page": "home", "key_name": "hero.title",│
│    "label": "Titre principal", "value": "..."},      │
│   ...                                                 │
│ ]                                                     │
└────────────────────────────────────────────────────────┘
```

### 3️⃣ Détail des include/services backend

#### **config.php — Chargement de l'environnement**

```php
// Charge le fichier .env
load_env(__DIR__ . '/.env');

// Définit les constantes globales
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('JWT_SECRET', $_ENV['JWT_SECRET']);
define('JWT_EXPIRY', 3600);  // 1 heure
define('FRONTEND_URL', $_ENV['FRONTEND_URL']);

// Ces constantes sont ensuite utilisées partout dans le code
```

**Rôle** : Centralise la configuration. Évite les données en dur dans le code.

#### **db.php — Singleton de connexion**

```php
function get_db(): PDO {
    static $pdo = null;  // Conservé en mémoire pour toute la requête
    
    if ($pdo !== null) return $pdo;  // Retourne la même instance
    
    $pdo = new PDO(
        "mysql:host={DB_HOST};dbname={DB_NAME}",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, ...]
    );
    return $pdo;
}
```

**Rôle** : Une seule connexion DB pour toute la requête HTTP. Économise les ressources.

#### **Auth.php — Gestion JWT**

```php
class JWT {
    public static function encode(array $payload): string {
        // 1. Encode l'en-tête
        $header = b64encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        
        // 2. Encode le payload
        $claims = b64encode(json_encode($payload));
        
        // 3. Signe avec HMAC-SHA256
        $signature = b64encode(
            hash_hmac('sha256', "$header.$claims", JWT_SECRET, true)
        );
        
        return "$header.$claims.$signature";  // eyJ0eXA... format
    }
    
    public static function decode(string $token): ?array {
        // 1. Sépare header.claims.signature
        [$header, $claims, $signature] = explode('.', $token);
        
        // 2. Recalcule la signature attendue
        $expected = b64encode(hash_hmac('sha256', "$header.$claims", JWT_SECRET, true));
        
        // 3. Compare avec hash_equals() (temps constant = sécurité)
        if (!hash_equals($expected, $signature)) {
            return null;  // Token falsifié
        }
        
        // 4. Retourne le payload
        return json_decode(b64decode($claims), true);
    }
}

// Middleware
function require_auth(): array {
    // 1. Récupère le token depuis le header X-Admin-Token
    $token = $_SERVER['HTTP_X_ADMIN_TOKEN'] ?? '';
    
    // 2. Le décode
    $payload = JWT::decode($token);
    if (!$payload) {
        error_response('Non authentifié', 401);
    }
    
    // 3. Vérifie l'expiration
    if ($payload['exp'] < time()) {
        error_response('Token expiré', 401);
    }
    
    return $payload;  // {'sub': 5, 'email': 'admin@...', 'exp': ...}
}
```

**Rôle** : Authentification stateless. Chaque requête envoie un token, pas de session serveur.

#### **Response.php — Utilitaires de réponse**

```php
function cors_headers(): void {
    // Autorise les requêtes depuis le frontend
    header('Access-Control-Allow-Origin: ' . FRONTEND_URL);
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-Admin-Token, ...');
    
    // Répond aux preflight OPTIONS automatiquement
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

function json_response(mixed $data, int $status = 200): never {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;  // Important : arrête l'exécution
}

function error_response(string $message, int $status = 400): never {
    json_response(['success' => false, 'error' => $message], $status);
}

function get_json_body(): array {
    // Lit le corps JSON de la requête POST/PUT
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}
```

**Rôle** : Standardise les réponses et gère CORS pour cross-origin.

#### **RoleCheck.php — Permissions et audit**

```php
const ROLE_HIERARCHY = [
    'superadmin' => 3,  // Peut tout faire
    'admin' => 2,       // Gère contenu et utilisateurs
    'editor' => 1,      // Édite le contenu uniquement
];

function require_role(string $required, int $admin_id): void {
    // Récupère le rôle de l'admin
    $stmt = get_db()->prepare('SELECT role FROM admins WHERE id = ?');
    $stmt->execute([$admin_id]);
    $row = $stmt->fetch();
    
    // Compare les niveaux hiérarchiques
    if (ROLE_HIERARCHY[$row['role']] < ROLE_HIERARCHY[$required]) {
        error_response('Accès refusé', 403);
    }
}

function log_audit(int $admin_id, string $action, string $target, ?int $id, array $changes): void {
    // Enregistre chaque modification dans la table audit_logs
    $db = get_db();
    $stmt = $db->prepare('
        INSERT INTO audit_logs 
        (admin_id, action, target_type, target_id, changes, ip_address, user_agent, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ');
    $stmt->execute([
        $admin_id,
        $action,
        $target,
        $id,
        json_encode($changes),
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT'],
    ]);
}
```

**Rôle** : Contrôle d'accès granulaire et traçabilité des actions admin.

---

## 🎨 FRONTEND - Structure et Flux en Détail

### 1️⃣ Organisation des fichiers

```
src/
├── main.js                      # Point d'entrée Vue + Pinia
├── App.vue                      # Composant racine
├── router/
│   └── index.js                 # Vue Router - définition des routes
│
├── stores/                      # Pinia stores (gestion d'état)
│   ├── admin.js                 # État auth admin (token, user)
│   ├── content.js               # État contenus dynamiques (textes)
│   └── counter.js               # Exemple simple
│
├── services/
│   └── api.js                   # Instance Axios + intercepteurs
│
├── pages/                       # Routes principales
│   ├── Accueil.vue              # Page d'accueil
│   ├── Apropos.vue              # À propos
│   ├── Contact.vue              # Formulaire contact
│   ├── Formation.vue            # Page formations
│   ├── FormationDetail.vue      # Détail d'une formation
│   ├── Solutions.vue            # Catalogue solutions
│   └── ... autres pages
│
├── views/admin/                 # Pages du dashboard admin
│   ├── AdminLayout.vue          # Layout admin (sidebar + header)
│   ├── AdminLogin.vue           # Formulaire de connexion
│   ├── AdminDashboard.vue       # Vue d'ensemble
│   ├── AdminContents.vue        # Gestion textes statiques
│   ├── AdminSolutions.vue       # Gestion solutions
│   ├── AdminFormations.vue      # Gestion formations
│   └── ... autres pages admin
│
├── components/                  # Composants réutilisables
│   ├── Login_Inscription.vue    # Modal login/inscription
│   ├── FormationsList.vue       # Liste formations
│   └── ...
│
├── data/                        # Données statiques (fallback)
│   ├── homeData.js              # Textes, images accueil
│   ├── formations.js            # Formations (backup)
│   ├── solutionsData.js         # Solutions
│   └── ...
│
└── assets/
    ├── main.css                 # Styles globaux
    └── images/
```

### 2️⃣ Flux d'une action utilisateur

**Exemple : Admin modifie le titre de la page Accueil**

```
┌────────────────────────────────────────────────────┐
│ 1. Admin clique sur le bouton "Éditer" dans       │
│    la page /admin/contents                        │
└──────────────┬─────────────────────────────────────┘
               │
               ↓
┌────────────────────────────────────────────────────┐
│ 2. AdminContents.vue se monte                     │
│                                                    │
│    onMounted(async () => {                         │
│      const { data } = await api.get('/admin/contents')
│      groupedContents.value = data  ← Update reactif
│    })                                              │
└──────────────┬─────────────────────────────────────┘
               │
               ↓
┌────────────────────────────────────────────────────┐
│ 3. API appelle le backend via Axios              │
│                                                    │
│    api.interceptors.request.use((config) => {    │
│      if (token) {                                 │
│        config.headers['X-Admin-Token'] = token   │
│      }                                            │
│      return config                                │
│    })                                              │
└──────────────┬─────────────────────────────────────┘
               │
               │ GET /api/admin/contents
               │ Header: X-Admin-Token: eyJ0...
               ↓
        ┌──────────────────────────────┐
        │ Backend traite la requête    │
        │ (voir flux backend ci-dessus)│
        └──────────────┬───────────────┘
                       │
                       │ Réponse:
                       │ [{
                       │   id: 1,
                       │   page: "home",
                       │   key_name: "hero.title",
                       │   label: "Titre principal",
                       │   value: "Des logiciels taillés pour vous.",
                       │   updated_at: "..."
                       │ }, ...]
                       ↓
┌────────────────────────────────────────────────────┐
│ 4. Frontend reçoit les données                    │
│                                                    │
│    groupedContents.value = {                      │
│      home: [{...}, {...}],                        │
│      about: [{...}, {...}],                       │
│      ...                                          │
│    }  ← Réactivité Vue : UI se met à jour!       │
└──────────────┬─────────────────────────────────────┘
               │
               ↓
┌────────────────────────────────────────────────────┐
│ 5. Admin voit les champs de texte pré-remplis    │
│    dans les textareas avec les valeurs du store  │
│                                                    │
│    <textarea v-model="drafts[row.id]">           │
│      {{ drafts[1] }}  ← "Des logiciels..."       │
│    </textarea>                                     │
└──────────────┬─────────────────────────────────────┘
               │
               ↓
┌────────────────────────────────────────────────────┐
│ 6. Admin modifie le texte                         │
│                                                    │
│    v-model="drafts[row.id]"  → "Bienvenue à..."  │
│                                                   │
│    Cette modification est en mémoire local        │
│    (pas encore envoyée au backend)                │
└──────────────┬─────────────────────────────────────┘
               │
               ↓
┌────────────────────────────────────────────────────┐
│ 7. Admin clique "Sauvegarder"                      │
│                                                    │
│    @click="save(row)"                             │
└──────────────┬─────────────────────────────────────┘
               │
               ↓
┌────────────────────────────────────────────────────┐
│ 8. Fonction save() déclenche l'appel API          │
│                                                    │
│    async function save(row) {                     │
│      saving[row.id] = true  ← Désactiver bouton  │
│      try {                                        │
│        await api.put(`/admin/contents?id=${row.id}`,
│          { value: drafts[row.id] }  ← Envoyer la valeur
│        )                                          │
│        row.value = drafts[row.id]  ← Mettre à jour
│        saved[row.id] = true  ← Afficher checkmark│
│        setTimeout(() => saved[row.id] = false)  │
│      } finally {                                 │
│        saving[row.id] = false  ← Réactiver       │
│      }                                            │
│    }                                              │
└──────────────┬─────────────────────────────────────┘
               │
               │ PUT /api/admin/contents?id=1
               │ Body: {"value": "Bienvenue à..."}
               │ Header: X-Admin-Token: eyJ0...
               ↓
        ┌──────────────────────────────┐
        │ Backend traite le PUT        │
        │ 1. Vérifie le JWT           │
        │ 2. Vérifie les permissions  │
        │ 3. Valide le contenu existe │
        │ 4. UPDATE contents SET...   │
        │ 5. log_audit()              │
        │ 6. Retourne succès          │
        └──────────────┬───────────────┘
                       │
                       │ {
                       │   "success": true,
                       │   "message": "Sauvegardé",
                       │   "id": 1
                       │ }
                       ↓
┌────────────────────────────────────────────────────┐
│ 9. Frontend affiche la confirmation               │
│                                                    │
│    ✓ Sauvé  (pendant 2 secondes)                 │
└──────────────┬─────────────────────────────────────┘
               │
               ↓
┌────────────────────────────────────────────────────┐
│ 10. Prochain utilisateur visitant /accueil        │
│                                                    │
│     Le composant Accueil.vue fait :               │
│     onMounted(async () => {                       │
│       await contentStore.load('home')  ← Fetch   │
│     })                                             │
│                                                    │
│     const heroTitle = computed(() =>              │
│       contentStore.get('home', 'hero.title')      │
│     )                                              │
│                                                    │
│     <h1>{{ heroTitle }}</h1>  ← Affiche la nouvelle
│                                   valeur sauvegardée!
│    </h1>                                           │
└────────────────────────────────────────────────────┘
```

### 3️⃣ Pinia Stores - Gestion d'état

#### **stores/admin.js**

```javascript
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useAdminStore = defineStore('admin', () => {
  // ── État réactif ──────────────────────────────────────────────
  const token = ref(localStorage.getItem('admin_token') || null)
  const admin = ref(JSON.parse(localStorage.getItem('admin_user') || 'null'))
  
  // ── Computed properties ────────────────────────────────────────
  const isAuthenticated = computed(() => !!token.value)
  
  // ── Actions ────────────────────────────────────────────────────
  async function login(email, password) {
    const { data } = await api.post('/admin/login', { email, password })
    
    // Stocke le token et les infos admin
    token.value = data.token
    admin.value = data.admin
    
    // Persiste en localStorage pour survive aux refreshs
    localStorage.setItem('admin_token', data.token)
    localStorage.setItem('admin_user', JSON.stringify(data.admin))
    
    return data
  }
  
  async function logout() {
    try {
      await api.post('/admin/logout')
    } catch { /* non-critique */ }
    finally {
      token.value = null
      admin.value = null
      localStorage.removeItem('admin_token')
      localStorage.removeItem('admin_user')
    }
  }
  
  return { token, admin, isAuthenticated, login, logout }
})

// Utilisation dans un composant :
// import { useAdminStore } from '@/stores/admin'
//
// const store = useAdminStore()
// console.log(store.isAuthenticated)  // computed
// await store.login(email, password)  // action
```

**Points clés** :
- Stockage du token en localStorage (persiste après refresh)
- Computed properties pour la réactivité
- Actions asynchrones pour les appels API

#### **stores/content.js**

```javascript
import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

export const useContentStore = defineStore('content', () => {
  // ── État ────────────────────────────────────────────────────────
  const pages = ref({})              // { home: { 'hero.title': '...' }, ... }
  const loading = ref({})            // { home: true, about: false, ... }
  const errors = ref({})             // { home: null, about: '...' }
  
  // ── Actions ──────────────────────────────────────────────────────
  async function load(page) {
    // Évite de charger deux fois la même page
    if (pages.value[page] !== undefined || loading.value[page]) {
      return
    }
    
    loading.value[page] = true
    errors.value[page] = null
    
    try {
      const { data } = await api.get(`/contents?page=${page}`)
      
      // Transforme le tableau de lignes en objet {key: value}
      pages.value[page] = {}
      for (const row of data) {
        pages.value[page][row.key_name] = row.value
      }
    } catch (e) {
      errors.value[page] = e.message
      pages.value[page] = {}  // Fallback : objet vide
    } finally {
      loading.value[page] = false
    }
  }
  
  // ── Getters ──────────────────────────────────────────────────────
  function get(page, key, fallback = '') {
    return pages.value[page]?.[key] ?? fallback
  }
  
  function isLoading(page) {
    return loading.value[page] === true
  }
  
  return { pages, loading, errors, load, get, isLoading }
})

// Utilisation dans un composant :
// import { useContentStore } from '@/stores/content'
//
// const contentStore = useContentStore()
//
// onMounted(async () => {
//   await contentStore.load('home')
// })
//
// const heroTitle = computed(() => 
//   contentStore.get('home', 'hero.title', 'Titre par défaut')
// )
```

**Points clés** :
- Cache en mémoire (une page chargée une seule fois)
- Fallback pour les valeurs manquantes
- État isLoading et errors pour UX

### 4️⃣ Axios - Communication HTTP

#### **services/api.js**

```javascript
import axios from 'axios'

// Crée l'instance Axios
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost/Access_Informatique/backend/api',
  timeout: 15000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

// ── Intercepteur de requête ────────────────────────────────────────
api.interceptors.request.use((config) => {
  if (config.url) {
    // 1. Ajoute .php à l'URL si pas d'extension
    const hasExtension = /\.\w+$/.test(config.url)
    if (!hasExtension) {
      config.url = config.url.replace(/\/?$/, '.php')
    }
    
    // 2. Ajoute le token JWT pour les routes /admin
    const token = localStorage.getItem('admin_token')
    if (token && config.url.includes('/admin')) {
      config.headers['X-Admin-Token'] = token
    }
  }
  return config
})

// ── Intercepteur de réponse ────────────────────────────────────────
api.interceptors.response.use(
  (response) => response,
  (error) => {
    // Si 401 sur une page admin, efface le token et redirige vers login
    if (error.response?.status === 401) {
      const isAdminPage = window.location.pathname.startsWith('/admin')
      if (isAdminPage && !window.location.pathname.includes('/login')) {
        localStorage.removeItem('admin_token')
        window.location.href = '/admin/login'
      }
    }
    return Promise.reject(error)
  }
)

export default api
```

**Points clés** :
- Ajoute automatiquement .php aux URLs (problème Apache mod_fcgid)
- Ajoute le token JWT aux requêtes admin
- Gère automatiquement les tokens expirés (401)

### 5️⃣ Vue Router - Navigation

#### **router/index.js**

```javascript
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    // Routes publiques
    { path: '/', name: 'accueil', component: Accueil },
    { path: '/contact', name: 'contact', component: Contact },
    { path: '/formations', name: 'formations', component: FormationsList },
    
    // Routes admin
    { path: '/admin/login', name: 'admin-login', component: AdminLogin },
    {
      path: '/admin',
      component: AdminLayout,
      meta: { requiresAuth: true },  ← Marker pour le guard
      children: [
        { path: 'dashboard', component: AdminDashboard },
        { path: 'contents', component: AdminContents },
        { path: 'formations', component: AdminFormations },
        // ...
      ],
    },
  ],
})

// ── Navigation Guard ──────────────────────────────────────────────
router.beforeEach((to) => {
  if (!to.meta.requiresAuth) return true  // Route publique : pas de check
  
  const token = localStorage.getItem('admin_token')
  if (!token) {
    // Pas de token : redirige vers login
    return { name: 'admin-login', query: { redirect: to.fullPath } }
  }
  
  return true  // Token existe : autorise l'accès
})

export default router
```

**Points clés** :
- Chargement différé des pages admin (lazy loading)
- Navigation guard vérifie l'authentification
- Redirection vers login si pas de token

---

## 🔄 Communication Frontend/Backend - Détail

### 1️⃣ Headers HTTP et métadonnées

#### Requête : GET /api/contents?page=home

```http
GET /api/contents?page=home HTTP/1.1
Host: localhost
Origin: http://localhost:5173
Content-Type: application/json
Accept: application/json
User-Agent: Mozilla/5.0...

(pas de corps)
```

**Points** :
- `Origin` : D'où vient la requête (utilisé pour CORS)
- `Content-Type` : Format des données envoyées
- `Accept` : Format attendu en retour

#### Requête : PUT /api/admin/contents?id=1

```http
PUT /api/admin/contents?id=1 HTTP/1.1
Host: localhost
Origin: http://localhost:5173
Content-Type: application/json
Accept: application/json
X-Admin-Token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWI6NSwi...
User-Agent: Mozilla/5.0...

{"value": "Nouvelle valeur du contenu"}
```

**Points** :
- `X-Admin-Token` : Token JWT (au lieu de Bearer qui pose pb avec Apache)
- Paramètre `?id=1` : Ressource à modifier
- Corps JSON : Les données à envoyer

#### Réponse : HTTP/1.1 200 OK

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
Access-Control-Allow-Origin: http://localhost:5173
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Credentials: true

[
  {
    "id": 1,
    "page": "home",
    "key_name": "hero.title",
    "label": "Titre principal",
    "value": "Des logiciels taillés pour vous.",
    "updated_at": "2024-01-15 10:30:45"
  },
  ...
]
```

### 2️⃣ Sérialisation des données

#### Envoi : JSON

```javascript
// Depuis le frontend (Axios)
const data = {
  email: 'admin@access.com',
  password: 'SecurePassword123!',
  formations: [1, 2, 3],
  metadata: { source: 'admin-panel' },
}

// Axios sérialise automatiquement en JSON
axios.post('/api/admin/login', data)

// Envoi brut : (voir ci-dessus)
// {"email":"admin@access.com","password":"...","formations":[1,2,3],"metadata":{"source":"admin-panel"}}
```

#### Réception : JSON

```php
// Backend (PHP)
$body = get_json_body();  // Tableau PHP associatif

$email = $body['email'];           // "admin@access.com"
$password = $body['password'];     // "SecurePassword123!"
$formations = $body['formations']; // [1, 2, 3]

// Les types sont préservés : int, string, array, bool
```

#### Retour : JSON

```php
// Backend retourne des données PHP
$data = [
    'success' => true,
    'token' => 'eyJ...',
    'admin' => [
        'id' => 5,
        'name' => 'Jean Dupont',
        'email' => 'admin@access.com',
        'role' => 'admin',
    ],
    'expiresIn' => 3600,
];

json_response($data);  // Convertit en JSON et envoie
```

### 3️⃣ Authentification par JWT

#### Flux complet

```
┌────────────────────────────────────────────────────────────────┐
│ 1. GENERATION DU TOKEN (lors du login)                        │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│ Admin envoie : POST /api/admin/login                          │
│   { "email": "...", "password": "..." }                       │
│                                                                │
│ Backend :                                                      │
│   1. Cherche l'admin par email                                │
│   2. Vérifie password_verify($password, $hash_bd)            │
│   3. Appelle generate_token($admin_id, $email)              │
│                                                                │
│      $payload = [                                             │
│        'sub' => 5,              // subject (admin_id)         │
│        'email' => 'admin@...',  // email                      │
│        'iat' => time(),         // issued at                  │
│        'exp' => time() + 3600   // expiration (1h)            │
│      ];                                                        │
│                                                                │
│      $token = JWT::encode($payload);                         │
│      // retourne: "eyJ0eXAiOiJKV1QiLCJhbGc..."              │
│                                                                │
│   4. Retourne le token au frontend                           │
└────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────┐
│ 2. STOCKAGE DU TOKEN (frontend)                               │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│ localStorage.setItem('admin_token',                           │
│   'eyJ0eXAiOiJKV1QiLCJhbGc...'                              │
│ )                                                              │
│                                                                │
│ Le token persiste : survit aux refreshs et fermetures         │
│                                                                │
│ Structure du token (3 parties séparées par des points) :      │
│                                                                │
│   eyJ0eXAiOiJKV1QiLCJhbGc...                                 │
│   ↑ Header base64                                             │
│                      .eyJzdWIiOjUsImVtYWls...                │
│                      ↑ Payload base64                          │
│                                      .Vk9T...                │
│                                      ↑ Signature              │
│                                                                │
│ Décode en :                                                   │
│   Header: {"typ":"JWT","alg":"HS256"}                        │
│   Payload: {"sub":5,"email":"admin@...","exp":1234567890}   │
│   Signature: (calculée avec JWT_SECRET)                      │
└────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────┐
│ 3. UTILISATION DU TOKEN (requêtes suivantes)                  │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│ Frontend envoie : GET /api/admin/contents                    │
│   Header: X-Admin-Token: eyJ0eXAiOiJKV1QiLCJhbGc...         │
│                                                                │
│ Backend reçoit :                                              │
│   $token = $_SERVER['HTTP_X_ADMIN_TOKEN']                   │
│   $payload = JWT::decode($token)                             │
│                                                                │
│   Vérifications :                                             │
│   1. Format valide ? (3 parties séparées par .)              │
│   2. Signature correcte ? (HMAC-SHA256 avec JWT_SECRET)      │
│   3. Pas expiré ? ($payload['exp'] > time())                │
│   4. Rôle suffisant ? (require_role('admin', $payload['sub']))
│                                                                │
│   Si tout ok : Continue, sinon : error_response(401 ou 403)  │
└────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────┐
│ 4. EXEMPLE : Vérifier la signature JWT                        │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│ Token reçu :                                                  │
│   "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.                   │
│    eyJzdWIiOjUsImVtYWlsIjoiYWRtaW5AYWNjZXNzLmNvbSIsImlhd... │
│    Vk9TS2t0MEc4ZV9vbUhLN05TRi1LaUROQm5PQzZ2aU5RTlE="        │
│                                                                │
│ Backend calcule :                                             │
│   header.payload = "eyJ0eXAi...eyJzdWIi..."                 │
│   signature_attendue = HMAC-SHA256(                          │
│     "eyJ0eXAi...eyJzdWIi...",                               │
│     "MySecretKey123"  ← JWT_SECRET                           │
│   )                                                            │
│   signature_attendue = "Vk9TS2t0MEc4ZV9v..."                │
│                                                                │
│   signature_reçue = "Vk9TS2t0MEc4ZV9v..."                   │
│                                                                │
│   hash_equals(signature_attendue, signature_reçue) ?         │
│   => true → Token valide                                      │
│                                                                │
│ Protégé contre :                                              │
│   - Falsification du payload                                  │
│   - Modification du token par le client                       │
│   - Timing attacks (hash_equals = temps constant)            │
└────────────────────────────────────────────────────────────────┘
```

**Avantages du JWT** :
- Stateless : pas de session serveur, scalable
- Sécurisé : signature HMAC impossible à contrefaire
- Standard : fonctionne partout (REST, GraphQL, etc.)
- Auto-contenu : porte toutes les infos (sub, email, exp)

---

## 💡 Exemple Complet : Modification du Titre d'Accueil

Prenons un cas concret complet du bout à bout.

### Scénario

L'admin veut changer le titre hero de la page Accueil de :
```
"Des logiciels taillés pour vous."
```
vers :
```
"Transformez votre gestion avec nos solutions."
```

### Étapes détaillées

#### ÉTAPE 1 : Admin accède au dashboard

```
1. Admin va sur http://localhost:5173/admin
2. Vue Router détecte meta.requiresAuth
3. Guard vérifie localStorage.getItem('admin_token')
   - Si pas de token → redirige vers /admin/login
   - Si token existe → autorise l'accès
```

#### ÉTAPE 2 : Admin se connecte

```
Frontend (Login.vue) :
  @submit="submitLogin"
  
  const { data } = await api.post('/api/admin/login', {
    email: form.email,      // "admin@access.com"
    password: form.password // "password123"
  })

Backend (POST /api/admin/login) :
  1. Reçoit les credentials
  2. Cherche l'admin dans la DB :
     SELECT * FROM admins WHERE email = "admin@access.com"
  3. Vérifie le hash :
     password_verify("password123", "$2y$10$ZDo...")  → true
  4. Génère le token JWT :
     payload = {
       'sub' => 5,
       'email' => 'admin@access.com',
       'exp' => 1726581234  (dans 1 heure)
     }
     token = JWT::encode(payload)
     → "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjUsImVtYWlsIjoiYWRtaW5AYWNjZXNzLmNvbSIsImlhdCI6MTcyNjU3Nzg3MCwiZXhwIjoxNzI2NTgxNDcwfQ...."
  5. Retourne :
     {
       "success": true,
       "token": "eyJ...",
       "admin": {
         "id": 5,
         "name": "Admin User",
         "email": "admin@access.com",
         "role": "admin"
       }
     }

Frontend (Login.vue) :
  localStorage.setItem('admin_token', "eyJ...")
  localStorage.setItem('admin_user', JSON.stringify({...}))
  
  router.push('/admin/dashboard')
```

#### ÉTAPE 3 : Admin clique sur "Contenus"

```
Frontend (Vue Router) :
  Navigue vers /admin/contents
  → Charge AdminContents.vue

AdminContents.vue (onMounted) :
  contentStore = useAdminStore()
  
  onMounted(async () => {
    try {
      const { data } = await api.get('/admin/contents')
      groupedContents.value = data
    } catch (err) {
      fetchError.value = err.message
    } finally {
      loading.value = false
    }
  })
```

**Axios effectue la requête :**
```javascript
// Intercepteur request
config.headers['X-Admin-Token'] = localStorage.getItem('admin_token')
// → "eyJ..."

// Appel
GET http://localhost/Access_Informatique/backend/api/admin/contents.php
Headers: {
  'X-Admin-Token': 'eyJ...',
  'Content-Type': 'application/json'
}
```

**Backend (GET /api/admin/contents) :**
```php
cors_headers();  // Autorise requête cross-origin

$payload = require_auth();
// Décode le token, vérifie la signature et l'expiration
// Retourne : ['sub' => 5, 'email' => '...', 'iat' => ..., 'exp' => ...]

$current_admin_id = $payload['sub'];  // 5

require_role('admin', 5);
// Récupère le rôle de l'admin 5 en DB
// admin >= admin ? Oui → continue

$db = get_db();
$stmt = $db->prepare('SELECT * FROM contents ORDER BY page ASC');
$stmt->execute();
$rows = $stmt->fetchAll();

// Regroupe par page
$result = [
  'home' => [
    ['id' => 1, 'page' => 'home', 'key_name' => 'hero.title', 'value' => '...', ...],
    ['id' => 2, 'page' => 'home', 'key_name' => 'hero.description', 'value' => '...', ...],
  ],
  'about' => [
    ['id' => 10, 'page' => 'about', 'key_name' => 'hero.title', 'value' => '...', ...],
    ...
  ],
  ...
];

json_response($result);  // Retourne le JSON
```

**Frontend reçoit :**
```javascript
// Réponse HTTP 200 OK
{
  "home": [
    {
      "id": 1,
      "page": "home",
      "key_name": "hero.title",
      "label": "Titre principal - Hero",
      "value": "Des logiciels taillés pour vous.",
      "updated_at": "2024-01-15 10:30:45"
    },
    {
      "id": 2,
      "page": "home",
      "key_name": "hero.description",
      "label": "Description - Hero",
      "value": "Access Informatique...",
      "updated_at": "2024-01-15 10:30:45"
    }
  ],
  "about": [ ... ],
  "contact": [ ... ]
}

// Mise à jour du composant
groupedContents.value = data

// Vue.js détecte le changement et re-render
// Les textareas se remplissent avec les valeurs
```

**Interface affichée :**
```
┌────────────────────────────────────────────────┐
│ 📄 Page d'accueil (2 textes)                   │
├────────────────────────────────────────────────┤
│                                                 │
│ Titre principal - Hero                         │
│ hero.title                                      │
│ [textarea] Des logiciels taillés pour vous.   │
│ [Sauvegarder] ✓ Sauvé                         │
│                                                 │
│ Description - Hero                             │
│ hero.description                                │
│ [textarea] Access Informatique conçoit...     │
│ [Sauvegarder]                                 │
│                                                 │
└────────────────────────────────────────────────┘
```

#### ÉTAPE 4 : Admin modifie le titre

```
Frontend (Template) :
  <textarea v-model="drafts[1]"></textarea>
  
  Utilisateur tape : "Transformez votre gestion..."
  
  v-model binding → drafts[1] = "Transformez votre gestion..."
  
  Cette modification est LOCALE, pas encore envoyée au backend
```

#### ÉTAPE 5 : Admin clique "Sauvegarder"

```
Frontend (AdminContents.vue) :
  @click="save(row)"
  
  row = {
    id: 1,
    page: 'home',
    key_name: 'hero.title',
    value: 'Des logiciels...',  ← Valeur actuelle
    ...
  }
  
  async function save(row) {
    saving[row.id] = true  // Désactiver le bouton
    
    try {
      await api.put(`/admin/contents?id=${row.id}`, {
        value: drafts[row.id]  // "Transformez votre gestion..."
      })
      
      // Succès : mettre à jour la référence
      row.value = drafts[row.id]
      saved[row.id] = true  // Afficher checkmark
      
      setTimeout(() => saved[row.id] = false, 2000)
    } catch (err) {
      // Erreur : afficher message
      formError.value = err.response?.data?.error
    } finally {
      saving[row.id] = false  // Réactiver le bouton
    }
  }
```

**Axios effectue la requête PUT :**
```javascript
// Intercepteur request
config.headers['X-Admin-Token'] = 'eyJ...'
config.url = '/admin/contents.php?id=1'  // Ajoute .php

// Appel
PUT http://localhost/Access_Informatique/backend/api/admin/contents.php?id=1
Headers: {
  'X-Admin-Token': 'eyJ...',
  'Content-Type': 'application/json'
}
Body: {
  "value": "Transformez votre gestion avec nos solutions."
}
```

#### ÉTAPE 6 : Backend traite la modification

```php
// POST /api/admin/contents/index.php
cors_headers();

$method = $_SERVER['REQUEST_METHOD'];  // "PUT"

$payload = require_auth();  // Vérifie JWT
$current_admin_id = $payload['sub'];   // 5

require_role('admin', 5);  // Vérifie rôle

match ($method) {
  'PUT' => handle_put(),
};

function handle_put() {
  global $current_admin_id;
  
  $id = $_GET['id'];  // 1
  $body = get_json_body();  // {'value': 'Transformez...'}
  
  if ($id <= 0) {
    error_response('ID invalide', 422);
  }
  
  $value = $body['value'];  // "Transformez..."
  
  if ($value === null) {
    error_response('Champ "value" requis', 422);
  }
  
  $db = get_db();
  
  // Vérifier que le contenu existe
  $stmt = $db->prepare('SELECT page, key_name, value FROM contents WHERE id = ?');
  $stmt->execute([1]);
  $row = $stmt->fetch();
  
  if (!$row) {
    error_response('Contenu introuvable', 404);
  }
  
  // Mettre à jour
  $stmt = $db->prepare('UPDATE contents SET value = ? WHERE id = ?');
  $stmt->execute([$value, 1]);
  
  // Enregistrer l'action en audit log
  log_audit(5, 'update', 'content', 1, [
    'page' => 'home',
    'key_name' => 'hero.title',
    'old' => 'Des logiciels taillés pour vous.',
    'new' => 'Transformez votre gestion avec nos solutions.'
  ]);
  
  // INSERT INTO audit_logs (...) VALUES (5, 'update', 'content', 1, '...')
  
  json_response([
    'success' => true,
    'message' => 'Contenu mis à jour avec succès.',
    'id' => 1
  ]);
}
```

**Base de données :**
```sql
-- Avant
SELECT * FROM contents WHERE id = 1;
-- id=1, page='home', key_name='hero.title', value='Des logiciels taillés pour vous.'

-- Après UPDATE
SELECT * FROM contents WHERE id = 1;
-- id=1, page='home', key_name='hero.title', value='Transformez votre gestion avec nos solutions.'

-- Audit log inséré
SELECT * FROM audit_logs WHERE target_id = 1 ORDER BY created_at DESC LIMIT 1;
-- admin_id=5, action='update', target_type='content', target_id=1,
-- changes='{"old":"Des logiciels...","new":"Transformez..."}'
```

#### ÉTAPE 7 : Frontend reçoit la confirmation

```javascript
// Réponse HTTP 200 OK
{
  "success": true,
  "message": "Contenu mis à jour avec succès.",
  "id": 1
}

// Fonction save() continue
row.value = drafts[row.id]  // Mettre à jour la référence
saved[row.id] = true        // Afficher checkmark

// UI affiche : ✓ Sauvé (pendant 2 sec)
```

#### ÉTAPE 8 : Utilisateur visite /accueil

```
Frontend (Accueil.vue - onMounted) :
  const contentStore = useContentStore()
  
  onMounted(async () => {
    await contentStore.load('home')
  })
```

**Axios effectue la requête GET :**
```javascript
GET http://localhost/Access_Informatique/backend/api/contents.php?page=home
// Pas besoin de token, c'est une route publique

Headers: {
  'Content-Type': 'application/json'
}
```

**Backend (GET /api/contents) :**
```php
cors_headers();  // Autorise CORS

$page = $_GET['page'];  // 'home'

$db = get_db();
$stmt = $db->prepare('SELECT key_name, value FROM contents WHERE page = ? ORDER BY id ASC');
$stmt->execute([$page]);
$rows = $stmt->fetchAll();

// Transforme en objet {key: value}
$data = [];
foreach ($rows as $row) {
  $data[$row['key_name']] = $row['value'];
}

json_response(['data' => $data]);
```

**Réponse :**
```javascript
{
  "data": {
    "hero.title": "Transformez votre gestion avec nos solutions.",
    "hero.description": "Access Informatique...",
    ...
  }
}
```

**Frontend met à jour le store :**
```javascript
// Dans contentStore.load('home')
pages.value['home'] = data.data;
// pages.value = {
//   'home': {
//     'hero.title': 'Transformez votre gestion avec nos solutions.',
//     'hero.description': '...',
//     ...
//   }
// }
```

**Composant Accueil.vue affiche la valeur :**
```javascript
const heroTitle = computed(() => 
  contentStore.get('home', 'hero.title', 'Fallback')
)

// heroTitle.value = "Transformez votre gestion avec nos solutions."

// Template
<h1>{{ heroTitle }}</h1>

// Résultat final affichéé
// "Transformez votre gestion avec nos solutions."  ← NOUVELLE VALEUR !
```

**L'utilisateur voit le nouveau titre ! ✅**

---

## 🛡️ Bonnes Pratiques & Points d'Attention

### 1️⃣ Sécurité

#### ✅ Ce qui est bien fait

```php
// 1. Requêtes préparées (SQL injection)
$stmt = $db->prepare('SELECT * FROM admins WHERE email = ?');
$stmt->execute([$email]);  // Paramètres séparés du SQL

// ❌ Jamais comme ça
$stmt = $db->prepare("SELECT * FROM admins WHERE email = '$email'");  // SQL injection!

// 2. password_verify() (timing attacks)
if (password_verify($password, $hash_bd)) { ... }  // Temps constant

// ❌ Ne jamais faire
if (md5($password) === $hash_bd) { ... }  // Sécurité foible

// 3. JWT avec signature HMAC
$signature = hash_hmac('sha256', "$header.$claims", JWT_SECRET, true);
if (!hash_equals($expected, $signature)) { ... }  // Temps constant

// 4. Rate limiting
rate_limit('admin_login_' . $ip, 5, 300);  // 5 tentatives par 5 min

// 5. Logging des actions
log_audit($admin_id, 'action', 'target', $id, $changes);  // Traçabilité
```

#### ⚠️ Points à surveiller

```javascript
// ❌ Pas de secrets en frontend (jamais!)
// const API_KEY = 'sk_live_...';  DANGEREUX

// ✅ Les tokens JWT en localStorage
// OK car le payload est lisible (pas de secrets dedans)
// mais la signature est vérifiée côté serveur

// ❌ Pas de données sensibles en localStorage
// localStorage.setItem('user_password', '...');  TRÈS DANGEREUX

// ✅ Secrets uniquement côté serveur
// JWT_SECRET = 'MySecretKey...'  → Jamais exposé
```

### 2️⃣ Performance

#### Frontend

```javascript
// ✅ Lazy loading des routes admin
const AdminDashboard = () => import('../views/admin/AdminDashboard.vue')

// ❌ Import eager (alourdit le bundle principal)
import AdminDashboard from '../views/admin/AdminDashboard.vue'

// ✅ Cache des données
const contentStore = useContentStore()
onMounted(async () => {
  await contentStore.load('home')  // Chargé une seule fois
})

// ❌ Pas de cache (requête à chaque fois)
onMounted(async () => {
  const { data } = await api.get('/contents?page=home')
  // Chaque visiteur refait la requête
})

// ✅ Computed properties
const heroTitle = computed(() => 
  contentStore.get('home', 'hero.title', 'Fallback')
)

// ❌ Appel de fonction à chaque rendu
<h1>{{ contentStore.get('home', 'hero.title') }}</h1>
```

#### Backend

```php
// ✅ Singleton PDO
static $pdo = null;
if ($pdo !== null) return $pdo;  // Réutilise la connexion

// ❌ Nouvelle connexion à chaque fois
$pdo = new PDO(...);  // Coûteux!

// ✅ Requête préparée (MySQL met en cache le plan d'exécution)
$stmt = $db->prepare('SELECT ... WHERE id = ?');
$stmt->execute([5]);

// ❌ Requête directe
$db->query("SELECT ... WHERE id = 5");

// ✅ Sélection des colonnes
$stmt = $db->prepare('SELECT id, name, email FROM admins WHERE id = ?');

// ❌ Tous les champs inutiles
$stmt = $db->prepare('SELECT * FROM admins WHERE id = ?');
// (inclut password_hash, etc. inutiles)

// ✅ Pagination pour les listes
$limit = 20;
$offset = ($page - 1) * $limit;
$stmt = $db->prepare('SELECT ... LIMIT ? OFFSET ?');

// ❌ Tout charger
$stmt = $db->query('SELECT * FROM leads_contact');  // Peut être énorme
```

### 3️⃣ Gestion des erreurs

#### Frontend

```javascript
// ✅ Gestion des erreurs API
try {
  const { data } = await api.get('/admin/contents')
  groupedContents.value = data
} catch (err) {
  if (err.response?.status === 401) {
    // Token expiré, logout automatique
    localStorage.removeItem('admin_token')
    router.push('/admin/login')
  } else if (err.response?.status === 403) {
    // Permissions insuffisantes
    errors.value = 'Vous n\'avez pas accès à cette action'
  } else {
    // Erreur serveur
    errors.value = err.response?.data?.error || 'Erreur serveur'
  }
} finally {
  loading.value = false
}

// ❌ Pas de gestion
const { data } = await api.get('/admin/contents')
groupedContents.value = data
```

#### Backend

```php
// ✅ Erreurs avec codes HTTP appropriés
error_response('Email invalide', 422);      // Unprocessable Entity
error_response('Non authentifié', 401);      // Unauthorized
error_response('Accès refusé', 403);         // Forbidden
error_response('Non trouvé', 404);           // Not Found
error_response('Erreur serveur', 500);       // Internal Server Error

// ❌ Tous les erreurs en 200 OK
json_response(['error' => 'Email invalide'], 200);

// ✅ Logging des erreurs
try {
  // code...
} catch (PDOException $e) {
  error_log('[API/admin/contents] PDO: ' . $e->getMessage());
  error_response('Erreur interne du serveur', 500);
}

// ❌ Pas de log
} catch (Exception $e) {
  error_response('Erreur interne', 500);
}
```

### 4️⃣ Validations

#### Entrées utilisateur

```php
// ✅ Validation
$email = trim($_POST['email'] ?? '');
if ($email === '') {
  error_response('Email requis', 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  error_response('Email invalide', 422);
}

$password = $_POST['password'] ?? '';
if (strlen($password) < 8) {
  error_response('Mot de passe min 8 caractères', 422);
}

// ❌ Pas de validation
$email = $_POST['email'];
$password = $_POST['password'];
// Risques : SQL injection, logique métier cassée, etc.

// ✅ Limite de taille
if (strlen((string) $value) > 65535) {
  error_response('Valeur trop longue', 422);
}

// ❌ Pas de limite
$value = $body['value'];  // Peut être énorme
```

#### Output encoding

```php
// ✅ Retour JSON (auto-échappé)
json_response(['message' => 'Contenu créé']);

// ❌ Pas d'échappement (si retour HTML)
echo "<h1>$title</h1>";  // XSS si $title = "<script>alert('XSS')</script>"

// ✅ Échappement HTML
echo "<h1>" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "</h1>";
```

### 5️⃣ Scalabilité

```
Current setup : Single server (Backend + Frontend + DB)

Limitations:
├── Connexions BD limitées
├── Pas de cache distribué (Redis)
├── Pas de queue (pour emails async)
└── Pas de CDN pour les assets

Future improvements:
├── Séparation Frontend/Backend sur serveurs différents
├── Cache Redis pour sessions et contenus
├── Queue RabbitMQ/Redis pour emails asynchrones
├── CDN Cloudflare pour les images
├── Load balancing pour le backend
└── Read replicas pour la base de données
```

### 6️⃣ Monitoring et observabilité

```php
// ✅ Logging structuré
error_log(json_encode([
  'timestamp' => date('Y-m-d H:i:s'),
  'level' => 'ERROR',
  'endpoint' => '/api/admin/contents',
  'admin_id' => 5,
  'error' => 'Database connection failed',
  'stack_trace' => ...
]));

// ✅ Métriques
log_audit($admin_id, 'action', 'target', $id, $changes);

// ✅ Audit log pour chaque action
// Permet de tracer qui a fait quoi et quand
```

---

## 📊 Résumé - Vue d'ensemble

### Stack technique

```
Frontend (Vue.js + Pinia + Vite):
  - Pages (routes)
  - Composants réutilisables
  - Stores (gestion d'état)
  - Axios (communication HTTP)

Backend (PHP 8 + PDO):
  - Endpoints API REST
  - Middleware Auth/RoleCheck/CORS
  - Services (Auth, DB, Mail, etc.)
  - Gestion d'erreurs centralisée

Database (MySQL):
  - Tables métier (admins, contents, solutions, etc.)
  - Audit logs pour la traçabilité
  - Indexes pour les performances
```

### Flux de données

```
┌─────────────────────────────────────────────────────────────┐
│ User Frontend                                               │
│ ├─ Vue Component (Accueil.vue)                             │
│ ├─ Pinia Store (contentStore)                              │
│ ├─ Axios Interceptor → + Token JWT                         │
│ └─ HTTP Request → /api/contents?page=home                  │
│                                                              │
│ Backend PHP                                                  │
│ ├─ CORS Headers                                             │
│ ├─ JWT Verification                                         │
│ ├─ Role Check                                               │
│ ├─ Request Routing (GET/POST/PUT/DELETE)                   │
│ ├─ Database Query                                           │
│ ├─ Audit Logging                                            │
│ └─ JSON Response                                             │
│                                                              │
│ Database                                                     │
│ ├─ SELECT/INSERT/UPDATE                                     │
│ ├─ Prepared Statements                                      │
│ └─ Transactions (optionnel)                                 │
│                                                              │
│ Frontend Refresh                                             │
│ ├─ Update Store                                             │
│ ├─ Computed Property Reactivity                             │
│ └─ DOM Update (Vue.js)                                      │
└─────────────────────────────────────────────────────────────┘
```

---

**Fin de la documentation**

Cette documentation couvre tous les aspects demandés avec des exemples concrets. Consultez-la comme référence lors de vos modifications.

