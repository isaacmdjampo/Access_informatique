/**
 * stores/admin.js
 * -----------------------------------------------------------
 * Store Pinia pour l'authentification de l'administrateur.
 * Persiste le token JWT et les infos admin dans localStorage.
 * -----------------------------------------------------------
 */

import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useAdminStore = defineStore('admin', () => {
  const token = ref(localStorage.getItem('admin_token') || null)
  const admin = ref(JSON.parse(localStorage.getItem('admin_user') || 'null'))

  const isAuthenticated = computed(() => !!token.value)

  /** Connecte l'admin : appelle le backend, stocke le JWT */
  async function login(email, password) {
    const { data } = await api.post('/admin/login', { email, password })
    token.value = data.token
    admin.value = data.admin
    localStorage.setItem('admin_token', data.token)
    localStorage.setItem('admin_user', JSON.stringify(data.admin))
    return data
  }

  /** Déconnecte l'admin : efface le JWT local */
  async function logout() {
    try {
      await api.post('/admin/logout')
    } catch {
      // Pas critique si l'appel échoue (token déjà expiré)
    } finally {
      token.value = null
      admin.value = null
      localStorage.removeItem('admin_token')
      localStorage.removeItem('admin_user')
    }
  }

  return { token, admin, isAuthenticated, login, logout }
})
