<template>
  <div class="apropos-page bg-white overflow-hidden">
    <!-- HERO  -->
    <section class="relative min-h-screen flex items-center bg-white overflow-hidden">
      <!-- GRID -->
      <div class="hero-grille absolute inset-0"></div>

      <!-- HALO -->
      <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full pointer-events-none"
        style="background: radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%)"
      ></div>

      <div class="relative z-10 max-w-7xl mx-auto px-6 py-32">
        <div class="max-w-4xl mx-auto text-center">
          <!-- BADGE -->
          <div
            class="hero-badge inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-green-500/30 bg-green-500/10 text-green-600 text-xs font-semibold tracking-widest uppercase mb-8 justify-center"
          >
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
            Access Informatique
          </div>

          <!-- TITRE -->
          <h1
            class="text-5xl md:text-7xl lg:text-8xl font-black text-slate-900 tracking-tight leading-[0.95] mb-8"
          >
            Nous développons<br />
            <span class="texte-degrade-green"> des solutions utiles. </span>
          </h1>

          <!-- TEXTE -->
          <p class="text-lg md:text-xl text-slate-600 leading-relaxed max-w-2xl mb-12 mx-auto">
            Depuis plus de 20 ans, Access Informatique accompagne les entreprises, écoles,
            cliniques, mutuelles et institutions avec des logiciels conçus spécialement pour leurs
            réalités métiers.
          </p>

          <!-- STATS  -->
          <div
            class="grid grid-cols-1 sm:grid-cols-3 gap-px bg-green-100 rounded-2xl overflow-hidden border border-green-200 max-w-3xl mx-auto"
          >
            <div class="bg-white px-8 py-6 text-center">
              <div class="text-4xl font-black text-green-600">+20</div>
              <div class="text-xs text-slate-500 uppercase tracking-wider mt-1">
                Ans d'expérience
              </div>
            </div>

            <div class="bg-white px-8 py-6 text-center border-l border-r border-green-100">
              <div class="text-4xl font-black text-green-600">6</div>
              <div class="text-xs text-slate-500 uppercase tracking-wider mt-1">
                Solutions métiers
              </div>
            </div>

            <div class="bg-white px-8 py-6 text-center">
              <div class="text-4xl font-black text-green-600">100%</div>
              <div class="text-xs text-slate-500 uppercase tracking-wider mt-1">Sur mesure</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION PRESENTATION -->
    <section class="py-28 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
          <!-- TEXTE -->
          <div>
            <p class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase mb-3">
              Notre présentation
            </p>
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-8">
              Une expertise construite sur le terrain.
            </h2>
            <div class="space-y-6 text-slate-500 text-lg leading-relaxed">
              <p>
                Access Informatique est une entreprise spécialisée dans la création de logiciels de
                gestion adaptés aux besoins réels des structures africaines.
              </p>
              <p>
                Nos solutions sont utilisées quotidiennement dans des écoles, cliniques, mutuelles,
                entreprises privées et structures administratives.
              </p>
              <p>
                Nous développons des outils modernes, fiables et simples à utiliser afin de
                simplifier la gestion quotidienne de nos clients.
              </p>
            </div>
          </div>

          <!-- CARD DESIGN (modifié : vert) -->
          <div class="relative">
            <div
              class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-green-100 blur-3xl opacity-50"
            ></div>

            <div class="relative bg-green-600 rounded-[2rem] p-10 overflow-hidden">
              <div
                class="absolute inset-0 opacity-20"
                style="
                  background: radial-gradient(
                    circle at top right,
                    rgba(255, 255, 255, 0.3),
                    transparent 40%
                  );
                "
              ></div>

              <div class="relative z-10">
                <div class="space-y-8">
                  <div>
                    <div class="text-5xl font-black text-white mb-2">2000+</div>
                    <p class="text-green-100">Utilisateurs actifs sur nos plateformes.</p>
                  </div>
                  <div class="w-full h-px bg-white/20"></div>
                  <div>
                    <div class="text-5xl font-black text-white mb-2">Côte d'Ivoire</div>
                    <p class="text-green-100">Une expertise locale adaptée aux réalités métiers.</p>
                  </div>
                  <div class="w-full h-px bg-white/20"></div>
                  <div>
                    <div class="text-5xl font-black text-white mb-2">Support humain</div>
                    <p class="text-green-100">Assistance technique et accompagnement inclus.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION LOGICIELS -->
    <section class="py-28 bg-slate-50">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20">
          <p class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase mb-3">
            Nos logiciels
          </p>
          <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
            Des solutions conçues<br />
            pour chaque métier.
          </h2>
          <p class="text-slate-500 text-lg mt-5 max-w-2xl mx-auto">
            Chaque logiciel répond à un besoin métier précis avec une approche moderne, fiable et
            évolutive.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
          <div
            v-for="logiciel in listeLogiciels"
            :key="logiciel.nom"
            class="group bg-white rounded-3xl overflow-hidden border border-slate-100 hover:border-green-100 shadow-xl shadow-slate-100 hover:shadow-2xl hover:shadow-green-100/40 transition-all duration-300 hover:-translate-y-1"
          >
            <div class="relative h-64 overflow-hidden">
              <img
                :src="logiciel.image"
                :alt="logiciel.nom"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
              <div class="absolute bottom-5 left-5">
                <span
                  class="inline-flex px-3 py-1 rounded-full bg-green-600 text-white text-xs font-semibold mb-3"
                >
                  {{ logiciel.categorie }}
                </span>
                <h3 class="text-3xl font-black text-white">{{ logiciel.nom }}</h3>
              </div>
            </div>

            <div class="p-8">
              <p class="text-slate-500 leading-relaxed mb-8">
                {{ logiciel.description }}
              </p>
              <div>
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">
                  Structures utilisatrices
                </h4>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="reference in logiciel.references"
                    :key="reference"
                    class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-sm font-medium"
                  >
                    {{ reference }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- REFERENCES -->
    <section class="py-28 bg-white overflow-hidden">
      <div class="max-w-7xl mx-auto px-6 mb-16 text-center">
        <p class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase mb-3">
          Nos références
        </p>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
          Ils nous font confiance.
        </h2>
        <p class="text-slate-500 text-lg mt-5 max-w-2xl mx-auto">
          Nos solutions accompagnent des structures de plusieurs secteurs d'activité.
        </p>
      </div>

      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
          <div
            v-for="reference in listeReferences"
            :key="reference.nom"
            class="group bg-slate-50 rounded-2xl border border-slate-100 h-36 flex items-center justify-center p-6 hover:bg-white hover:border-green-100 hover:shadow-lg hover:shadow-green-50 transition-all duration-300"
          >
            <img
              :src="reference.logo"
              :alt="reference.nom"
              class="max-h-16 object-contain opacity-70 group-hover:opacity-100 transition-all duration-300"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- CTA FINAL (modifié : vert) -->
    <section class="py-28 bg-green-600 relative overflow-hidden">
      <div
        class="absolute inset-0"
        style="
          background: radial-gradient(circle at center, rgba(255, 255, 255, 0.15), transparent 60%);
        "
      ></div>

      <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-tight mb-6">
          Construisons ensemble<br />
          votre futur digital.
        </h2>
        <p class="text-green-100 text-lg md:text-xl max-w-2xl mx-auto mb-10">
          Nous créons des logiciels modernes, fiables et adaptés à votre activité.
        </p>
        <router-link
          to="/contact"
          class="inline-flex items-center gap-2 px-10 py-4 rounded-xl bg-white text-green-600 font-bold shadow-lg shadow-black/20 hover:shadow-black/30 hover:-translate-y-0.5 transition-all duration-200"
        >
          Nous contacter
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
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const listeLogiciels = ref([
  {
    nom: 'SoluMed',
    categorie: 'Santé',
    image: '/src/assets/images/Logiciels/cvsolumed.png',
    description: 'Solution complète pour la gestion des cliniques, centres médicaux et hôpitaux.',
    references: ['Clinique St Trinité', 'Centre Médical Lumière', 'GM CITECHNO'],
  },
  {
    nom: 'MySchool',
    categorie: 'Éducation',
    image: '/src/assets/images/Logiciels/cvmyschool.png',
    description: 'Plateforme scolaire moderne pour la gestion administrative et pédagogique.',
    references: ['Université Atlantique', 'IS Blaise Pascal', 'Collège MERAJEA'],
  },
  {
    nom: 'Matrix',
    categorie: 'Comptabilité',
    image: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=1200&q=80',
    description: 'Gestion comptable conforme aux normes SYSCOHADA.',
    references: ['FPPN', 'TANCHIVOIRE', 'FHACI'],
  },
  {
    nom: 'SmartRHPaie',
    categorie: 'Ressources humaines',
    image: '/src/assets/images/Logiciels/cvsmartrh.png',
    description: 'Gestion RH, paie, contrats et suivi du personnel.',
    references: ['PME', 'Entreprises privées', 'Structures administratives'],
  },
  {
    nom: 'Simba',
    categorie: 'Immobilier',
    image: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&q=80',
    description: 'Gestion immobilière et suivi des loyers.',
    references: ['Cabinets immobiliers', 'Agences privées', 'EPSI'],
  },
])

const listeReferences = ref([
  {
    nom: 'Centre Médical Lumière',
    logo: '/src/assets/images/References/logrefcentremedicalelumiere.png',
  },
  { nom: 'YNACASS', logo: '/src/assets/images/References/logo_synacassci.png' },
  { nom: 'FPPN', logo: '/src/assets/images/References/logrefFPPN.png' },
  { nom: 'EPSI', logo: '/src/assets/images/References/logrefepsi.png' },
  { nom: 'CMAC', logo: '/src/assets/images/References/logrefCliniqMedicalAcasa.png' },
  { nom: 'IRMA', logo: '/src/assets/images/References/logrefirmabassam.jpeg' },
  { nom: 'MUSACNRA', logo: '/src/assets/images/References/logrefMUSACNRA.png' },
  { nom: 'TANCHIVOIRE', logo: '/src/assets/images/References/logreftranchIvoire.png' },
  {
    nom: 'Université Atlantique',
    logo: '/src/assets/images/References/logrefuniversiteatlantiq.jfif',
  },
  {
    nom: 'Clinique Sainte Henriette',
    logo: '/src/assets/images/References/logrefpolycliqstHenriette.jpg',
  },
])
</script>

<style scoped>
/* GRILLE HERO */
.hero-grille {
  background-image:
    linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
  background-size: 80px 80px;
}

/* TEXTE VERT (couleur bouton) */
.texte-degrade-green {
  color: #16a34a;
  font-weight: 700;
}
</style>
