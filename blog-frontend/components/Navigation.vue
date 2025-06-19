<template>
    <nav class="bg-gray-50 border-b border-gray-200 px-6 py-3">
        <div class="flex gap-6">
            <NuxtLink
                to="/BlogPosts"
                class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors"
                :class="{ 'text-gray-900 font-semibold': route.path === '/BlogPosts' }"
            >
                Plain Table
            </NuxtLink>
            <NuxtLink
                to="/BlogPostsUi"
                class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors"
                :class="{ 'text-gray-900 font-semibold': route.path === '/BlogPostsUi' }"
            >
                UI Table
            </NuxtLink>
            <NuxtLink
                to="/categories"
                class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors"
                :class="{ 'text-gray-900 font-semibold': route.path === '/categories' }"
            >
                Сategories
            </NuxtLink>
        </div>
    </nav>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
const route = useRoute()
</script>
