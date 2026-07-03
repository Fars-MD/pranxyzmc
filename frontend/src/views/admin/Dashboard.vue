<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const authStore = useAuthStore()
const stats = ref({ products: 0, testimonials: 0, customers: 0 })

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/portal')
    return
  }
  try {
    const [productsRes, testimonialsRes] = await Promise.all([
      api.get('/products?per_page=1'),
      api.get('/testimonials'),
    ])
    stats.value.products = productsRes.data.total || 0
    stats.value.testimonials = testimonialsRes.data.length || 0
  } catch (e) {
    console.error(e)
  }
})

function handleLogout() {
  authStore.logout()
  router.push('/portal')
}
</script>

<template>
  <div class="min-h-screen bg-background flex">
    <aside class="w-64 bg-surface border-r border-border min-h-screen p-6 hidden md:block">
      <h2 class="text-xl font-heading font-bold text-white mb-8">
        Japran<span class="text-primary">MCEH</span>
      </h2>
      <nav class="space-y-2">
        <router-link to="/admin" class="block px-4 py-3 rounded-xl bg-primary/10 text-primary font-medium text-sm">
          Dashboard
        </router-link>
        <router-link to="/admin/products" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">
          Produk
        </router-link>
        <router-link to="/admin/categories" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">
          Kategori
        </router-link>
        <router-link to="/admin/testimonials" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">
          Testimoni
        </router-link>
        <router-link to="/admin/settings" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">
          Pengaturan
        </router-link>
      </nav>
      <div class="absolute bottom-6 left-6 right-6">
        <button
          @click="handleLogout"
          class="w-full px-4 py-3 bg-red-500/10 text-red-400 rounded-xl text-sm font-medium hover:bg-red-500/20 transition-colors"
        >
          Logout
        </button>
      </div>
    </aside>

    <div class="flex-1 p-6 md:p-8">
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-heading font-bold text-white">Dashboard</h1>
        <div class="md:hidden">
          <button @click="handleLogout" class="px-4 py-2 bg-red-500/10 text-red-400 rounded-xl text-sm">Logout</button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-card border border-border rounded-2xl p-6">
          <p class="text-text-secondary text-sm mb-2">Total Produk</p>
          <p class="text-3xl font-heading font-bold text-primary">{{ stats.products }}</p>
        </div>
        <div class="bg-card border border-border rounded-2xl p-6">
          <p class="text-text-secondary text-sm mb-2">Total Testimoni</p>
          <p class="text-3xl font-heading font-bold text-accent">{{ stats.testimonials }}</p>
        </div>
        <div class="bg-card border border-border rounded-2xl p-6">
          <p class="text-text-secondary text-sm mb-2">Total Pelanggan</p>
          <p class="text-3xl font-heading font-bold text-green-400">{{ stats.customers }}</p>
        </div>
      </div>

      <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <router-link to="/admin/products" class="bg-card border border-border rounded-2xl p-6 hover:border-primary/50 transition-all group">
          <h3 class="text-white font-semibold mb-2 group-hover:text-primary transition-colors">Kelola Produk</h3>
          <p class="text-text-secondary text-sm">Tambah, edit, atau hapus produk</p>
        </router-link>
        <router-link to="/admin/categories" class="bg-card border border-border rounded-2xl p-6 hover:border-accent/50 transition-all group">
          <h3 class="text-white font-semibold mb-2 group-hover:text-accent transition-colors">Kelola Kategori</h3>
          <p class="text-text-secondary text-sm">Atur kategori produk</p>
        </router-link>
        <router-link to="/admin/testimonials" class="bg-card border border-border rounded-2xl p-6 hover:border-green-400/50 transition-all group">
          <h3 class="text-white font-semibold mb-2 group-hover:text-green-400 transition-colors">Kelola Testimoni</h3>
          <p class="text-text-secondary text-sm">Atur testimoni pelanggan</p>
        </router-link>
        <router-link to="/admin/settings" class="bg-card border border-border rounded-2xl p-6 hover:border-yellow-400/50 transition-all group">
          <h3 class="text-white font-semibold mb-2 group-hover:text-yellow-400 transition-colors">Pengaturan</h3>
          <p class="text-text-secondary text-sm">Konfigurasi website</p>
        </router-link>
      </div>
    </div>
  </div>
</template>
