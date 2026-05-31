# Access Informatique - Documentation Complète

## 📋 Vue d'ensemble

**Access Informatique** est une plateforme complète de gestion de solutions logicielles et de formations. Le site propose un éditeur de logiciels avec dashboard admin pour gérer dynamiquement le contenu.

**Repository :** https://github.com/isaacschamir/Access_Informatique

---

## 🏗️ Architecture & Stack Technique

### Frontend
- **Framework :** Vue.js 3 (Composition API)
- **Routing :** Vue Router 4
- **State Management :** Pinia
- **HTTP Client :** Axios
- **CSS :** Tailwind CSS
- **Build :** Vite
- **Responsive :** Mobile-first design (breakpoints: sm, md, lg, xl)

### Backend
- **Language :** PHP 8+
- **Architecture :** API REST (sans framework)
- **Authentification :** JWT (HS256)
- **Email :** PHPMailer
- **Routing :** Apache mod_rewrite + wrapper files

### Base de Données
- **SGBD :** MySQL 8+
- **Tables :** 16 tables normalisées
- **Connexion :** PDO

### Infrastructure
- **Serveur :** Apache 2.4+
- **PHP :** FastCGI (mod_fcgid)
- **Environnement :** WAMP64 (Windows) ou standard Linux

---

## 📁 Structure du Projet

```
Access_Informatique/
├── backend/                          # Backend PHP
│   ├── api/                          # Endpoints REST
│   │   ├── admin/                    # Routes protégées (JWT)
│   │   │   ├── login.php
│   │   │   ├── logout.php
│   │   │   ├── stats.php
│   │   │   ├── upload.php
│   │   │   ├── contents/
│   │   │   ├── solutions/
│   │   │   ├── formations/
│   │   │   ├── partners/
│   │   │   └── leads/
│   │   ├── forms/                    # Routes publiques (forms)
│   │   │   ├── contact.php
│   │   │   └── inscription.php
│   │   ├── contents.php
│   │   ├── solutions.php
│   │   ├── formations.php
│   │   ├── partners.php
│   │   └── hackathon.php
│   ├── includes/                     # Core files
│   │   ├── config.php                # Env variables
│   │   ├── db.php                    # PDO connection
│   │   ├── Response.php              # JSON responses, CORS
│   │   ├── Auth.php                  # JWT handling
│   │   ├── RateLimit.php             # Rate limiting
│   │   └── Mailer.php                # Email
│   ├── uploads/                      # User files
│   ├── .env                          # Environment
│   ├── .htaccess                     # Apache rules
│   └── index.php                     # Router
├── database/                         # DB scripts
│   ├── schema.sql                    # Tables
│   ├── seeds.sql                     # Initial data
│   ├── setup_admin.php               # Admin setup
│   └── debug_login.php               # Debug
├── src/                              # Frontend Vue.js
│   ├── components/
│   ├── pages/
│   ├── Logiciels/                    # Solution details
│   ├── views/admin/                  # Admin dashboard
│   ├── services/                     # API client
│   ├── stores/                       # Pinia
│   ├── router/
│   ├── assets/
│   └── App.vue
├── public/                           # Static
└── package.json
```

---

## 🗄️ Base de Données

### Connexion

```
Serveur : localhost
Port : 3306
User : root
Password : (vide)
Database : access_informatique
```

### Configuration Backend (.env)

```php
DB_HOST=localhost
DB_PORT=3306
DB_NAME=access_informatique
DB_USER=root
DB_PASS=

JWT_SECRET=votre-secret-very-secure
JWT_ALGO=HS256
JWT_EXP=86400

FRONTEND_URL=http://localhost:5173
UPLOAD_URL=http://localhost/api/uploads

MAIL_FROM=noreply@accessinformatique.com
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USER=votre-email@gmail.com
MAIL_PASS=app-password
```

### Tables Principales

#### solutions
```sql
id, name, slug, category, tagline, short_description, 
hero_image, tags, is_active, created_at
```

#### formations
```sql
id, title, slug, category, level, duration, price, 
description, image_url, is_active, created_at
```

#### partners
```sql
id, name, logo_url, is_active, sort_order
```

#### contents
```sql
id, page, key_name, label, value
Pages: home, about, contact, formation, hackathon
```

#### leads_contact
```sql
id, name, email, phone, message, submitted_at, status
```

