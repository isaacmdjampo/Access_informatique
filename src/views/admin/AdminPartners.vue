<template>
  <div>
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-black text-slate-900">Partenaires</h1>
        <p class="text-slate-500 mt-1">Gérez les logos affichés sur l'accueil et la page À propos.</p>
      </div>
      <button @click="openForm()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-500 text-white font-semibold text-sm transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Ajouter un partenaire
      </button>
    </div>

    <!-- Grille de logos -->
    <div v-if="loading" class="p-8 text-center text-slate-400 text-sm bg-white rounded-2xl border border-slate-100">Chargement...</div>
    <div v-else-if="partners.length === 0" class="p-8 text-center text-slate-400 text-sm bg-white rounded-2xl border border-slate-100">Aucun partenaire.</div>

    <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
      <div
        v-for="p in partners" :key="p.id"
        class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col items-center gap-4 relative group hover:border-green-200 hover:shadow-md transition-all"
      >
        <!-- Toggle actif/inactif -->
        <div class="absolute top-3 right-3">
          <button
            @click="toggleActive(p)"
            :class="p.is_active ? 'bg-green-500' : 'bg-slate-300'"
            :title="p.is_active ? 'Visible — cliquer pour masquer' : 'Masqué — cliquer pour afficher'"
            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors duration-200 focus:outline-none"
          >
            <span
              :class="p.is_active ? 'translate-x-4' : 'translate-x-1'"
              class="inline-block h-3 w-3 rounded-full bg-white shadow transition-transform duration-200"
            />
          </button>
        </div>

        <!-- Logo -->
        <div class="w-full h-20 flex items-center justify-center">
          <img
            :src="p.logo_url"
            :alt="p.name"
            class="max-h-16 max-w-full object-contain opacity-70 group-hover:opacity-100 transition-opacity"
            @error="$event.target.style.display='none'"
          />
        </div>

        <p class="text-xs font-semibold text-slate-600 text-center truncate w-full">{{ p.name }}</p>

        <!-- Actions -->
        <div class="flex gap-2">
          <button @click="openForm(p)" class="p-1.5 rounded-lg bg-slate-50 hover:bg-green-50 text-slate-500 hover:text-green-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          </button>
          <button @click="confirmDelete(p)" class="p-1.5 rounded-lg bg-slate-50 hover:bg-red-50 text-slate-500 hover:text-red-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Modal formulaire -->
    <Teleport to="body">
      <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">{{ form.id ? 'Modifier le partenaire' : 'Ajouter un partenaire' }}</h2>
            <button @click="showForm = false" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <div class="px-6 py-6 space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Nom *</label>
              <input v-model="form.name" class="field" placeholder="Centre Médical Lumière" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase mb-1.5">Logo (URL ou upload)</label>
              <div class="flex gap-3">
                <input v-model="form.logo_url" class="field flex-1" placeholder="http://... ou /uploads/partners/..." />
                <label class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium cursor-pointer flex-shrink-0">
                  Uploader
                  <input type="file" accept="image/*" class="hidden" @change="uploadLogo" />
                </label>
              </div>
              <img v-if="form.logo_url" :src="form.logo_url" class="mt-3 h-16 w-auto rounded-xl object-contain border border-slate-200 bg-slate-50 p-2" />
            </div>
            <div class="flex items-center gap-4">
              <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="checkbox" v-model="form.is_active" :true-value="1" :false-value="0" class="rounded" />
                Visible sur le site
              </label>
              <div class="flex items-center gap-2">
                <label class="text-xs font-bold text-slate-600 uppercase">Ordre</label>
                <input v-model.number="form.sort_order" type="number" min="0" class="w-16 px-2 py-1 rounded-lg border border-slate-200 text-sm text-center" />
              </div>
            </div>
            <p v-if="formError" class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3">{{ formError }}</p>
          </div>
          <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
            <button @click="showForm = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">Annuler</button>
            <button @click="submitForm" :disabled="submitting" class="px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-500 disabled:opacity-60 text-white text-sm font-bold">
              {{ submitting ? '...' : (form.id ? 'Mettre à jour' : 'Ajouter') }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Confirmation suppression -->
    <Teleport to="body">
      <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center">
          <h3 class="text-lg font-bold text-slate-900 mb-2">Supprimer {{ deleteTarget.name }} ?</h3>
          <p class="text-sm text-slate-500 mb-10">Cette action est irréversible.</p>
          <div class="flex gap-3 justify-center">
            <button @click="deleteTarget = null" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">Annuler</button>
            <button @click="deletePartner" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-sm font-bold">Supprimer</button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '@/services/api'

const loading      = ref(true)
const partners     = ref([])
const showForm     = ref(false)
const submitting   = ref(false)
const formError    = ref('')
const deleteTarget = ref(null)

const form = reactive({ id: null, name: '', logo_url: '', is_active: 1, sort_order: 0 })

onMounted(load)

async function load() {
  loading.value = true
  try { const { data } = await api.get('/admin/partners'); partners.value = data }
  catch { /* silencieux */ }
  finally { loading.value = false }
}

function openForm(p = null) {
  formError.value = ''
  if (p) Object.assign(form, { id: p.id, name: p.name, logo_url: p.logo_url, is_active: p.is_active, sort_order: p.sort_order })
  else   Object.assign(form, { id: null, name: '', logo_url: '', is_active: 1, sort_order: 0 })
  showForm.value = true
}

async function uploadLogo(e) {
  const file = e.target.files?.[0]; if (!file) return
  const fd = new FormData(); fd.append('image', file); fd.append('type', 'partner')
  try { const { data } = await api.post('/admin/upload', fd, { headers: { 'Content-Type': 'multipart/form-data' } }); form.logo_url = data.url }
  catch { formError.value = 'Erreur lors de l\'upload.' }
}

async function submitForm() {
  if (!form.name || !form.logo_url) { formError.value = 'Nom et logo obligatoires.'; return }
  formError.value  = ''
  submitting.value = true
  try {
    if (form.id) await api.put(`/admin/partners?id=${form.id}`, form)
    else         await api.post('/admin/partners', form)
    showForm.value = false
    load()
  } catch (err) {
    formError.value = err.response?.data?.error || 'Erreur lors de la sauvegarde.'
  } finally { submitting.value = false }
}

async function toggleActive(p) {
  const newVal = p.is_active ? 0 : 1
  try {
    await api.patch(`/admin/partners?id=${p.id}`, { is_active: newVal })
    p.is_active = newVal
  } catch { /* silencieux */ }
}

function confirmDelete(p) { deleteTarget.value = p }
async function deletePartner() {
  if (!deleteTarget.value) return
  try { await api.delete(`/admin/partners?id=${deleteTarget.value.id}`); deleteTarget.value = null; load() }
  catch { /* silencieux */ }
}
</script>

<style scoped>
@reference "../../assets/main.css";
.field { @apply w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all; }
</style>
