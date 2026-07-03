<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/services/api'
import ProductCard from '@/components/ProductCard.vue'

const route = useRoute()
const router = useRouter()

const products = ref([])
const categories = ref([])
const loading = ref(true)
const search = ref(route.query.search || '')
const selectedCategory = ref(route.query.category_id || '')
const sort = ref(route.query.sort || 'newest')
const meta = ref({})

async function fetchProducts() {
  loading.value = true
  try {
    const params = { per_page: 12 }
    if (search.value) params.search = search.value
    if (selectedCategory.value) params.category_id = selectedCategory.value
    if (sort.value) params.sort = sort.value

    const [productsRes, categoriesRes] = await Promise.all([
      api.get('/products', { params }),
      api.get('/categories'),
    ])

    products.value = productsRes.data.data
    meta.value = {
      current_page: productsRes.data.current_page,
      last_page: productsRes.data.last_page,
      total: productsRes.data.total,
    }
    categories.value = categoriesRes.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

function applyFilters() {
  router.replace({
    query: {
      ...(search.value && { search: search.value }),
      ...(selectedCategory.value && { category_id: selectedCategory.value }),
      ...(sort.value && { sort: sort.value }),
    }
  })
  fetchProducts()
}

onMounted(fetchProducts)
</script>

<template>
  <div class="min-h-screen pt-24 md:pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-3">
          Semua Produk
        </h1>
        <p class="text-text-secondary text-lg">
          Temukan produk Minecraft terbaik untukmu
        </p>
      </div>

      <div class="flex flex-col md:flex-row gap-4 mb-8">
        <div class="flex-1">
          <input
            v-model="search"
            type="text"
            placeholder="Cari produk..."
            @input="applyFilters"
            class="w-full px-5 py-3 bg-card border border-border rounded-xl text-white placeholder-text-secondary focus:outline-none focus:border-primary transition-colors"
          />
        </div>
        <select
          v-model="selectedCategory"
          @change="applyFilters"
          class="px-5 py-3 bg-card border border-border rounded-xl text-white focus:outline-none focus:border-primary transition-colors"
        >
          <option value="">Semua Kategori</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">
            {{ cat.name }}
          </option>
        </select>
        <select
          v-model="sort"
          @change="applyFilters"
          class="px-5 py-3 bg-card border border-border rounded-xl text-white focus:outline-none focus:border-primary transition-colors"
        >
          <option value="newest">Terbaru</option>
          <option value="oldest">Terlama</option>
          <option value="price_asc">Termurah</option>
          <option value="price_desc">Termahal</option>
        </select>
      </div>

      <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="i in 8" :key="i" class="bg-card border border-border rounded-2xl overflow-hidden animate-pulse">
          <div class="aspect-[4/3] bg-surface" />
          <div class="p-5 space-y-3">
            <div class="h-3 bg-surface rounded w-1/3" />
            <div class="h-5 bg-surface rounded w-2/3" />
            <div class="h-6 bg-surface rounded w-1/2" />
          </div>
        </div>
      </div>

      <div v-else-if="!products.length" class="text-center py-20">
        <p class="text-text-secondary text-lg">Produk tidak ditemukan</p>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <ProductCard v-for="product in products" :key="product.id" :product="product" />
      </div>

      <div v-if="meta.last_page > 1" class="flex justify-center gap-2 mt-10">
        <button
          v-for="page in meta.last_page"
          :key="page"
          @click="selectedCategory = page; fetchProducts()"
          :class="[
            'px-4 py-2 rounded-xl text-sm font-medium transition-all',
            meta.current_page === page
              ? 'bg-primary text-white'
              : 'bg-card text-text-secondary hover:text-white border border-border'
          ]"
        >
          {{ page }}
        </button>
      </div>
    </div>
  </div>
</template>
