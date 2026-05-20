<template>
  <div class="accueil">

    <!-- SECTION 1 — HERO -->
    <section class="hero-section relative min-h-screen flex flex-col justify-center overflow-hidden bg-slate-950">

      <!-- Grille décorative en fond -->
      <div class="hero-grid absolute inset-0 pointer-events-none" aria-hidden="true"></div>

      <!-- Halo lumineux central -->
      <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
               w-[600px] h-[600px] rounded-full pointer-events-none"
        style="background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);"
        aria-hidden="true"
      ></div>

      <!-- Contenu hero -->
      <div class="relative z-10 max-w-6xl mx-auto px-6 py-32 text-center">

        <!-- Badge -->
        <div class="hero-badge inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-blue-500/30 bg-blue-500/10 text-blue-400 text-xs font-semibold tracking-widest uppercase mb-8">
          <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
          Éditeur de logiciels — Côte d'Ivoire
        </div>

        <!-- Titre principal -->
        <h1 class="hero-title text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[0.95] tracking-tight mb-8">
          Des logiciels<br>
          <span class="hero-gradient">taillés pour vous.</span>
        </h1>

        <!-- Sous-titre -->
        <p class="hero-subtitle text-lg md:text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed mb-12">
          Access Informatique conçoit des solutions de gestion sur mesure pour les
          <strong class="text-slate-200 font-medium">particuliers</strong> et les
          <strong class="text-slate-200 font-medium">professionnels</strong>
          qui veulent aller plus loin.
        </p>

        <!-- CTAs -->
        <div class="hero-ctas flex flex-col sm:flex-row gap-4 justify-center items-center mb-20">
          <router-link
            to="/solutions"
            class="group inline-flex items-center gap-2 px-8 py-4 rounded-xl
                   bg-blue-600 hover:bg-blue-500 text-white font-semibold text-base
                   transition-all duration-200 shadow-lg shadow-blue-600/25 hover:shadow-blue-500/40
                   hover:-translate-y-0.5"
          >
            Découvrir nos solutions
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </router-link>
          <router-link
            to="/contact"
            class="inline-flex items-center gap-2 px-8 py-4 rounded-xl
                   border border-slate-700 hover:border-slate-500
                   text-slate-300 hover:text-white font-semibold text-base
                   transition-all duration-200 hover:-translate-y-0.5"
          >
            Nous contacter
          </router-link>
        </div>

        <!-- Indicateurs de confiance -->
        <div class="hero-stats grid grid-cols-1 sm:grid-cols-3 gap-px bg-slate-800/50 rounded-2xl overflow-hidden border border-slate-800 max-w-2xl mx-auto">
          <div class="bg-slate-900/80 px-8 py-5 text-center">
            <div class="text-3xl font-black text-white">+20</div>
            <div class="text-xs text-slate-500 mt-1 uppercase tracking-wider">Ans d'expérience</div>
          </div>
          <div class="bg-slate-900/80 px-8 py-5 text-center border-l border-r border-slate-800">
            <div class="text-3xl font-black text-white">6</div>
            <div class="text-xs text-slate-500 mt-1 uppercase tracking-wider">Solutions actives</div>
          </div>
          <div class="bg-slate-900/80 px-8 py-5 text-center">
            <div class="text-3xl font-black text-white">100%</div>
            <div class="text-xs text-slate-500 mt-1 uppercase tracking-wider">Sur mesure</div>
          </div>
        </div>

      </div>

      <!-- Flèche scroll vers le bas -->
      <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </div>
    </section>


    <!-- SECTION 2 — SLIDER DES LOGICIELS -->
    <section class="py-24 bg-white">
      <div class="max-w-7xl mx-auto px-6">

        <!-- En-tête section -->
        <div class="text-center mb-16">
          <p class="text-xs font-bold tracking-[0.2em] text-blue-600 uppercase mb-3">Nos réalisations</p>
          <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
            Des logiciels qui<br class="hidden md:block"> font la différence
          </h2>
          <p class="text-slate-500 mt-4 max-w-xl mx-auto text-lg">
            De la gestion scolaire à la comptabilité, chaque solution est pensée pour votre métier.
          </p>
        </div>

        <!-- Carrousel -->
        <div class="relative">
          <!-- Slides -->
          <div class="slider-container relative h-[420px] md:h-[560px] rounded-3xl overflow-hidden shadow-2xl shadow-slate-200">

            <!-- Chaque slide est positionnée en absolute pour permettre le crossfade. Seule la slide active a une opacité de 100% et un z-index supérieur. -->
            <div
              v-for="(slide, index) in softwareSlides"
              :key="slide.id"
              class="slide absolute inset-0 transition-opacity duration-1000 ease-in-out"
              :class="currentSlide === index ? 'opacity-100 z-10' : 'opacity-0 z-0'"
            >
              <!-- Image cliquable -->
              <router-link :to="slide.route" class="block w-full h-full">
                <img
                  :src="slide.image"
                  :alt="slide.name"
                  class="w-full h-full object-cover"
                />
                <!-- Overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
              </router-link>

              <!-- Info overlay bas -->
              <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12 z-20 pointer-events-none">
                <div class="flex items-end justify-between">
                  <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-blue-600/80 text-white text-xs font-semibold mb-3 backdrop-blur-sm">
                      {{ slide.category }}
                    </span>
                    <h3 class="text-2xl md:text-4xl font-black text-white mb-1">{{ slide.name }}</h3>
                    <p class="text-slate-300 text-sm md:text-base">{{ slide.description }}</p>
                  </div>
                  <!-- Indicateur numéro -->
                   <!-- Affiché uniquement sur écrans moyens et plus pour éviter de surcharger visuellement les petits écrans. -->
                  <div class="hidden md:block text-right">
                    <span class="text-5xl font-black text-white/20 leading-none">
                      {{ String(index + 1).padStart(2, '0') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Dots de navigation -->
          <div class="flex justify-center gap-2 mt-6">
            <button
              v-for="(slide, index) in softwareSlides"
              :key="`dot-${index}`"
              @click="goToSlide(index)"
              class="transition-all duration-300 rounded-full"
              :class="currentSlide === index
                ? 'w-8 h-2 bg-blue-600'
                : 'w-2 h-2 bg-slate-300 hover:bg-slate-400'"
              :aria-label="`Slide ${index + 1} — ${slide.name}`"
            ></button>
          </div>

         <!-- Bouton précédent -->
        <button
        @click.stop="prevSlide"
        class="absolute top-1/2 -translate-y-1/2 left-4 z-50
                w-12 h-12 rounded-full bg-black/40 backdrop-blur-md
                border border-white/20 flex items-center justify-center
                text-white  hover:scale-110
                transition-all duration-200 cursor-pointer"
        aria-label="Slide précédente"
        >
        <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        </button>

        <!-- Bouton suivant -->
        <button
        @click.stop="nextSlide"
        class="absolute top-1/2 -translate-y-1/2 right-4 z-50
                w-12 h-12 rounded-full bg-black/40 backdrop-blur-md
                border border-white/20 flex items-center justify-center
                text-white hover:scale-110
                transition-all duration-200 cursor-pointer"
        aria-label="Slide suivante"
        >
        <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        </button>
        </div>

        <!-- CTA sous le slider -->
        <div class="text-center mt-10">
          <router-link
            to="/solutions"
            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700
                   font-semibold text-sm transition-colors group"
          >
            Voir toutes nos solutions
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </router-link>
        </div>

      </div>
    </section>


    <!-- SECTION 3 — CE QU'ON FAIT -->
    <section class="py-24 bg-slate-50">
      <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-16">
          <p class="text-xs font-bold tracking-[0.2em] text-blue-600 uppercase mb-3">Notre expertise</p>
          <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
            Pourquoi Access Informatique ?
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            v-for="pillar in pillars"
            :key="pillar.title"
            class="pillar-card group p-8 rounded-2xl bg-white border border-slate-100
                   hover:border-blue-100 hover:shadow-xl hover:shadow-blue-50
                   transition-all duration-300 hover:-translate-y-1"
          >
            <!-- Icône -->
            <div class="w-12 h-12 rounded-xl bg-blue-50 group-hover:bg-blue-100
                        flex items-center justify-center mb-6 transition-colors duration-300">
              <component :is="pillar.icon" class="w-6 h-6 text-blue-600" />
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-3">{{ pillar.title }}</h3>
            <p class="text-slate-500 text-sm leading-relaxed">{{ pillar.text }}</p>
          </div>
        </div>

      </div>
    </section>


    <!-- SECTION 4 — PARTENAIRES / ILS NOUS FONT CONFIANCE -->

    <section class="py-24 bg-white overflow-hidden">
      <div class="max-w-7xl mx-auto px-6 mb-12 text-center">
        <p class="text-xs font-bold tracking-[0.2em] text-slate-400 uppercase mb-3">Ils nous font confiance</p>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
          Nos partenaires
        </h2>
        <p class="text-slate-500 mt-4 max-w-xl mx-auto">
          Écoles, cliniques, entreprises et institutions qui nous font confiance au quotidien.
        </p>
      </div>

      <!-- Infinite scroll band -->
      <!-- Masques de fondu gauche/droite pour effet propre -->
      <div class="partners-track-wrapper relative">
        <div class="partners-fade-left absolute inset-y-0 left-0 w-24 z-10 pointer-events-none"
             style="background: linear-gradient(to right, white, transparent);"></div>
        <div class="partners-fade-right absolute inset-y-0 right-0 w-24 z-10 pointer-events-none"
             style="background: linear-gradient(to left, white, transparent);"></div>

        <!-- Première rangée : défilement gauche -->
        <div class="partners-track flex gap-6 mb-6">
          <div class="partners-list flex gap-6 animate-scroll-left">
            <a
              v-for="partner in [...partners, ...partners]"
              :key="`p1-${partner.id}`"
              :href="partner.url"
              class="partner-logo flex-shrink-0 flex items-center justify-center
                     w-64 h-32 rounded-2xl border border-slate-100 
                     hover:border-blue-200 hover:bg-blue-50 hover:shadow-md
                     transition-all duration-200 px-6 group"
            >
              <img
                :src="partner.logo"
                :alt="partner.name"
                class="max-h-14 max-w-full object-contain 
                       group-hover:grayscale-0 transition-all duration-300 opacity-60
                       group-hover:opacity-100"
              />
            </a>
          </div>
        </div>

        <!-- Deuxième rangée : défilement droite  -->
        <div class="partners-track flex gap-6">
          <div class="partners-list flex gap-6 animate-scroll-right">
            <a
              v-for="partner in [...partners, ...partners].reverse()"
              :key="`p2-${partner.id}`"
              :href="partner.url"
              class="partner-logo flex-shrink-0 flex items-center justify-center
                     w-48 h-24 rounded-2xl border border-slate-100 bg-slate-50
                     hover:border-blue-200 hover:bg-blue-50 hover:shadow-md
                     transition-all duration-200 px-6 group"
            >
              <img
                :src="partner.logo"
                :alt="partner.name"
                class="max-h-14 max-w-full object-contain filter 
                       group-hover:grayscale-0 transition-all duration-300 opacity-60
                       group-hover:opacity-100"
              />
            </a>
          </div>
        </div>
      </div>
    </section>


    <!--  SECTION 5 — BANDE CTA FINALE avant le footer -->

    <section class="py-24 bg-slate-950 relative overflow-hidden">

      <!-- Fond décoratif -->
      <div
        class="absolute inset-0 pointer-events-none"
        style="background: radial-gradient(ellipse at 60% 50%, rgba(59,130,246,0.1) 0%, transparent 60%);"
        aria-hidden="true"
      ></div>

      <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-tight mb-6">
          Prêt à transformer<br>
          <span style="background: linear-gradient(135deg, #60a5fa, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            votre gestion ?
          </span>
        </h2>
        <p class="text-slate-400 text-lg md:text-xl max-w-xl mx-auto mb-10">
          Discutons de votre projet. Notre équipe vous répond en moins de 24h.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            to="/contact"
            class="group inline-flex items-center justify-center gap-2 px-10 py-4 rounded-xl
                   bg-blue-600 hover:bg-blue-500 text-white font-bold text-base
                   transition-all duration-200 shadow-lg shadow-blue-600/30
                   hover:shadow-blue-500/50 hover:-translate-y-0.5"
          >
            Parler à un expert
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </router-link>
          <router-link
            to="/solutions"
            class="inline-flex items-center justify-center px-10 py-4 rounded-xl
                   border border-slate-700 hover:border-slate-500
                   text-slate-300 hover:text-white font-bold text-base
                   transition-all duration-200 hover:-translate-y-0.5"
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
    route: '/solutions/solumed'
  },
  {
    id: 2,
    name: 'MySchool',
    category: 'Éducation',
    description: "Plateforme collaborative pour écoles, enseignants et parents d'élèves",
    image: '/src/assets/images/Logiciels/cvmyschool.png',
    route: '/solutions/myschool'
  },
  {
    id: 3,
    name: 'Matrix',
    category: 'Comptabilité',
    description: 'Traitement comptable conforme aux normes SYSCOHADA',
    image: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=1200&q=80',
    route: '/solutions/matrix'
  },
  {
    id: 4,
    name: 'SmartRHPaie',
    category: 'Ressources Humaines',
    description: 'Pilotez votre capital humain avec précision et conformité',
    image: '/src/assets/images/Logiciels/cvsmartrh.png',
    route: '/solutions/smartrhpaie'
  },
  {
    id: 5,
    name: 'Simba',
    category: 'Immobilier',
    description: 'Gestion de loyers, quittances et relances automatiques',
    image: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&q=80',
    route: '/solutions/simba'
  }
])

