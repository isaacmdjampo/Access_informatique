/**
 * stores/content.js
 * -----------------------------------------------------------
 * Store Pinia pour les textes dynamiques du site.
 *
 * Principe :
 *  - Les textes sont stockés par page (ex: "home", "about")
 *  - Chaque page est chargée une seule fois puis mise en cache
 *  - useContent() expose un helper get(page, key, fallback)
 *
 * Usage dans un composant :
 *   const { load, get, isLoading } = useContent()
 *   onMounted(() => load('home'))
 *   const title = computed(() => get('home', 'hero.title', 'Titre par défaut'))
 * -----------------------------------------------------------
 */

import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

export const useContentStore = defineStore('content', () => {
  // Contenus chargés, indexés par page : { home: { 'hero.title': 'val', ... }, ... }
  const pages = ref({})

  // Pages en cours de chargement
  const loading = ref({})

  // Erreurs par page
  const errors = ref({})

  /**
   * Charge les contenus d'une page depuis l'API.
   * Ne refait pas l'appel si la page est déjà en cache.
   *
   * @param {string} page  Identifiant de la page (ex: "home")
   */
  async function load(page) {
    // Cache : évite les appels en double
    if (pages.value[page] !== undefined || loading.value[page]) return

    loading.value[page] = true
    errors.value[page] = null

    try {
      const { data } = await api.get(`/contents?page=${page}`)
      // L'API renvoie { page: "home", data: { "hero.title": "..." } }
      pages.value[page] = data.data ?? data
    } catch (e) {
      errors.value[page] = e.message || 'Erreur de chargement'
      // Initialiser avec un objet vide pour éviter les undefined
      pages.value[page] = {}
    } finally {
      loading.value[page] = false
    }
  }

  /**
   * Retourne la valeur d'un contenu par page + clé.
   * Retourne le fallback si la clé n'est pas encore chargée.
   *
   * @param {string} page      Identifiant de la page
   * @param {string} key       Clé du contenu (ex: "hero.title")
   * @param {string} fallback  Valeur par défaut si clé absente
   */
  function get(page, key, fallback = '') {
    return pages.value[page]?.[key] ?? fallback
  }

  /**
   * Indique si une page est en cours de chargement.
   */
  function isLoading(page) {
    return loading.value[page] === true
  }

  return { pages, loading, errors, load, get, isLoading }
})
