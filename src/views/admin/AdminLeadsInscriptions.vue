<template>
  <div>
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-black text-slate-900">Inscriptions aux formations</h1>
        <p class="text-slate-500 mt-1">{{ total }} demande{{ total > 1 ? 's' : '' }} reçue{{ total > 1 ? 's' : '' }} au total.</p>
      </div>
      <a
        :href="`${apiBase}/admin/leads/export/inscriptions`"
        target="_blank"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm transition-all"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        Exporter CSV
      </a>
    </div>

    <!-- Filtres -->
    <div class="flex gap-2 mb-8 flex-wrap">
      <button v-for="s in statuses" :key="s.value"
        @click="filterStatus = s.value; page = 1; load()"
        :class="filterStatus === s.value ? 'bg-green-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'"
        class="px-4 py-1.5 rounded-full text-sm font-medium transition-all"
      >
        {{ s.label }}
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-slate-400 text-sm">Chargement...</div>
      <div v-else-if="inscriptions.length === 0" class="p-8 text-center text-slate-400 text-sm">Aucune inscription pour ce filtre.</div>
      <div class="overflow-x-auto"><table class="w-full text-sm min-w-[640px]">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Candidat</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase hidden md:table-cell">Formation</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase hidden lg:table-cell">Date</th>
            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Statut</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          <tr v-for="ins in inscriptions" :key="ins.id"
            @click="selected = ins"
            class="hover:bg-slate-50 cursor-pointer transition-colors"
            :class="ins.status === 'new' ? 'font-semibold' : ''"
          >
            <td class="px-6 py-4">
              <p class="text-slate-900">{{ ins.prenom }} {{ ins.nom }}</p>
              <p class="text-xs text-slate-400">{{ ins.email }}</p>
            </td>
            <td class="px-6 py-4 text-slate-600 hidden md:table-cell">{{ ins.formation_requested }}</td>
            <td class="px-6 py-4 text-slate-400 text-xs hidden lg:table-cell">{{ formatDate(ins.created_at) }}</td>
            <td class="px-6 py-4 text-center" @click.stop>
              <select
                :value="ins.status"
                @change="updateStatus(ins, $event.target.value)"
                class="text-xs rounded-full px-2 py-1 border-0 font-medium cursor-pointer focus:outline-none"
                :class="statusClass(ins.status)"
              >
                <option value="new">Nouveau</option>
                <option value="contacted">Contacté</option>
                <option value="enrolled">Inscrit</option>
                <option value="cancelled">Annulé</option>
              </select>
            </td>
            <td class="px-6 py-4 text-right" @click.stop>
              <button @click="deleteIns(ins)" class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Pagination -->
      <div v-if="lastPage > 1" class="flex items-center justify-between px-6 py-4 border-t border-slate-100">
        <button @click="prevPage" :disabled="page === 1" class="px-4 py-2 rounded-xl border border-slate-200 text-sm disabled:opacity-40">← Précédent</button>
        <span class="text-sm text-slate-500">Page {{ page }} / {{ lastPage }}</span>
        <button @click="nextPage" :disabled="page === lastPage" class="px-4 py-2 rounded-xl border border-slate-200 text-sm disabled:opacity-40">Suivant →</button>
      </div>
    </div>

    <!-- Modal détail inscription -->
    <Teleport to="body">
      <div v-if="selected" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Inscription de {{ selected.prenom }} {{ selected.nom }}</h2>
            <button @click="selected = null" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <div class="px-6 py-5 text-sm space-y-4">
            <div class="grid grid-cols-2 gap-3 bg-slate-50 rounded-xl p-4">
              <div><p class="text-xs text-slate-400 uppercase font-bold mb-1">Prénom</p><p>{{ selected.prenom }}</p></div>
              <div><p class="text-xs text-slate-400 uppercase font-bold mb-1">Nom</p><p>{{ selected.nom }}</p></div>
              <div><p class="text-xs text-slate-400 uppercase font-bold mb-1">Email</p><a :href="`mailto:${selected.email}`" class="text-green-600 hover:underline">{{ selected.email }}</a></div>
              <div><p class="text-xs text-slate-400 uppercase font-bold mb-1">Téléphone</p><p>{{ selected.telephone || '—' }}</p></div>
              <div><p class="text-xs text-slate-400 uppercase font-bold mb-1">Ville</p><p>{{ selected.ville || '—' }}</p></div>
              <div><p class="text-xs text-slate-400 uppercase font-bold mb-1">Date</p><p>{{ formatDate(selected.created_at) }}</p></div>
              <div class="col-span-2"><p class="text-xs text-slate-400 uppercase font-bold mb-1">Formation souhaitée</p><p class="font-semibold text-green-700">{{ selected.formation_requested }}</p></div>
              <div><p class="text-xs text-slate-400 uppercase font-bold mb-1">Format</p><p>{{ selected.format_prefere }}</p></div>
              <div><p class="text-xs text-slate-400 uppercase font-bold mb-1">Niveau</p><p>{{ selected.niveau }}</p></div>
            </div>
            <div v-if="selected.message">
              <p class="text-xs text-slate-400 uppercase font-bold mb-2">Message</p>
              <p class="bg-slate-50 rounded-xl p-4 whitespace-pre-wrap leading-relaxed">{{ selected.message }}</p>
            </div>
          </div>
          <div class="px-6 py-4 border-t border-slate-100 flex gap-3 justify-end">
            <a :href="`mailto:${selected.email}`" class="px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-500 text-white text-sm font-bold">Contacter</a>
            <button @click="selected = null" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">Fermer</button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'

const apiBase      = import.meta.env.VITE_API_URL || ''
const loading      = ref(true)
const inscriptions = ref([])
const total        = ref(0)
const page         = ref(1)
const lastPage     = ref(1)
const filterStatus = ref('')
const selected     = ref(null)

const statuses = [
  { value: '', label: 'Tous' },
  { value: 'new', label: 'Nouveaux' },
  { value: 'contacted', label: 'Contactés' },
  { value: 'enrolled', label: 'Inscrits' },
  { value: 'cancelled', label: 'Annulés' },
]

onMounted(load)

async function load() {
  loading.value = true
  try {
    const params = new URLSearchParams({ page: page.value, per_page: 20 })
    if (filterStatus.value) params.set('status', filterStatus.value)
    const { data } = await api.get(`/admin/leads/inscriptions?${params}`)
    inscriptions.value = data.data
    total.value        = data.total
    lastPage.value     = data.last_page
  } catch { /* silencieux */ }
  finally { loading.value = false }
}

async function updateStatus(ins, status) {
  try { await api.put(`/admin/leads/inscriptions?id=${ins.id}`, { status }); ins.status = status }
  catch { /* silencieux */ }
}

async function deleteIns(ins) {
  if (!confirm(`Supprimer l'inscription de ${ins.prenom} ${ins.nom} ?`)) return
  try { await api.delete(`/admin/leads/inscriptions?id=${ins.id}`); load() }
  catch { /* silencieux */ }
}

function prevPage() { if (page.value > 1) { page.value--; load() } }
function nextPage() { if (page.value < lastPage.value) { page.value++; load() } }

const formatDate = (d) => d ? new Date(d).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '—'

const STATUS_CLS = {
  new: 'bg-blue-100 text-blue-700', contacted: 'bg-amber-100 text-amber-700',
  enrolled: 'bg-green-100 text-green-700', cancelled: 'bg-red-100 text-red-600',
}
const statusClass = (s) => STATUS_CLS[s] ?? 'bg-slate-100 text-slate-500'
</script>
