<template>
  <div class="accueil">
    <!-- SECTION 1 — SLIDER DES LOGICIELS (visible dès l'ouverture) -->
    <section class="pt-14 pb-0 bg-white">

      <!-- Badge centré au-dessus du slider -->
      <!-- <div class="text-center py-16">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-green-500/30 bg-green-500/10 text-green-600 text-xs font-semibold tracking-widest uppercase">
          <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
          Éditeur de logiciels — Côte d'Ivoire
        </div>
      </div> -->

      <div class="w-full px-0">
        <!-- Slider pleine largeur -->
        <div class="relative w-full">
          <!-- Conteneur des slides -->
          <div class="relative w-full h-65 sm:h-95 md:h-120 lg:h-140 overflow-hidden">
            <div
              v-for="(slide, index) in softwareSlides"
              :key="slide.id"
              class="absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out"
              :class="currentSlide === index ? 'opacity-100 z-10' : 'opacity-0 z-0'"
            >
              <router-link :to="slide.route" class="block w-full h-full cursor-pointer">
                <img
                  :src="slide.image"
                  :alt="slide.name"
                  class="w-full h-full object-cover object-center"
                />
              </router-link>
            </div>
          </div>

          <!-- Dots de navigation -->
          <div class="flex justify-center gap-3 mt-6">
            <button
              v-for="(slide, index) in softwareSlides"
              :key="`dot-${index}`"
              @click="goToSlide(index)"
              class="transition-all duration-300 rounded-full"
              :class="
                currentSlide === index
                  ? 'w-10 h-2 bg-green-600'
                  : 'w-2 h-2 bg-slate-300 hover:bg-slate-400'
              "
              :aria-label="`Slide ${index + 1} — ${slide.name}`"
            ></button>
          </div>

          <!-- Bouton précédent -->
          <button
            @click.stop="prevSlide"
            class="absolute top-1/2 -translate-y-1/2 left-6 z-30 w-12 h-12 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-slate-700 hover:text-green-600 transition-all duration-200 cursor-pointer backdrop-blur-sm"
            aria-label="Slide précédente"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
              />
            </svg>
          </button>

          <!-- Bouton suivant -->
          <button
            @click.stop="nextSlide"
            class="absolute top-1/2 -translate-y-1/2 right-6 z-30 w-12 h-12 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-slate-700 hover:text-green-600 transition-all duration-200 cursor-pointer backdrop-blur-sm"
            aria-label="Slide suivante"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5l7 7-7 7"
              />
            </svg>
          </button>
        </div>
      </div>
    </section>

    <!-- SECTION 3 — BOUTONS CTA  -->
    <section class="py-16 bg-white">
      <div class="max-w-6xl mx-auto px-6 text-center">
        <div class="hero-ctas flex flex-col sm:flex-row gap-4 justify-center items-center">
          <router-link
            to="/solutions"
            class="group inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-green-600 hover:bg-green-500 text-white font-semibold text-base transition-all duration-200 shadow-lg shadow-green-600/25 hover:shadow-green-500/40 hover:-translate-y-0.5"
          >
            Découvrir nos solutions
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
            class="inline-flex items-center gap-2 px-8 py-4 rounded-xl border border-slate-300 hover:border-green-500 text-slate-700 hover:text-green-600 font-semibold text-base transition-all duration-200 hover:-translate-y-0.5"
          >
            Nous contacter
          </router-link>
        </div>
      </div>
    </section>

    <!-- SECTION 3B — TITRE PRINCIPAL -->
    <section class="py-16 bg-white overflow-hidden">
      <div class="relative max-w-6xl mx-auto px-6 text-center">
        <div class="hero-grid absolute inset-0 pointer-events-none" aria-hidden="true"></div>
        <div class="relative z-10">
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-green-500/30 bg-green-500/10 text-green-600 text-xs font-semibold tracking-widest uppercase mb-10">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
            Éditeur de logiciels — Côte d'Ivoire
          </div>
          <h1 class="hero-title text-3xl sm:text-5xl md:text-7xl lg:text-8xl font-black text-slate-900 leading-[0.95] tracking-tight">
            Des logiciels<br />
            <span class="hero-gradient-green">taillés pour vous.</span>
          </h1>
        </div>
      </div>
    </section>

    <!-- SECTION 4 — TEXTE DE PRÉSENTATION -->
    <section class="py-16 bg-slate-50">
      <div class="max-w-4xl mx-auto px-6 text-center">
        <p class="text-lg md:text-xl text-slate-600 leading-relaxed">
          Access Informatique conçoit des solutions de gestion sur mesure pour les
          <strong class="text-slate-800 font-semibold">particuliers</strong> et les
          <strong class="text-slate-800 font-semibold">professionnels</strong>
          qui veulent aller plus loin.
        </p>

        <!-- Indicateurs de confiance -->
        <div
          class="hero-stats grid grid-cols-1 sm:grid-cols-3 gap-px bg-green-100 rounded-2xl overflow-hidden border border-green-200 max-w-2xl mx-auto mt-4"
        >
          <div class="bg-white px-8 py-5 text-center">
            <div class="text-3xl font-black text-green-600">+20</div>
            <div class="text-xs text-slate-500 mt-1 uppercase tracking-wider">Ans d'expérience</div>
          </div>
          <div class="bg-white px-8 py-5 text-center border-l border-r border-green-100">
            <div class="text-3xl font-black text-green-600">6</div>
            <div class="text-xs text-slate-500 mt-1 uppercase tracking-wider">
              Solutions actives
            </div>
          </div>
          <div class="bg-white px-8 py-5 text-center">
            <div class="text-3xl font-black text-green-600">100%</div>
            <div class="text-xs text-slate-500 mt-1 uppercase tracking-wider">Sur mesure</div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 5 — CE QU'ON FAIT -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-10">
          <p class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase mb-3">
            Notre expertise
          </p>
          <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
            Pourquoi Access Informatique ?
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            v-for="pillar in pillars"
            :key="pillar.title"
            class="pillar-card group p-8 rounded-2xl bg-white border border-slate-100 hover:border-green-100 hover:shadow-xl hover:shadow-green-50 transition-all duration-300 hover:-translate-y-1"
          >
            <div
              class="w-12 h-12 rounded-xl bg-green-50 group-hover:bg-green-100 flex items-center justify-center mb-10 transition-colors duration-300"
            >
              <component :is="pillar.icon" class="w-6 h-6 text-green-600" />
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-3">{{ pillar.title }}</h3>
            <p class="text-slate-500 text-sm leading-relaxed">{{ pillar.text }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 6 — PARTENAIRES -->
    <section class="py-16 bg-slate-50 overflow-hidden">
      <div class="max-w-7xl mx-auto px-6 mb-8 text-center">
        <p class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase mb-3">
          Ils nous font confiance
        </p>
        <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
          Nos partenaires
        </h2>
        <p class="text-slate-500 mt-4 max-w-xl mx-auto">
          Écoles, cliniques, entreprises et institutions qui nous font confiance au quotidien.
        </p>
      </div>

      <div class="partners-track-wrapper relative">
        <div
          class="partners-fade-left absolute inset-y-0 left-0 w-24 z-10 pointer-events-none"
          style="background: linear-gradient(to right, #f8fafc, transparent)"
        ></div>
        <div
          class="partners-fade-right absolute inset-y-0 right-0 w-24 z-10 pointer-events-none"
          style="background: linear-gradient(to left, #f8fafc, transparent)"
        ></div>

        <div class="partners-track flex gap-6 mb-10">
          <div class="partners-list flex gap-6 animate-scroll-left">
            <a
              v-for="partner in [...partners, ...partners]"
              :key="`p1-${partner.id}`"
              :href="partner.url"
              class="partner-logo flex-shrink-0 flex items-center justify-center w-36 h-20 sm:w-52 sm:h-28 md:w-64 md:h-32 rounded-2xl border border-slate-100 bg-white hover:border-green-200 hover:bg-green-50 hover:shadow-md transition-all duration-200 px-3 md:px-6 group"
            >
              <img
                :src="partner.logo"
                :alt="partner.name"
                class="max-h-20 max-w-full object-contain transition-all duration-300"
              />
            </a>
          </div>
        </div>

        <div class="partners-track flex gap-6">
          <div class="partners-list flex gap-6 animate-scroll-right">
            <a
              v-for="partner in [...partners, ...partners].reverse()"
              :key="`p2-${partner.id}`"
              :href="partner.url"
              class="partner-logo flex-shrink-0 flex items-center justify-center w-32 h-18 sm:w-44 sm:h-24 md:w-56 md:h-28 rounded-2xl border border-slate-100 bg-white hover:border-green-200 hover:bg-green-50 hover:shadow-md transition-all duration-200 px-3 md:px-6 group"
            >
              <img
                :src="partner.logo"
                :alt="partner.name"
                class="max-h-18 max-w-full object-contain transition-all duration-300"
              />
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 7 — BANDE CTA FINALE -->
    <section class="py-16 bg-green-600 relative overflow-hidden">
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

      <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-2xl sm:text-4xl md:text-6xl font-black text-white tracking-tight leading-tight mb-10">
          Prêt à transformer<br />
          <span class="text-white/90"> votre gestion ? </span>
        </h2>
        <p class="text-green-100 text-lg md:text-xl max-w-xl mx-auto mb-10">
          Discutons de votre projet. Notre équipe vous répond en moins de 24h.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            to="/contact"
            class="group inline-flex items-center justify-center gap-2 px-10 py-4 rounded-xl bg-white hover:bg-slate-50 text-green-600 font-bold text-base transition-all duration-200 shadow-lg shadow-black/20 hover:shadow-black/30 hover:-translate-y-0.5"
          >
            Parler à un expert
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
            to="/solutions"
            class="inline-flex items-center justify-center px-10 py-4 rounded-xl border border-white/30 hover:border-white/50 text-white hover:text-white font-bold text-base transition-all duration-200 hover:-translate-y-0.5"
          >
            Explorer les solutions
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { pillars } from '@/data/homeData'
import api from '@/services/api'

// ── Slider : solutions chargées depuis l'API ──────────────────────────────────
// Quand l'admin change l'image d'une solution, le slider se met à jour.
const softwareSlides = ref([])

// ── Partenaires chargés depuis l'API ─────────────────────────────────────────
const partners = ref([])

onMounted(async () => {
  // Charger les solutions pour le slider
  try {
    const { data } = await api.get('/solutions')
    softwareSlides.value = data.map((s) => ({
      id:    s.id,
      name:  s.name,
      image: s.hero_image,
      route: '/solutions/' + s.slug,
    }))
  } catch {
    // Fallback sur les slides statiques si l'API est indisponible
    const { softwareSlides: staticSlides } = await import('@/data/homeData')
    softwareSlides.value = staticSlides
  }

  // Charger les partenaires
  try {
    const { data } = await api.get('/partners')
    partners.value = data.map((p) => ({
      id:   p.id,
      name: p.name,
      logo: p.logo_url,
      url:  '#',
    }))
  } catch {
    const { listeReferences } = await import('@/data/aboutData')
    partners.value = listeReferences.map((r, i) => ({
      id:   i + 1,
      name: r.nom,
      logo: r.logo,
      url:  '#',
    }))
  }

  startSlider()
})

// ── Contrôles du slider ───────────────────────────────────────────────────────
const currentSlide = ref(0)
let sliderInterval = null

function goToSlide(index) {
  currentSlide.value = index
  clearInterval(sliderInterval)
  startSlider()
}

function nextSlide() {
  const len = softwareSlides.value.length || 1
  currentSlide.value = (currentSlide.value + 1) % len
  clearInterval(sliderInterval)
  startSlider()
}

function prevSlide() {
  const len = softwareSlides.value.length || 1
  currentSlide.value = (currentSlide.value - 1 + len) % len
  clearInterval(sliderInterval)
  startSlider()
}

function startSlider() {
  clearInterval(sliderInterval)
  sliderInterval = setInterval(() => {
    const len = softwareSlides.value.length || 1
    currentSlide.value = (currentSlide.value + 1) % len
  }, 4500)
}

onUnmounted(() => clearInterval(sliderInterval))
</script>

<style scoped>
/* HERO : Grille décorative */
.hero-grid {
  background-image:
    linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
  background-size: 80px 80px;
}

/* HERO : Titre vert (couleur bouton) */
.hero-gradient-green {
  color: #166030;
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
.hero-ctas {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.35s;
}
.hero-stats {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.45s;
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

/* Animation des stats */
.hero-stats {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.6s;
}

/* Partenaires */
.partners-track {
  overflow: hidden;
  width: 100%;
}

.partners-list {
  width: max-content;
  will-change: transform;
}

@keyframes scrollLeft {
  from {
    transform: translateX(0);
  }
  to {
    transform: translateX(-50%);
  }
}

@keyframes scrollRight {
  from {
    transform: translateX(-50%);
  }
  to {
    transform: translateX(0);
  }
}

.animate-scroll-left {
  animation: scrollLeft 85s linear infinite;
}
.animate-scroll-right {
  animation: scrollRight 85s linear infinite;
}

.partners-track-wrapper:hover .animate-scroll-left,
.partners-track-wrapper:hover .animate-scroll-right {
  animation-play-state: paused;
}

@media (max-width: 640px) {
  .animate-scroll-left {
    animation-duration: 30s;
  }
  .animate-scroll-right {
    animation-duration: 28s;
  }
  .hero-grid {
    background-size: 40px 40px;
  }
}
</style>
