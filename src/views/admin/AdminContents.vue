<template>
  <div>
    <div class="mb-8">
      <h1 class="text-2xl font-black text-slate-900">Textes du site</h1>
      <p class="text-slate-500 mt-1">Modifiez les textes affichés sur chaque page. Les modifications sont visibles immédiatement.</p>
    </div>

    <!-- Erreur de chargement (debug temporaire) -->
    <div v-if="fetchError" class="mb-10 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-mono break-all">
      ⚠️ Erreur API : {{ fetchError }}
    </div>

    <!-- Loader -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="w-8 h-8 border-4 border-green-600 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <!-- Contenu groupé par page -->
    <div v-else class="space-y-6">
      <div
        v-for="(rows, page) in groupedContents"
        :key="page"
        class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden"
      >
        <!-- En-tête de page -->
        <button
          @click="togglePage(page)"
          class="w-full flex items-center justify-between px-6 py-4 border-b border-slate-100 hover:bg-slate-50 transition-colors text-left"
        >
          <div class="flex items-center gap-3">
            <span class="text-lg">{{ PAGE_ICONS[page] ?? '📄' }}</span>
            <div>
              <p class="font-bold text-slate-900 capitalize">{{ PAGE_LABELS[page] ?? page }}</p>
              <p class="text-xs text-slate-400">{{ rows.length }} texte{{ rows.length > 1 ? 's' : '' }}</p>
            </div>
          </div>
          <svg
            class="w-5 h-5 text-slate-400 transition-transform duration-200"
            :class="expandedPages[page] ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>

        <!-- Lignes de contenu -->
        <Transition name="expand">
          <div v-if="expandedPages[page]" class="divide-y divide-slate-50">
            <div
              v-for="row in rows"
              :key="row.id"
              class="px-6 py-4 flex flex-col md:flex-row md:items-start gap-4"
            >
              <div class="md:w-56 flex-shrink-0">
                <p class="text-sm font-semibold text-slate-700">{{ row.label }}</p>
                <p class="text-xs text-slate-400 mt-0.5 font-mono">{{ row.key_name }}</p>
              </div>
              <div class="flex-1">
                <textarea
                  v-model="drafts[row.id]"
                  rows="2"
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all resize-none"
                ></textarea>
              </div>
              <div class="flex items-center gap-2 md:flex-shrink-0">
                <button
                  @click="save(row)"
                  :disabled="saving[row.id] || drafts[row.id] === row.value"
                  class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-500 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-bold transition-all"
                >
                  {{ saving[row.id] ? '...' : 'Sauvegarder' }}
                </button>
                <Transition name="fade">
                  <span v-if="saved[row.id]" class="text-xs text-green-600 font-semibold">✓ Sauvé</span>
                </Transition>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '@/services/api'

const loading  = ref(true)
const fetchError = ref('')
const groupedContents = ref({})
const drafts   = reactive({})
const saving   = reactive({})
const saved    = reactive({})
const expandedPages = reactive({})

const PAGE_LABELS = { global: 'En-tête & pied de page', home: 'Page d\'accueil', about: 'À propos', contact: 'Contact', formation: 'Formations', hackathon: 'Hackathon' }
const PAGE_ICONS  = { global: '🌐', home: '🏠', about: 'ℹ️', contact: '✉️', formation: '🎓', hackathon: '⚡' }

onMounted(async () => {
  try {
    const { data } = await api.get('/admin/contents')
    groupedContents.value = data
    let first = true
    for (const [page, rows] of Object.entries(data)) {
      if (first) { expandedPages[page] = true; first = false }
      for (const row of rows) {
        drafts[row.id] = row.value
      }
    }
  } catch (err) {
    // Affichage temporaire pour déboguer
    fetchError.value = err.response
      ? `[${err.response.status}] ${JSON.stringify(err.response.data)}`
      : err.message
  } finally {
    loading.value = false
  }
})

function togglePage(page) {
  expandedPages[page] = !expandedPages[page]
}

async function save(row) {
  saving[row.id] = true
  try {
    await api.put(`/admin/contents?id=${row.id}`, { value: drafts[row.id] })
    // Mettre à jour la valeur de référence pour désactiver le bouton
    row.value = drafts[row.id]
    saved[row.id] = true
    setTimeout(() => { saved[row.id] = false }, 2000)
  } catch { /* silencieux */ }
  finally { saving[row.id] = false }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.expand-enter-active, .expand-leave-active { transition: all .25s ease; overflow: hidden; }
.expand-enter-from, .expand-leave-to { opacity: 0; max-height: 0; }
.expand-enter-to, .expand-leave-from { max-height: 2000px; opacity: 1; }
</style>
