/**
 * composables/useContent.js
 * -----------------------------------------------------------
 * Composable réutilisable pour accéder aux textes dynamiques
 * du site depuis n'importe quel composant Vue.
 *
 * Usage simplifié :
 *   const { t, loading } = useContent('home')
 *   // En template : {{ t('hero.title', 'Titre par défaut') }}
 * -----------------------------------------------------------
 */

import { computed, onMounted } from 'vue'
import { useContentStore } from '@/stores/content'

/**
 * @param {string} page  Identifiant de la page à charger automatiquement
 * @returns {{ t, loading, error }}
 */
export function useContent(page) {
  const store = useContentStore()

  // Chargement automatique au montage du composant
  onMounted(() => {
    store.load(page)
  })

  /**
   * Retourne le texte correspondant à une clé.
   *
   * @param {string} key       Clé du contenu (ex: "hero.title")
   * @param {string} fallback  Texte affiché si le contenu n'est pas encore chargé
   */
  function t(key, fallback = '') {
    return store.get(page, key, fallback)
  }

  const loading = computed(() => store.isLoading(page))
  const error   = computed(() => store.errors[page] || null)

  return { t, loading, error }
}
