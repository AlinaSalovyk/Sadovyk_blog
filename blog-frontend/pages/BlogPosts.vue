<template>
    <div class="p-4">
        <h1 class="text-3xl font-bold mb-6">Пости</h1>
        <table class="w-full border-collapse">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">#</th>
                <th class="p-2 border">Автор</th>
                <th class="p-2 border">Категорія</th>
                <th class="p-2 border">Заголовок</th>
                <th class="p-2 border">Дата публікації</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-50">
                <td class="p-2 border">{{ post.id }}</td>
                <td class="p-2 border">{{ post.user.name }}</td>
                <td class="p-2 border">{{ post.category.title }}</td>
                <td class="p-2 border">
                    <a :href="`/blog/posts/${post.id}`" class="text-blue-600 hover:underline">
                        {{ post.title }}
                    </a>
                </td>
                <td class="p-2 border">{{ post.published_at }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface Post {
    id: number
    title: string
    published_at: string | null
    user: { name: string }
    category: { title: string }
}

const posts = ref<Post[]>([])

async function loadPosts() {
    const res = await $fetch('http://localhost:8000/api/blog/posts?per_page=100')
    posts.value = res.data
}

onMounted(loadPosts)
</script>
