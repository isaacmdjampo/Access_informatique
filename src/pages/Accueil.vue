<template>
  <div class="accueil">
    <!-- SECTION 1 — HERO avec titre -->
    <section
      class="hero-section relative pt-32 pb-12 flex flex-col justify-center overflow-hidden bg-white"
    >
      <!-- Grille décorative en fond -->
      <div class="hero-grid absolute inset-0 pointer-events-none" aria-hidden="true"></div>

      <!-- Halo lumineux central -->
      <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full pointer-events-none"
        style="background: radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%)"
        aria-hidden="true"
      ></div>

      <!-- Contenu hero  -->
      <div class="relative z-10 max-w-6xl mx-auto px-6 text-center">
        <!-- Badge  -->
        <div
          class="hero-badge inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-green-500/30 bg-green-500/10 text-green-600 text-xs font-semibold tracking-widest uppercase mb-8"
        >
          <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
          Éditeur de logiciels — Côte d'Ivoire
        </div>

        <!-- Titre principal -->
        <h1
          class="hero-title text-5xl md:text-7xl lg:text-8xl font-black text-slate-900 leading-[0.95] tracking-tight"
        >
          Des logiciels<br />
          <span class="hero-gradient-green">taillés pour vous.</span>
        </h1>
      </div>
    </section>

    <!-- SECTION 2 — SLIDER DES LOGICIELS  -->
    <section class="py-8 bg-white">
      <div class="w-full px-0">
        <!-- Slider pleine largeur -->
        <div class="relative w-full">
          <!-- Conteneur des slides -->
          <div class="relative w-full h-[480px] md:h-[560px] overflow-hidden">
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
    <section class="py-12 bg-white">
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

    <!-- SECTION 4 — TEXTE DE PRÉSENTATION -->
    <section class="py-24 bg-slate-50">
      <div class="max-w-4xl mx-auto px-6 text-center">
        <p class="text-lg md:text-xl text-slate-600 leading-relaxed">
          Access Informatique conçoit des solutions de gestion sur mesure pour les
          <strong class="text-slate-800 font-semibold">particuliers</strong> et les
          <strong class="text-slate-800 font-semibold">professionnels</strong>
          qui veulent aller plus loin.
        </p>

        <!-- Indicateurs de confiance -->
        <div
          class="hero-stats grid grid-cols-1 sm:grid-cols-3 gap-px bg-green-100 rounded-2xl overflow-hidden border border-green-200 max-w-2xl mx-auto mt-12"
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
    <section class="py-24 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
          <p class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase mb-3">
            Notre expertise
          </p>
          <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
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
              class="w-12 h-12 rounded-xl bg-green-50 group-hover:bg-green-100 flex items-center justify-center mb-6 transition-colors duration-300"
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
    <section class="py-24 bg-slate-50 overflow-hidden">
      <div class="max-w-7xl mx-auto px-6 mb-12 text-center">
        <p class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase mb-3">
          Ils nous font confiance
        </p>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
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

        <div class="partners-track flex gap-6 mb-6">
          <div class="partners-list flex gap-6 animate-scroll-left">
            <a
              v-for="partner in [...partners, ...partners]"
              :key="`p1-${partner.id}`"
              :href="partner.url"
              class="partner-logo flex-shrink-0 flex items-center justify-center w-64 h-32 rounded-2xl border border-slate-100 bg-white hover:border-green-200 hover:bg-green-50 hover:shadow-md transition-all duration-200 px-6 group"
            >
              <img
                :src="partner.logo"
                :alt="partner.name"
                class="max-h-14 max-w-full object-contain group-hover:grayscale-0 transition-all duration-300 opacity-60 group-hover:opacity-100"
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
              class="partner-logo flex-shrink-0 flex items-center justify-center w-48 h-24 rounded-2xl border border-slate-100 bg-white hover:border-green-200 hover:bg-green-50 hover:shadow-md transition-all duration-200 px-6 group"
            >
              <img
                :src="partner.logo"
                :alt="partner.name"
                class="max-h-14 max-w-full object-contain filter group-hover:grayscale-0 transition-all duration-300 opacity-60 group-hover:opacity-100"
              />
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 7 — BANDE CTA FINALE -->
    <section class="py-24 bg-green-600 relative overflow-hidden">
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
        <h2 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-tight mb-6">
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
import { ref, onMounted, onUnmounted, h } from 'vue'

// Slider logiciels
const softwareSlides = ref([
  {
    id: 1,
    name: 'SoluMed',
    category: 'Santé',
    description: 'Gestion complète de cliniques, hôpitaux et centres de santé',
    image: '/src/assets/images/Logiciels/cvsolumed.png',
    route: '/solutions/solumed',
  },
  {
    id: 2,
    name: 'MySchool',
    category: 'Éducation',
    description: "Plateforme collaborative pour écoles, enseignants et parents d'élèves",
    image: '/src/assets/images/Logiciels/cvmyschool.png',
    route: '/solutions/myschool',
  },
  {
    id: 3,
    name: 'Matrix',
    category: 'Comptabilité',
    description: 'Traitement comptable conforme aux normes SYSCOHADA',
    image: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=1200&q=80',
    route: '/solutions/matrix',
  },
  {
    id: 4,
    name: 'SmartRHPaie',
    category: 'Ressources Humaines',
    description: 'Pilotez votre capital humain avec précision et conformité',
    image: '/src/assets/images/Logiciels/cvsmartrh.png',
    route: '/solutions/smartrhpaie',
  },
  {
    id: 5,
    name: 'Simba',
    category: 'Immobilier',
    description: 'Gestion de loyers, quittances et relances automatiques',
    image: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&q=80',
    route: '/solutions/simba',
  },
])

