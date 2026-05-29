<template>
  <div class="formation-list-page bg-white text-slate-900 min-h-screen">
    <!-- HERO avec recherche -->
    <section class="relative pt-36 pb-20 overflow-hidden bg-gradient-to-br from-slate-50 to-white">
      <div class="hero-grid absolute inset-0 pointer-events-none"></div>

      <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
        <router-link
          to="/formation"
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

        <h1 class="text-4xl md:text-6xl font-black tracking-tight text-slate-900 mb-6">
          Toutes nos <span class="text-green-600">formations</span>
        </h1>

        <p class="text-lg text-slate-500 max-w-2xl mx-auto mb-12">
          Découvrez l'intégralité de notre catalogue et trouvez la formation qui correspond à vos
          objectifs.
        </p>

        <!-- Barre de recherche -->
        <div class="max-w-2xl mx-auto relative">
          <div class="relative group">
            <div
              class="absolute inset-0 rounded-2xl bg-gradient-to-r from-green-500 to-green-600 opacity-0 group-focus-within:opacity-100 transition-opacity duration-500 blur-xl"
            ></div>

            <div
              class="relative flex items-center bg-white border border-slate-200 rounded-2xl shadow-xl focus-within:border-green-500 focus-within:ring-4 focus-within:ring-green-500/10 transition-all duration-300"
            >
              <div class="pl-5 pr-2 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
              </div>

              <input
                v-model="searchQuery"
                type="text"
                placeholder="Rechercher une formation... (ex: Big Data, Web, Design...)"
                class="flex-1 py-5 pr-4 bg-transparent outline-none text-slate-800 placeholder-slate-400"
              />

              <button
                v-if="searchQuery"
                @click="clearSearch"
                class="mr-3 text-slate-400 hover:text-slate-600 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>

          <!-- Suggestion orthographique -->
          <Transition name="slide-down">
            <div
              v-if="
                suggestion && searchQuery.trim() && filteredFormations.length === 0 && !isExactMatch
              "
              class="absolute left-0 right-0 mt-3 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden z-20"
            >
              <div class="p-4 bg-amber-50 border-b border-amber-100">
                <div class="flex items-center gap-2 text-amber-700 text-sm">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                  <span
                    >Aucun résultat pour <strong>"{{ searchQuery }}"</strong></span
                  >
                </div>
              </div>

              <div
                class="p-4 hover:bg-slate-50 transition-colors cursor-pointer group"
                @click="applySuggestion"
              >
                <div class="text-sm text-slate-500 mb-1">💡 Peut-être cherchez-vous :</div>
                <div
                  class="font-semibold text-green-600 flex items-center gap-2 group-hover:gap-3 transition-all"
                >
                  {{ suggestion }}
                  <svg
                    class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14 5l7 7m0 0l-7 7m7-7H3"
                    />
                  </svg>
                </div>
              </div>
            </div>
          </Transition>
        </div>

        <!-- Résultats de recherche -->
        <div class="mt-6 text-sm text-slate-400">
          <Transition name="fade" mode="out-in">
            <span :key="filteredFormations.length">
              {{ filteredFormations.length }} formation(s) trouvée(s)
            </span>
          </Transition>
        </div>
      </div>
    </section>

    <!-- Liste des formations -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <!-- Loader rapide au chargement initial -->
        <div v-if="isPageLoading" class="flex justify-center py-20">
          <div class="text-center">
            <div class="relative w-16 h-16 mx-auto mb-4">
              <div class="absolute inset-0 rounded-full border-4 border-slate-200"></div>
              <div
                class="absolute inset-0 rounded-full border-4 border-green-600 border-t-transparent animate-spin"
              ></div>
            </div>
            <p class="text-slate-500 text-sm">Chargement...</p>
          </div>
        </div>

        <!-- Grille des formations -->
        <TransitionGroup
          v-else
          name="card"
          tag="div"
          class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8"
        >
          <div
            v-for="(formation, index) in filteredFormations"
            :key="formation.title"
            :style="{ transitionDelay: `${index * 30}ms` }"
            class="group bg-white border border-slate-200 rounded-[2rem] overflow-hidden hover:shadow-2xl hover:shadow-green-100/50 hover:-translate-y-2 transition-all duration-500 flex flex-col"
          >
            <div class="relative overflow-hidden h-60">
              <img
                :src="formation.image"
                :alt="formation.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"
              ></div>
            </div>

            <div class="p-8 flex flex-col flex-grow">
              <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-widest text-green-600 font-bold">
                  {{ formation.category }}
                </span>
              </div>

              <h3 class="text-2xl font-black text-slate-900 mb-4 leading-tight">
                {{ formation.title }}
              </h3>

              <p class="text-slate-500 leading-relaxed text-sm mb-6">
                {{ formation.description }}
              </p>

              <div class="flex flex-wrap gap-2 mb-8">
                <span
                  v-for="skill in formation.skills"
                  :key="skill"
                  class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium"
                >
                  {{ skill }}
                </span>
              </div>

              <!-- Bouton aligné en bas de la carte -->
              <div class="mt-auto">
                <router-link
                  :to="{ name: 'formation-detail', params: { slug: formation.slug } }"
                  class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl bg-slate-900 hover:bg-green-600 text-white font-semibold transition-all duration-300 group/btn"
                >
                  Détails de la formation
                  <svg
                    class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
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
          </div>
        </TransitionGroup>

        <!-- État vide -->
        <Transition name="fade">
          <div
            v-if="!isPageLoading && filteredFormations.length === 0 && !suggestion"
            class="text-center py-20"
          >
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-2xl font-black text-slate-900 mb-2">Aucune formation trouvée</h3>
            <p class="text-slate-500">
              Essayez avec d'autres mots-clés comme "Web", "Design" ou "Data"
            </p>
            <button
              @click="clearSearch"
              class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-500 transition-all"
            >
              Voir toutes les formations
            </button>
          </div>
        </Transition>
      </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-green-600 relative overflow-hidden">
      <div
        class="absolute inset-0 opacity-30"
        style="
          background: radial-gradient(
            circle at center,
            rgba(255, 255, 255, 0.2) 0%,
            transparent 70%
          );
        "
      ></div>
      <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-5xl font-black text-white tracking-tight">
          Vous ne trouvez pas votre bonheur ?
        </h2>
        <p class="mt-6 text-xl text-green-100">
          Contactez-nous pour une formation sur mesure adaptée à vos besoins.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-10">
          <router-link
            to="/contact"
            class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-white text-green-600 hover:bg-slate-100 font-black transition-all hover:-translate-y-1"
          >
            Nous contacter
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { formations } from '@/data/formations'

