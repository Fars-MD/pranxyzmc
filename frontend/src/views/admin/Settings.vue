<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const authStore = useAuthStore()
const loading = ref(true)
const saving = ref(false)
const form = ref({
  website_name: 'JapranMCEH',
  hero_title: 'Premium Minecraft Store',
  hero_subtitle: '',
  whatsapp_number: '',
  discord_url: '',
  youtube_url: '',
  instagram_url: '',
  tiktok_url: '',
  logo: '',
  favicon: '',
})

onMounted(async () => {
  if (!authStore.isAuthenticated) return router.push('/portal')
  try {
    const { data } = await api.get('/settings')
    if (data) form.value = { ...form.value, ...data }
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

async function save() {
  saving.value = true
  try {
    await api.put('/settings', form.value)
    alert('Pengaturan berhasil disimpan')
  } catch (e) {
    alert('Gagal menyimpan pengaturan')
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-background flex">
    <aside class="w-64 bg-surface border-r border-border min-h-screen p-6 hidden md:block">
      <h2 class="text-xl font-heading font-bold text-white mb-8">Japran<span class="text-primary">MCEH</span></h2>
      <nav class="space-y-2">
        <router-link to="/admin" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">Dashboard</router-link>
        <router-link to="/admin/products" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">Produk</router-link>
        <router-link to="/admin/categories" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">Kategori</router-link>
        <router-link to="/admin/testimonials" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">Testimoni</router-link>
        <router-link to="/admin/settings" class="block px-4 py-3 rounded-xl bg-primary/10 text-primary font-medium text-sm">Pengaturan</router-link>
      </nav>
    </aside>

    <div class="flex-1 p-6 md:p-8">
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-heading font-bold text-white">Pengaturan Website</h1>
      </div>

      <div v-if="loading" class="space-y-4">
        <div v-for="i in 5" :key="i" class="h-14 bg-card rounded-2xl animate-pulse" />
      </div>

      <form v-else @submit.prevent="save" class="max-w-2xl space-y-5">
        <div class="bg-card border border-border rounded-2xl p-6 space-y-5">
          <h2 class="text-lg font-semibold text-white">Informasi Umum</h2>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Nama Website</label>
            <input v-model="form.website_name" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">URL Logo</label>
            <input v-model="form.logo" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">URL Favicon</label>
            <input v-model="form.favicon" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
        </div>

        <div class="bg-card border border-border rounded-2xl p-6 space-y-5">
          <h2 class="text-lg font-semibold text-white">Hero Section</h2>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Judul Hero</label>
            <input v-model="form.hero_title" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Subtitle Hero</label>
            <textarea v-model="form.hero_subtitle" rows="3" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
        </div>

        <div class="bg-card border border-border rounded-2xl p-6 space-y-5">
          <h2 class="text-lg font-semibold text-white">Kontak & Media Sosial</h2>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Nomor WhatsApp</label>
            <input v-model="form.whatsapp_number" placeholder="6281234567890" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Discord URL</label>
            <input v-model="form.discord_url" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">YouTube URL</label>
            <input v-model="form.youtube_url" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Instagram URL</label>
            <input v-model="form.instagram_url" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">TikTok URL</label>
            <input v-model="form.tiktok_url" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
        </div>

        <button type="submit" :disabled="saving" class="px-8 py-3 bg-primary hover:bg-secondary text-white rounded-xl font-semibold transition-all hover:shadow-lg hover:shadow-primary/25 disabled:opacity-50">
          {{ saving ? 'Menyimpan...' : 'Simpan Pengaturan' }}
        </button>
      </form>
    </div>
  </div>
</template>
