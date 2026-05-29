import { createRouter, createWebHistory } from 'vue-router'
import Accueil from '../pages/Accueil.vue'
import Solution from '../pages/Solutions.vue'
import Apropos from '../pages/Apropos.vue'
import Formation from '../pages/Formation.vue'
import Contact from '../pages/Contact.vue'
import Hackathon from '../pages/Hackathon.vue'
import LoginInscription from '../components/Login_Inscription.vue'

// IMPORT pour toutes les solutions
import SolutionDetails from '../Logiciels/SolutionDetails.vue'

// Pages formations
import FormationsList from '../components/FormationsList.vue'
import FormationDetail from '../pages/FormationDetail.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition
    return { top: 0 }
  },
  routes: [
    // Pages principales
    { path: '/', name: 'accueil', component: Accueil },
    { path: '/solutions', name: 'solutions', component: Solution },
    { path: '/formations', name: 'formations', component: FormationsList },
    { path: '/apropos', name: 'a-propos', component: Apropos },
    { path: '/formation', name: 'formation', component: Formation },
    { path: '/inscription', name: 'inscription', component: LoginInscription },
    { path: '/contact', name: 'contact', component: Contact },
    { path: '/hackathon', name: 'hackathon', component: Hackathon },

    //  Route dynamique pour les formations
    { path: '/formation/:slug', name: 'formation-detail', component: FormationDetail },

    //  Route dynamique pour les solutions
    { path: '/solutions/:slug', name: 'solution-detail', component: SolutionDetails },
  ],
})

export default router
