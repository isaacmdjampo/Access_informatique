import { createRouter, createWebHistory } from 'vue-router'
import Accueil from '../pages/Accueil.vue';
import Solution from '../pages/Solutions.vue';
import Apropos from '../pages/Apropos.vue';
import Formation from '../pages/Formation.vue';
import Contact from '../pages/Contact.vue';

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
      path: '/about',
      name: 'a-propos',
      component: Apropos,
    },
    {
      path: '/formation',
      name: 'formation',
      component: Formation,
    },
    {
      path: '/contact',
      name: 'contact',
      component: Contact,
    },
  ],
})

export default router