//  Données : Partenaires 
const partners = ref([
  { id: 1, name: 'La Cocinelle', logo: '/src/assets/images/References/Logefcoccinelle.jpg', url: '#' },
  { id: 2, name: 'Centre Médical Lumière', logo: '/src/assets/images/References/logrefcentremedicalelumiere.png', url: '#' },
  { id: 4, name: 'YNACASS.CI', logo: '/src/assets/images/References/logo_synacassci.png', url: '#' },
  { id: 5, name: 'FPPN', logo: '/src/assets/images/References/logrefFPPN.png', url: '#' },
  { id: 6, name: 'Ecole Supérieure Des Professions Immobilières', logo: '/src/assets/images/References/logrefepsi.png', url: '#' },
  { id: 7, name: 'Groupe Médicale Côte D\'ivoire Techno', logo: '/src/assets/images/References/logrefTechno.png', url: '#' },
  { id: 8, name: 'Clinique Médicale St. Trinité', logo: '/src/assets/images/References/logCliniqstTrinite.png', url: '#' },
  { id: 9, name: 'Institut Supérieur Blaise Pascal', logo: '/src/assets/images/References/LOGO2.jpg', url: '#' },
  { id: 10, name: 'FIV FATIMA', logo: '/src/assets/images/References/logrefCentremedicalFatima.png', url: '#' },
  { id: 11, name: 'CMAC', logo: '/src/assets/images/References/logrefCliniqMedicalAcasa.png', url: '#' },
  { id: 12, name: 'Clinique Médicale Les drés', logo: '/src/assets/images/References/logrefCliniqMedicalDre.png', url: '#' },
  { id: 13, name: 'Ecole Hoetière', logo: '/src/assets/images/References/logrefehbbassam.png', url: '#' },
  { id: 14, name: 'G S Lavoisier', logo: '/src/assets/images/References/logrefGSLAVOLIERE.jpg', url: '#' },
  {id: 15, name: 'Groupe Médical La Renaissance', logo: '/src/assets/images/References/logrefHACI.png', url: '#' },
//   {id: 16, name: 'FHACI', logo: '/src/assets/images/References/logrefRenaissance.png', url: '#' },
  {id: 17, name: 'IRMA', logo: '/src/assets/images/References/logrefirmabassam.jpeg', url: '#' },
  {id: 18, name: 'Collège MERAJEA', logo: '/src/assets/images/References/logrefmerajea.png', url: '#' },
  {id: 19, name: 'MUSACNRA', logo: '/src/assets/images/References/logrefMUSACNRA.png', url: '#' },
  {id: 20, name: 'Clinique Polyvalente Sainte Henriette', logo: '/src/assets/images/References/logrefpolycliqstHenriette.jpg', url: '#' },
  {id: 21, name: 'GM CITECHNO', logo: '/src/assets/images/References/logrefcliniqTechnos.jpeg', url: '#' },
  {id: 22, name: 'TANCHIVOIRE', logo: '/src/assets/images/References/logreftranchIvoire.png', url: '#' },
  {id: 23, name: 'UA', logo: '/src/assets/images/References/logrefuniversiteatlantiq.jfif', url: '#' },

])

