<template>
  <div class="solutions-page">
    <!-- HERO SECTION -->
    <section class="solutions-hero relative pt-20 pb-16 overflow-hidden bg-white">
      <div class="hero-grid absolute inset-0 pointer-events-none" aria-hidden="true"></div>
      <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full pointer-events-none"
        style="background: radial-gradient(circle, rgba(34, 197, 94, 0.07) 0%, transparent 70%)"
        aria-hidden="true"
      ></div>
      <div class="relative z-10 max-w-6xl mx-auto px-6 text-center">
        <div class="mb-8 text-sm text-slate-500">
          <router-link to="/" class="hover:text-green-600 transition-colors">Accueil</router-link>
          <span class="mx-2">/</span>
          <span class="font-semibold text-slate-900">Solutions</span>
        </div>
        <div
          class="hero-badge inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-green-500/30 bg-green-500/10 text-green-600 text-xs font-semibold tracking-widest uppercase mb-8"
        >
          <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
          Nos solutions logicielles
        </div>
        <h1
          class="hero-title text-5xl md:text-7xl font-black text-slate-900 leading-[0.95] tracking-tight mb-6"
        >
          Des outils conçus<br />
          <span class="hero-gradient-green">pour vos métiers.</span>
        </h1>
        <p
          class="hero-subtitle text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed"
        >
          Depuis plus de 20 ans, Access Informatique développe des logiciels de gestion sur mesure
          pour les secteurs de la santé, l'éducation, la comptabilité, l'immobilier et bien
          d'autres.
        </p>
      </div>
    </section>

    <!-- FILTRES PAR CATÉGORIE -->
    <section class="py-8 bg-white top-[64px] z-20 border-b border-slate-100 shadow-sm">
      <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-wrap gap-2 justify-center">
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="activeCategory = cat.id"
            class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200"
            :class="
              activeCategory === cat.id
                ? 'bg-green-600 text-white shadow-md shadow-green-600/25'
                : 'bg-slate-100 text-slate-600 hover:bg-green-50 hover:text-green-700'
            "
          >
            {{ cat.label }}
          </button>
        </div>
      </div>
    </section>

    <!-- GRILLE DES SOLUTIONS -->
    <section class="py-20 bg-slate-50">
      <div class="max-w-7xl mx-auto px-6">
        <TransitionGroup
          name="cards"
          tag="div"
          class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8"
        >
          <article
            v-for="solution in filteredSolutions"
            :key="solution.id"
            class="solution-card group relative bg-white rounded-3xl overflow-hidden border border-slate-100 hover:border-green-200 shadow-sm hover:shadow-2xl hover:shadow-green-900/8 transition-all duration-400 hover:-translate-y-2 flex flex-col"
          >
            <!-- Image du logiciel -->
            <div class="relative overflow-hidden h-56 bg-slate-100 flex-shrink-0">
              <img
                :src="solution.image"
                :alt="`Aperçu de ${solution.name}`"
                class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"
              ></div>

              <span
                class="absolute top-4 left-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide backdrop-blur-sm border"
                :style="solution.categoryStyle"
              >
                <component :is="solution.categoryIcon" class="w-3 h-3" />
                {{ solution.category }}
              </span>

              <div class="absolute bottom-4 left-4 right-4">
                <h2 class="text-2xl font-black text-white tracking-tight drop-shadow-lg">
                  {{ solution.name }}
                </h2>
              </div>
            </div>

            <!-- Contenu -->
            <div class="p-7 flex flex-col flex-grow">
              <p class="text-xs font-bold tracking-[0.15em] text-green-600 uppercase mb-3">
                {{ solution.tagline }}
              </p>

              <!-- Description uniforme avec shortDescription -->
              <div class="h-14 overflow-hidden">
                <p class="text-slate-600 text-sm leading-relaxed line-clamp-2">
                  {{ solution.shortDescription }}
                </p>
              </div>

              <!-- Tags -->
              <div class="flex flex-wrap gap-2 mt-5 min-h-[60px]">
                <span
                  v-for="tag in solution.tags.slice(0, 3)"
                  :key="tag"
                  class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-50 text-slate-500 border border-slate-100"
                >
                  {{ tag }}
                </span>
              </div>

              <!-- Bouton -->
              <div class="border-t border-slate-100 mt-6 pt-6">
                <router-link
                  :to="solution.route"
                  class="group/btn inline-flex items-center gap-2 w-full justify-center px-6 py-3.5 rounded-xl font-semibold text-sm bg-green-600 hover:bg-green-500 text-white shadow-md shadow-green-600/20 hover:shadow-green-500/40 transition-all duration-200 hover:-translate-y-0.5"
                >
                  En savoir plus
                  <svg
                    class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform duration-200"
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
          </article>
        </TransitionGroup>

        <!-- État vide -->
        <div v-if="filteredSolutions.length === 0" class="text-center py-24">
          <div class="text-5xl mb-4">🔍</div>
          <p class="text-slate-500 text-lg">Aucune solution dans cette catégorie pour le moment.</p>
        </div>
      </div>
    </section>

    <!-- SECTION ACCOMPAGNEMENT -->
    <section class="py-24 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
          <p class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase mb-3">
            Pourquoi nous choisir
          </p>
          <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
            Au-delà du logiciel
          </h2>
          <p class="text-slate-500 mt-4 max-w-xl mx-auto">
            Chaque solution est accompagnée d'un service humain, d'une formation et d'un support
            continu.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="feature in whyUs"
            :key="feature.title"
            class="flex flex-col p-8 rounded-2xl bg-white border border-slate-100 hover:border-green-100 hover:shadow-lg hover:shadow-green-50 transition-all duration-300 hover:-translate-y-1"
          >
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center mb-6">
              <component :is="feature.icon" class="w-6 h-6 text-green-600" />
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-3">{{ feature.title }}</h3>
            <p class="text-slate-500 text-sm leading-relaxed">{{ feature.text }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA BANDE FINALE -->
    <section class="py-24 bg-green-600 relative overflow-hidden">
      <div
        class="absolute inset-0 pointer-events-none"
        style="
          background: radial-gradient(
            ellipse at 60% 50%,
            rgba(255, 255, 255, 0.12) 0%,
            transparent 60%
          );
        "
        aria-hidden="true"
      ></div>
      <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight leading-tight mb-6">
          Une question sur<br />
          <span class="text-white/85">l'une de nos solutions ?</span>
        </h2>
        <p class="text-green-100 text-lg max-w-xl mx-auto mb-10">
          Notre équipe est disponible pour vous présenter les logiciels, organiser une démo ou vous
          proposer un devis personnalisé.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            to="/contact"
            class="group inline-flex items-center justify-center gap-2 px-10 py-4 rounded-xl bg-white hover:bg-slate-50 text-green-600 font-bold text-base transition-all duration-200 shadow-lg shadow-black/20 hover:-translate-y-0.5"
          >
            Demander une démo
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
                d="M17 8l4 4m0 0l-4 4m4-4H3"
              />
            </svg>
          </router-link>
          <router-link
            to="/contact"
            class="inline-flex items-center justify-center px-10 py-4 rounded-xl border border-white/30 hover:border-white/50 text-white font-bold text-base transition-all duration-200 hover:-translate-y-0.5"
          >
            Nous contacter
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { categories, solutions, whyUs } from '@/data/solutionsData'

const activeCategory = ref('all')

// Filtrage des solutions en fonction de la catégorie active
const filteredSolutions = computed(() => {
  if (activeCategory.value === 'all') return solutions
  return solutions.filter((s) => s.categoryId === activeCategory.value)
})
</script>

<style scoped>
/* HERO */
.hero-grid {
  background-image:
    linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
  background-size: 80px 80px;
}

.hero-gradient-green {
  color: #16a34a;
  font-weight: 700;
}

.hero-badge {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.1s;
}
.hero-title {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.2s;
}
.hero-subtitle {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.35s;
}

@keyframes fadeSlideUp {
  from {
    opacity: 0;
    transform: translateY(24px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Cards transition group */
.cards-enter-active,
.cards-leave-active {
  transition: all 0.35s ease;
}
.cards-enter-from,
.cards-leave-to {
  opacity: 0;
  transform: translateY(16px) scale(0.97);
}

/* Card hover glow */
.solution-card:hover {
  box-shadow:
    0 20px 60px rgba(34, 197, 94, 0.08),
    0 8px 24px rgba(0, 0, 0, 0.06);
}

/* Limite le texte à 2 lignes */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

@media (max-width: 640px) {
  .hero-grid {
    background-size: 40px 40px;
  }
}
</style>
