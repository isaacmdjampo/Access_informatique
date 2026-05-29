<template>
  <div class="hackathon-page bg-white text-slate-900 overflow-hidden">
    <!-- HERO -->
    <section class="relative min-h-screen flex items-center overflow-hidden bg-white">
      <!-- GRID -->
      <div class="hero-grid absolute inset-0 opacity-40"></div>

      <!-- GLOW -->
      <div
        class="absolute top-0 left-0 w-[600px] h-[600px] rounded-full blur-3xl opacity-20"
        style="background: radial-gradient(circle, rgba(34, 197, 94, 0.25) 0%, transparent 70%)"
      ></div>

      <div
        class="absolute bottom-0 right-0 w-[500px] h-[500px] rounded-full blur-3xl opacity-20"
        style="background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, transparent 70%)"
      ></div>

      <div
        class="relative z-10 max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center pt-40 pb-28"
      >
        <!-- LEFT -->
        <div>
          <!-- BADGE -->
          <div
            class="inline-flex items-center gap-3 px-5 py-3 rounded-full border border-green-200 bg-green-50 text-green-700 text-xs font-bold uppercase tracking-[0.2em] mb-8"
          >
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            Hackathon Access Informatique
          </div>

          <!-- TITLE -->
          <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tight leading-[0.95]">
            Construisons les
            <span class="text-green-600">solutions numériques</span>
            de demain.
          </h1>

          <!-- DESCRIPTION -->
          <p class="mt-8 text-xl text-slate-500 leading-relaxed max-w-2xl">
            Rejoignez développeurs, designers, étudiants et innovateurs durant 48h de création
            intensive autour des technologies du futur.
          </p>

          <!-- CTA -->
          <div class="flex flex-col sm:flex-row gap-5 mt-10">
            <router-link
              to="/inscription"
              class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-green-600 hover:bg-green-500 text-white font-black transition-all duration-300 hover:-translate-y-1 shadow-2xl shadow-green-600/20"
            >
              Participer maintenant
            </router-link>

            <a
              href="#programme"
              class="inline-flex items-center justify-center px-8 py-4 rounded-2xl border border-slate-200 hover:border-green-500 text-slate-700 hover:text-green-600 bg-white font-semibold transition-all duration-300"
            >
              Voir le programme
            </a>
          </div>

          <!-- STATS -->
          <div
            class="grid grid-cols-2 md:grid-cols-4 gap-px overflow-hidden rounded-3xl bg-slate-100 border border-slate-200 mt-16 shadow-xl"
          >
            <div v-for="stat in hackathonStats" :key="stat.label" class="bg-white p-6 text-center">
              <div class="text-3xl font-black text-green-600">
                {{ stat.value }}
              </div>

              <div class="text-xs uppercase tracking-widest text-slate-500 mt-2">
                {{ stat.label }}
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT -->
        <div class="relative">
          <div class="absolute inset-0 rounded-[2rem] bg-green-500/10 blur-3xl scale-95"></div>

          <div
            class="relative rounded-[2rem] overflow-hidden border border-slate-200 shadow-2xl shadow-slate-200"
          >
            <img
              src="https://images.unsplash.com/photo-1515879218367-8466d910aaa4?q=80&w=1600&auto=format&fit=crop"
              alt="Hackathon"
              class="w-full h-[700px] object-cover"
            />

            <div
              class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/10 to-transparent"
            ></div>

            <div class="absolute bottom-0 left-0 p-10">
              <div class="text-sm uppercase tracking-[0.2em] text-green-300 mb-4">
                Innovation • IA • Startup • Tech
              </div>

              <h3 class="text-4xl font-black text-white leading-tight">
                48h d'innovation intensive.
              </h3>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- THEMES -->
    <section class="py-28 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-20">
          <p class="text-green-600 uppercase tracking-[0.2em] text-xs font-bold mb-4">
            Thématiques
          </p>

          <h2 class="text-4xl md:text-6xl font-black tracking-tight text-slate-900">
            Créez des projets à fort impact.
          </h2>

          <p class="mt-6 text-lg text-slate-500 leading-relaxed">
            Développez des solutions innovantes autour des grands enjeux numériques de demain.
          </p>
        </div>

        <!-- CARDS -->
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">
          <div
            v-for="theme in hackathonThemes"
            :key="theme.title"
            class="group bg-white border border-slate-200 rounded-[2rem] p-10 hover:-translate-y-2 hover:shadow-2xl hover:shadow-green-100 transition-all duration-500"
          >
            <div
              class="w-20 h-20 rounded-3xl bg-green-100 text-4xl flex items-center justify-center mb-8"
            >
              {{ theme.icon }}
            </div>

            <h3 class="text-2xl font-black text-slate-900 mb-4">
              {{ theme.title }}
            </h3>

            <p class="text-slate-500 leading-relaxed">
              {{ theme.description }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- TIMELINE -->
    <section id="programme" class="py-28 bg-slate-50">
      <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-20">
          <p class="text-green-600 uppercase tracking-[0.2em] text-xs font-bold mb-4">
            Déroulement
          </p>

          <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900">
            Le programme du Hackathon.
          </h2>
        </div>

        <div class="space-y-10">
          <div
            v-for="(step, index) in hackathonTimeline"
            :key="step.title"
            class="bg-white rounded-[2rem] border border-slate-200 p-10 flex flex-col md:flex-row gap-8 items-start hover:shadow-xl transition-all duration-300"
          >
            <div
              class="w-20 h-20 rounded-3xl bg-green-600 text-white flex items-center justify-center text-3xl font-black flex-shrink-0"
            >
              {{ index + 1 }}
            </div>

            <div>
              <div class="text-sm uppercase tracking-[0.2em] text-green-600 font-bold mb-3">
                {{ step.day }}
              </div>

              <h3 class="text-3xl font-black text-slate-900 mb-4">
                {{ step.title }}
              </h3>

              <p class="text-slate-500 leading-relaxed text-lg">
                {{ step.description }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- REWARDS -->
    <section class="py-28 bg-slate-900 text-white relative overflow-hidden">
      <div
        class="absolute inset-0 opacity-20"
        style="
          background: radial-gradient(circle at center, rgba(34, 197, 94, 0.2) 0%, transparent 70%);
        "
      ></div>

      <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="text-center mb-20">
          <p class="text-green-400 uppercase tracking-[0.2em] text-xs font-bold mb-4">
            Récompenses
          </p>

          <h2 class="text-4xl md:text-6xl font-black tracking-tight">
            Gagnez des prix exceptionnels.
          </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div
            v-for="reward in rewards"
            :key="reward.title"
            :class="
              reward.featured
                ? 'bg-green-600 scale-105 shadow-2xl shadow-green-600/20'
                : 'bg-white/5 border border-white/10'
            "
            class="rounded-[2rem] p-10 text-center backdrop-blur-sm"
          >
            <div class="text-6xl mb-6">
              {{ reward.emoji }}
            </div>

            <div class="text-2xl font-black mb-4">
              {{ reward.title }}
            </div>

            <div
              :class="reward.featured ? 'text-white' : 'text-green-400'"
              class="text-4xl font-black mb-4"
            >
              {{ reward.amount }}
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { hackathonStats, hackathonThemes, hackathonTimeline, rewards } from '../data/hackathon'
</script>

<style scoped>
.hero-grid {
  background-image:
    linear-gradient(rgba(15, 23, 42, 0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(15, 23, 42, 0.05) 1px, transparent 1px);

  background-size: 80px 80px;
}

@media (max-width: 768px) {
  .hero-grid {
    background-size: 40px 40px;
  }
}
</style>