#### leads_inscriptions
```sql
id, name, email, phone, formation_id, message, submitted_at
```

---

## 🔐 Authentification

### Admin Default
```
Email: admin@accessinformatique.com
Password: Admin@Access2024!
```

Créer via: `php backend/includes/setup_admin.php`

### JWT
- **Header :** `X-Admin-Token: <jwt>`
- **Algorithm :** HS256
- **Expiry :** 24h
- **Rate Limit :** 5 tries / 5 min per IP

### Security
✅ JWT tokens  
✅ Bcrypt passwords  
✅ Rate limiting  
✅ CORS configured  
✅ SQL injection protected  
✅ Input validation  

---

## 🔌 API Endpoints

### Public Routes

```
GET  /api/contents              # Textes site
GET  /api/solutions             # Toutes solutions
GET  /api/solutions/:slug       # Détail solution
GET  /api/formations            # Toutes formations
GET  /api/formations/:slug      # Détail formation
GET  /api/partners              # Partenaires
POST /api/forms/contact         # Contact message
POST /api/forms/inscription     # Formation signup
```

### Admin Routes (JWT Required)

```
POST   /api/admin/login                    # Login
POST   /api/admin/logout                   # Logout
GET    /api/admin/stats                    # Dashboard stats
POST   /api/admin/upload                   # Upload file

GET    /api/admin/solutions                # List
POST   /api/admin/solutions                # Create
PUT    /api/admin/solutions?id=X           # Update
PATCH  /api/admin/solutions?id=X           # Toggle active
DELETE /api/admin/solutions?id=X           # Delete

GET    /api/admin/formations               # List
POST   /api/admin/formations               # Create
PUT    /api/admin/formations?id=X          # Update
PATCH  /api/admin/formations?id=X          # Toggle
DELETE /api/admin/formations?id=X          # Delete

GET    /api/admin/partners                 # List
POST   /api/admin/partners                 # Create
PUT    /api/admin/partners?id=X            # Update
PATCH  /api/admin/partners?id=X            # Toggle
DELETE /api/admin/partners?id=X            # Delete

GET    /api/admin/contents                 # List
PUT    /api/admin/contents?id=X            # Update

GET    /api/admin/leads/contact            # Messages
GET    /api/admin/leads/inscriptions       # Signups
POST   /api/admin/leads/export             # Export CSV
```

---

## 📦 Installation

### 1. Database
```bash
mysql -u root < database/schema.sql
mysql -u root access_informatique < database/seeds.sql
php backend/includes/setup_admin.php
```

### 2. Backend Config
```bash
cd backend
# Edit .env with your settings
chmod 777 uploads/
```

### 3. Frontend
```bash
npm install
npm run dev
```

### 4. Access
- **Site :** http://localhost:5173
- **Admin :** http://localhost:5173/admin
- **API :** http://localhost/api

---

## 🖥️ Dashboard Admin

**URL :** http://localhost:5173/admin

**Features :**
- 📊 Dashboard with stats
- 🔧 Solutions CRUD + image upload
- 🎓 Formations CRUD with modules
- 🏢 Partners management
- 📝 Site texts editing
- 📮 Contact leads & signups
- 📥 CSV export

---

## 🚀 Production Deploy

**Checklist :**
- [ ] Update .env credentials
- [ ] Change JWT_SECRET
- [ ] Enable HTTPS
- [ ] Configure real SMTP
- [ ] Set permissions 755/775
- [ ] Test file uploads
- [ ] Verify CORS domain
- [ ] Database backups

---

## 🐛 Troubleshooting

### CORS Error
→ Check `Response.php` has `cors_headers()` called

### Login fails
```bash
mysql access_informatique -e "SELECT * FROM users_admins;"
php backend/includes/setup_admin.php  # recreate
```

### Upload fails
```bash
chmod 777 backend/uploads/
```

### Solutions not showing
- Check `is_active = 1`
- Hard refresh (Ctrl+Shift+R)
- Test: `GET http://localhost/api/solutions`

---

## 📞 Contact

**Email :** contact@accessinformatique.com  
**Phone :** +225 07 07 26 18 58  
**Site :** www.accessinformatique.com

---

© 2024 Access Informatique. All rights reserved.  
Version 1.0.0 | Last updated: Dec 2024