// Données : Piliers expertise 
// Icônes SVG en composants render function.
// Utiliser des composants render function pour les icônes permet de les styliser facilement avec Tailwind (couleur, taille, hover) et de garantir une bonne qualité d'affichage sur tous les écrans sans dépendre d'images externes.
const IconCode = { render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', // Icône de code (chevrons) pour représenter le développement logiciel
    d: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4' }) // Chevrons pointant vers la droite et la gauche pour symboliser le code et le développement sur mesure
]) }

const IconShield = { render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2',
    d: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' })
]) }

const IconUsers = { render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2',
    d: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' })
]) }

const pillars = [
  {
    icon: IconCode,
    title: 'Développement sur mesure',
    text: "Chaque logiciel est conçu de zéro pour correspondre exactement à vos processus métier. Aucun compromis, aucune fonctionnalité inutile."
  },
  {
    icon: IconShield,
    title: 'Fiabilité & conformité',
    text: "Nos solutions sont conformes aux standards locaux (SYSCOHADA, réglementation ivoirienne) et sont utilisées en production depuis plus de 20 ans."
  },
  {
    icon: IconUsers,
    title: 'Accompagnement humain',
    text: "Assistance technique permanente, formations incluses, équipe disponible du lundi au samedi. Vous n'êtes jamais seuls."
  }
]

