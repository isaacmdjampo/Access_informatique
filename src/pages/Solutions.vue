<template>
  <div class="solutions-page">
    <!-- HERO SECTION -->
    <section class="solutions-hero relative pt-32 pb-16 overflow-hidden bg-white">
      <div class="hero-grid absolute inset-0 pointer-events-none" aria-hidden="true"></div>
      <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full pointer-events-none"
        style="background: radial-gradient(circle, rgba(34, 197, 94, 0.07) 0%, transparent 70%)"
        aria-hidden="true"
      ></div>
      <div class="relative z-10 max-w-6xl mx-auto px-6 text-center">
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
    <section class="py-8 bg-white sticky top-[64px] z-20 border-b border-slate-100 shadow-sm">
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
              <!-- Overlay gradient -->
              <div
                class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"
              ></div>

              <!-- Badge catégorie -->
              <span
                class="absolute top-4 left-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide backdrop-blur-sm border"
                :style="solution.categoryStyle"
              >
                <component :is="solution.categoryIcon" class="w-3 h-3" />
                {{ solution.category }}
              </span>

              <!-- Nom du logiciel en bas de l'image -->
              <div class="absolute bottom-4 left-4 right-4">
                <h2 class="text-2xl font-black text-white tracking-tight drop-shadow-lg">
                  {{ solution.name }}
                </h2>
              </div>
            </div>

            <!-- Contenu -->
            <div class="p-7 flex flex-col flex-grow">
              <!-- Tagline -->
              <p class="text-xs font-bold tracking-[0.15em] text-green-600 uppercase mb-3">
                {{ solution.tagline }}
              </p>

              <!-- Description tronquée -->
              <p class="text-slate-600 text-sm leading-relaxed flex-grow">
                {{ solution.description.substring(0, 140) }}<span class="text-slate-400">…</span>
              </p>

              <!-- Modules / fonctionnalités clés -->
              <div class="flex flex-wrap gap-2 mt-5">
                <span
                  v-for="tag in solution.tags"
                  :key="tag"
                  class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-50 text-slate-500 border border-slate-100"
                >
                  {{ tag }}
                </span>
              </div>

              <!-- Séparateur -->
              <div class="border-t border-slate-100 mt-6 pt-6">
                <router-link
                  :to="solution.route"
                  class="group/btn inline-flex items-center gap-2 w-full justify-center px-6 py-3.5 rounded-xl font-semibold text-sm bg-green-600 hover:bg-green-500 text-white shadow-md shadow-green-600/20 hover:shadow-green-500/40 transition-all duration-200 hover:-translate-y-0.5"
                >
                  Lire la suite
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
// h est utilisé pour créer des icônes SVG en ligne compatibles avec <component :is="...">
import { ref, computed, h } from 'vue'

const activeCategory = ref('all')

const categories = [
  { id: 'all', label: 'Tous les logiciels' },
  { id: 'sante', label: '🏥 Santé' },
  { id: 'education', label: '🎓 Éducation' },
  { id: 'compta', label: '📊 Comptabilité' },
  { id: 'rh', label: '👥 RH & Paie' },
  { id: 'immo', label: '🏠 Immobilier' },
  { id: 'asso', label: '🤝 Associations' },
]

// ------------- ICÔNES SVG en ligne  ---------------

const IconHospital = {
  render: () =>
    h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9',
      }),
    ]),
}

const IconSchool = {
  render: () =>
    h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
      }),
    ]),
}

const IconChart = {
  render: () =>
    h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
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

const IconHome = {
  render: () =>
    h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
      }),
    ]),
}

const IconHandshake = {
  render: () =>
    h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0',
      }),
    ]),
}

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

const IconHeadset = {
  render: () =>
    h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
      }),
    ]),
}

// ------------- DONNÉES DES SOLUTIONS ---------------

