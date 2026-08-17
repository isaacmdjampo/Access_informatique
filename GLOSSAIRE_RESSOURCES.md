# 📖 Glossaire, Ressources et Bonnes Pratiques

## Table des matières
1. [Glossaire technique](#glossaire)
2. [Ressources d'apprentissage](#ressources)
3. [Checklist de sécurité](#checklist-securite)
4. [Performance tips](#performance-tips)
5. [FAQ - Questions fréquentes](#faq)

---

## 📚 Glossaire Technique

### Concepts Frontend

**Vue.js**
- Framework JavaScript pour construire des interfaces utilisateur réactives
- Utilise la liaison de données bidirectionnelle (v-model)
- Composants réutilisables avec état local et props
- [Documentation officielle](https://vuejs.org)

**Pinia**
- Gestionnaire d'état pour Vue.js (remplace Vuex)
- Centralise l'état partagé entre composants
- Permet la réactivité globale
- Plus léger et plus simple que Vuex
- [Documentation Pinia](https://pinia.vuejs.org)

**Axios**
- Bibliothèque JavaScript pour faire des requêtes HTTP
- Supporte les intercepteurs (ajouter headers, gérer erreurs)
- Meilleure qu'un simple fetch()
- Support des timeouts et cancel tokens

**Vue Router**
- Gestionnaire de routes pour Single Page Applications (SPA)
- Navigation sans rechargement de page
- Lazy loading des composants
- Navigation guards pour protéger les routes

**Composant Vue**
- Fichier `.vue` contenant template, script et styles
- Peut avoir des props (données reçues du parent)
- Émet des événements vers le parent
- État local via `ref()`, `reactive()`
- Computed properties pour dériver l'état

**v-model**
- Liaison bidirectionnelle : `<input v-model="variable">`
- Automatiquement synchronisé avec la variable
- Sucre syntaxique pour `:value` + `@input`

**Computed property**
```javascript
const isAuthenticated = computed(() => !!token.value)
// S'il change, les components qui l'utilisent se re-rendent automatiquement
```

**Watch**
```javascript
watch(() => user.value.age, (newVal, oldVal) => {
  console.log(`Age changed from ${oldVal} to ${newVal}`)
})
```

---

### Concepts Backend

**PDO (PHP Data Objects)**
- Interface uniforme pour accéder aux bases de données
- Support de requêtes préparées (protection SQL injection)
- Gestion des erreurs via exceptions
- Meilleur que `mysqli` ou requêtes directes

**Requête préparée**
```php
$stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
// Le ? est remplacé de manière sécurisée
// Impossible d'injecter du SQL
```

**JWT (JSON Web Token)**
- Standard pour l'authentification stateless
- Token signé, pas de session serveur
- Contient des informations (claims) dans le payload
- Structure : `header.payload.signature` en Base64
- Valide tant que la signature est correcte

**CORS (Cross-Origin Resource Sharing)**
- Mécanisme de sécurité du navigateur
- Permet à un domaine d'accéder aux ressources d'un autre
- Requête `OPTIONS` preflight vérifiée par le serveur
- Headers `Access-Control-Allow-*` nécessaires

**REST API**
- Architecture pour les APIs web
- Chaque URL représente une ressource
- Méthodes HTTP : GET (lire), POST (créer), PUT (modifier), DELETE (supprimer)
- Stateless : chaque requête contient toutes les infos nécessaires

**Middleware**
- Fonction exécutée avant/après le traitement d'une requête
- Exemples : authentification, logging, rate limiting
- Dans notre code : `require_auth()`, `require_role()`, `cors_headers()`

**Singleton Pattern**
```php
// Une seule instance créée et réutilisée
static $db = null;
if ($db === null) { $db = new PDO(...); }
return $db;
```

---

### Concepts Généraux

**HTTP Status Codes**
- 2xx : Succès (200 OK, 201 Created)
- 3xx : Redirection (301 Moved, 304 Not Modified)
- 4xx : Erreur client (400 Bad Request, 401 Unauthorized, 404 Not Found)
- 5xx : Erreur serveur (500 Internal Server Error)

**JSON (JavaScript Object Notation)**
- Format de données texte, lisible par l'humain
- Paires clé-valeur : `{"name": "Jean", "age": 30}`
- Tableaux : `[1, 2, 3]`
- Types : string, number, boolean, null, object, array

**Hash (cryptographie)**
- Fonction unidirectionnelle : hash(data) ≠ data
- Même légère modification de data = hash différent
- Impossible de retrouver data à partir du hash
- Usage : stocker les mots de passe (via `password_hash()`)

**Token JWT Signature**
```
HMAC-SHA256(
  base64(header).base64(payload),
  SECRET_KEY
)
```
- Garantit que le token n'a pas été modifié
- Seul le serveur peut signer (il connaît la clé secrète)

**Rate Limiting**
- Limite le nombre de requêtes par IP et par période
- Protection contre les brute-force attacks
- Exemple : 5 tentatives de login par IP par 5 minutes

---

## 📖 Ressources d'Apprentissage

### Vue.js
- [Guide officiel Vue.js 3](https://vuejs.org/guide/introduction.html)
- [Composition API](https://vuejs.org/guide/extras/composition-api-faq.html)
- [Tutoriels interactifs](https://vuejs.org/tutorial/)
- YouTube : "Vue 3 Crash Course" par Traversy Media

### Pinia
- [Documentation officielle](https://pinia.vuejs.org/introduction.html)
- "Pinia for Vue 3" sur YouTube
- Comparaison Vuex vs Pinia

### Axios
- [Documentation Axios](https://axios-http.com/)
- Intercepteurs : Comment ajouter des headers automatiquement
- Gestion des erreurs et timeouts

### PHP/Backend
- [PHP Official Documentation](https://www.php.net/docs.php)
- [PDO Tutorial](https://www.php.net/manual/en/book.pdo.php)
- [PHP Best Practices](https://www.phptherightway.com/)

### Sécurité Web
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- SQL Injection, XSS, CSRF - Ce qu'il faut savoir
- JWT Security Best Practices

### REST API Design
- [RESTful API Best Practices](https://restfulapi.net/)
- Status codes HTTP expliqués
- Versioning d'API

### HTTP
- [MDN Web Docs - HTTP](https://developer.mozilla.org/en-US/docs/Web/HTTP)
- Request/Response headers
- CORS explained

---

## 🛡️ Checklist de Sécurité

### Frontend

- [ ] Ne jamais stocker les passwords en localStorage
- [ ] Les tokens JWT : OK dans localStorage (payload lisible mais signature vérifiée)
- [ ] Pas de clés API dans le code frontend
- [ ] Valider les entrées utilisateur avant envoi au backend
- [ ] Afficher les messages d'erreur sans révéler des infos sensibles
- [ ] Utiliser HTTPS en production (pas HTTP)
- [ ] Content Security Policy (CSP) headers
- [ ] Protection XSS : échapper les contenus utilisateur
- [ ] Vérifier les origins des messages postMessage()

### Backend

- [ ] Toutes les requêtes SQL doivent être préparées
- [ ] Valider et nettoyer TOUTES les entrées
- [ ] Vérifier l'authentification ET l'autorisation
- [ ] Les erreurs ne doivent pas révéler la structure interne
- [ ] Logging des tentatives échouées (bruteforce, accès refusé)
- [ ] Rate limiting sur les endpoints sensibles
- [ ] Hachage des passwords avec password_hash()
- [ ] Secrets (JWT_SECRET, DB_PASS) en variables d'environnement
- [ ] HTTPS obligatoire en production
- [ ] Headers de sécurité HTTP :
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: DENY`
  - `Strict-Transport-Security`
- [ ] CORS configuré restrictif (pas * en production)
- [ ] Audit logging de toutes les actions sensibles
- [ ] Mise à jour des dépendances (PHP, libraries)

### Base de Données

- [ ] Utilisateur DB avec permissions minimales
- [ ] Passwords DB en variables d'environnement
- [ ] Backups réguliers
- [ ] Chiffrement des données sensibles
- [ ] Logging des modifications (audit_logs)

### Déploiement

- [ ] HTTPS activé
- [ ] Certificat SSL/TLS valide
- [ ] .env en .gitignore (jamais commiter)
- [ ] Logs accessibles seulement au serveur
- [ ] Uploads sécurisés (pas d'exécution)
- [ ] Firewall activé
- [ ] Monitoring des anomalies

---

## ⚡ Performance Tips

### Frontend

```javascript
// ❌ Mauvais : refait la requête à chaque fois
onMounted(async () => {
  const { data } = await api.get('/api/contents?page=home')
})

// ✅ Bon : cache en mémoire
const contentStore = useContentStore()
onMounted(async () => {
  await contentStore.load('home')  // Une seule fois
})
```

```javascript
// ❌ Mauvais : re-calcule à chaque rendu
<div>{{ data.filter(x => x.active).length }}</div>

// ✅ Bon : cached et réactif
const activeCount = computed(() => data.value.filter(x => x.active).length)
<div>{{ activeCount }}</div>
```

```javascript
// ❌ Mauvais : charge tout le bundle en une fois
import AdminDashboard from '../views/admin/AdminDashboard.vue'

// ✅ Bon : lazy loading
const AdminDashboard = () => import('../views/admin/AdminDashboard.vue')
```

```javascript
// ❌ Mauvais : liste énorme sans pagination
const { data } = await api.get('/api/leads/contact')  // Peut avoir 10000 items

// ✅ Bon : pagination
const { data } = await api.get('/api/leads/contact?page=1&limit=50')
```

### Backend

```php
// ❌ Mauvais : N+1 queries
$posts = $db->query('SELECT * FROM posts LIMIT 10');
foreach ($posts as $post) {
    $comments = $db->query("SELECT * FROM comments WHERE post_id = {$post['id']}");
    // 10 posts = 11 requêtes!
}

// ✅ Bon : requête préparée et JOIN
$stmt = $db->prepare('
    SELECT p.id, p.title, c.text
    FROM posts p
    LEFT JOIN comments c ON p.id = c.post_id
    LIMIT 10
');
$stmt->execute();
$results = $stmt->fetchAll();  // 1 requête!
```

```php
// ❌ Mauvais : tous les champs
$stmt = $db->query('SELECT * FROM admins');

// ✅ Bon : champs nécessaires seulement
$stmt = $db->prepare('SELECT id, name, email FROM admins WHERE id = ?');
```

```php
// ❌ Mauvais : pas de limite
$stmt = $db->query('SELECT * FROM leads_contact');
$rows = $stmt->fetchAll();  // 50000 rows en mémoire!

// ✅ Bon : pagination
$page = (int) ($_GET['page'] ?? 1);
$limit = 50;
$offset = ($page - 1) * $limit;
$stmt = $db->prepare('SELECT * FROM leads_contact LIMIT ? OFFSET ?');
$stmt->execute([$limit, $offset]);
```

### Database

```sql
-- ❌ Pas d'index : requête lente
SELECT * FROM contents WHERE page = 'home';

-- ✅ Avec index : requête rapide
CREATE INDEX idx_contents_page ON contents(page);
SELECT * FROM contents WHERE page = 'home';  -- Index utilisé
```

```sql
-- ✅ Optimisation requête
-- Sélectionner seulement les colonnes nécessaires
SELECT id, page, value FROM contents WHERE page = ? LIMIT 50;

-- Éviter les sous-requêtes complexes
-- Utiliser les JOINs
```

---

## ❓ FAQ - Questions Fréquentes

### Q: Qu'est-ce qui se passe si le token JWT expire?

**R:** 
```javascript
// Interceptor axios détecte le 401
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401 && isAdminPage) {
      localStorage.removeItem('admin_token')
      window.location.href = '/admin/login'
    }
    return Promise.reject(error)
  }
)
```
L'utilisateur est redirigé automatiquement vers le login.

---

### Q: Comment Pinia diffère de Vuex?

**R:** 
```javascript
// Vuex : très verbeux
const store = {
  state: { count: 0 },
  mutations: { increment(state) { state.count++ } },
  actions: { async fetchData({ commit }) { ... } }
}

// Pinia : beaucoup plus simple
const store = defineStore('counter', () => {
  const count = ref(0)
  function increment() { count.value++ }
  async function fetchData() { ... }
  return { count, increment, fetchData }
})
```

Pinia est plus simple, plus léger et plus moderne.

---

### Q: SQL Injection - comment ça marche?

**R:**
```php
// ❌ DANGEREUX - SQL Injection
$email = 'admin@" OR "1"="1';
$stmt = $db->query("SELECT * FROM users WHERE email = '$email'");
// SELECT * FROM users WHERE email = 'admin@" OR "1"="1'
// Les guillemets ferment la chaîne, OR 1=1 est toujours vrai
// Retourne TOUS les utilisateurs!

// ✅ SAFE - Requête préparée
$email = 'admin@" OR "1"="1';
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
// Le ? est remplacé de manière safe, pas d'interprétation du contenu
```

---

### Q: Comment fonctionne la réactivité Vue?

**R:**
```javascript
// Vue utilise des Proxies JavaScript pour détecter les changements
const data = ref({ count: 0 })

// Quand on change data.value.count
data.value.count = 1
// Vue détecte le changement et re-rend automatiquement

// Les computed properties observent leurs dépendances
const doubled = computed(() => data.value.count * 2)
// Si data.count change, doubled se recalcule automatiquement
```

---

### Q: À quelle fréquence faire de nouvelles requêtes API?

**R:**
- **Page load** : Toujours (données fraîches)
- **Après modification** : Toujours (confirmer la sauvegarde)
- **Input utilisateur rapide** : Throttler/Debounce (ne pas faire 100 requêtes/sec)
- **Rafraîchissement auto** : Polling tous les 30-60 secondes (ou WebSocket)

```javascript
// Throttle : max 1 requête par seconde
import { throttle } from 'lodash'
const search = throttle(async (query) => {
  const { data } = await api.get(`/search?q=${query}`)
}, 1000)
```

---

### Q: Comment gérer les authentifications avec 2FA?

**R:**
Pour implémenter 2FA (Two-Factor Authentication) :

1. Après login avec email/password, retourner un token temporaire (5 min)
2. Frontend demande un code (SMS, email, authenticator)
3. Vérifier le code avec le token temporaire
4. Si ok, émettre le JWT définitif

```
POST /api/admin/login → token temporaire (5 min)
POST /api/admin/verify-2fa → JWT définitif
```

---

### Q: Comment échapper les contenus pour éviter XSS?

**R:**
```javascript
// Vue.js échappe automatiquement le HTML
const user = ref({ name: '<script>alert("XSS")</script>' })
<div>{{ user.name }}</div>
// Affiche le texte littéralement, pas d'exécution du script

// Si vous utilisez v-html, c'est à vos risques
<div v-html="user.name"></div>  // ❌ Dangereux!
// Utilisez DOMPurify pour nettoyer
import DOMPurify from 'dompurify'
<div v-html="DOMPurify.sanitize(user.name)"></div>  // ✅ Sûr
```

---

### Q: Comment faire un système de notifications en temps réel?

**R:**
Options croissantes en complexité :

1. **Polling** (simple)
```javascript
setInterval(async () => {
  const { data } = await api.get('/api/notifications')
  notifications.value = data
}, 5000)  // Tous les 5 secondes
```

2. **WebSocket** (meilleur)
```javascript
const ws = new WebSocket('wss://example.com/notifications')
ws.onmessage = (event) => {
  const notification = JSON.parse(event.data)
  notifications.value.push(notification)
}
```

3. **Server-Sent Events (SSE)** (milieu)
```javascript
const eventSource = new EventSource('/api/notifications/stream')
eventSource.onmessage = (event) => {
  notifications.value.push(JSON.parse(event.data))
}
```

---

### Q: Comment implémenter un système de cache côté client?

**R:**
Pinia déjà fait partie du caching :

```javascript
export const useContentStore = defineStore('content', () => {
  const pages = ref({})  // Cache ici
  
  async function load(page) {
    if (pages.value[page]) return  // Déjà chargé
    
    const { data } = await api.get(`/contents?page=${page}`)
    pages.value[page] = data  // Mettre en cache
  }
  
  return { pages, load }
})
```

Avec expiration (cache 30 minutes) :

```javascript
const cache = ref({})
const cacheExpiry = ref({})
const CACHE_DURATION = 30 * 60 * 1000  // 30 minutes

async function load(page) {
  const now = Date.now()
  const expiry = cacheExpiry.value[page] || 0
  
  if (cache.value[page] && now < expiry) {
    return cache.value[page]  // Cache valide
  }
  
  const { data } = await api.get(`/contents?page=${page}`)
  cache.value[page] = data
  cacheExpiry.value[page] = now + CACHE_DURATION
  
  return data
}
```

---

### Q: Comment déboguer une requête API qui échoue?

**R:**
1. Ouvrir DevTools (F12)
2. Onglet Network
3. Chercher la requête qui échoue
4. Vérifier :
   - Request Headers (token présent?)
   - Request Body (JSON correct?)
   - Response Status (401, 403, 500?)
   - Response Body (message d'erreur?)
5. Utiliser console.log pour déboguer le code JS
6. Pour PHP : vérifier error_log et logs du serveur

Avec Axios, attraper les erreurs :
```javascript
try {
  const { data } = await api.put('/api/admin/contents', payload)
} catch (err) {
  console.error('Status:', err.response?.status)
  console.error('Error:', err.response?.data?.error)
  console.error('Message:', err.message)
}
```

---

### Q: Quelle est la taille idéale d'un token JWT?

**R:**
- Petit est mieux (environs 500-1000 caractères max)
- Plus le token est gros, plus les headers HTTP sont grands
- Mettre seulement les infos essentielles dans le payload :
  - `sub` (subject ID) : obligatoire
  - `email` : utile
  - `role` : utile
  - Les autres données : récupérer du backend si nécessaire

```javascript
// ✅ Bon : minimal
{ sub: 5, email: 'admin@...', role: 'admin', exp: 1234567890 }

// ❌ Mauvais : bloated
{ sub: 5, email: '...', role: '...', full_name: '...', 
  avatar: 'base64...(long)', permissions: [...],
  address: '...', phone: '...', ... }
```

---

## 📚 Lectures Supplémentaires

### Articles Importants
1. [JWT Best Practices](https://tools.ietf.org/html/rfc8725)
2. [OWASP Authentication Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)
3. [REST API Best Practices](https://restfulapi.net/)

### Livres Recommandés
1. "You Don't Know JS" (Kyle Simpson) - JavaScript en profondeur
2. "Eloquent JavaScript" - Concepts JavaScript avancés
3. "The Pragmatic Programmer" - Bonnes pratiques générales

### Communautés
- Stack Overflow : Questions/réponses
- GitHub Discussions : Discussions de projets
- Reddit : r/webdev, r/learnprogramming
- Discord Communities : Vue.js, PHP, Dev General

---

Fin du glossaire et ressources!

