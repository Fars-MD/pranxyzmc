<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const authStore = useAuthStore()
const products = ref([])
const categories = ref([])
const loading = ref(true)
const showModal = ref(false)
const editingProduct = ref(null)
const form = ref({ category_id: '', name: '', description: '', price: '', stock: '', image: '', featured: false, status: true })

onMounted(async () => {
  if (!authStore.isAuthenticated) return router.push('/portal')
  await fetchData()
})

async function fetchData() {
  loading.value = true
  try {
    const [productsRes, categoriesRes] = await Promise.all([
      api.get('/products?per_page=50'),
      api.get('/categories'),
    ])
    products.value = productsRes.data.data
    categories.value = categoriesRes.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editingProduct.value = null
  form.value = { category_id: '', name: '', description: '', price: '', stock: '', image: '', featured: false, status: true }
  showModal.value = true
}

function openEdit(product) {
  editingProduct.value = product
  form.value = { ...product }
  showModal.value = true
}

async function save() {
  try {
    if (editingProduct.value) {
      await api.put(`/products/${editingProduct.value.id}`, form.value)
    } else {
      await api.post('/products', form.value)
    }
    showModal.value = false
    await fetchData()
  } catch (e) {
    alert('Gagal menyimpan produk')
  }
}

async function deleteProduct(id) {
  if (!confirm('Hapus produk ini?')) return
  try {
    await api.delete(`/products/${id}`)
    await fetchData()
  } catch (e) {
    alert('Gagal menghapus produk')
  }
}

function formatPrice(price) {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price)
}
</script>

<template>
  <div class="min-h-screen bg-background flex">
    <aside class="w-64 bg-surface border-r border-border min-h-screen p-6 hidden md:block">
      <h2 class="text-xl font-heading font-bold text-white mb-8">
        Japran<span class="text-primary">MCEH</span>
      </h2>
      <nav class="space-y-2">
        <router-link to="/admin" class="block px-4 py-3 rounded-xl text-text-secondary hover:text-white hover:bg-card transition-colors text-sm">
          Dashboard
        </router-link>
        <router-link to="/admin/products" class="block px-4 py-3 rounded-xl bg-primary/10 text-primary font-medium text-sm">
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
    </aside>

    <div class="flex-1 p-6 md:p-8">
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-heading font-bold text-white">Produk</h1>
        <button @click="openCreate" class="px-5 py-2.5 bg-primary hover:bg-secondary text-white rounded-xl text-sm font-semibold transition-all">
          + Tambah Produk
        </button>
      </div>

      <div v-if="loading" class="space-y-4">
        <div v-for="i in 5" :key="i" class="h-16 bg-card rounded-2xl animate-pulse" />
      </div>

      <div v-else-if="!products.length" class="text-center py-20">
        <p class="text-text-secondary">Belum ada produk</p>
      </div>

      <div v-else class="space-y-3">
        <div v-for="product in products" :key="product.id" class="bg-card border border-border rounded-2xl p-5 flex items-center justify-between hover:border-primary/30 transition-all">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-surface flex items-center justify-center text-primary font-bold text-sm">
              {{ product.name.charAt(0) }}
            </div>
            <div>
              <h3 class="text-white font-medium">{{ product.name }}</h3>
              <p class="text-text-secondary text-sm">{{ product.category?.name }} • {{ formatPrice(product.price) }}</p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <button @click="openEdit(product)" class="px-3 py-1.5 bg-accent/10 text-accent rounded-lg text-xs font-medium hover:bg-accent/20 transition-colors">
              Edit
            </button>
            <button @click="deleteProduct(product.id)" class="px-3 py-1.5 bg-red-500/10 text-red-400 rounded-lg text-xs font-medium hover:bg-red-500/20 transition-colors">
              Hapus
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="showModal = false">
      <div class="bg-card border border-border rounded-2xl p-6 w-full max-w-lg max-h-[80vh] overflow-y-auto">
        <h2 class="text-xl font-heading font-bold text-white mb-6">{{ editingProduct ? 'Edit Produk' : 'Tambah Produk' }}</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm text-text-secondary mb-1">Kategori</label>
            <select v-model="form.category_id" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary">
              <option value="">Pilih kategori</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Nama Produk</label>
            <input v-model="form.name" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Harga</label>
            <input v-model="form.price" type="number" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Stok</label>
            <input v-model="form.stock" type="number" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">URL Gambar</label>
            <input v-model="form.image" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm text-text-secondary mb-1">Deskripsi</label>
            <textarea v-model="form.description" rows="3" class="w-full px-4 py-2.5 bg-surface border border-border rounded-xl text-white focus:outline-none focus:border-primary" />
          </div>
          <div class="flex items-center gap-6">
            <label class="flex items-center gap-2">
              <input v-model="form.featured" type="checkbox" class="rounded bg-surface border-border" />
              <span class="text-sm text-text-secondary">Featured</span>
            </label>
            <label class="flex items-center gap-2">
              <input v-model="form.status" type="checkbox" class="rounded bg-surface border-border" />
              <span class="text-sm text-text-secondary">Aktif</span>
            </label>
          </div>
          <div class="flex gap-3 pt-4">
            <button @click="showModal = false" class="flex-1 px-4 py-2.5 bg-surface text-text-secondary rounded-xl text-sm font-medium hover:text-white transition-colors">Batal</button>
            <button @click="save" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-secondary transition-all">Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
