<template>
  <div v-if="solution" class="solution-detail-page">
    <!-- HERO SECTION -->
    <section class="relative pt-20 pb-0 overflow-hidden bg-white">
      <div class="hero-grid absolute inset-0 pointer-events-none" aria-hidden="true"></div>
      <div
        class="absolute top-0 right-0 w-[700px] h-[700px] rounded-full pointer-events-none"
        style="background: radial-gradient(circle, #16603018 0%, transparent 70%)"
        aria-hidden="true"
      ></div>

      <div class="relative z-10 max-w-7xl mx-auto px-6">
        <!-- Fil d'ariane -->
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-10 animate-fade-up">
          <router-link to="/" class="hover:text-slate-600 transition-colors">Accueil</router-link>
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            />
          </svg>
          <router-link to="/solutions" class="hover:text-slate-600 transition-colors"
            >Solutions</router-link
          >
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            />
          </svg>
          <span class="font-semibold text-slate-700">{{ solution.name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center pb-16">
          <!-- Colonne gauche : texte + CTAs -->
          <div class="animate-fade-up">
            <!-- Badge catégorie -->
            <span
              class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-10 border border-green-500/30"
              style="background: #f0faf4; color: #166030"
            >
              <span class="w-1.5 h-1.5 rounded-full" style="background: #166030"></span>
              {{ solution.category }}
            </span>

            <!-- Nom -->
            <h1
              class="text-5xl md:text-6xl font-black text-slate-900 leading-tight tracking-tight mb-3"
            >
              {{ solution.name }}
            </h1>

            <!-- Tagline -->
            <p class="text-lg md:text-xl font-semibold mb-8" style="color: #166030">
              {{ solution.tagline }}
            </p>

            <!-- Description courte -->
            <p class="text-slate-500 text-base leading-relaxed mb-10">
              {{ solution.shortDescription }}
            </p>

            <!-- CTAs principaux -->
            <div class="flex flex-col sm:flex-row gap-4">
              <!-- Bouton Télécharger présentation -->
              <a
                :href="solution.brochureUrl"
                download
                class="group inline-flex items-center justify-center gap-3 px-7 py-4 rounded-xl font-bold text-sm text-white shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl"
                style="background: #166030; box-shadow: 0 8px 24px #16603040"
              >
                <svg
                  class="w-5 h-5 group-hover:-translate-y-0.5 transition-transform"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                  />
                </svg>
                Télécharger la présentation
              </a>

              <!-- Bouton Voir la démo -->
              <a
                :href="solution.demoUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="group inline-flex items-center justify-center gap-3 px-7 py-4 rounded-xl font-bold text-sm border-2 bg-white transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg"
                style="color: #166030; border-color: #166030"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                Voir la démo en ligne
              </a>
            </div>

            <!-- Tags -->
            <div class="flex flex-wrap gap-2 mt-8">
              <span
                v-for="tag in solution.tags"
                :key="tag"
                class="px-3 py-1 rounded-lg text-xs font-medium bg-slate-50 text-slate-500 border border-slate-100"
              >
                {{ tag }}
              </span>
            </div>
          </div>

          <!-- Colonne droite : image hero -->
          <div class="relative animate-fade-up-delay">
            <div
              class="relative rounded-2xl overflow-hidden shadow-2xl border border-slate-100 ring-1 ring-slate-900/5"
            >
              <img
                :src="solution.heroImage"
                :alt="`Aperçu de ${solution.name}`"
                class="w-full h-auto object-cover"
                loading="lazy"
              />
              <div
                class="absolute inset-0 opacity-10 pointer-events-none"
                style="background: linear-gradient(135deg, #166030 0%, transparent 60%)"
              ></div>
            </div>
            <div
              class="absolute -bottom-8 -right-8 w-40 h-40 rounded-full -z-10 opacity-15"
              style="background: #166030"
            ></div>
            <div
              class="absolute -top-4 -left-4 w-20 h-20 rounded-full -z-10 opacity-10"
              style="background: #166030"
            ></div>
          </div>
        </div>
      </div>
    </section>

    <!-- BARRE DE STATS -->
    <section class="py-16 bg-slate-50 border-y border-slate-100">
      <div class="max-w-5xl mx-auto px-6">
        <div class="grid grid-cols-3 gap-6 text-center divide-x divide-slate-200">
          <div v-for="stat in solution.stats" :key="stat.label" class="px-4">
            <p class="text-3xl md:text-4xl font-black" style="color: #166030">
              {{ stat.value }}
            </p>
            <p class="text-sm text-slate-500 mt-1 font-medium">{{ stat.label }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- DESCRIPTION + AVANTAGES -->
    <section class="py-14 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
          <div>
            <p class="text-xs font-bold tracking-[0.2em] uppercase mb-3" style="color: #166030">
              À propos
            </p>
            <h2
              class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight mb-10 leading-tight"
            >
              Pourquoi choisir<br />
              <span style="color: #166030">{{ solution.name }} ?</span>
            </h2>
            <p class="text-slate-600 leading-relaxed text-base mb-8">
              {{ solution.fullDescription }}
            </p>
            <router-link
              to="/contact"
              class="inline-flex items-center gap-2 font-semibold text-sm transition-all hover:gap-3"
              style="color: #166030"
            >
              Parler à un expert
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 8l4 4m0 0l-4 4m4-4H3"
                />
              </svg>
            </router-link>
          </div>

          <div class="rounded-2xl p-8 border" style="background: #f0faf4; border-color: #16603022">
            <h3 class="text-lg font-black text-slate-900 mb-10">✦ Les points forts</h3>
            <ul class="space-y-5">
              <li
                v-for="advantage in solution.advantages"
                :key="advantage"
                class="flex items-start gap-4"
              >
                <span
                  class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                  style="background: #166030"
                >
                  <svg
                    class="w-3.5 h-3.5 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="3"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </span>
                <span class="text-slate-700 text-sm leading-relaxed font-medium">{{
                  advantage
                }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- GRILLE DES MODULES -->
    <section class="py-14 bg-slate-50">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-8">
          <p class="text-xs font-bold tracking-[0.2em] uppercase mb-3" style="color: #166030">
            Fonctionnalités
          </p>
          <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
            Les modules de {{ solution.name }}
          </h2>
          <p class="text-slate-500 mt-4 max-w-lg mx-auto text-base">
            Une suite complète de fonctionnalités pensées pour votre secteur d'activité.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
          <div
            v-for="(module, index) in solution.modules"
            :key="module.title"
            class="module-card group bg-white rounded-2xl p-7 border border-slate-100 hover:border-transparent hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col"
          >
            <div
              class="w-11 h-11 rounded-xl flex items-center justify-center mb-8 text-sm font-black flex-shrink-0 transition-transform group-hover:scale-110"
              style="background: #f0faf4; color: #166030"
            >
              {{ String(index + 1).padStart(2, '0') }}
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-2">{{ module.title }}</h3>
            <p class="text-slate-500 text-sm leading-relaxed flex-grow">{{ module.description }}</p>
            <div
              class="mt-5 h-0.5 w-0 group-hover:w-full rounded-full transition-all duration-400"
              style="background: #166030"
            ></div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA BANDE FINALE -->
    <section class="py-14 relative overflow-hidden" style="background: #166030">
      <div
        class="absolute inset-0 pointer-events-none"
        style="
          background: radial-gradient(
            ellipse at 60% 50%,
            rgba(255, 255, 255, 0.15) 0%,
            transparent 60%
          );
        "
        aria-hidden="true"
      ></div>
      <div
        class="cta-grid absolute inset-0 pointer-events-none opacity-10"
        aria-hidden="true"
      ></div>

      <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight mb-10">
          Prêt à démarrer<br />
          <span class="text-white/80">avec {{ solution.name }} ?</span>
        </h2>
        <p class="text-white/75 text-lg max-w-xl mx-auto mb-10">
          Téléchargez notre documentation ou contactez notre équipe pour une démonstration
          personnalisée et gratuite.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a
            :href="solution.brochureUrl"
            download
            class="group inline-flex items-center justify-center gap-3 px-10 py-4 rounded-xl bg-white font-bold text-sm transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-black/20 hover:shadow-xl"
            style="color: #166030"
          >
            <svg
              class="w-5 h-5 group-hover:-translate-y-0.5 transition-transform"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
              />
            </svg>
            Télécharger la présentation
          </a>

          <a
            :href="solution.demoUrl"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center justify-center gap-3 px-10 py-4 rounded-xl border-2 border-white/40 hover:border-white/70 text-white font-bold text-sm transition-all duration-200 hover:-translate-y-0.5"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"
              />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            Voir la démo
          </a>

          <router-link
            to="/contact"
            class="inline-flex items-center justify-center px-10 py-4 rounded-xl border border-white/25 hover:border-white/50 text-white/80 hover:text-white font-bold text-sm transition-all duration-200 hover:-translate-y-0.5"
          >
            Nous contacter
          </router-link>
        </div>
      </div>
    </section>
  </div>

  <!-- Solution non trouvée -->
  <div
    v-else
    class="min-h-screen flex flex-col items-center justify-center text-center px-6 bg-white"
  >
    <div class="text-7xl mb-8">🔍</div>
    <h2 class="text-3xl font-black text-slate-900 mb-3">Solution introuvable</h2>
    <p class="text-slate-500 text-lg mb-10">Cette page n'existe pas ou a été déplacée.</p>
    <router-link
      to="/solutions"
      class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-green-600/25"
      style="background: #166030; color: white"
    >
      ← Retour aux solutions
    </router-link>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/services/api'

const route    = useRoute()
const solution = ref(null)
const isLoading = ref(true)
const error     = ref(null)

async function fetchSolution(slug) {
  isLoading.value = true
  error.value     = null
  try {
    const { data } = await api.get(`/solutions/${slug}`)
    // Transformer snake_case → camelCase pour garder la compatibilité du template
    solution.value = {
      ...data,
      heroImage:        data.hero_image,
      shortDescription: data.short_description,
      fullDescription:  data.full_description,
      brochureUrl:      data.brochure_url,
      demoUrl:          data.demo_url,
      accentColor:      data.accent_color,
      accentColorLight: data.accent_color_light,
    }
  } catch (e) {
    // Fallback sur les données locales si l'API est indisponible
    try {
      const { getSolutionBySlug } = await import('@/data/solutionsDetailData')
      solution.value = getSolutionBySlug(slug)
    } catch {
      error.value = 'Solution introuvable.'
      solution.value = null
    }
  } finally {
    isLoading.value = false
  }
}

onMounted(() => fetchSolution(route.params.slug))

// Recharge si le slug change (navigation entre solutions)
watch(() => route.params.slug, (newSlug) => { if (newSlug) fetchSolution(newSlug) })
</script>

<style scoped>
/* Grid déco hero */
.hero-grid {
  background-image:
    linear-gradient(rgba(0, 0, 0, 0.025) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.025) 1px, transparent 1px);
  background-size: 80px 80px;
}

/* Grid déco CTA */
.cta-grid {
  background-image:
    linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
  background-size: 60px 60px;
}

/* Hover glow sur les cards modules */
.module-card:hover {
  box-shadow:
    0 20px 50px rgba(0, 0, 0, 0.07),
    0 8px 20px rgba(0, 0, 0, 0.04);
}

/* Animations d'entrée */
.animate-fade-up {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.1s;
}
.animate-fade-up-delay {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.25s;
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

.transition-all {
  transition-property: all;
}
.duration-400 {
  transition-duration: 400ms;
}

@media (max-width: 768px) {
  .hero-grid {
    background-size: 40px 40px;
  }
}
</style>
