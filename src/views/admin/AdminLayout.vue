<template>
  <div class="min-h-screen bg-slate-50 flex">

    <!-- ── SIDEBAR ──────────────────────────────────────────────────────────── -->
    <aside
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 shadow-xl flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:shadow-none"
    >
      <!-- Logo -->
      <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
        <div class="w-9 h-9 rounded-xl bg-green-600 flex items-center justify-center flex-shrink-0">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </div>
        <div>
          <p class="font-black text-slate-900 text-sm leading-tight">Access Informatique</p>
          <p class="text-xs text-slate-500">Administration</p>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Tableau de bord</p>

        <router-link
          v-for="link in navLinks"
          :key="link.to"
          :to="link.to"
          @click="sidebarOpen = false"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150"
          :class="isActive(link.to)
            ? 'bg-green-50 text-green-700 font-semibold'
            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
        >
          <span class="w-5 h-5 flex items-center justify-center" v-html="link.icon"></span>
          {{ link.label }}
          <span
            v-if="link.badge && badges[link.badge]"
            class="ml-auto text-xs bg-green-600 text-white rounded-full px-2 py-0.5 font-bold"
          >
            {{ badges[link.badge] }}
          </span>
        </router-link>

        <div class="pt-14">
          <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Leads</p>
          <router-link
            v-for="link in leadLinks"
            :key="link.to"
            :to="link.to"
            @click="sidebarOpen = false"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150"
            :class="isActive(link.to)
              ? 'bg-green-50 text-green-700 font-semibold'
              : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
          >
            <span class="w-5 h-5 flex items-center justify-center" v-html="link.icon"></span>
            {{ link.label }}
            <span
              v-if="link.badge && badges[link.badge]"
              class="ml-auto text-xs bg-red-500 text-white rounded-full px-2 py-0.5 font-bold"
            >
              {{ badges[link.badge] }}
            </span>
          </router-link>
        </div>
      </nav>

      <!-- Footer sidebar : infos admin + déconnexion -->
      <div class="border-t border-slate-100 px-4 py-4">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm">
            {{ adminInitials }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-slate-900 truncate">{{ adminStore.admin?.name }}</p>
            <p class="text-xs text-slate-500 truncate">{{ adminStore.admin?.email }}</p>
          </div>
        </div>
        <button
          @click="handleLogout"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-sm text-slate-600 hover:text-red-600 hover:bg-red-50 transition-all font-medium"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Déconnexion
        </button>
      </div>
    </aside>

    <!-- Overlay mobile -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
    ></div>

    <!-- ── CONTENU PRINCIPAL ─────────────────────────────────────────────────── -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Top bar mobile -->
      <header class="lg:hidden sticky top-0 z-30 bg-white border-b border-slate-200 px-4 py-3 flex items-center gap-3">
        <button @click="sidebarOpen = true" class="p-2 rounded-lg hover:bg-slate-100">
          <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <span class="font-bold text-slate-900 text-sm">Administration</span>
      </header>

      <!-- Zone de contenu des pages enfants -->
      <main class="flex-1 p-4 md:p-8">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAdminStore } from '@/stores/admin'
import api from '@/services/api'

const route       = useRoute()
const router      = useRouter()
const adminStore  = useAdminStore()
const sidebarOpen = ref(false)

// Badges de notification (nouvelles entrées non lues)
const badges = ref({ contacts_new: 0, inscriptions_new: 0 })

onMounted(async () => {
  try {
    const { data } = await api.get('/admin/stats')
    badges.value.contacts_new      = data.counters?.contacts_new      ?? 0
    badges.value.inscriptions_new  = data.counters?.inscriptions_new  ?? 0
  } catch { /* silencieux */ }
})

const adminInitials = computed(() => {
  const name = adminStore.admin?.name || 'A'
  return name.split(' ').map((w) => w[0]).slice(0, 2).join('').toUpperCase()
})

function isActive(path) {
  return route.path === path || route.path.startsWith(path + '/')
}

async function handleLogout() {
  await adminStore.logout()
  router.push('/admin/login')
}

// ── Navigation links ─────────────────────────────────────────────────────────
const ICON_GRID = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`
const ICON_TEXT = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>`
const ICON_BOX  = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>`
const ICON_BOOK = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>`
const ICON_IMG  = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`
const ICON_MAIL = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`
const ICON_USER = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`

const navLinks = [
  { to: '/admin/dashboard',  label: 'Vue d\'ensemble',      icon: ICON_GRID },
  { to: '/admin/contents',   label: 'Textes du site',       icon: ICON_TEXT },
  { to: '/admin/solutions',  label: 'Solutions',            icon: ICON_BOX  },
  { to: '/admin/formations', label: 'Formations',           icon: ICON_BOOK },
  { to: '/admin/partners',   label: 'Partenaires',          icon: ICON_IMG  },
]

const leadLinks = [
  { to: '/admin/leads/contact',      label: 'Messages contact',  icon: ICON_MAIL, badge: 'contacts_new' },
  { to: '/admin/leads/inscriptions', label: 'Inscriptions',      icon: ICON_USER, badge: 'inscriptions_new' },
]
</script>
