<template>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto p-6">
            <div v-if="error" class="text-red-600 text-center text-lg">
                {{ error }}
            </div>
            <div v-else-if="!post" class="text-center text-gray-500">
                Завантаження...
            </div>
            <div v-else class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                            <h2 class="text-sm font-medium text-gray-700">Опубліковано</h2>
                        </div>

                        <!-- Tabs -->
                        <div class="border-b border-gray-200">
                            <nav class="flex">
                                <button
                                    @click="activeTab = 'main'"
                                    :class="['px-6 py-3 text-sm font-medium border-r border-gray-200 relative', activeTab === 'main' ? 'text-gray-900 bg-white' : 'text-blue-600 hover:text-blue-800 transition-colors']">
                                    Основні дані
                                    <div v-if="activeTab === 'main'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></div>
                                </button>
                                <button
                                    @click="activeTab = 'extra'"
                                    :class="['px-6 py-3 text-sm font-medium relative', activeTab === 'extra' ? 'text-gray-900 bg-white' : 'text-blue-600 hover:text-blue-800 transition-colors']">
                                    Додаткові дані
                                    <div v-if="activeTab === 'extra'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600"></div>
                                </button>
                            </nav>
                        </div>

                        <!-- Tabs Content -->
                        <div class="p-6 space-y-6">
                            <div v-if="activeTab === 'main'">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Заголовок</label>
                                    <input
                                        type="text"
                                        :value="post.title"
                                        disabled
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Текст статті</label>
                                    <textarea
                                        :value="post.content_raw"
                                        disabled
                                        rows="12"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 resize-none focus:outline-none"
                                    ></textarea>
                                </div>
                            </div>
                            <div v-else>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Псевдонім (slug)</label>
                                    <input
                                        type="text"
                                        :value="post.slug"
                                        disabled
                                        class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-600"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Короткий текст (excerpt)</label>
                                    <textarea
                                        :value="post.excerpt"
                                        disabled
                                        rows="4"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-600 resize-none"
                                    ></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Категорія</label>
                                    <input
                                        type="text"
                                        :value="post.category ? post.category.title : `Категорія #${post.category_id}`"
                                        disabled
                                        class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-600"
                                    />
                                </div>
                                <div class="flex items-center gap-2 pt-2">
                                    <input type="checkbox" :checked="!!post.is_published" disabled />
                                    <label class="text-sm text-gray-700">Опубліковано</label>
                                </div>
                            </div>
                        </div>
                        <!-- Bottom Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg flex justify-between items-center">
                            <button class="text-blue-600 text-sm hover:underline">
                                Видалити
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Sidebar -->
                <div class="space-y-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-4">
                            <button class="w-full bg-blue-600 text-white px-4 py-2 text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                Зберегти
                            </button>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-gray-600">ID: </span>
                                <span class="font-medium">{{ post.id }}</span>
                            </div>
                            <div>
                                <div class="text-gray-600 font-medium mb-1">Створено</div>
                                <div class="text-gray-800">{{ formatDate(post.created_at) }}</div>
                            </div>
                            <div>
                                <div class="text-gray-600 font-medium mb-1">Змінено</div>
                                <div class="text-gray-800">{{ formatDate(post.updated_at) }}</div>
                            </div>
                            <div>
                                <div class="text-gray-600 font-medium mb-1">Опубліковано</div>
                                <div class="text-gray-800">{{ formatDate(post.published_at) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useRoute } from 'vue-router'
import { ref, onMounted } from 'vue'

const route = useRoute()
const postId = route.params.id
const post = ref<any>(null)
const error = ref<string | null>(null)
const activeTab = ref<'main' | 'extra'>('main')

onMounted(async () => {
    try {
        const response = await fetch(`http://localhost:8000/api/posts/${postId}`)
        if (!response.ok) throw new Error('Пост не знайдено')
        post.value = await response.json()
    } catch (err: any) {
        error.value = err.message
    }
})

function formatDate(date: string): string {
    return new Date(date).toLocaleString('uk-UA', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>
