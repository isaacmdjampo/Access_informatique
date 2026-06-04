import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

// https://vite.dev/config/
export default defineConfig(({ command }) => ({
  base: command === 'serve' ? '/' : '/Access_Informatique_2/',
  plugins: [
    vue(),
    tailwindcss(),
  ],

  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },

  server: {
    proxy: {
      '/api': {
        target: 'http://localhost/Access_Informatique/backend',
        changeOrigin: true,
        secure: false,

        // Réécriture des URLs avant envoi à Apache.
        //
        // Problème : Apache répond 301 pour les dossiers (/admin/contents →
        // /admin/contents/). Vite renvoie ce 301 au navigateur qui essaie de
        // suivre la redirection directement vers le port 80 → erreur CORS.
        //
        // Solution : ajouter .php à chaque endpoint pour atteindre directement
        // le fichier PHP (wrapper ou endpoint réel), sans passer par un dossier.
        //
        // Exceptions (gérées par les RewriteRules Apache) :
        //   - Routes avec slug  : /api/solutions/solumed
        //   - Route export CSV  : /api/admin/leads/export/contact
        rewrite: (path) => {
          const qi       = path.indexOf('?')
          const pathPart = qi >= 0 ? path.slice(0, qi) : path
          const query    = qi >= 0 ? path.slice(qi)    : ''

          // Déjà une extension → ne pas toucher
          if (/\.\w+$/.test(pathPart)) return path

          // Routes avec slug → Apache s'en charge via RewriteRule
          if (/^\/api\/(solutions|formations)\/[^/]+\/?$/.test(pathPart)) return path

          // Route export CSV → Apache s'en charge via RewriteRule
          if (/^\/api\/admin\/leads\/export\//.test(pathPart)) return path

          // Tout le reste → ajouter .php (endpoint nommé ou wrapper)
          return pathPart.replace(/\/?$/, '') + '.php' + query
        },
      }
    }
  }
}))
