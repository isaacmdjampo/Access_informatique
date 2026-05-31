<template>
  <div>
    <div class="mb-8">
      <h1 class="text-2xl font-black text-slate-900">Vue d'ensemble</h1>
      <p class="text-slate-500 mt-1">Bonjour {{ adminStore.admin?.name }} — voici l'état du site.</p>
    </div>

    <!-- Compteurs -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div v-for="stat in stats" :key="stat.label" class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <div :class="`w-10 h-10 rounded-xl ${stat.bg} flex items-center justify-center mb-10`">
          <span v-html="stat.icon" :class="stat.color"></span>
        </div>
        <p class="text-3xl font-black text-slate-900">{{ stat.value }}</p>
        <p class="text-sm text-slate-500 mt-1">{{ stat.label }}</p>
        <p v-if="stat.new > 0" class="text-xs text-green-600 font-semibold mt-1">
          +{{ stat.new }} nouveau{{ stat.new > 1 ? 'x' : '' }}
        </p>
      </div>
    </div>

    <!-- Derniers messages + inscriptions -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <!-- Messages contact -->
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <h2 class="font-bold text-slate-900">Derniers messages</h2>
          <router-link to="/admin/leads/contact" class="text-xs text-green-600 font-semibold hover:underline">
            Voir tout →
          </router-link>
        </div>
        <div v-if="loading" class="p-8 text-center text-slate-400 text-sm">Chargement...</div>
        <div v-else-if="lastContacts.length === 0" class="p-8 text-center text-slate-400 text-sm">Aucun message reçu.</div>
        <div v-else>
          <div
            v-for="c in lastContacts" :key="c.id"
            class="flex items-start gap-4 px-6 py-4 border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors"
          >
            <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-sm flex-shrink-0">
              {{ c.name?.charAt(0)?.toUpperCase() }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-slate-900 text-sm truncate">{{ c.name }}</p>
              <p class="text-xs text-slate-500 truncate">{{ c.subject || c.email }}</p>
            </div>
            <span :class="statusClass(c.status)" class="text-xs px-2 py-0.5 rounded-full font-medium flex-shrink-0">
              {{ statusLabel(c.status) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Inscriptions -->
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <h2 class="font-bold text-slate-900">Dernières inscriptions</h2>
          <router-link to="/admin/leads/inscriptions" class="text-xs text-green-600 font-semibold hover:underline">
            Voir tout →
          </router-link>
        </div>
        <div v-if="loading" class="p-8 text-center text-slate-400 text-sm">Chargement...</div>
        <div v-else-if="lastInscriptions.length === 0" class="p-8 text-center text-slate-400 text-sm">Aucune inscription reçue.</div>
        <div v-else>
          <div
            v-for="ins in lastInscriptions" :key="ins.id"
            class="flex items-start gap-4 px-6 py-4 border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors"
          >
            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm flex-shrink-0">
              {{ ins.prenom?.charAt(0)?.toUpperCase() }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-slate-900 text-sm truncate">{{ ins.prenom }} {{ ins.nom }}</p>
              <p class="text-xs text-slate-500 truncate">{{ ins.formation_requested }}</p>
            </div>
            <span :class="statusClass(ins.status)" class="text-xs px-2 py-0.5 rounded-full font-medium flex-shrink-0">
              {{ statusLabel(ins.status) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import api from '@/services/api'

const adminStore      = useAdminStore()
const loading         = ref(true)
const lastContacts    = ref([])
const lastInscriptions = ref([])
const counters        = ref({})

onMounted(async () => {
  try {
    const { data } = await api.get('/admin/stats')
    counters.value         = data.counters        ?? {}
    lastContacts.value     = data.last_contacts   ?? []
    lastInscriptions.value = data.last_inscriptions ?? []
  } catch { /* silencieux */ }
  finally { loading.value = false }
})

const ICON_SOL  = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>`
const ICON_FORM = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>`
const ICON_MSG  = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`
const ICON_USR  = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`

const stats = computed(() => [
  { label: 'Solutions actives',       value: counters.value.solutions        ?? '—', new: 0,                                    icon: ICON_SOL,  bg: 'bg-blue-50',   color: 'text-blue-600'  },
  { label: 'Formations actives',      value: counters.value.formations       ?? '—', new: 0,                                    icon: ICON_FORM, bg: 'bg-purple-50', color: 'text-purple-600'},
  { label: 'Messages de contact',     value: counters.value.contacts_total   ?? '—', new: counters.value.contacts_new      ?? 0, icon: ICON_MSG,  bg: 'bg-amber-50',  color: 'text-amber-600' },
  { label: 'Inscriptions reçues',     value: counters.value.inscriptions_total ?? '—', new: counters.value.inscriptions_new ?? 0, icon: ICON_USR,  bg: 'bg-green-50',  color: 'text-green-600' },
])

const STATUS_MAP = {
  new:        { label: 'Nouveau',   cls: 'bg-blue-100 text-blue-700'   },
  read:       { label: 'Lu',        cls: 'bg-slate-100 text-slate-600' },
  replied:    { label: 'Répondu',   cls: 'bg-green-100 text-green-700' },
  contacted:  { label: 'Contacté',  cls: 'bg-amber-100 text-amber-700' },
  enrolled:   { label: 'Inscrit',   cls: 'bg-green-100 text-green-700' },
  cancelled:  { label: 'Annulé',    cls: 'bg-red-100 text-red-600'    },
}
const statusLabel = (s) => STATUS_MAP[s]?.label ?? s
const statusClass = (s) => STATUS_MAP[s]?.cls   ?? 'bg-slate-100 text-slate-600'
</script>
