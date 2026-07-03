<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const authStore = useAuthStore()
const testimonials = ref([])
const loading = ref(true)
const showModal = ref(false)
const editingTestimonial = ref(null)
const form = ref({ name: '', avatar: '', rating: 5, message: '' })

onMounted(async () => {
  if (!authStore.isAuthenticated) return router.push('/portal')
  await fetchData()
})

async function fetchData() {
  loading.value = true
  try {
    const { data } = await api.get('/testimonials')
    testimonials.value = data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editingTestimonial.value = null
  form.value = { name: '', avatar: '', rating: 5, message: '' }
  showModal.value = true
}

function openEdit(testimonial) {
  editingTestimonial.value = testimonial
  form.value = { ...testimonial }
  showModal.value = true
}

async function save() {
  try {
    if (editingTestimonial.value) {
      await api.put(`/testimonials/${editingTestimonial.value.id}`, form.value)
    } else {
      await api.post('/testimonials', form.value)
    }
    showModal.value = false
    await fetchData()
  } catch (e) {
    alert('Gagal menyimpan testimoni')
  }
}

async function deleteTestimonial(id) {
  if (!confirm('Hapus testimoni ini?')) return
  try {
    await api.delete(`/testimonials/${id}`)
    await fetchData()
  } catch (e) {
    alert('Gagal menghapus testimoni')
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
        <router-link to="/admin/testimonials" class="block px-4 py-3 rounded-xl bg-primary/10 text-primary font-medium text-sm">Testimoni</router-link>
        <router-link to="/admin/settings" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">Pengaturan</router-link>
      </nav>
    </aside>

    <div class="flex-1 p-6 md:p-8">
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-heading font-bold text-white">Testimoni</h1>
        <button @click="openCreate" class="px-5 py-2.5 bg-primary hover:bg-secondary text-white rounded-xl text-sm font-semibold transition-all">+ Tambah Testimoni</button>
      </div>

      <div v-if="loading">
        <div v-for="i in 3" :key="i" class="h-24 bg-card rounded-2xl mb-3 animate-pulse" />
      </div>

      <div v-else class="space-y-3">
        <div v-for="item in testimonials" :key="item.id" class="bg-card border border-border rounded-2xl p-5 hover:border-primary/30 transition-all">
          <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">{{ item.name.charAt(0) }}</div>
              <div>
                <h3 class="text-white font-medium">{{ item.name }}</h3>
                <div class="flex items-center gap-1 mt-1">
                  <span v-for="i in 5" :key="i" :class="['text-sm', i <= item.rating ? 'text-yellow-400' : 'text-border']">★</span>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button @click="openEdit(item)" class="px-3 py-1.5 bg-accent/10 text-accent rounded-lg text-xs font-medium hover:bg-accent/20">Edit</button>
              <button @click="deleteTestimonial(item.id)" class="px-3 py-1.5 bg-red-500/10 text-red-400 rounded-lg text-xs font-medium hover:bg-red-500/20">Hapus</button>
            </div>
          </div>
          <p class="text-text-secondary text-sm mt-3">"{{ item.message }}"</p>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="showModal = false">
      <div class="bg-card border border-border rounded-2xl p-6 w-full max-w-lg">
        <h2 class="text-xl font-heading font-bold text-white mb-6">{{ editingTestimonial ? 'Edit Testimoni' : 'Tambah Testimoni' }}</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm text-text-secondary mb-1">Nama</label>
            <input v-model="form.name" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Rating (1-5)</label>
            <select v-model.number="form.rating" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary">
              <option :value="5">★★★★★</option>
              <option :value="4">★★★★☆</option>
              <option :value="3">★★★☆☆</option>
              <option :value="2">★★☆☆☆</option>
              <option :value="1">★☆☆☆☆</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Pesan</label>
            <textarea v-model="form.message" rows="3" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
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
