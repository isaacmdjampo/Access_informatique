<template>
  <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-green-600 mb-4">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </div>
        <h1 class="text-2xl font-black text-slate-900">Access Informatique</h1>
        <p class="text-slate-500 text-sm mt-1">Espace administrateur</p>
      </div>

      <!-- Card login -->
      <div class="bg-white rounded-2xl shadow-xl shadow-slate-200 p-8 border border-slate-100">
        <h2 class="text-xl font-bold text-slate-800 mb-6">Connexion</h2>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Adresse email</label>
            <input
              v-model="form.email"
              type="email"
              placeholder="admin@accessinformatique.com"
              required
              class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Mot de passe</label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPwd ? 'text' : 'password'"
                placeholder="••••••••••"
                required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all pr-12"
              />
              <button
                type="button"
                @click="showPwd = !showPwd"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
              >
                <svg v-if="!showPwd" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Message d'erreur -->
          <Transition name="fade">
            <div v-if="error" class="flex items-center gap-2 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
              <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              {{ error }}
            </div>
          </Transition>

          <button
            type="submit"
            :disabled="loading"
            class="w-full py-3 rounded-xl bg-green-600 hover:bg-green-500 disabled:opacity-60 disabled:cursor-not-allowed text-white font-bold transition-all duration-200"
          >
            <span v-if="!loading">Se connecter</span>
            <span v-else>Connexion en cours...</span>
          </button>
        </form>
      </div>

      <p class="text-center text-xs text-slate-400 mt-6">
        <router-link to="/" class="hover:text-green-600 transition-colors">← Retour au site</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAdminStore } from '@/stores/admin'

const router   = useRouter()
const route    = useRoute()
const store    = useAdminStore()

const loading  = ref(false)
const error    = ref('')
const showPwd  = ref(false)

const form = reactive({ email: '', password: '' })

async function handleLogin() {
  error.value   = ''
  loading.value = true
  try {
    await store.login(form.email, form.password)
    const redirect = route.query.redirect || '/admin/dashboard'
    router.push(redirect)
  } catch (err) {
    // Message d'erreur détaillé pour faciliter le débogage
    if (err.response) {
      // Le serveur a répondu avec une erreur
      const status = err.response.status
      const msg    = err.response.data?.error || JSON.stringify(err.response.data)
      error.value  = `[${status}] ${msg}`
    } else if (err.request) {
      // La requête est partie mais pas de réponse (CORS, réseau, timeout)
      error.value = 'Pas de réponse du serveur — vérifier CORS ou que WAMP est actif.'
    } else {
      // Erreur avant l'envoi
      error.value = 'Erreur : ' + err.message
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
