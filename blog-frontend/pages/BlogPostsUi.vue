<template>
    <div class="p-4">
        <h1 class="text-3xl font-bold mb-6">Пости (Nuxt UI Table)</h1>
        <client-only placeholder="Завантажую таблицю...">
            <n-data-table
                :columns="columns"
                :data="posts"
                :loading="loading"
            />
            <n-pagination
                v-model:page="page"
                v-model:page-size="perPage"
                :item-count="total"
                :page-sizes="[10, 20, 50, 100]"
                show-size-picker
                @update:page="onPageChange"
                @update:page-size="onPageSizeChange"
            />

        </client-only>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, h } from 'vue'
import type { DataTableColumns } from 'naive-ui'

interface Post {
    id: number
    title: string
    published_at: string | null
    user: { name: string }
    category: { title: string }
}

const posts = ref<Post[]>([])
const loading = ref(false)
const page = ref(1)
const perPage = ref(10)
const total = ref(0)

const columns: DataTableColumns<Post> = [
    { title: '#', key: 'id', width: 60 },
    { title: 'Автор', key: 'user.name' },
    { title: 'Категорія', key: 'category.title' },
    {
        title: 'Заголовок',
        key: 'title',
        render(row) {
            return h(
                'a',
                {
                    href: `/admin/blog/posts/${row.id}/edit`,
                    class: 'text-blue-600 hover:underline'
                },
                row.title
            )
        }
    },
    { title: 'Дата публікації', key: 'published_at' }
]

const pagination = computed(() => ({
    page: page.value,
    pageSize: perPage.value,
    itemCount: total.value,
    showSizePicker: true,
    pageSizes: [10, 20, 50, 100],
}))

async function fetchPosts() {
    loading.value = true
    try {
        const res = await $fetch('http://localhost:8000/api/blog/posts', {
            params: {
                page: page.value,
                per_page: perPage.value
            }
        })
        posts.value = res.data
        total.value = res.total
        page.value = res.current_page
        perPage.value = Number(res.per_page)
    } finally {
        loading.value = false
    }
}

function onPageChange(newPage: number) {
    page.value = newPage
}
function onPageSizeChange(newSize: number) {
    perPage.value = newSize
    page.value = 1
}

onMounted(() => {
    fetchPosts()
})
watch([page, perPage], fetchPosts)
</script>