const partners = ref([
  {
    id: 1,
    name: 'La Cocinelle',
    logo: '/src/assets/images/References/Logefcoccinelle.jpg',
    url: '#',
  },
  {
    id: 2,
    name: 'Centre Médical Lumière',
    logo: '/src/assets/images/References/logrefcentremedicalelumiere.png',
    url: '#',
  },
  {
    id: 4,
    name: 'YNACASS.CI',
    logo: '/src/assets/images/References/logo_synacassci.png',
    url: '#',
  },
  { id: 5, name: 'FPPN', logo: '/src/assets/images/References/logrefFPPN.png', url: '#' },
  {
    id: 6,
    name: 'Ecole Supérieure Des Professions Immobilières',
    logo: '/src/assets/images/References/logrefepsi.png',
    url: '#',
  },
  {
    id: 7,
    name: "Groupe Médicale Côte D'ivoire Techno",
    logo: '/src/assets/images/References/logrefTechno.png',
    url: '#',
  },
  {
    id: 8,
    name: 'Clinique Médicale St. Trinité',
    logo: '/src/assets/images/References/logCliniqstTrinite.png',
    url: '#',
  },
  {
    id: 9,
    name: 'Institut Supérieur Blaise Pascal',
    logo: '/src/assets/images/References/LOGO2.jpg',
    url: '#',
  },
  {
    id: 10,
    name: 'FIV FATIMA',
    logo: '/src/assets/images/References/logrefCentremedicalFatima.png',
    url: '#',
  },
  {
    id: 11,
    name: 'CMAC',
    logo: '/src/assets/images/References/logrefCliniqMedicalAcasa.png',
    url: '#',
  },
  {
    id: 12,
    name: 'Clinique Médicale Les drés',
    logo: '/src/assets/images/References/logrefCliniqMedicalDre.png',
    url: '#',
  },
  {
    id: 13,
    name: 'Ecole Hoetière',
    logo: '/src/assets/images/References/logrefehbbassam.png',
    url: '#',
  },
  {
    id: 14,
    name: 'G S Lavoisier',
    logo: '/src/assets/images/References/logrefGSLAVOLIERE.jpg',
    url: '#',
  },
  {
    id: 15,
    name: 'Groupe Médical La Renaissance',
    logo: '/src/assets/images/References/logrefHACI.png',
    url: '#',
  },
  { id: 17, name: 'IRMA', logo: '/src/assets/images/References/logrefirmabassam.jpeg', url: '#' },
  {
    id: 18,
    name: 'Collège MERAJEA',
    logo: '/src/assets/images/References/logrefmerajea.png',
    url: '#',
  },
  { id: 19, name: 'MUSACNRA', logo: '/src/assets/images/References/logrefMUSACNRA.png', url: '#' },
  {
    id: 20,
    name: 'Clinique Polyvalente Sainte Henriette',
    logo: '/src/assets/images/References/logrefpolycliqstHenriette.jpg',
    url: '#',
  },
  {
    id: 21,
    name: 'GM CITECHNO',
    logo: '/src/assets/images/References/logrefcliniqTechnos.jpeg',
    url: '#',
  },
  {
    id: 22,
    name: 'TANCHIVOIRE',
    logo: '/src/assets/images/References/logreftranchIvoire.png',
    url: '#',
  },
  {
    id: 23,
    name: 'UA',
    logo: '/src/assets/images/References/logrefuniversiteatlantiq.jfif',
    url: '#',
  },
])

const IconCode = {
  render: () =>
    h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
      }),
    ]),
}

const IconShield = {
  render: () =>
    h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
      }),
    ]),
}

const IconUsers = {
  render: () =>
    h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
      }),
    ]),
}

const pillars = [
  {
    icon: IconCode,
    title: 'Développement sur mesure',
    text: 'Chaque logiciel est conçu de zéro pour correspondre exactement à vos processus métier. Aucun compromis, aucune fonctionnalité inutile.',
  },
  {
    icon: IconShield,
    title: 'Fiabilité & conformité',
    text: 'Nos solutions sont conformes aux standards locaux (SYSCOHADA, réglementation ivoirienne) et sont utilisées en production depuis plus de 20 ans.',
  },
  {
    icon: IconUsers,
    title: 'Accompagnement humain',
    text: "Assistance technique permanente, formations incluses, équipe disponible du lundi au samedi. Vous n'êtes jamais seuls.",
  },
]

const currentSlide = ref(0)
let sliderInterval = null

function goToSlide(index) {
  currentSlide.value = index
  clearInterval(sliderInterval)
  startSlider()
}

function nextSlide() {
  currentSlide.value = (currentSlide.value + 1) % softwareSlides.value.length
  clearInterval(sliderInterval)
  startSlider()
}

function prevSlide() {
  currentSlide.value =
    (currentSlide.value - 1 + softwareSlides.value.length) % softwareSlides.value.length
  clearInterval(sliderInterval)
  startSlider()
}

function startSlider() {
  sliderInterval = setInterval(() => {
    currentSlide.value = (currentSlide.value + 1) % softwareSlides.value.length
  }, 4500)
}

onMounted(() => startSlider())
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
