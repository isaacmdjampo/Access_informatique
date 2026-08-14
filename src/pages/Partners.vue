<template>
  <div class="partners-page min-h-screen bg-white">
    <!-- HERO SECTION -->
    <section class="relative pt-32 pb-24 overflow-hidden">
      <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div
          class="absolute top-0 right-0 w-[600px] h-[600px] rounded-full pointer-events-none"
          style="background: radial-gradient(circle, #5ca17015 0%, transparent 70%)"
        ></div>
      </div>

      <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
        <nav class="flex items-center justify-center gap-2 text-sm text-slate-400 mb-10">
          <router-link to="/" class="hover:text-slate-600 transition-colors">Accueil</router-link>
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="font-semibold text-slate-700">Nos partenaires</span>
        </nav>

        <div class="mb-10">
          <span
            class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase border"
            style="background: #eef7f2; color: #5ca170; border-color: rgba(92, 161, 112, 0.3)"
          >
            <span class="w-1.5 h-1.5 rounded-full" style="background: #5ca170"></span>
            Confiance et excellence
          </span>
        </div>

        <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-slate-900 leading-tight mb-6">
          Nos partenaires<br />
          <span style="color: #5ca170">nous font confiance</span>
        </h1>

        <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto mb-10">
          Découvrez les entreprises, institutions et organisations qui utilisent nos solutions
          pour optimiser leur activité et booster leur croissance.
        </p>

        <div class="h-1 w-20 mx-auto rounded-full" style="background: #5ca170"></div>
      </div>
    </section>

    <!-- GRILLE DES PARTENAIRES -->
    <section class="py-20 bg-slate-50">
      <div class="max-w-7xl mx-auto px-6">
        <div class="mb-16 text-center">
          <p class="text-xs font-bold tracking-[0.2em] uppercase mb-3" style="color: #5ca170">
            Nos références
          </p>
          <h2 class="text-3xl md:text-4xl font-black text-slate-900">
            {{ partners.length }} partenaires actifs
          </h2>
          <p class="text-slate-500 mt-4 max-w-xl mx-auto">
            Une communauté croissante de professionnels qui transforment leur secteur grâce à nos solutions.
          </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
          <div
            v-for="partner in partners"
            :key="partner.nom"
            class="group relative bg-white rounded-2xl p-8 border border-slate-100 hover:border-transparent hover:shadow-lg transition-all duration-300 hover:-translate-y-1 flex flex-col items-center justify-center text-center min-h-48"
          >
            <img
              :src="partner.logo"
              :alt="partner.nom"
              class="h-28 w-auto object-contain transition-all duration-300 mb-4"
            />
            <h3 class="text-sm font-bold text-slate-900 group-hover:text-slate-700 transition-colors">
              {{ partner.nom }}
            </h3>
            <div
              class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r w-0 group-hover:w-full transition-all duration-400 rounded-b-2xl"
              style="background: linear-gradient(90deg, #5ca170, transparent)"
            ></div>
          </div>
        </div>
      </div>
    </section>

    <!-- STATISTIQUES -->
    <section class="py-20 bg-white">
      <div class="max-w-5xl mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
          <div class="p-8">
            <p class="text-4xl md:text-5xl font-black" style="color: #5ca170">
              {{ partners.length }}+
            </p>
            <p class="text-slate-600 text-sm uppercase tracking-wider font-semibold mt-3">
              Partenaires actifs
            </p>
          </div>
          <div class="p-8 border-l border-r border-slate-100">
            <p class="text-4xl md:text-5xl font-black" style="color: #5ca170">
              100%
            </p>
            <p class="text-slate-600 text-sm uppercase tracking-wider font-semibold mt-3">
              Satisfaction garantie
            </p>
          </div>
          <div class="p-8">
            <p class="text-4xl md:text-5xl font-black" style="color: #5ca170">
              24/7
            </p>
            <p class="text-slate-600 text-sm uppercase tracking-wider font-semibold mt-3">
              Support disponible
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA SECTION -->
    <section class="py-20 relative overflow-hidden" style="background: #5ca170">
      <div
        class="absolute inset-0 pointer-events-none"
        style="background: radial-gradient(ellipse at 60% 50%, rgba(255,255,255,0.15) 0%, transparent 60%)"
        aria-hidden="true"
      ></div>

      <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-black text-white mb-6">
          Rejoignez nos partenaires
        </h2>
        <p class="text-white/80 text-lg mb-10 max-w-2xl mx-auto">
          Vous souhaitez transformer votre activité avec nos solutions ? Contactez notre équipe pour
          une démonstration personnalisée et gratuite.
        </p>

        <router-link
          to="/contact"
          class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-white font-bold text-sm transition-all duration-200 hover:-translate-y-0.5 shadow-lg"
          style="color: #5ca170"
        >
          Nous contacter
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </router-link>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { listeReferences } from '@/data/aboutData'

const partners = ref([])

onMounted(() => {
  partners.value = listeReferences
})
</script>

<style scoped>
.partners-page {
  overflow-x: hidden;
}
</style>