const searchQuery = ref('')
const isPageLoading = ref(true)

const normalize = (str) => {
  return str
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
}

const getSimilarity = (str1, str2) => {
  const s1 = normalize(str1)
  const s2 = normalize(str2)

  if (s1 === s2) return 1
  if (s1.includes(s2) || s2.includes(s1)) return 0.8

  const words1 = s1.split(' ')
  const words2 = s2.split(' ')

  for (const w1 of words1) {
    for (const w2 of words2) {
      if (w1 === w2 || w1.includes(w2) || w2.includes(w1)) {
        if (w1.length > 3 || w2.length > 3) return 0.7
      }
    }
  }

  if (s1.length > 2 && s2.length > 2) {
    let differences = 0
    const minLen = Math.min(s1.length, s2.length)
    for (let i = 0; i < minLen; i++) {
      if (s1[i] !== s2[i]) differences++
    }
    differences += Math.abs(s1.length - s2.length)
    if (differences <= 2) return 0.6
  }

  return 0
}

const isExactMatch = computed(() => {
  if (!searchQuery.value.trim()) return false
  const query = normalize(searchQuery.value)
  return formations.some((f) => normalize(f.title) === query)
})

const filteredFormations = computed(() => {
  if (!searchQuery.value.trim()) return formations

  const query = normalize(searchQuery.value)

  return formations.filter((formation) => {
    const title = normalize(formation.title)
    const category = normalize(formation.category)
    const skills = formation.skills.some((skill) => normalize(skill).includes(query))

    return title.includes(query) || category.includes(query) || skills
  })
})

const suggestion = computed(() => {
  if (!searchQuery.value.trim()) return null
  if (filteredFormations.value.length > 0) return null

  const query = normalize(searchQuery.value)
  let bestMatch = null
  let bestScore = 0

  for (const formation of formations) {
    const score = getSimilarity(query, normalize(formation.title))
    if (score > bestScore && score > 0.35) {
      bestScore = score
      bestMatch = formation.title
    }
  }

  return bestMatch
})

const applySuggestion = () => {
  if (suggestion.value) {
    searchQuery.value = suggestion.value
  }
}

const clearSearch = () => {
  searchQuery.value = ''
}

onMounted(() => {
  setTimeout(() => {
    isPageLoading.value = false
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

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-down-enter-active {
  transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.slide-down-leave-active {
  transition: all 0.2s ease;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.card-enter-active {
  transition: all 0.3s cubic-bezier(0.34, 1.2, 0.64, 1);
}
.card-leave-active {
  transition: all 0.2s ease;
}
.card-enter-from {
  opacity: 0;
  transform: translateY(30px) scale(0.95);
}
.card-leave-to {
  opacity: 0;
  transform: translateY(-20px) scale(0.95);
}
.card-move {
  transition: transform 0.3s ease;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
.animate-spin {
  animation: spin 0.6s linear infinite;
}

@media (max-width: 768px) {
  .hero-grid {
    background-size: 40px 40px;
  }
}
</style>
