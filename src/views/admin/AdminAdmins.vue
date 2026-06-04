<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-black text-slate-900">Gestion des administrateurs</h1>
        <p class="text-slate-500 mt-1">{{ admins.length }} administrateur{{ admins.length > 1 ? 's' : '' }} inscrit{{ admins.length > 1 ? 's' : '' }}.</p>
      </div>
      <button
        @click="showCreateModal = true"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-500 text-white font-semibold text-sm transition-all"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter un admin
      </button>
    </div>

    <!-- Tableau des admins -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-slate-400 text-sm">Chargement...</div>
      <div v-else-if="admins.length === 0" class="p-8 text-center text-slate-400 text-sm">Aucun administrateur.</div>
      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
          <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nom</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Email</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Rôle</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Créé par</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Date</th>
              <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-for="admin in admins" :key="admin.id" class="hover:bg-slate-50 transition-colors" :class="admin.id === currentAdminId ? 'bg-green-50' : ''">
              <td class="px-6 py-4 font-medium text-slate-900">{{ admin.name }}</td>
              <td class="px-6 py-4 text-slate-600 text-sm">{{ admin.email }}</td>
              <td class="px-6 py-4">
                <span :class="{
                  'bg-red-100 text-red-700': admin.role === 'superadmin',
                  'bg-blue-100 text-blue-700': admin.role === 'admin',
                  'bg-amber-100 text-amber-700': admin.role === 'editor'
                }" class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold">
                  {{ roleLabel(admin.role) }}
                </span>
              </td>
              <td class="px-6 py-4 text-slate-600 text-sm">
                {{ creatorName(admin.created_by) || '—' }}
              </td>
              <td class="px-6 py-4 text-slate-400 text-xs">{{ formatDate(admin.created_at) }}</td>
              <td class="px-6 py-4 text-right" @click.stop>
                <div class="flex items-center justify-end gap-2">
                  <button
                    v-if="isSuperAdmin && admin.id !== currentAdminId"
                    @click="editAdmin(admin)"
                    class="p-1.5 rounded-lg hover:bg-blue-50 text-slate-400 hover:text-blue-600 transition-colors"
                    title="Modifier le rôle"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button
                    v-if="isSuperAdmin && admin.id !== currentAdminId"
                    @click="deleteAdmin(admin)"
                    class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors"
                    title="Supprimer"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal : Créer un nouvel admin -->
    <Teleport to="body">
      <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Créer un nouvel administrateur</h2>
            <button @click="showCreateModal = false" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <div class="px-6 py-6 space-y-4 text-sm">
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Nom *</label>
              <input
                v-model="newAdmin.name"
                type="text"
                placeholder="Nom complet"
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Email *</label>
              <input
                v-model="newAdmin.email"
                type="email"
                placeholder="admin@example.com"
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Mot de passe *</label>
              <input
                v-model="newAdmin.password"
                type="password"
                placeholder="Au moins 8 caractères"
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Rôle</label>
              <select
                v-model="newAdmin.role"
                :disabled="!isSuperAdmin"
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none disabled:bg-slate-50 disabled:text-slate-400 cursor-pointer"
              >
                <option value="editor">Éditeur (contenu)</option>
                <option v-if="isSuperAdmin" value="admin">Administrateur</option>
                <option v-if="isSuperAdmin" value="superadmin">Super Administrateur</option>
              </select>
            </div>
            <div v-if="createError" class="p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs">
              {{ createError }}
            </div>
          </div>
          <div class="px-6 py-4 border-t border-slate-100 flex gap-3 justify-end">
            <button @click="showCreateModal = false" class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium hover:bg-slate-50">
              Annuler
            </button>
            <button
              @click="createAdmin"
              :disabled="isCreating"
              class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-500 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-bold"
            >
              {{ isCreating ? 'Création...' : 'Créer' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Modal : Modifier le rôle -->
    <Teleport to="body">
      <div v-if="showEditModal && editingAdmin" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Modifier le rôle</h2>
            <button @click="showEditModal = false" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <div class="px-6 py-6 space-y-4 text-sm">
            <p class="text-slate-600">
              Administrateur: <strong>{{ editingAdmin.name }}</strong> ({{ editingAdmin.email }})
            </p>
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Nouveau rôle</label>
              <select
                v-model="editingAdmin.role"
                class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none cursor-pointer"
              >
                <option value="editor">Éditeur</option>
                <option value="admin">Administrateur</option>
                <option value="superadmin">Super Administrateur</option>
              </select>
            </div>
            <div v-if="editError" class="p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs">
              {{ editError }}
            </div>
          </div>
          <div class="px-6 py-4 border-t border-slate-100 flex gap-3 justify-end">
            <button @click="showEditModal = false" class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium hover:bg-slate-50">
              Annuler
            </button>
            <button
              @click="updateAdminRole"
              :disabled="isUpdating"
              class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-500 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-bold"
            >
              {{ isUpdating ? 'Mise à jour...' : 'Mettre à jour' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '@/services/api'

const admins = ref([])
const loading = ref(true)
const showCreateModal = ref(false)
const showEditModal = ref(false)
const isCreating = ref(false)
const isUpdating = ref(false)
const createError = ref('')
const editError = ref('')
const editingAdmin = ref(null)
const currentAdminId = ref(null)

const newAdmin = ref({
  name: '',
  email: '',
  password: '',
  role: 'editor',
})

const isSuperAdmin = computed(() => {
  const admin = admins.value.find(a => a.id === currentAdminId.value)
  return admin?.role === 'superadmin'
})

onMounted(async () => {
  // Récupérer le rôle de l'admin connecté depuis le store
  const token = localStorage.getItem('admin_token')
  if (token) {
    try {
      const payload = JSON.parse(atob(token.split('.')[1]))
      currentAdminId.value = payload.admin_id
    } catch (e) {
      console.error('Erreur parsing token', e)
    }
  }
  load()
})

async function load() {
  loading.value = true
  try {
    const { data } = await api.get('/admin/admins')
    admins.value = data.data
  } catch (err) {
    console.error('Erreur loading admins:', err)
  } finally {
    loading.value = false
  }
}

async function createAdmin() {
  createError.value = ''

  if (!newAdmin.value.name || !newAdmin.value.email || !newAdmin.value.password) {
    createError.value = 'Tous les champs sont obligatoires.'
    return
  }

  isCreating.value = true
  try {
    await api.post('/admin/admins', {
      name: newAdmin.value.name,
      email: newAdmin.value.email,
      password: newAdmin.value.password,
      role: newAdmin.value.role,
    })

    showCreateModal.value = false
    newAdmin.value = { name: '', email: '', password: '', role: 'editor' }
    await load()
  } catch (err) {
    createError.value = err.response?.data?.error || err.response?.data?.errors?.[0] || 'Erreur de création'
  } finally {
    isCreating.value = false
  }
}

function editAdmin(admin) {
  editingAdmin.value = { ...admin }
  editError.value = ''
  showEditModal.value = true
}

async function updateAdminRole() {
  editError.value = ''
  isUpdating.value = true
  try {
    await api.put(`/admin/admins?id=${editingAdmin.value.id}`, {
      role: editingAdmin.value.role,
    })

    showEditModal.value = false
    editingAdmin.value = null
    await load()
  } catch (err) {
    editError.value = err.response?.data?.error || 'Erreur de mise à jour'
  } finally {
    isUpdating.value = false
  }
}

async function deleteAdmin(admin) {
  if (!confirm(`Êtes-vous sûr de vouloir supprimer ${admin.name} ?`)) return

  try {
    await api.delete(`/admin/admins?id=${admin.id}`)
    await load()
  } catch (err) {
    console.error('Erreur suppression:', err)
  }
}

function roleLabel(role) {
  const labels = {
    superadmin: 'Super Admin',
    admin: 'Admin',
    editor: 'Éditeur',
  }
  return labels[role] || role
}

function creatorName(createdBy) {
  if (!createdBy) return null
  const creator = admins.value.find(a => a.id === createdBy)
  return creator?.name || null
}

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}
</script>
