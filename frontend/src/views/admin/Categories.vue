<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const authStore = useAuthStore()
const categories = ref([])
const loading = ref(true)
const showModal = ref(false)
const editingCategory = ref(null)
const form = ref({ name: '', icon: '' })

onMounted(async () => {
  if (!authStore.isAuthenticated) return router.push('/portal')
  await fetchData()
})

async function fetchData() {
  loading.value = true
  try {
    const { data } = await api.get('/categories')
    categories.value = data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editingCategory.value = null
  form.value = { name: '', icon: '' }
  showModal.value = true
}

function openEdit(category) {
  editingCategory.value = category
  form.value = { name: category.name, icon: category.icon || '' }
  showModal.value = true
}

async function save() {
  try {
    if (editingCategory.value) {
      await api.put(`/categories/${editingCategory.value.id}`, form.value)
    } else {
      await api.post('/categories', form.value)
    }
    showModal.value = false
    await fetchData()
  } catch (e) {
    alert('Gagal menyimpan kategori')
  }
}

async function deleteCategory(id) {
  if (!confirm('Hapus kategori ini? Semua produk di dalamnya juga akan dihapus.')) return
  try {
    await api.delete(`/categories/${id}`)
    await fetchData()
  } catch (e) {
    alert('Gagal menghapus kategori')
  }
}
</script>

<template>
  <div class="min-h-screen bg-background flex">
    <aside class="w-64 bg-surface border-r border-border min-h-screen p-6 hidden md:block">
      <h2 class="text-xl font-heading font-bold text-white mb-8">
        Japran<span class="text-primary">MCEH</span>
      </h2>
      <nav class="space-y-2">
        <router-link to="/admin" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">Dashboard</router-link>
        <router-link to="/admin/products" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">Produk</router-link>
        <router-link to="/admin/categories" class="block px-4 py-3 rounded-xl bg-primary/10 text-primary font-medium text-sm">Kategori</router-link>
        <router-link to="/admin/testimonials" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">Testimoni</router-link>
        <router-link to="/admin/settings" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">Pengaturan</router-link>
      </nav>
    </aside>

    <div class="flex-1 p-6 md:p-8">
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-heading font-bold text-white">Kategori</h1>
        <button @click="openCreate" class="px-5 py-2.5 bg-primary hover:bg-secondary text-white rounded-xl text-sm font-semibold transition-all">+ Tambah Kategori</button>
      </div>

      <div v-if="loading">
        <div v-for="i in 3" :key="i" class="h-16 bg-card rounded-2xl mb-3 animate-pulse" />
      </div>

      <div v-else class="space-y-3">
        <div v-for="cat in categories" :key="cat.id" class="bg-card border border-border rounded-2xl p-5 flex items-center justify-between hover:border-primary/30 transition-all">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-surface flex items-center justify-center text-primary font-bold text-lg">{{ cat.icon || cat.name.charAt(0) }}</div>
            <div>
              <h3 class="text-white font-medium">{{ cat.name }}</h3>
              <p class="text-text-secondary text-sm">{{ cat.products_count }} produk</p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <button @click="openEdit(cat)" class="px-3 py-1.5 bg-accent/10 text-accent rounded-lg text-xs font-medium hover:bg-accent/20">Edit</button>
            <button @click="deleteCategory(cat.id)" class="px-3 py-1.5 bg-red-500/10 text-red-400 rounded-lg text-xs font-medium hover:bg-red-500/20">Hapus</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="showModal = false">
      <div class="bg-card border border-border rounded-2xl p-6 w-full max-w-md">
        <h2 class="text-xl font-heading font-bold text-white mb-6">{{ editingCategory ? 'Edit Kategori' : 'Tambah Kategori' }}</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm text-text-secondary mb-1">Nama Kategori</label>
            <input v-model="form.name" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Icon (emoji atau teks)</label>
            <input v-model="form.icon" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div class="flex gap-3 pt-4">
            <button @click="showModal = false" class="flex-1 px-4 py-2.5 bg-surface text-text-secondary rounded-xl text-sm font-medium hover:text-white">Batal</button>
            <button @click="save" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-secondary">Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
