<template>
  <div>
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-black text-slate-900">Solutions</h1>
        <p class="text-slate-500 mt-1">Gérez les logiciels affichés sur le site.</p>
      </div>
      <button
        @click="openForm()"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-500 text-white font-semibold text-sm transition-all"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle solution
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-slate-400 text-sm">Chargement...</div>
      <div v-else-if="solutions.length === 0" class="p-8 text-center text-slate-400 text-sm">Aucune solution.</div>
      <div class="overflow-x-auto"><table class="w-full text-sm min-w-[640px]">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nom</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider hidden md:table-cell">Catégorie</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Prix</th>
            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Actif</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          <tr v-for="sol in solutions" :key="sol.id" class="hover:bg-slate-50 transition-colors">
            <td class="px-6 py-4 font-semibold text-slate-900">{{ sol.name }}</td>
            <td class="px-6 py-4 text-slate-500 hidden md:table-cell">{{ sol.category }}</td>
            <td class="px-6 py-4 text-slate-500 hidden lg:table-cell">{{ sol.price }}</td>
            <td class="px-6 py-4 text-center">
              <button
                @click="toggleActive(sol)"
                :class="sol.is_active ? 'bg-green-500' : 'bg-slate-300'"
                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none"
                :title="sol.is_active ? 'Visible — cliquer pour masquer' : 'Masqué — cliquer pour afficher'"
              >
                <span
                  :class="sol.is_active ? 'translate-x-6' : 'translate-x-1'"
                  class="inline-block h-4 w-4 rounded-full bg-white shadow transition-transform duration-200"
                />
              </button>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <button @click="openForm(sol.id)" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-green-600 transition-colors" title="Modifier">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button @click="confirmDelete(sol)" class="p-1.5 rounded-lg hover:bg-red-50 text-slate-500 hover:text-red-600 transition-colors" title="Supprimer">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table></div>
    </div>

    <!-- Modal formulaire -->
    <Teleport to="body">
      <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 sticky top-0 bg-white">
            <h2 class="font-bold text-slate-900">{{ form.id ? 'Modifier la solution' : 'Nouvelle solution' }}</h2>
            <button @click="showForm = false" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>

          <div class="px-6 py-6 space-y-5">
            <!-- Champs principaux -->
            <div class="grid grid-cols-2 gap-4">
              <div class="col-span-2 md:col-span-1">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Nom *</label>
                <input v-model="form.name" class="field" placeholder="SoluMed" />
              </div>
              <div class="col-span-2 md:col-span-1">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Slug *</label>
                <input v-model="form.slug" class="field" placeholder="solumed" />
              </div>
              <div class="col-span-2">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Tagline *</label>
                <input v-model="form.tagline" class="field" placeholder="La solution complète pour..." />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Catégorie *</label>
                <input v-model="form.category" class="field" placeholder="Santé" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Prix *</label>
                <input v-model="form.price" class="field" placeholder="650 000 FCFA" />
              </div>
              <div class="col-span-2">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Description courte *</label>
                <textarea v-model="form.short_description" rows="2" class="field resize-none"></textarea>
              </div>
              <div class="col-span-2">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Description complète *</label>
                <textarea v-model="form.full_description" rows="4" class="field resize-none"></textarea>
              </div>
            </div>

            <!-- Image -->
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Image</label>
              <div class="flex gap-3">
                <input v-model="form.hero_image" class="field flex-1" placeholder="URL ou chemin après upload" />
                <label class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium cursor-pointer transition-all flex-shrink-0">
                  Uploader
                  <input type="file" accept="image/*" class="hidden" @change="uploadImage" />
                </label>
              </div>
              <img v-if="form.hero_image" :src="form.hero_image" class="mt-3 h-24 w-auto rounded-xl object-cover border border-slate-200" />
            </div>

            <!-- Stats -->
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-3">Statistiques (3 max)</label>
              <div class="grid grid-cols-3 gap-3">
                <div v-for="i in [1,2,3]" :key="i" class="space-y-2">
                  <input v-model="form[`stat${i}_value`]" class="field text-sm" :placeholder="`Valeur ${i}`" />
                  <input v-model="form[`stat${i}_label`]" class="field text-sm" :placeholder="`Label ${i}`" />
                </div>
              </div>
            </div>

            <!-- Modules -->
            <div>
              <div class="flex items-center justify-between mb-3">
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Modules</label>
                <button @click="addModule" class="text-xs text-green-600 font-semibold hover:underline">+ Ajouter</button>
              </div>
              <div class="space-y-2">
                <div v-for="(m, i) in form.modules" :key="i" class="flex gap-2">
                  <div class="flex-1 space-y-1.5">
                    <input v-model="m.title" class="field text-sm" placeholder="Titre du module" />
                    <input v-model="m.description" class="field text-sm" placeholder="Description" />
                  </div>
                  <button @click="form.modules.splice(i, 1)" class="p-2 text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Avantages -->
            <div>
              <div class="flex items-center justify-between mb-3">
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">Avantages</label>
                <button @click="form.advantages.push('')" class="text-xs text-green-600 font-semibold hover:underline">+ Ajouter</button>
              </div>
              <div class="space-y-2">
                <div v-for="(_, i) in form.advantages" :key="i" class="flex gap-2">
                  <input v-model="form.advantages[i]" class="field flex-1 text-sm" placeholder="Avantage..." />
                  <button @click="form.advantages.splice(i, 1)" class="p-2 text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Tags -->
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Tags (séparés par des virgules)</label>
              <input v-model="tagsInput" class="field" placeholder="Facturation, Pharmacie, Statistiques" />
            </div>

            <!-- Options -->
            <div class="flex gap-4">
              <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="checkbox" v-model="form.is_active" :true-value="1" :false-value="0" class="rounded border-slate-300" />
                Visible sur le site
              </label>
              <div>
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wide mr-2">Ordre</label>
                <input v-model.number="form.sort_order" type="number" min="0" class="w-16 px-2 py-1 rounded-lg border border-slate-200 text-sm text-center" />
              </div>
            </div>

            <!-- Erreur -->
            <p v-if="formError" class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3">{{ formError }}</p>
          </div>

          <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 sticky bottom-0 bg-white">
            <button @click="showForm = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50 transition-colors">Annuler</button>
            <button @click="submitForm" :disabled="submitting" class="px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-500 disabled:opacity-60 text-white text-sm font-bold transition-all">
              {{ submitting ? 'Sauvegarde...' : (form.id ? 'Mettre à jour' : 'Créer') }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Confirmation suppression -->
    <Teleport to="body">
      <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center">
          <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-10">
            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          </div>
          <h3 class="text-lg font-bold text-slate-900 mb-2">Supprimer {{ deleteTarget.name }} ?</h3>
          <p class="text-sm text-slate-500 mb-10">Cette action est irréversible. Les modules, avantages et tags associés seront également supprimés.</p>
          <div class="flex gap-3 justify-center">
            <button @click="deleteTarget = null" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">Annuler</button>
            <button @click="deleteSolution" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-sm font-bold">Supprimer</button>
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
const solutions    = ref([])
const showForm     = ref(false)
const submitting   = ref(false)
const formError    = ref('')
const deleteTarget = ref(null)
const tagsInput    = ref('')

const emptyForm = () => ({
  id: null, slug: '', name: '', tagline: '', category: '',
  accent_color: '#166030', accent_color_light: '#dcfce7',
  hero_image: '', price: '', short_description: '', full_description: '',
  brochure_url: '', demo_url: '',
  stat1_value: '', stat1_label: '', stat2_value: '', stat2_label: '', stat3_value: '', stat3_label: '',
  is_active: 1, sort_order: 0,
  modules: [], advantages: [],
})

const form = reactive(emptyForm())

onMounted(loadSolutions)

async function loadSolutions() {
  loading.value = true
  try {
    const { data } = await api.get('/admin/solutions')
    solutions.value = data
  } catch { /* silencieux */ }
  finally { loading.value = false }
}

async function openForm(id = null) {
  Object.assign(form, emptyForm())
  tagsInput.value = ''
  formError.value = ''
  if (id) {
    const { data } = await api.get(`/admin/solutions?id=${id}`)
    Object.assign(form, data)
    form.advantages = Array.isArray(data.advantages) ? data.advantages.map((a) => a.text ?? a) : []
    form.modules    = Array.isArray(data.modules)    ? data.modules.map((m) => ({ title: m.title, description: m.description })) : []
    tagsInput.value = Array.isArray(data.tags) ? data.tags.map((t) => t.tag ?? t).join(', ') : ''
  }
  showForm.value = true
}

function addModule() { form.modules.push({ title: '', description: '' }) }

async function uploadImage(e) {
  const file = e.target.files?.[0]
  if (!file) return
  const fd = new FormData()
  fd.append('image', file)
  fd.append('type', 'solution')
  try {
    const { data } = await api.post('/admin/upload', fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    form.hero_image = data.url
  } catch { formError.value = 'Erreur lors de l\'upload de l\'image.' }
}

async function submitForm() {
  formError.value = ''
  submitting.value = true
  try {
    const payload = {
      ...form,
      tags:       tagsInput.value.split(',').map((t) => t.trim()).filter(Boolean),
      advantages: form.advantages.filter(Boolean),
      modules:    form.modules.filter((m) => m.title),
    }
    if (form.id) {
      await api.put(`/admin/solutions?id=${form.id}`, payload)
    } else {
      await api.post('/admin/solutions', payload)
    }
    showForm.value = false
    loadSolutions()
  } catch (err) {
    formError.value = err.response?.data?.error || 'Erreur lors de la sauvegarde.'
  } finally {
    submitting.value = false
  }
}

async function toggleActive(sol) {
  const newVal = sol.is_active ? 0 : 1
  try {
    await api.patch(`/admin/solutions?id=${sol.id}`, { is_active: newVal })
    sol.is_active = newVal
  } catch { /* silencieux */ }
}

function confirmDelete(sol) { deleteTarget.value = sol }

async function deleteSolution() {
  if (!deleteTarget.value) return
  try {
    await api.delete(`/admin/solutions?id=${deleteTarget.value.id}`)
    deleteTarget.value = null
    loadSolutions()
  } catch { /* silencieux */ }
}
</script>

<style scoped>
@reference "../../assets/main.css";
.field {
  @apply w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800
         focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all;
}
</style>