//  Logique : Slider automatique  
const currentSlide = ref(0)
let sliderInterval = null // Timer pour le changement automatique de slide

// Fonction pour aller à une slide spécifique lors du clic sur les dots
function goToSlide(index) {
  currentSlide.value = index
  // Réinitialise l'intervalle pour éviter un saut immédiat après clic manuel
  clearInterval(sliderInterval)
  startSlider()
}

// Fonction pour passer à la slide suivante
function nextSlide() {
  currentSlide.value = (currentSlide.value + 1) % softwareSlides.value.length // Passe à la slide suivante de manière cyclique
  clearInterval(sliderInterval) // Réinitialise l'intervalle pour éviter un saut immédiat après clic manuel
  startSlider()
}

// Fonction pour passer à la slide précédente
function prevSlide() {
  currentSlide.value =
    (currentSlide.value - 1 + softwareSlides.value.length) % softwareSlides.value.length
  clearInterval(sliderInterval)
  startSlider()
}

// fonction pour démarrer le slider automatique
function startSlider() {
  sliderInterval = setInterval(() => {
    currentSlide.value = (currentSlide.value + 1) % softwareSlides.value.length // Passe à la slide suivante de manière cyclique
  }, 4500) // Change de slide toutes les 4.5 secondes
}

onMounted(() => startSlider())
onUnmounted(() => clearInterval(sliderInterval))
</script>


