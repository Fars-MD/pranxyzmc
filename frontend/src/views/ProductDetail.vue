<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/services/api'
import ProductCard from '@/components/ProductCard.vue'

const route = useRoute()
const product = ref(null)
const related = ref([])
const loading = ref(true)

function formatPrice(price) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(price)
}

const whatsappMessage = computed(() => {
  if (!product.value) return ''
  return `Halo JapranMCEH, saya tertarik dengan produk *${product.value.name}* (${formatPrice(product.value.price)}). Apakah masih tersedia?`
})

const whatsappUrl = computed(() => {
  return `https://wa.me/6281234567890?text=${encodeURIComponent(whatsappMessage.value)}`
})

onMounted(async () => {
  try {
    const { data } = await api.get(`/products/${route.params.slug}`)
    product.value = data.product
    related.value = data.related
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="min-h-screen pt-24 md:pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div v-if="loading" class="animate-pulse">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="aspect-square bg-card rounded-2xl" />
          <div class="space-y-4">
            <div class="h-4 bg-card rounded w-1/4" />
            <div class="h-8 bg-card rounded w-3/4" />
            <div class="h-6 bg-card rounded w-1/3" />
            <div class="h-20 bg-card rounded" />
          </div>
        </div>
      </div>

      <div v-else-if="product" class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
        <div class="relative aspect-square rounded-2xl overflow-hidden bg-surface border border-border">
          <img
            v-if="product.image"
            :src="product.image"
            :alt="product.name"
            class="w-full h-full object-cover"
          />
          <div v-else class="w-full h-full flex items-center justify-center">
            <span class="text-text-secondary text-6xl font-bold">MC</span>
          </div>
        </div>

        <div>
          <span v-if="product.category" class="text-sm text-accent font-medium">
            {{ product.category.name }}
          </span>
          <h1 class="text-3xl md:text-4xl font-heading font-bold text-white mt-2 mb-4">
            {{ product.name }}
          </h1>

          <div class="text-3xl md:text-4xl font-bold text-primary mb-6">
            {{ formatPrice(product.price) }}
          </div>

          <div class="mb-6">
            <span
              :class="[
                'px-4 py-2 rounded-xl text-sm font-semibold',
                product.status
                  ? 'bg-green-500/10 text-green-400 border border-green-500/20'
                  : 'bg-red-500/10 text-red-400 border border-red-500/20'
              ]"
            >
              {{ product.status ? 'Tersedia' : 'Stok Habis' }}
            </span>
          </div>

          <p v-if="product.description" class="text-text-secondary leading-relaxed mb-8">
            {{ product.description }}
          </p>

          <a
            :href="whatsappUrl"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center gap-3 px-8 py-4 bg-green-500 hover:bg-green-600 text-white rounded-2xl font-semibold text-lg transition-all duration-300 hover:shadow-lg hover:shadow-green-500/25 hover:scale-105"
          >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Beli via WhatsApp
          </a>
        </div>
      </div>

      <div v-if="related.length" class="mt-20">
        <h2 class="text-2xl md:text-3xl font-heading font-bold text-white mb-8">
          Produk Terkait
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <ProductCard v-for="item in related" :key="item.id" :product="item" />
        </div>
      </div>
    </div>
  </div>
</template>
