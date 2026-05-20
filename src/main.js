import './assets/main.css'

import { createApp } from "vue";
import App from "./App.vue";

// === Router ===
import router from "./router";

// === Pinia ===
import { createPinia } from "pinia";

// === Axios ===
import axios from "axios";

// === Reservation Store ===
// import reservationStore from "./magasins/useReservation.js";

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
