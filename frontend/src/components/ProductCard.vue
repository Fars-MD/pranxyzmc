<script setup>
defineProps({
  product: { type: Object, required: true }
})

function formatPrice(price) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(price)
}
</script>

<template>
  <router-link
    :to="`/products/${product.slug}`"
    class="group relative bg-card border border-border rounded-2xl overflow-hidden transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:shadow-primary/10 hover:border-primary/50"
  >
    <div class="relative aspect-[4/3] overflow-hidden bg-surface">
      <img
        v-if="product.image"
        :src="product.image"
        :alt="product.name"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
        loading="lazy"
      />
      <div v-else class="w-full h-full flex items-center justify-center">
        <span class="text-text-secondary text-4xl font-bold">MC</span>
      </div>
      <div class="absolute inset-0 bg-gradient-to-t from-card/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

      <div v-if="product.featured" class="absolute top-3 left-3">
        <span class="px-3 py-1 bg-primary text-white text-xs font-semibold rounded-full shadow-lg shadow-primary/30">
          Featured
        </span>
      </div>
    </div>

    <div class="p-5">
      <span v-if="product.category" class="text-xs text-accent font-medium">
        {{ product.category.name }}
      </span>
      <h3 class="text-white font-semibold text-lg mt-1 mb-2 line-clamp-2 group-hover:text-accent transition-colors">
        {{ product.name }}
      </h3>
      <div class="flex items-center justify-between">
        <span class="text-primary font-bold text-xl">
          {{ formatPrice(product.price) }}
        </span>
        <span
          :class="[
            'text-xs font-medium px-3 py-1 rounded-full',
            product.status ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400'
          ]"
        >
          {{ product.status ? 'Tersedia' : 'Habis' }}
        </span>
      </div>
    </div>
  </router-link>
</template>