const solutions = ref([
  {
    id: 1,
    name: 'SoluMed',
    category: 'Santé',
    categoryId: 'sante',
    categoryIcon: IconHospital,
    categoryStyle:
      'background: rgba(239,246,255,0.95); color: #1d4ed8; border-color: rgba(59,130,246,0.25);',
    tagline: 'Gestion médicale complète',
    description:
      "SoluMed est un véritable outil de gestion de votre établissement médical (Clinique, Centre de santé, Groupe Médical). Développé depuis 2006 par Access Informatique, ce logiciel vous permet de gérer les consultations, les hospitalisations, la pharmacie, la facturation et bien plus encore. Il est conçu pour s'adapter à toutes les tailles d'établissements, des petits cabinets aux polycliniques multi-sites.",
    image: '/src/assets/images/Logiciels/cvsolumed.png',
    tags: ['Consultations', 'Hospitalisation', 'Pharmacie', 'Facturation', 'Statistiques'],
    route: '/solutions/solumed',
  },
  {
    id: 2,
    name: 'MySchool',
    category: 'Éducation',
    categoryId: 'education',
    categoryIcon: IconSchool,
    categoryStyle:
      'background: rgba(254,252,232,0.95); color: #92400e; border-color: rgba(245,158,11,0.25);',
    tagline: 'La gestion scolaire digitale',
    description:
      "MySchool est une application web et mobile développée pour les Écoles Primaires, Secondaires, Grandes Écoles et Universités. C'est un espace collaboratif pour l'école, les enseignants, les parents d'élèves et les étudiants. Elle met à disposition des outils permettant de gérer la vie scolaire, les bulletins, les absences, la comptabilité scolaire et la communication école-parents.",
    image: '/src/assets/images/Logiciels/cvmyschool.png',
    tags: ['Bulletins', 'Inscriptions', 'Absences', 'Parents', 'Paiements'],
    route: '/solutions/myschool',
  },
  {
    id: 3,
    name: 'Matrix',
    category: 'Comptabilité',
    categoryId: 'compta',
    categoryIcon: IconChart,
    categoryStyle:
      'background: rgba(240,253,244,0.95); color: #166534; border-color: rgba(34,197,94,0.25);',
    tagline: 'Comptabilité SYSCOHADA',
    description:
      "Matrix est un logiciel destiné au traitement informatique de toutes les opérations comptables d'une entreprise, mutuelle ou ONG. Développé par Access Informatique, cette solution est conforme aux normes SYSCOHADA en vigueur et est régulièrement mise à jour pour suivre les évolutions réglementaires. Elle couvre la saisie, le contrôle, l'édition des états financiers et la gestion budgétaire.",
    image: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&q=80',
    tags: ['SYSCOHADA', 'Bilan', 'Grand Livre', 'Budget', 'Déclarations'],
    route: '/solutions/matrix',
  },
  {
    id: 4,
    name: 'SmartRH Paie',
    category: 'RH & Paie',
    categoryId: 'rh',
    categoryIcon: IconUsers,
    categoryStyle:
      'background: rgba(253,242,248,0.95); color: #9d174d; border-color: rgba(236,72,153,0.25);',
    tagline: 'Capital humain & conformité',
    description:
      'SmartRH Paie est une solution complète pour la gestion des ressources humaines et le traitement de la paie. Elle permet la gestion des contrats, des congés, des absences, des bulletins de salaire et des déclarations sociales conformément à la législation ivoirienne. Un outil indispensable pour les DRH qui souhaitent gagner en efficacité et en conformité.',
    image: '/src/assets/images/Logiciels/cvsmartrh.png',
    tags: ['Bulletins', 'Congés', 'CNPS', 'Contrats', 'Reporting RH'],
    route: '/solutions/smartrhpaie',
  },
  {
    id: 5,
    name: 'Simba',
    category: 'Immobilier',
    categoryId: 'immo',
    categoryIcon: IconHome,
    categoryStyle:
      'background: rgba(255,247,237,0.95); color: #c2410c; border-color: rgba(249,115,22,0.25);',
    tagline: 'Gestion locative simplifiée',
    description:
      "La gestion de plusieurs appartements ou maisons en location peut vite devenir complexe : régularisation des impayés, lettres de relance, quittances de loyers. Simba est la solution de gestion immobilière d'Access Informatique qui automatise et simplifie l'ensemble du cycle locatif, de l'entrée du locataire à la clôture du bail, en passant par les encaissements et la comptabilité immobilière.",
    image: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80',
    tags: ['Loyers', 'Quittances', 'Relances', 'Locataires', 'Propriétés'],
    route: '/solutions/simba',
  },
  {
    id: 6,
    name: 'Musa',
    category: 'Associations',
    categoryId: 'asso',
    categoryIcon: IconHandshake,
    categoryStyle:
      'background: rgba(245,243,255,0.95); color: #6d28d9; border-color: rgba(139,92,246,0.25);',
    tagline: 'Mutuelles & coopératives',
    description:
      'Musa est le logiciel de gestion des communautés, mutuelles, associations et coopératives développé par Access Informatique. Il permet de gérer les adhérents, les cotisations, les prestations, les fonds de solidarité et de produire des rapports financiers détaillés. Une solution complète pour les organisations qui souhaitent moderniser leur gestion administrative et financière.',
    image: 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=800&q=80',
    tags: ['Adhérents', 'Cotisations', 'Prestations', 'Solidarité', 'Rapports'],
    route: '/solutions/musa',
  },
])

// Filtrage des solutions en fonction de la catégorie active
const filteredSolutions = computed(() => {
  if (activeCategory.value === 'all') return solutions.value
  return solutions.value.filter((s) => s.categoryId === activeCategory.value) // Filtre par catégorie. s est chaque solution, s.categoryId est la catégorie de la solution, activeCategory.value est la catégorie sélectionnée. Si elles correspondent, la solution est incluse dans le résultat.
})

// "Pourquoi nous choisir" features
const whyUs = [
  {
    icon: IconCode,
    title: 'Développement sur mesure',
    text: 'Chaque logiciel est conçu pour correspondre exactement à vos processus métier. Aucun compromis, aucune fonctionnalité superflue imposée.',
  },
  {
    icon: IconShield,
    title: 'Conformité locale',
    text: 'Nos solutions respectent les normes ivoiriennes (SYSCOHADA, CNPS, DGI) et sont maintenues à jour lors de chaque évolution réglementaire.',
  },
  {
    icon: IconHeadset,
    title: 'Support humain permanent',
    text: 'Formation incluse, assistance technique disponible du lundi au samedi. Une équipe locale, proche de vous, toujours prête à intervenir.',
  },
]
</script>

<style scoped>
/* HERO */
.hero-grid {
  background-image:
    linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
  background-size: 80px 80px;
}

..hero-gradient-green {
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

@media (max-width: 640px) {
  .hero-grid {
    background-size: 40px 40px;
  }
}
</style>
