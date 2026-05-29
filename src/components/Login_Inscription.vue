<template>
  <div class="inscription-page bg-white text-slate-900 overflow-hidden min-h-screen">
    <!-- HERO -->
    <section class="relative pt-36 pb-16 overflow-hidden bg-white">
      <div class="hero-grid absolute inset-0 pointer-events-none"></div>

      <div
        class="absolute -top-20 right-0 w-[500px] h-[500px] rounded-full blur-3xl opacity-20"
        style="background: radial-gradient(circle, rgba(34, 197, 94, 0.25) 0%, transparent 70%)"
      ></div>

      <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
        <div
          class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-green-500/20 bg-green-50 text-green-700 text-xs font-bold uppercase tracking-[0.2em] mb-8"
        >
          <span class="hero-badge w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
          Inscription — Access Informatique
        </div>

        <h1
          class="hero-title text-4xl md:text-6xl font-black tracking-tight leading-[0.95] text-slate-900"
        >
          Rejoignez nos
          <span class="text-green-600">formations.</span>
        </h1>

        <p class="hero-copy mt-6 text-lg text-slate-500 leading-relaxed max-w-xl mx-auto">
          Remplissez ce formulaire et notre équipe vous contactera dans les 24h pour confirmer votre
          inscription.
        </p>
      </div>
    </section>

    <!-- FORMULAIRE -->
    <section class="pb-32 bg-white">
      <div class="max-w-3xl mx-auto px-6">
        <!-- Card formulaire -->
        <div
          class="bg-white border border-slate-200 rounded-[2rem] shadow-2xl shadow-slate-950/5 overflow-hidden"
        >
          <!-- En-tête colorée -->
          <div class="bg-gradient-to-r from-green-600 to-green-500 p-8 text-white">
            <h2 class="text-2xl font-black mb-1">Formulaire d'inscription</h2>
            <p class="text-green-100 text-sm">Tous les champs marqués * sont obligatoires.</p>
          </div>

          <!-- Formulaire -->
          <div class="p-8 md:p-12 space-y-8">
            <!-- Infos personnelles -->
            <div>
              <h3
                class="text-xs font-bold uppercase tracking-[0.2em] text-green-600 mb-6 flex items-center gap-3"
              >
                <span
                  class="w-6 h-6 rounded-full bg-green-600 text-white text-xs flex items-center justify-center font-black"
                  >1</span
                >
                Informations personnelles
              </h3>

              <div class="grid md:grid-cols-2 gap-5">
                <div class="flex flex-col gap-2">
                  <label class="text-sm font-semibold text-slate-700">Prénom *</label>
                  <input
                    v-model="form.prenom"
                    type="text"
                    placeholder="Ex : Kouamé"
                    class="px-4 py-3 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none transition-all text-slate-800 placeholder-slate-400"
                  />
                </div>

                <div class="flex flex-col gap-2">
                  <label class="text-sm font-semibold text-slate-700">Nom *</label>
                  <input
                    v-model="form.nom"
                    type="text"
                    placeholder="Ex : Assouman"
                    class="px-4 py-3 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none transition-all text-slate-800 placeholder-slate-400"
                  />
                </div>

                <div class="flex flex-col gap-2">
                  <label class="text-sm font-semibold text-slate-700">Email *</label>
                  <input
                    v-model="form.email"
                    type="email"
                    placeholder="vous@exemple.com"
                    class="px-4 py-3 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none transition-all text-slate-800 placeholder-slate-400"
                  />
                </div>

                <div class="flex flex-col gap-2">
                  <label class="text-sm font-semibold text-slate-700">Téléphone *</label>
                  <input
                    v-model="form.telephone"
                    type="tel"
                    placeholder="+225 07 00 00 00 00"
                    class="px-4 py-3 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none transition-all text-slate-800 placeholder-slate-400"
                  />
                </div>

                <div class="flex flex-col gap-2 md:col-span-2">
                  <label class="text-sm font-semibold text-slate-700">Ville de résidence</label>
                  <input
                    v-model="form.ville"
                    type="text"
                    placeholder="Ex : Abidjan, Bouaké..."
                    class="px-4 py-3 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none transition-all text-slate-800 placeholder-slate-400"
                  />
                </div>
              </div>
            </div>

            <div class="border-t border-slate-100"></div>

            <!-- Formation choisie -->
            <div>
              <h3
                class="text-xs font-bold uppercase tracking-[0.2em] text-green-600 mb-6 flex items-center gap-3"
              >
                <span
                  class="w-6 h-6 rounded-full bg-green-600 text-white text-xs flex items-center justify-center font-black"
                  >2</span
                >
                Formation souhaitée
              </h3>

              <div class="grid md:grid-cols-2 gap-5">
                <div class="flex flex-col gap-2 md:col-span-2">
                  <label class="text-sm font-semibold text-slate-700"
                    >Choisissez une formation *</label
                  >
                  <select
                    v-model="form.formation"
                    class="px-4 py-3 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none transition-all text-slate-800 bg-white appearance-none cursor-pointer"
                  >
                    <option value="" disabled>Sélectionnez une formation</option>
                    <option value="dev-web">Développement Web Full Stack — 150 000 FCFA</option>
                    <option value="bureautique">Bureautique Professionnelle — 60 000 FCFA</option>
                    <option value="design">Design Graphique & UI/UX — 120 000 FCFA</option>
                    <option value="cybersecurite">Cybersécurité & Réseaux — 180 000 FCFA</option>
                    <option value="ia">Intelligence Artificielle — 200 000 FCFA</option>
                    <option value="marketing">Marketing Digital — 80 000 FCFA</option>
                  </select>
                </div>

                <div class="flex flex-col gap-2">
                  <label class="text-sm font-semibold text-slate-700">Format préféré</label>
                  <select
                    v-model="form.format"
                    class="px-4 py-3 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none transition-all text-slate-800 bg-white appearance-none cursor-pointer"
                  >
                    <option value="" disabled>Choisir un format</option>
                    <option value="presentiel">Présentiel</option>
                    <option value="enligne">En ligne</option>
                    <option value="hybride">Hybride</option>
                  </select>
                </div>

                <div class="flex flex-col gap-2">
                  <label class="text-sm font-semibold text-slate-700">Niveau actuel</label>
                  <select
                    v-model="form.niveau"
                    class="px-4 py-3 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none transition-all text-slate-800 bg-white appearance-none cursor-pointer"
                  >
                    <option value="" disabled>Votre niveau</option>
                    <option value="debutant">Débutant</option>
                    <option value="intermediaire">Intermédiaire</option>
                    <option value="avance">Avancé</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="border-t border-slate-100"></div>

            <!-- Message -->
            <div>
              <h3
                class="text-xs font-bold uppercase tracking-[0.2em] text-green-600 mb-6 flex items-center gap-3"
              >
                <span
                  class="w-6 h-6 rounded-full bg-green-600 text-white text-xs flex items-center justify-center font-black"
                  >3</span
                >
                Message (optionnel)
              </h3>

              <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-slate-700">Votre message</label>
                <textarea
                  v-model="form.message"
                  rows="4"
                  placeholder="Questions, disponibilités, informations supplémentaires..."
                  class="px-4 py-3 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/10 outline-none transition-all text-slate-800 placeholder-slate-400 resize-none"
                ></textarea>
              </div>
            </div>

            <!-- Bouton submit -->
            <div class="pt-2">
              <button
                @click="handleSubmit"
                :disabled="isSubmitting"
                class="w-full group inline-flex items-center justify-center gap-3 px-8 py-5 rounded-2xl bg-green-600 hover:bg-green-500 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-black text-lg transition-all duration-300 shadow-xl shadow-green-600/20 hover:-translate-y-1"
              >
                <span v-if="!isSubmitting">Envoyer ma demande d'inscription</span>
                <span v-else>Envoi en cours...</span>

                <svg
                  v-if="!isSubmitting"
                  class="w-5 h-5 group-hover:translate-x-1 transition-transform"
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
              </button>

              <p class="text-center text-xs text-slate-400 mt-4">
                En soumettant ce formulaire, vous acceptez d'être contacté par notre équipe.
              </p>
            </div>
          </div>
        </div>

        <!-- Message de succès -->
        <transition name="fade">
          <div
            v-if="submitted"
            class="mt-8 p-8 rounded-[2rem] bg-green-50 border border-green-200 text-center"
          >
            <div
              class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4"
            >
              <svg
                class="w-8 h-8 text-green-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2.5"
                  d="M5 13l4 4L19 7"
                />
              </svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-2">Demande envoyée !</h3>
            <p class="text-slate-500 leading-relaxed">
              Merci <strong>{{ form.prenom }}</strong> ! Notre équipe vous contactera dans les 24h à
              l'adresse <strong>{{ form.email }}</strong
              >.
            </p>
            <router-link
              to="/formation"
              class="inline-flex items-center gap-2 mt-6 px-6 py-3 rounded-xl border border-green-300 text-green-700 font-semibold hover:bg-green-100 transition-all"
            >
              ← Retour aux formations
            </router-link>
          </div>
        </transition>
      </div>
    </section>

    <!-- INFOS BAS DE PAGE -->
    <section class="py-16 bg-slate-50 border-t border-slate-100">
      <div class="max-w-3xl mx-auto px-6 grid md:grid-cols-3 gap-8 text-center">
        <div>
          <div class="text-3xl mb-3">📞</div>
          <h4 class="font-bold text-slate-900 mb-1">Besoin d'aide ?</h4>
          <p class="text-sm text-slate-500">Appelez-nous directement pour plus d'informations.</p>
        </div>
        <div>
          <div class="text-3xl mb-3">⚡</div>
          <h4 class="font-bold text-slate-900 mb-1">Réponse rapide</h4>
          <p class="text-sm text-slate-500">Notre équipe répond en moins de 24h.</p>
        </div>
        <div>
          <div class="text-3xl mb-3">🎓</div>
          <h4 class="font-bold text-slate-900 mb-1">Certification</h4>
          <p class="text-sm text-slate-500">Toutes nos formations sont certifiantes.</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'

