<template>
  <div class="min-h-screen flex flex-col bg-white">
    <!-- HEADER / NAVIGATION (masqué sur les routes /admin) -->
    <nav v-if="!isAdmin"
      class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-in-out"
      :class="
        scrolled
          ? 'bg-white/95 backdrop-blur-md shadow-xl shadow-slate-950/10 border-b border-slate-200'
          : 'bg-white/80 backdrop-blur-sm border-b border-slate-200/50'
      "
    >
      <!-- Barre verte au dessus du header -->
      <div class="px-6 py-2 bg-green-600 text-white w-full">
        <div
          class="max-w-7xl mx-auto flex flex-col gap-3 md:flex-row md:items-center md:justify-between text-sm"
        >
          <div class="flex flex-wrap items-center justify-center gap-2 text-center md:text-left">
            <span
              class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]"
            >
              <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
              <span class="hidden sm:inline">Bienvenue chez</span> Access Informatique
            </span>
            <span class="hidden md:inline text-white font-semibold">|</span>
            <a :href="`mailto:${email}`" class="hidden md:inline text-white hover:text-white/80 transition-colors duration-200">{{ email }}</a>
            <span class="hidden md:inline text-white font-semibold">|</span>
            <a :href="`tel:${phone1.replace(/\s|\(|\)/g, '')}`" class="text-white hover:text-white/80 transition-colors duration-200 text-xs">{{ phone1 }}</a>
            <span class="hidden sm:inline text-white font-semibold">|</span>
            <a :href="`tel:${phone2.replace(/\s|\(|\)/g, '')}`" class="hidden sm:inline text-white hover:text-white/80 transition-colors duration-200 text-xs">{{ phone2 }}</a>
          </div>
          <div
            class="flex flex-wrap items-center justify-center gap-3 text-xs font-semibold uppercase tracking-[0.18em]"
          >
            <a
              href="https://www.facebook.com/accessinformatique"
              target="_blank"
              rel="noreferrer"
              class="inline-flex items-center gap-2 text-white hover:text-white/80 transition-all duration-200"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path
                  d="M22 12.07C22 6.43 17.52 2 12 2S2 6.43 2 12.07c0 4.99 3.66 9.12 8.44 9.93v-7.04H7.9V12h2.54V9.8c0-2.5 1.49-3.88 3.78-3.88 1.1 0 2.24.2 2.24.2v2.47h-1.27c-1.25 0-1.64.78-1.64 1.58V12h2.8l-.45 2.96h-2.35v7.04C18.34 21.19 22 17.06 22 12.07z"
                />
              </svg>
              Facebook
            </a>
            <a
              href="https://wa.me/2250707261858"
              target="_blank"
              rel="noreferrer"
              class="inline-flex items-center gap-2 text-white hover:text-white/80 transition-all duration-200"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path
                  d="M20.52 3.48A11.8 11.8 0 0012.04 0C5.5 0 .2 5.3.2 11.84c0 2.08.54 4.1 1.56 5.88L0 24l6.46-1.7a11.8 11.8 0 005.58 1.42h.01c6.54 0 11.84-5.3 11.84-11.84 0-3.16-1.23-6.13-3.37-8.4zM12.05 21.7a9.8 9.8 0 01-5-1.37l-.36-.21-3.83 1 1.02-3.73-.23-.38a9.76 9.76 0 01-1.5-5.17c0-5.4 4.4-9.8 9.81-9.8 2.62 0 5.08 1.02 6.93 2.87a9.72 9.72 0 012.87 6.93c0 5.4-4.4 9.8-9.81 9.8zm5.38-7.35c-.3-.15-1.77-.88-2.04-.98-.27-.1-.47-.15-.67.15-.2.3-.77.98-.95 1.18-.17.2-.35.22-.65.07-.3-.15-1.25-.46-2.38-1.46-.88-.79-1.48-1.76-1.66-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.5h-.57c-.2 0-.52.08-.8.37-.27.3-1.05 1.02-1.05 2.5s1.08 2.9 1.23 3.1c.15.2 2.12 3.24 5.13 4.54.72.3 1.28.48 1.72.62.72.23 1.37.2 1.88.12.57-.08 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35z"
                />
              </svg>
              WhatsApp
            </a>
          </div>
        </div>
      </div>

      <!-- HEADER -->
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo -->
        <router-link to="/" class="flex-shrink-0 transition-opacity hover:opacity-80">
          <img :src="logoUrl" alt="Access Informatique" class="h-10 w-auto" />
        </router-link>

        <!-- Menu desktop -->
        <ul class="hidden md:flex items-center space-x-1 text-sm font-medium">
          <li v-for="link in navLinks" :key="link.to">
            <router-link
              :to="link.to"
              class="px-4 py-2 rounded-lg transition-all duration-200 text-slate-700 hover:text-green-600 hover:bg-green-50"
              active-class="!text-green-600 font-semibold"
            >
              {{ link.label }}
            </router-link>
          </li>
          <!-- CTA contact -->
          <li class="ml-4">
            <router-link
              to="/contact"
              class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-500 text-white font-semibold transition-all duration-200 shadow-md shadow-green-600/20 hover:shadow-green-500/30 hover:-translate-y-px"
            >
              Nous contacter
            </router-link>
          </li>
        </ul>

        <!-- Bouton hamburger mobile -->
        <button
          @click="toggleMobileMenu"
          class="md:hidden relative w-9 h-9 flex flex-col items-center justify-center gap-1.5 rounded-lg transition-colors duration-200 text-slate-800"
          aria-label="Ouvrir le menu"
        >
          <span
            class="block w-5 h-0.5 bg-current rounded-full transition-all duration-300 origin-center"
            :class="mobileMenuOpen ? 'rotate-45 translate-y-2' : ''"
          ></span>
          <span
            class="block w-5 h-0.5 bg-current rounded-full transition-all duration-300"
            :class="mobileMenuOpen ? 'opacity-0 scale-x-0' : ''"
          ></span>
          <span
            class="block w-5 h-0.5 bg-current rounded-full transition-all duration-300 origin-center"
            :class="mobileMenuOpen ? '-rotate-45 -translate-y-2' : ''"
          ></span>
        </button>
      </div>

      <!-- Menu mobile (slide down) -->
      <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-4"
      >
        <div
          v-if="mobileMenuOpen"
          class="md:hidden bg-white/98 backdrop-blur-xl border-t border-slate-200"
        >
          <ul class="max-w-7xl mx-auto px-6 py-4 space-y-1">
            <li v-for="link in navLinks" :key="`mob-${link.to}`">
              <router-link
                :to="link.to"
                @click="mobileMenuOpen = false"
                class="block px-4 py-3 rounded-xl text-slate-700 hover:text-green-600 hover:bg-green-50 font-medium transition-all duration-200"
                active-class="!text-green-600 bg-green-100"
              >
                {{ link.label }}
              </router-link>
            </li>
            <li class="pt-2">
              <router-link
                to="/contact"
                @click="mobileMenuOpen = false"
                class="block px-4 py-3 rounded-xl bg-green-600 hover:bg-green-500 text-white font-semibold text-center transition-colors"
              >
                Nous contacter
              </router-link>
            </li>
            <li>
              <router-link
                to="/inscription"
                @click="mobileMenuOpen = false"
                class="block px-4 py-3 rounded-xl bg-green-600 hover:bg-green-500 text-white font-semibold text-center transition-colors"
              >
                S'inscrire à une formation
              </router-link>
            </li>
          </ul>
        </div>
      </Transition>
    </nav>

    <!-- =========================================================
         CONTENU PRINCIPAL
         ========================================================= -->
    <main :class="isAdmin ? 'flex-1' : 'flex-1 pt-14'">
      <router-view />
    </main>

    <!-- =========================================================
         FOOTER (masqué sur les routes /admin)
         ========================================================= -->
    <footer v-if="!isAdmin" class="bg-slate-950 text-slate-400">
      <!-- Contenu principal du footer -->
      <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
          <!-- Colonne 1 : Marque -->
          <div class="md:col-span-2 space-y-4">
            <img :src="logoUrl" alt="Access Informatique" class="h-9 w-auto brightness-0 invert" />
            <p class="text-sm leading-relaxed max-w-xs">
              {{ footerTagline }}
            </p>
            <!-- Réseaux sociaux -->
            <div class="flex gap-3 pt-2">
              <a
                href="#"
                aria-label="Facebook"
                class="w-9 h-9 rounded-lg bg-white/5 hover:bg-green-600 flex items-center justify-center transition-colors duration-200"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                  />
                </svg>
              </a>
              <a
                href="#"
                aria-label="LinkedIn"
                class="w-9 h-9 rounded-lg bg-white/5 hover:bg-green-600 flex items-center justify-center transition-colors duration-200"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                  />
                </svg>
              </a>
            </div>
          </div>

          <!-- Colonne 2 : Solutions -->
          <div>
            <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">
              Nos solutions
            </h4>
            <ul class="space-y-2.5 text-sm">
              <li v-for="sol in footerSolutions" :key="sol.to">
                <router-link
                  :to="sol.to"
                  class="hover:text-white transition-colors duration-200 hover:translate-x-0.5 inline-block"
                >
                  {{ sol.label }}
                </router-link>
              </li>
            </ul>
          </div>

          <!-- Colonne 3 : Coordonnées -->
          <div>
            <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Contact</h4>
            <ul class="space-y-3 text-sm">
              <li class="flex items-start gap-2.5">
                <span class="mt-0.5 text-green-500 flex-shrink-0">✉</span>
                <a
                  :href="`mailto:${email}`"
                  class="hover:text-white transition-colors break-all"
                >
                  {{ email }}
                </a>
              </li>
              <li class="flex items-center gap-2.5">
                <span class="text-green-500 flex-shrink-0">✆</span>
                <span>{{ phone1 }}</span>
              </li>
              <li class="flex items-center gap-2.5">
                <span class="text-green-500 flex-shrink-0">✆</span>
                <span>{{ phone2 }}</span>
              </li>
              <li class="flex items-start gap-2.5 pt-1">
                <span class="mt-0.5 text-green-500 flex-shrink-0">◎</span>
                <span>{{ footerAddress }}</span>
              </li>
              <li class="flex items-center gap-2.5 text-xs text-slate-500 pt-1">
                <span>{{ footerHours }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Bas du footer -->
      <div class="border-t border-white/5">
        <div
          class="max-w-7xl mx-auto px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600"
        >
          <span>© 2026 Access Informatique. Tous droits réservés.</span>
          <div class="flex gap-6">
            <router-link to="/mentions-legales" class="hover:text-slate-400 transition-colors"
              >Mentions légales</router-link
            >
            <router-link to="/confidentialite" class="hover:text-slate-400 transition-colors"
              >Confidentialité</router-link
            >
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import logoUrl from './assets/images/logo.png'
import { useContentStore } from '@/stores/content'

// ─── Détecte les routes admin pour masquer le header/footer public ────────────
const route = useRoute()
const isAdmin = computed(() => route.path.startsWith('/admin'))

// ─── Contenus globaux dynamiques (email, téléphone, adresse…) ─────────────────
const contentStore = useContentStore()
onMounted(() => contentStore.load('global'))

const email  = computed(() => contentStore.get('global', 'header.email',  'info@accessinformatique.com'))
const phone1 = computed(() => contentStore.get('global', 'header.phone1', '(+225) 01 01 57 30 54'))
const phone2 = computed(() => contentStore.get('global', 'header.phone2', '(+225) 07 07 26 18 58'))
const footerTagline = computed(() => contentStore.get('global', 'footer.tagline', "Éditeur de solutions de gestion sur mesure pour les entreprises, institutions et professionnels de Côte d'Ivoire et d'Afrique."))
const footerAddress = computed(() => contentStore.get('global', 'footer.address', 'Yopougon Sable, Andokoi, Abidjan, Côte d\'Ivoire'))
const footerHours   = computed(() => contentStore.get('global', 'footer.hours',   'Lun–Ven : 08h–18h · Sam : 09h–13h'))

// ─── Navigation ───────────────────────────────────────────────────────────────
const navLinks = [
  { to: '/', label: 'Accueil' },
  { to: '/solutions', label: 'Nos solutions' },
  { to: '/apropos', label: 'Qui sommes-nous ?' },
  { to: '/hackathon', label: 'Hackathon' },
  { to: '/formation', label: 'Formation' },
]

// ─── Footer solutions ─────────────────────────────────────────────────────────
const footerSolutions = [
  { to: '/solutions/solumed', label: 'SoluMed — Santé' },
  { to: '/solutions/myschool', label: 'MySchool — Éducation' },
  { to: '/solutions/matrix', label: 'Matrix — Comptabilité' },
  { to: '/solutions/simba', label: 'Simba — Immobilier' },
  { to: '/solutions/musa', label: 'Musa — Mutuelles' },
  { to: '/solutions/smartrhpaie', label: 'SmartRHPaie — RH & Paie' },
]

// ─── Header scroll effect ─────────────────────────────────────────────────────
const scrolled = ref(false)

function handleScroll() {
  scrolled.value = window.scrollY > 30
}

// ─── Menu mobile ──────────────────────────────────────────────────────────────
const mobileMenuOpen = ref(false)

function toggleMobileMenu() {
  mobileMenuOpen.value = !mobileMenuOpen.value
}

// Ferme le menu si on clique en dehors
function handleClickOutside(e) {
  if (mobileMenuOpen.value && !e.target.closest('nav')) {
    mobileMenuOpen.value = false
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  document.removeEventListener('click', handleClickOutside)
})
</script>
