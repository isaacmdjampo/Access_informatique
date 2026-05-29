<template>
  <div class="formation-detail-page bg-white text-slate-900 min-h-screen">
    <section class="relative pt-40 pb-20 overflow-hidden bg-slate-50">
      <div class="hero-grid absolute inset-0 pointer-events-none"></div>
      <div class="relative z-10 max-w-6xl mx-auto px-6">
        <div class="text-center">
          <router-link
            :to="{ name: 'formations' }"
            class="inline-flex items-center gap-2 text-slate-500 hover:text-green-600 mb-8 transition-colors group"
          >
            <svg
              class="w-4 h-4 group-hover:-translate-x-1 transition-transform"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
              />
            </svg>
            Retour aux formations
          </router-link>
        </div>

        <div v-if="isLoading" class="py-24 text-center">
          <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-full border-4 border-slate-200"
          >
            <div
              class="w-8 h-8 border-4 border-green-600 border-t-transparent rounded-full animate-spin"
            ></div>
          </div>
          <p class="mt-6 text-slate-500">Chargement de la formation...</p>
        </div>

        <div v-else>
          <div v-if="!formation" class="py-24 text-center">
            <div class="text-6xl mb-4">🔍</div>
            <h1 class="text-3xl font-black text-slate-900 mb-3">Formation non trouvée</h1>
            <p class="text-slate-500 max-w-xl mx-auto">
              La formation recherchée est introuvable. Vérifie l'URL ou retourne à la liste des
              formations.
            </p>
            <button
              @click="goBack"
              class="mt-8 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-500 transition-all"
            >
              Retour aux formations
            </button>
          </div>

          <div v-else class="grid gap-12 lg:grid-cols-[1.1fr_0.9fr] items-start">
            <div class="rounded-[2rem] border border-slate-200 bg-white shadow-xl overflow-hidden">
              <img :src="formation.image" :alt="formation.title" class="w-full h-80 object-cover" />
              <div class="p-10">
                <span
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 text-green-700 text-xs font-semibold uppercase tracking-[0.2em] mb-6"
                >
                  {{ formation.category }}
                </span>
                <h1 class="text-4xl font-black text-slate-900 mb-2">{{ formation.title }}</h1>
                <div class="flex items-center justify-between mb-6 gap-4">
                  <p class="text-2xl font-extrabold text-green-600">{{ formation.price }}</p>
                  <p class="text-slate-500 text-sm">Niveau : {{ formation.level }}</p>
                </div>

                <p class="text-slate-600 leading-relaxed text-lg mb-8">
                  {{ formation.description }}
                </p>

                <div class="grid gap-4 sm:grid-cols-2 mb-10">
                  <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <p class="text-slate-500 text-sm uppercase tracking-[0.2em] mb-3">Durée</p>
                    <p class="text-xl font-semibold text-slate-900">{{ formation.duration }}</p>
                  </div>
                  <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <p class="text-slate-500 text-sm uppercase tracking-[0.2em] mb-3">Niveau</p>
                    <p class="text-xl font-semibold text-slate-900">{{ formation.level }}</p>
                  </div>
                </div>

                <div class="mb-10">
                  <p class="text-slate-500 text-sm uppercase tracking-[0.2em] mb-4">
                    Compétences clés
                  </p>
                  <div class="flex flex-wrap gap-3">
                    <span
                      v-for="skill in formation.skills"
                      :key="skill"
                      class="px-4 py-2 rounded-full bg-slate-100 text-slate-700 text-sm"
                    >
                      {{ skill }}
                    </span>
                  </div>
                </div>

                <div v-if="outcomes.length" class="mb-10">
                  <p class="text-slate-500 text-sm uppercase tracking-[0.2em] mb-4">
                    Objectifs de la formation
                  </p>
                  <ul class="grid gap-3">
                    <li
                      v-for="(item, index) in outcomes"
                      :key="index"
                      class="rounded-3xl border border-slate-200 bg-slate-50 p-4 text-slate-700"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </div>

                <router-link
                  :to="{ name: 'inscription', query: { formation: formation.slug } }"
                  class="inline-flex items-center gap-3 px-7 py-4 rounded-3xl bg-green-600 text-white font-semibold hover:bg-green-500 transition-all"
                >
                  S'inscrire à cette formation
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M17 8l4 4m0 0l-4 4m4-4H3"
                    />
                  </svg>
                </router-link>
              </div>
            </div>

            <div class="space-y-8">
              <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="text-3xl font-black text-slate-900 mb-4">Programme de la formation</h2>
                <div class="space-y-4">
                  <div
                    v-for="(module, index) in modules"
                    :key="module.title"
                    class="rounded-3xl border border-slate-200 p-6"
                  >
                    <div class="flex items-center justify-between mb-3">
                      <span class="text-green-600 font-bold">Module {{ index + 1 }}</span>
                      <span class="text-slate-400 text-xs uppercase tracking-[0.2em]">{{
                        module.duration
                      }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ module.title }}</h3>
                    <p class="text-slate-500 leading-relaxed">{{ module.description }}</p>
                  </div>
                </div>
              </div>

              <div
                class="rounded-[2rem] border border-slate-200 bg-green-600 p-8 text-white shadow-xl"
              >
                <h3 class="text-3xl font-black mb-4">Pourquoi cette formation ?</h3>
                <ul class="space-y-3 text-slate-100">
                  <li v-for="(benefit, index) in benefits" :key="index">✅ {{ benefit }}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { formations } from '@/data/formations'

const route = useRoute()
const router = useRouter()
const formation = ref(null)
const isLoading = ref(true)

const defaultModules = [
  {
    title: 'Introduction à la formation',
    description: 'Comprendre les objectifs, le plan de cours et les résultats attendus.',
    duration: '1 semaine',
  },
  {
    title: 'Travail pratique',
    description: 'Mise en application des connaissances au travers d’exercices concrets.',
    duration: '2 semaines',
  },
  {
    title: 'Approfondissement',
    description: 'Perfectionnement sur les outils et les bonnes pratiques du métier.',
    duration: '2 semaines',
  },
  {
    title: 'Projet final',
    description: 'Réalisation d’un projet complet avec suivi et retours personnalisés.',
    duration: '2 semaines',
  },
]

const modules = computed(() => formation.value?.modules ?? defaultModules)
const benefits = computed(
  () =>
    formation.value?.benefits ?? [
      'Contenu orienté projet',
      'Support et accompagnement dédiés',
      'Certification professionnelle',
      'Accès aux ressources pédagogiques',
    ],
)
const outcomes = computed(() => formation.value?.outcomes ?? [])

const loadFormation = () => {
  const slug = route.params.slug
  formation.value = formations.find((item) => item.slug === slug) ?? null
}

const goBack = () => router.push({ name: 'formations' })
const goContact = () => router.push({ name: 'contact' })

onMounted(() => {
  setTimeout(() => {
    loadFormation()
    isLoading.value = false
  }, 200)
})
</script>

<style scoped>
.hero-grid {
  background-image:
    linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
  background-size: 80px 80px;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 0.8s linear infinite;
}
</style>
