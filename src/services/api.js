/**
 * api.js
 * -----------------------------------------------------------
 * Instance Axios configurée pour communiquer avec le backend PHP.
 * L'URL de base est définie dans .env.local :
 *   VITE_API_URL=http://localhost/Access_Informatique/backend/api
 * -----------------------------------------------------------
 */

import axios from 'axios'

const BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost/Access_Informatique/backend/api'

const api = axios.create({
  baseURL: BASE_URL,
  timeout: 15000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

/**
 * Intercepteur de requête.
 *
 * 1. Ajoute .php à toutes les URLs qui n'ont pas d'extension
 *    (sauf les routes avec slug : /solutions/solumed, /formations/xxx)
 *    → Apache reçoit directement le fichier PHP, pas un dossier.
 *    → Évite la redirection 301 qui causait les erreurs CORS/Network Error.
 *
 * 2. Envoie le token JWT dans X-Admin-Token pour les routes /admin
 *    (Authorization: Bearer est bloqué par Apache mod_fcgid).
 */
api.interceptors.request.use(
  (config) => {
    if (config.url) {
      const [pathPart, queryPart] = config.url.split('?')
      const query = queryPart ? '?' + queryPart : ''

      // Ajouter .php si : pas d'extension ET pas une route avec slug
      const hasExtension  = /\.\w+$/.test(pathPart)
      const isSlugRoute   = /\/(solutions|formations)\/[^/?]+/.test(pathPart)
      const isExportRoute = /\/leads\/export\//.test(pathPart)

      if (!hasExtension && !isSlugRoute && !isExportRoute) {
        config.url = pathPart.replace(/\/?$/, '') + '.php' + query
      }

      // Token JWT pour les routes admin
      const token = localStorage.getItem('admin_token')
      if (token && config.url.includes('/admin')) {
        config.headers['X-Admin-Token'] = token
      }
    }

    return config
  },
  (error) => Promise.reject(error),
)

/**
 * Intercepteur de réponse.
 * 401 hors page login → efface le token et redirige vers login.
 */
api.interceptors.response.use(
  (response) => response,
  (error) => {
    const isAdminPage = window.location.pathname.startsWith('/admin')
    const isLoginPage = window.location.pathname === '/admin/login'
    if (error.response?.status === 401 && isAdminPage && !isLoginPage) {
      localStorage.removeItem('admin_token')
      window.location.href = '/admin/login'
    }
    return Promise.reject(error)
  },
)

export default api