<style scoped>
/*  HERO : Grille décorative avec Linear-style  */
.hero-grid {
  background-image:
    linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
  background-size: 80px 80px;
}

/* HERO : Titre dégradé */
.hero-gradient {
  background: linear-gradient(135deg, #60a5fa 0%, #818cf8 50%, #a78bfa 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/*  HERO : Animations d'entrée  */
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
.hero-ctas {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.45s;
}
.hero-stats {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.6s;
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

/*  SLIDER LOGICIELS  */
.slider-container {
  background: #0f172a; /* Fond pendant le chargement des images */
}

/*  PARTENAIRES : Infinite scroll  */
.partners-track {
  overflow: hidden;
  width: 100%;
}

.partners-list {
  /* Largeur infinie = la liste est dupliquée dans le template (v-for avec spread) */
  width: max-content;
  will-change: transform;
}

/* Défilement vers la gauche */
@keyframes scrollLeft {
  from { transform: translateX(0); }
  to   { transform: translateX(-50%); }
}

/* Défilement vers la droite */
@keyframes scrollRight {
  from { transform: translateX(-50%); }
  to   { transform: translateX(0); }
}

.animate-scroll-left {
  animation: scrollLeft 85s linear infinite;
}
.animate-scroll-right {
  animation: scrollRight 85s linear infinite; /* vitesse légèrement différente pour l'effet de profondeur */
}

/* Pause au survol de toute la bande */
.partners-track-wrapper:hover .animate-scroll-left,
.partners-track-wrapper:hover .animate-scroll-right {
  animation-play-state: paused;
}

/* CARDS PILIERS : hover effet discret  */
.pillar-card:hover .pillar-icon {
  transform: scale(1.05);
}

/*  Responsive : réduire vitesse sur mobile */
@media (max-width: 640px) {
  .animate-scroll-left  { animation-duration: 30s; }
  .animate-scroll-right { animation-duration: 28s; }

  .hero-grid {
    background-size: 40px 40px;
  }
}
</style>