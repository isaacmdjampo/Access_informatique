import { createRouter, createWebHistory } from 'vue-router'
import Accueil from '../pages/Accueil.vue'
import Solution from '../pages/Solutions.vue'
import Apropos from '../pages/Apropos.vue'
import Formation from '../pages/Formation.vue'
import Contact from '../pages/Contact.vue'
import Hackathon from '../pages/Hackathon.vue'
import LoginInscription from '../components/Login_Inscription.vue'
import SoluMed from '../components/SoluMed.vue'
import MySchool from '../components/Myschool.vue'
import Matrix from '../components/Matrix.vue'
import Simba from '../components/Simba.vue'
import Musa from '../components/Musa.vue'
import SmartRH from '../components/SmartRH.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'accueil',
      component: Accueil,
    },
    {
      path: '/solutions',
      name: 'solutions',
      component: Solution,
    },
    {
      path: '/solutions/solumed',
      name: 'solumed',
      component: SoluMed,
    },
    {
      path: '/solutions/myschool',
      name: 'myschool',
      component: MySchool,
    },
    {
      path: '/solutions/matrix',
      name: 'matrix',
      component: Matrix,
    },
    {
      path: '/solutions/simba',
      name: 'simba',
      component: Simba,
    },
    {
      path: '/solutions/musa',
      name: 'musa',
      component: Musa,
    },
    {
      path: '/solutions/smartrhpaie',
      name: 'smartrhpaie',
      component: SmartRH,
    },
    {
      path: '/apropos',
      name: 'a-propos',
      component: Apropos,
    },
    {
      path: '/formation',
      name: 'formation',
      component: Formation,
    },
    {
      path: '/inscription',
      name: 'inscription',
      component: LoginInscription,
    },
    {
      path: '/contact',
      name: 'contact',
      component: Contact,
    },
    {
      path: '/hackathon',
      name: 'hackathon',
      component: Hackathon,
    },
  ],
})

export default router
