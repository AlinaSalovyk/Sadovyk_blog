import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
    devtools: { enabled: true },
    css: ['@/assets/css/tailwind.css'],
    postcss: {
        plugins: {
            tailwindcss: {},
            autoprefixer: {},
        },
    },
    build: {
        transpile: ['vueuc', 'naive-ui'],
    },
    vite: {
        optimizeDeps: {
            include: ['vueuc', 'naive-ui'],
        },
    },
})
