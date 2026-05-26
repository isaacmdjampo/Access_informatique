# Formation.vue ```vue
<template>
  <div class="formation-page bg-white text-slate-900 overflow-hidden">
    <!-- HERO -->
    <section class="relative pt-36 pb-24 overflow-hidden bg-white">
      <!-- grille -->
      <div class="hero-grid absolute inset-0 pointer-events-none"></div>

      <!-- halos -->
      <div
        class="absolute -top-20 right-0 w-[500px] h-[500px] rounded-full blur-3xl opacity-20"
        style="background: radial-gradient(circle, rgba(34, 197, 94, 0.25) 0%, transparent 70%)"
      ></div>

      <div
        class="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full blur-3xl opacity-10"
        style="background: radial-gradient(circle, rgba(22, 163, 74, 0.25) 0%, transparent 70%)"
      ></div>

      <div class="relative z-10 max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
        <!-- Texte -->
        <div>
          <div
            class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-green-500/20 bg-green-50 text-green-700 text-xs font-bold uppercase tracking-[0.2em] mb-8"
          >
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            Formation professionnelle — Access Informatique
          </div>

          <h1 class="text-5xl md:text-7xl font-black tracking-tight leading-[0.95] text-slate-900">
            Formez-vous aux métiers du
            <span class="text-green-600">numérique.</span>
          </h1>

          <p class="mt-8 text-lg text-slate-600 leading-relaxed max-w-2xl">
            Des formations modernes, pratiques et professionnalisantes pour étudiants, entreprises
            et particuliers. Développement web, bureautique, design, cybersécurité, intelligence
            artificielle et bien plus.
          </p>

          <!-- boutons -->
          <div class="flex flex-col sm:flex-row gap-4 mt-10">
            <router-link
              to="/contact"
              class="group inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-green-600 hover:bg-green-500 text-white font-bold transition-all duration-300 shadow-xl shadow-green-600/20 hover:-translate-y-1"
            >
              Commencer maintenant
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

            <a
              href="#catalogue"
              class="inline-flex items-center justify-center px-8 py-4 rounded-2xl border border-slate-300 hover:border-green-500 hover:text-green-600 font-semibold transition-all duration-300 hover:-translate-y-1"
            >
              Voir les formations
            </a>
          </div>

          <!-- statistiques -->
          <div
            class="grid grid-cols-2 md:grid-cols-4 gap-px overflow-hidden rounded-3xl border border-green-100 bg-green-100 mt-14"
          >
            <div class="bg-white px-6 py-6 text-center">
              <div class="text-3xl font-black text-green-600">+500</div>
              <div class="text-xs uppercase tracking-wider text-slate-500 mt-2">Apprenants</div>
            </div>

            <div class="bg-white px-6 py-6 text-center">
              <div class="text-3xl font-black text-green-600">15+</div>
              <div class="text-xs uppercase tracking-wider text-slate-500 mt-2">Formations</div>
            </div>

            <div class="bg-white px-6 py-6 text-center">
              <div class="text-3xl font-black text-green-600">100%</div>
              <div class="text-xs uppercase tracking-wider text-slate-500 mt-2">Pratique</div>
            </div>

            <div class="bg-white px-6 py-6 text-center">
              <div class="text-3xl font-black text-green-600">24h</div>
              <div class="text-xs uppercase tracking-wider text-slate-500 mt-2">Support</div>
            </div>
          </div>
        </div>

        <!-- image hero -->
        <div class="relative">
          <div
            class="absolute inset-0 rounded-[2rem] bg-gradient-to-br from-green-100 to-green-50 blur-3xl opacity-60 scale-95"
          ></div>

          <div
            class="relative bg-white rounded-[2rem] border border-slate-200 shadow-2xl shadow-slate-950/5 overflow-hidden"
          >
            <img
              src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1600&auto=format&fit=crop"
              alt="Formation Access Informatique"
              class="w-full h-[620px] object-cover"
            />

            <div
              class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"
            ></div>

            <div class="absolute bottom-0 left-0 p-8 text-white">
              <div class="text-sm uppercase tracking-[0.2em] text-white/70 mb-3">
                Formation certifiante
              </div>
              <h3 class="text-3xl font-black leading-tight max-w-md">
                Apprenez avec des projets concrets.
              </h3>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION DOMAINES -->
    <section id="catalogue" class="py-24 bg-slate-50">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <p class="text-green-600 font-bold uppercase tracking-[0.2em] text-xs mb-4">
            Nos formations
          </p>

          <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900">
            Choisissez votre parcours.
          </h2>

          <p class="mt-6 text-slate-500 text-lg leading-relaxed">
            Notre approche combine théorie, pratique et accompagnement.
          </p>
        </div>

        <!-- cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
          <div
            v-for="formation in formations"
            :key="formation.title"
            class="group bg-white border border-slate-200 rounded-[2rem] overflow-hidden hover:shadow-2xl hover:shadow-green-100/50 hover:-translate-y-2 transition-all duration-500"
          >
            <!-- image -->
            <div class="relative overflow-hidden h-60">
              <img
                :src="formation.image"
                :alt="formation.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
              />

              <div
                class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"
              ></div>

              <div
                class="absolute top-5 left-5 px-3 py-1 rounded-full bg-white/90 text-xs font-bold uppercase tracking-wider text-green-700"
              >
                {{ formation.level }}
              </div>
            </div>

            <!-- contenu -->
            <div class="p-8">
              <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-widest text-green-600 font-bold">
                  {{ formation.category }}
                </span>

                <span class="text-sm text-slate-400 font-medium">
                  {{ formation.duration }}
                </span>
              </div>

              <h3 class="text-2xl font-black text-slate-900 mb-4 leading-tight">
                {{ formation.title }}
              </h3>

              <p class="text-slate-500 leading-relaxed text-sm mb-6">
                {{ formation.description }}
              </p>

              <!-- compétences -->
              <div class="flex flex-wrap gap-2 mb-8">
                <span
                  v-for="skill in formation.skills"
                  :key="skill"
                  class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium"
                >
                  {{ skill }}
                </span>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <div class="text-sm text-slate-400">À partir de</div>
                  <div class="text-2xl font-black text-green-600">
                    {{ formation.price }}
                  </div>
                </div>

                <router-link
                  to="/inscription"
                  class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-slate-900 hover:bg-green-600 text-white font-semibold transition-all duration-300"
                >
                  S'inscrire
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION METHODE -->
    <section class="py-24 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-20 items-center">
          <!-- gauche -->
          <div>
            <p class="text-green-600 uppercase tracking-[0.2em] text-xs font-bold mb-4">
              Notre méthode
            </p>

            <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900 leading-tight">
              Une pédagogie moderne et immersive.
            </h2>

            <p class="mt-8 text-lg text-slate-500 leading-relaxed">
              Nos formations sont orientées terrain avec des projets réels, des exercices pratiques
              et un accompagnement personnalisé.
            </p>

            <div class="mt-12 space-y-8">
              <div class="flex gap-5">
                <div
                  class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center font-black text-xl flex-shrink-0"
                >
                  1
                </div>

                <div>
                  <h3 class="text-xl font-bold text-slate-900 mb-2">Cours pratiques</h3>
                  <p class="text-slate-500 leading-relaxed">
                    Travaillez sur des cas réels et développez des compétences directement
                    applicables.
                  </p>
                </div>
              </div>

              <div class="flex gap-5">
                <div
                  class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center font-black text-xl flex-shrink-0"
                >
                  2
                </div>

                <div>
                  <h3 class="text-xl font-bold text-slate-900 mb-2">Encadrement personnalisé</h3>
                  <p class="text-slate-500 leading-relaxed">
                    Nos formateurs vous accompagnent tout au long du parcours.
                  </p>
                </div>
              </div>

              <div class="flex gap-5">
                <div
                  class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center font-black text-xl flex-shrink-0"
                >
                  3
                </div>

                <div>
                  <h3 class="text-xl font-bold text-slate-900 mb-2">Certification finale</h3>
                  <p class="text-slate-500 leading-relaxed">
                    Obtenez une certification reconnue à la fin de votre formation.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- droite -->
          <div class="relative">
            <div class="grid grid-cols-2 gap-6">
              <div class="space-y-6 pt-16">
                <div class="rounded-[2rem] overflow-hidden shadow-xl">
                  <img
                    src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop"
                    class="w-full h-[280px] object-cover"
                    alt="Etudiants"
                  />
                </div>

                <div class="rounded-[2rem] bg-slate-900 p-8 text-white">
                  <div class="text-5xl font-black text-green-500 mb-3">95%</div>
                  <p class="text-white/70 leading-relaxed">
                    de nos apprenants recommandent nos formations.
                  </p>
                </div>
              </div>

              <div class="space-y-6">
                <div class="rounded-[2rem] bg-green-600 p-10 text-white">
                  <div class="text-sm uppercase tracking-[0.2em] text-white/70 mb-3">
                    Access Informatique
                  </div>

                  <h3 class="text-3xl font-black leading-tight mb-6">
                    Formez-vous avec des experts du terrain.
                  </h3>

                  <p class="text-white/80 leading-relaxed text-sm">
                    Nos intervenants sont des professionnels actifs dans leurs domaines.
                  </p>
                </div>

                <div class="rounded-[2rem] overflow-hidden shadow-xl">
                  <img
                    src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?q=80&w=1200&auto=format&fit=crop"
                    class="w-full h-[360px] object-cover"
                    alt="Formation digitale"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION FORMAT -->
    <section class="py-24 bg-slate-950 text-white relative overflow-hidden">
      <div
        class="absolute inset-0 opacity-20"
        style="
          background: radial-gradient(
            circle at center,
            rgba(34, 197, 94, 0.25) 0%,
            transparent 60%
          );
        "
      ></div>

      <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-20">
          <p class="text-green-400 uppercase tracking-[0.2em] text-xs font-bold mb-4">
            Flexibilité
          </p>

          <h2 class="text-4xl md:text-5xl font-black tracking-tight leading-tight">
            Des formats adaptés à tous.
          </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div
            class="bg-white/5 border border-white/10 rounded-[2rem] p-10 backdrop-blur-sm hover:border-green-500/40 transition-all duration-300"
          >
            <div
              class="w-16 h-16 rounded-2xl bg-green-500/10 text-green-400 flex items-center justify-center mb-8"
            >
              💻
            </div>

            <h3 class="text-2xl font-black mb-4">En ligne</h3>

            <p class="text-white/70 leading-relaxed">
              Suivez vos cours depuis n'importe où avec des contenus accessibles 24h/24.
            </p>
          </div>

          <div
            class="bg-white/5 border border-white/10 rounded-[2rem] p-10 backdrop-blur-sm hover:border-green-500/40 transition-all duration-300"
          >
            <div
              class="w-16 h-16 rounded-2xl bg-green-500/10 text-green-400 flex items-center justify-center mb-8"
            >
              🏫
            </div>

            <h3 class="text-2xl font-black mb-4">Présentiel</h3>

            <p class="text-white/70 leading-relaxed">
              Apprenez dans nos locaux avec un accompagnement direct de nos formateurs.
            </p>
          </div>

          <div
            class="bg-white/5 border border-white/10 rounded-[2rem] p-10 backdrop-blur-sm hover:border-green-500/40 transition-all duration-300"
          >
            <div
              class="w-16 h-16 rounded-2xl bg-green-500/10 text-green-400 flex items-center justify-center mb-8"
            >
              🏢
            </div>

            <h3 class="text-2xl font-black mb-4">Entreprise</h3>

            <p class="text-white/70 leading-relaxed">
              Des programmes sur mesure pour renforcer les compétences de vos équipes.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA FINAL -->
    <section class="py-24 bg-green-600 relative overflow-hidden">
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
        <h2 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-tight">
          Prêt à apprendre
          <br />
          les compétences du futur ?
        </h2>

        <p class="mt-8 text-xl text-green-100 leading-relaxed max-w-2xl mx-auto">
          Rejoignez Access Informatique et développez des compétences concrètes pour votre carrière.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-10">
          <router-link
            to="/inscription"
            class="inline-flex items-center justify-center px-10 py-4 rounded-2xl bg-white text-green-600 hover:bg-slate-100 font-black transition-all duration-300 hover:-translate-y-1"
          >
            Demander une inscription
          </router-link>

          <router-link
            to="/solutions"
            class="inline-flex items-center justify-center px-10 py-4 rounded-2xl border border-white/30 hover:border-white text-white font-bold transition-all duration-300"
          >
            Voir nos solutions
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
const formations = [
  {
    title: 'Développement Web Full Stack',
    category: 'Développement',
    level: 'Débutant à avancé',
    duration: '6 mois',
    price: '150 000 FCFA',
    image:
      'https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1200&auto=format&fit=crop',
    description:
      'Apprenez HTML, CSS, JavaScript, Vue.js, Node.js et les bases de données avec des projets concrets.',
    skills: ['HTML', 'Vue.js', 'Tailwind', 'API'],
  },

  {
    title: 'Bureautique Professionnelle',
    category: 'Productivité',
    level: 'Tous niveaux',
    duration: '2 mois',
    price: '60 000 FCFA',
    image:
      'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=1200&auto=format&fit=crop',
    description: 'Maîtrisez Word, Excel, PowerPoint et les outils collaboratifs modernes.',
    skills: ['Word', 'Excel', 'PowerPoint', 'Google Workspace'],
  },

  {
    title: 'Design Graphique & UI/UX',
    category: 'Design',
    level: 'Intermédiaire',
    duration: '4 mois',
    price: '120 000 FCFA',
    image:
      'https://images.unsplash.com/photo-1558655146-9f40138edfeb?q=80&w=1200&auto=format&fit=crop',
    description: 'Créez des interfaces modernes avec Figma, Photoshop et les principes UX/UI.',
    skills: ['Figma', 'Photoshop', 'UI Design', 'UX'],
  },

  {
    title: 'Cybersécurité & Réseaux',
    category: 'Sécurité',
    level: 'Avancé',
    duration: '5 mois',
    price: '180 000 FCFA',
    image:
      'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop',
    description:
      'Découvrez les fondamentaux de la sécurité informatique et de la protection des réseaux.',
    skills: ['Sécurité', 'Linux', 'Réseaux', 'Firewall'],
  },

  {
    title: 'Intelligence Artificielle',
    category: 'IA',
    level: 'Intermédiaire',
    duration: '3 mois',
    price: '200 000 FCFA',
    image:
      'https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=1200&auto=format&fit=crop',
    description:
      'Initiez-vous au Machine Learning, aux IA génératives et aux outils d’automatisation modernes.',
    skills: ['Python', 'Machine Learning', 'ChatGPT', 'Data'],
  },

  {
    title: 'Marketing Digital',
    category: 'Business',
    level: 'Tous niveaux',
    duration: '2 mois',
    price: '80 000 FCFA',
    image:
      'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1200&auto=format&fit=crop',
    description:
      'Développez votre visibilité avec les réseaux sociaux, la publicité et le branding.',
    skills: ['Facebook Ads', 'SEO', 'Canva', 'Branding'],
  },
]
</script>

<style scoped>
.hero-grid {
  background-image:
    linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
  background-size: 80px 80px;
}

@media (max-width: 768px) {
  .hero-grid {
    background-size: 40px 40px;
  }
}
</style>