const form = reactive({
  prenom: '',
  nom: '',
  email: '',
  telephone: '',
  ville: '',
  formation: '',
  format: '',
  niveau: '',
  message: '',
})

const isSubmitting = ref(false)
const submitted = ref(false)

// Forcer le scroll en haut au chargement de la page
onMounted(() => {
  window.scrollTo({ top: 0, behavior: 'instant' })
})

const handleSubmit = async () => {
  if (!form.prenom || !form.nom || !form.email || !form.telephone || !form.formation) {
    alert('Veuillez remplir tous les champs obligatoires (*).')
    return
  }

  isSubmitting.value = true

  // Simule un appel API (à remplacer par votre backend)
  await new Promise((resolve) => setTimeout(resolve, 1500))

  isSubmitting.value = false
  submitted.value = true

  // Scroll vers le message de succès
  setTimeout(() => {
    document.querySelector('.fade-enter-active')?.scrollIntoView({
      behavior: 'smooth',
      block: 'center',
    })
  }, 100)
}
</script>

<style scoped>
@keyframes fadeSlideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.hero-grid {
  background-image:
    linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
  background-size: 80px 80px;
}

.fade-enter-active,
.fade-leave-active {
  transition:
    opacity 0.4s ease,
    transform 0.4s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(12px);
}

.hero-badge {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.1s;
}
.hero-title {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.2s;
}
.hero-copy {
  animation: fadeSlideUp 0.6s ease both;
  animation-delay: 0.35s;
}

select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 14px center;
  padding-right: 40px;
}
</style>
