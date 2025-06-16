<template>
    <nav class="p-4 bg-gray-100 flex gap-4">
        <NuxtLink
            to="/BlogPosts"
            class="text-blue-600 hover:underline"
            :class="{ 'font-bold': route.path === '/BlogPosts' }"
        >
            Plain Table
        </NuxtLink>
        <NuxtLink
            to="/BlogPostsUi"
            class="text-blue-600 hover:underline"
            :class="{ 'font-bold': route.path === '/BlogPostsUi' }"
        >
            UI Table
        </NuxtLink>
    </nav>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
const route = useRoute()
</script>

<style>

</style>
