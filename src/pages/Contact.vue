<template>
  <div class="contact-page bg-white min-h-screen">
    <!-- HERO  -->
    <section class="relative overflow-hidden bg-white pt-40 pb-28">
      <!-- Grid background -->
      <div class="absolute inset-0 hero-grid"></div>

      <!-- Halo lumineux central (vert) -->
      <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full pointer-events-none"
        style="background: radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%)"
        aria-hidden="true"
      ></div>

      <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
        <div
          class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-green-500/30 bg-green-500/10 text-green-600 text-xs font-semibold tracking-widest uppercase mb-8"
        >
          <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
          Contact & Assistance
        </div>

        <h1
          class="text-5xl md:text-7xl font-black text-slate-900 leading-[0.95] tracking-tight mb-6"
        >
          Parlons de<br />
          <span class="hero-gradient-green">votre projet.</span>
        </h1>

        <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
          Notre équipe vous accompagne dans la conception de vos solutions logicielles, applications
          métiers et systèmes de gestion.
        </p>
      </div>
    </section>

    <!-- SECTION CONTACT -->
    <section class="py-24 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-start">
          <!-- FORMULAIRE -->
          <div>
            <div class="mb-10">
              <p class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase mb-3">
                Envoyez-nous un message
              </p>

              <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                Contactez-nous
              </h2>

              <p class="text-slate-500 mt-4 text-lg leading-relaxed">
                Une question, un besoin spécifique ou un projet ? Remplissez le formulaire et notre
                équipe vous répondra rapidement.
              </p>
            </div>

            <!-- FORM -->
            <form
              @submit.prevent="submitForm"
              class="space-y-6 bg-white border border-slate-100 rounded-3xl p-8 shadow-xl shadow-slate-100"
            >
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2"> Nom complet </label>
                <input
                  v-model="form.name"
                  type="text"
                  placeholder="Votre nom complet"
                  class="w-full px-5 py-4 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                  Adresse email
                </label>
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="exemple@email.com"
                  class="w-full px-5 py-4 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2"> Téléphone </label>
                <input
                  v-model="form.phone"
                  type="tel"
                  placeholder="+225 01 01 57 30 54"
                  class="w-full px-5 py-4 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2"> Sujet </label>
                <input
                  v-model="form.subject"
                  type="text"
                  placeholder="Objet du message"
                  class="w-full px-5 py-4 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2"> Message </label>
                <textarea
                  v-model="form.message"
                  rows="6"
                  placeholder="Décrivez votre besoin..."
                  class="w-full px-5 py-4 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 resize-none"
                  required
                ></textarea>
              </div>

              <button
                type="submit"
                :disabled="loading"
                class="w-full inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-green-600 hover:bg-green-500 text-white font-bold transition-all duration-200 shadow-lg shadow-green-600/20"
              >
                <span v-if="!loading">Envoyer le message</span>
                <span v-else>Envoi en cours...</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                  />
                </svg>
              </button>
            </form>
          </div>

          <!-- INFOS (modifié : vert au lieu de bleu, fond blanc avec bordure) -->
          <div class="space-y-8">
            <!-- Coordonnées (modifié : fond blanc, bordure verte) -->
            <div
              class="bg-white rounded-3xl p-10 text-slate-800 relative overflow-hidden border border-green-100 shadow-xl"
            >
              <div
                class="absolute top-0 right-0 w-72 h-72 rounded-full opacity-20"
                style="
                  background: radial-gradient(circle, rgba(34, 197, 94, 0.3) 0%, transparent 70%);
                "
              ></div>

              <div class="relative z-10">
                <p class="text-xs font-bold tracking-[0.2em] text-green-600 uppercase mb-3">
                  Coordonnées
                </p>

                <h3 class="text-3xl font-black text-slate-900 mb-8">Access Informatique</h3>

                <div class="space-y-6">
                  <div class="flex items-start gap-4">
                    <div
                      class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 text-xl"
                    >
                      📍
                    </div>
                    <div>
                      <p class="font-semibold text-slate-900 mb-1">Adresse</p>
                      <p class="text-slate-500 text-sm leading-relaxed">
                        Yopougon Sable, Andokoi<br />
                        Abidjan, Côte d'Ivoire
                      </p>
                    </div>
                  </div>

                  <div class="flex items-start gap-4">
                    <div
                      class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 text-xl"
                    >
                      ☎
                    </div>
                    <div>
                      <p class="font-semibold text-slate-900 mb-1">Téléphone</p>
                      <p class="text-slate-500 text-sm">(+225) 01 01 57 30 54</p>
                      <p class="text-slate-500 text-sm">(+225) 07 07 26 18 58</p>
                    </div>
                  </div>

                  <div class="flex items-start gap-4">
                    <div
                      class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 text-xl"
                    >
                      ✉
                    </div>
                    <div>
                      <p class="font-semibold text-slate-900 mb-1">Email</p>
                      <p class="text-slate-500 text-sm">info@accessinformatique.com</p>
                    </div>
                  </div>

                  <div class="flex items-start gap-4">
                    <div
                      class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 text-xl"
                    >
                      🕒
                    </div>
                    <div>
                      <p class="font-semibold text-slate-900 mb-1">Horaires</p>
                      <p class="text-slate-500 text-sm">Lun – Ven : 08h00 – 18h00</p>
                      <p class="text-slate-500 text-sm">Sam : 09h00 – 13h00</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- MAP (garde tel quel) -->
            <div
              class="rounded-3xl overflow-hidden border border-slate-200 shadow-xl shadow-slate-100 h-[420px]"
            >
              <iframe
                src="https://maps.google.com/maps?q=Yopougon%20Andokoi%20Abidjan&t=&z=13&ie=UTF8&iwloc=&output=embed"
                width="100%"
                height="100%"
                style="border: 0"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
              ></iframe>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'

const loading = ref(false)

const form = reactive({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: '',
})

const submitForm = async () => {
  loading.value = true

  try {
    console.log('Formulaire envoyé :', form)
    alert('Message envoyé avec succès.')

    form.name = ''
    form.email = ''
    form.phone = ''
    form.subject = ''
    form.message = ''
  } catch (error) {
    console.error(error)
    alert("Erreur lors de l'envoi du message.")
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* HERO GRID */
.hero-grid {
  background-image:
    linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
  background-size: 80px 80px;
}

/* TEXTE VERT (couleur bouton) */
.hero-gradient-green {
  color: #16a34a;
  font-weight: 700;
}
</style>
