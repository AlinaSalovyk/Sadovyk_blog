// nuxt.config.ts
import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
    devtools: { enabled: true },
    build: {
        transpile: ['vueuc', 'naive-ui']
    },
    vite: {
        optimizeDeps: {
            include: ['vueuc', 'naive-ui']
        }
    }
})
