<template>
    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">Категорії</h1>
        <div class="mb-4">
            <button @click="showAddForm = !showAddForm"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-2">
                {{ showAddForm ? "Сховати форму" : "Додати категорію" }}
            </button>
        </div>
        <form v-if="showAddForm" @submit.prevent="addCategory" class="mb-6 bg-gray-50 p-4 rounded shadow">
            <div class="mb-2">
                <input v-model="form.title" type="text" placeholder="Назва" class="border p-2 rounded w-full"/>
            </div>
            <div class="mb-2">
                <input v-model="form.slug" type="text" placeholder="Slug" class="border p-2 rounded w-full"/>
            </div>
            <div class="mb-2">
                <input v-model="form.parent_id" type="number" placeholder="ID батьківської категорії" class="border p-2 rounded w-full"/>
            </div>
            <div class="mb-2">
                <textarea v-model="form.description" placeholder="Опис" class="border p-2 rounded w-full"/>
            </div>
            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Зберегти</button>
        </form>
        <table class="w-full border-collapse bg-white shadow">
            <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Назва</th>
                <th class="p-2 border">Slug</th>
                <th class="p-2 border">Parent ID</th>
                <th class="p-2 border">Опис</th>
                <th class="p-2 border">Дії</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="cat in categories" :key="cat.id">
                <td class="border p-2">{{ cat.id }}</td>
                <td class="border p-2">{{ cat.title }}</td>
                <td class="border p-2">{{ cat.slug }}</td>
                <td class="border p-2">{{ cat.parent_id }}</td>
                <td class="border p-2">{{ cat.description }}</td>
                <td class="border p-2">
                    <button class="text-yellow-600 hover:underline mr-2"
                            @click="startEdit(cat)">Редагувати</button>
                    <button class="text-red-600 hover:underline"
                            @click="deleteCategory(cat.id)">Видалити</button>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- Модальне вікно редагування -->
        <div v-if="showEditForm" class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white p-6 rounded shadow max-w-md w-full">
                <h2 class="text-lg font-bold mb-4">Редагувати категорію</h2>
                <form @submit.prevent="saveEdit">
                    <div class="mb-2">
                        <input v-model="editForm.title" type="text" placeholder="Назва" class="border p-2 rounded w-full"/>
                    </div>
                    <div class="mb-2">
                        <input v-model="editForm.slug" type="text" placeholder="Slug" class="border p-2 rounded w-full"/>
                    </div>
                    <div class="mb-2">
                        <input v-model="editForm.parent_id" type="number" placeholder="ID батьківської категорії" class="border p-2 rounded w-full"/>
                    </div>
                    <div class="mb-2">
                        <textarea v-model="editForm.description" placeholder="Опис" class="border p-2 rounded w-full"/>
                    </div>
                    <div class="flex gap-2">
                        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Зберегти</button>
                        <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400"
                                @click="showEditForm = false">Скасувати</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const categories = ref<any[]>([])
const showAddForm = ref(false)
const showEditForm = ref(false)
const form = ref({ title: '', slug: '', parent_id: null, description: '' })
const editForm = ref({ id: null, title: '', slug: '', parent_id: null, description: '' })

async function loadCategories() {
    categories.value = await $fetch('http://localhost:8000/api/blog/categories')
}
onMounted(loadCategories)

async function addCategory() {
    await $fetch('http://localhost:8000/api/blog/categories', {
        method: 'POST',
        body: form.value,
    })
    form.value = { title: '', slug: '', parent_id: null, description: '' }
    showAddForm.value = false
    await loadCategories()
}

function startEdit(cat: any) {
    editForm.value = { ...cat }
    showEditForm.value = true
}

async function saveEdit() {
    await $fetch(`http://localhost:8000/api/blog/categories/${editForm.value.id}`, {
        method: 'PUT',
        body: editForm.value,
    })
    showEditForm.value = false
    await loadCategories()
}

async function deleteCategory(id: number) {
    if (!confirm('Видалити категорію?')) return
    await $fetch(`http://localhost:8000/api/blog/categories/${id}`, {
        method: 'DELETE',
    })
    await loadCategories()
}
</script>
