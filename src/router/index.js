import { createRouter, createWebHistory } from 'vue-router'
import Accueil from '../pages/Accueil.vue'
import Solution from '../pages/Solutions.vue'
import Apropos from '../pages/Apropos.vue'
import Formation from '../pages/Formation.vue'
import Contact from '../pages/Contact.vue'
import Hackathon from '../pages/Hackathon.vue'
import LoginInscription from '../components/Login_Inscription.vue'
import SolutionDetails from '../Logiciels/SolutionDetails.vue'
import FormationsList from '../components/FormationsList.vue'
import FormationDetail from '../pages/FormationDetail.vue'

// ── Admin (chargement différé pour ne pas alourdir le bundle public) ──────────
const AdminLogin    = () => import('../views/admin/AdminLogin.vue')
const AdminLayout   = () => import('../views/admin/AdminLayout.vue')
const AdminDashboard      = () => import('../views/admin/AdminDashboard.vue')
const AdminContents       = () => import('../views/admin/AdminContents.vue')
const AdminSolutions      = () => import('../views/admin/AdminSolutions.vue')
const AdminFormations     = () => import('../views/admin/AdminFormations.vue')
const AdminLeadsContact   = () => import('../views/admin/AdminLeadsContact.vue')
const AdminLeadsInscriptions = () => import('../views/admin/AdminLeadsInscriptions.vue')
const AdminPartners       = () => import('../views/admin/AdminPartners.vue')

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(_to, _from, savedPosition) {
    if (savedPosition) return savedPosition
    return { top: 0 }
  },
  routes: [
    // ── Site public ───────────────────────────────────────────────────────────
    { path: '/',            name: 'accueil',          component: Accueil },
    { path: '/solutions',   name: 'solutions',        component: Solution },
    { path: '/formations',  name: 'formations',       component: FormationsList },
    { path: '/apropos',     name: 'a-propos',         component: Apropos },
    { path: '/formation',   name: 'formation',        component: Formation },
    { path: '/inscription', name: 'inscription',      component: LoginInscription },
    { path: '/contact',     name: 'contact',          component: Contact },
    { path: '/hackathon',   name: 'hackathon',        component: Hackathon },
    { path: '/formation/:slug', name: 'formation-detail', component: FormationDetail },
    { path: '/solutions/:slug', name: 'solution-detail',  component: SolutionDetails },

    // ── Admin : page de login (sans layout admin) ──────────────────────────────
    { path: '/admin/login', name: 'admin-login', component: AdminLogin },

    // ── Admin : routes protégées (avec layout admin) ───────────────────────────
    {
      path: '/admin',
      component: AdminLayout,
      meta: { requiresAuth: true },
      children: [
        { path: '',                  redirect: '/admin/dashboard' },
        { path: 'dashboard',         name: 'admin-dashboard',              component: AdminDashboard },
        { path: 'contents',          name: 'admin-contents',               component: AdminContents },
        { path: 'solutions',         name: 'admin-solutions',              component: AdminSolutions },
        { path: 'formations',        name: 'admin-formations',             component: AdminFormations },
        { path: 'leads/contact',     name: 'admin-leads-contact',          component: AdminLeadsContact },
        { path: 'leads/inscriptions',name: 'admin-leads-inscriptions',     component: AdminLeadsInscriptions },
        { path: 'partners',          name: 'admin-partners',               component: AdminPartners },
      ],
    },
  ],
})

// ── Navigation guard : redirige vers /admin/login si non authentifié ──────────
router.beforeEach((to) => {
  if (!to.meta.requiresAuth) return true

  const token = localStorage.getItem('admin_token')
  if (!token) {
    return { name: 'admin-login', query: { redirect: to.fullPath } }
  }
  return true
})

export default router
