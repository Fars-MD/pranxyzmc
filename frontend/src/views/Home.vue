<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import HeroSection from '@/components/HeroSection.vue'
import ProductCard from '@/components/ProductCard.vue'

const settings = ref(null)
const featuredProducts = ref([])
const testimonials = ref([])
const statistics = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const [settingsRes, productsRes, testimonialsRes] = await Promise.all([
      api.get('/settings'),
      api.get('/products?featured=true&per_page=8'),
      api.get('/testimonials'),
    ])
    settings.value = settingsRes.data
    featuredProducts.value = productsRes.data.data
    testimonials.value = testimonialsRes.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <HeroSection :settings="settings" />

  <section class="py-16 md:py-24 relative">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary/5 to-transparent" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
        <div
          v-for="stat in [
            { label: 'Produk', value: statistics?.total_product || '25+' },
            { label: 'Pelanggan', value: statistics?.total_customer || '150+' },
            { label: 'Transaksi', value: statistics?.total_transaction || '320+' },
            { label: 'Rating', value: '5.0' },
          ]"
          :key="stat.label"
          class="text-center p-6 md:p-8 bg-card/50 border border-border rounded-2xl hover:border-primary/30 transition-all duration-300"
        >
          <div class="text-3xl md:text-4xl font-heading font-bold text-primary mb-2">
            {{ stat.value }}
          </div>
          <div class="text-text-secondary text-sm font-medium">
            {{ stat.label }}
          </div>
        </div>
      </div>
    </div>
  </section>

  <section v-if="featuredProducts.length" class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-heading font-bold text-white mb-3">
          Produk Unggulan
        </h2>
        <p class="text-text-secondary text-lg">
          Produk terbaik pilihan untuk kamu
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <ProductCard
          v-for="product in featuredProducts"
          :key="product.id"
          :product="product"
        />
      </div>

      <div class="text-center mt-10">
        <router-link
          to="/products"
          class="inline-flex px-8 py-4 bg-primary hover:bg-secondary text-white rounded-2xl font-semibold transition-all duration-300 hover:shadow-lg hover:shadow-primary/25"
        >
          Lihat Semua Produk
        </router-link>
      </div>
    </div>
  </section>

  <section v-if="testimonials.length" class="py-16 md:py-24 bg-surface/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-heading font-bold text-white mb-3">
          Apa Kata Pelanggan
        </h2>
        <p class="text-text-secondary text-lg">
          Kepercayaan pelanggan adalah prioritas kami
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="testimonial in testimonials"
          :key="testimonial.id"
          class="bg-card border border-border rounded-2xl p-6 hover:border-primary/30 transition-all duration-300"
        >
          <div class="flex items-center gap-2 mb-4">
            <span
              v-for="i in 5"
              :key="i"
              :class="[
                'text-lg',
                i <= testimonial.rating ? 'text-yellow-400' : 'text-border'
              ]"
            >★</span>
          </div>
          <p class="text-text-secondary mb-4 leading-relaxed">
            "{{ testimonial.message }}"
          </p>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-semibold">
              {{ testimonial.name.charAt(0) }}
            </div>
            <span class="text-white font-medium text-sm">{{ testimonial.name }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-16 md:py-24">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-3xl md:text-4xl font-heading font-bold text-white mb-4">
        Siap Order?
      </h2>
      <p class="text-text-secondary text-lg mb-8">
        Hubungi kami langsung melalui WhatsApp untuk pemesanan cepat dan mudah
      </p>
      <a
        :href="`https://wa.me/${settings?.whatsapp_number || '6281234567890'}?text=Halo%20JapranMCEH%2C%20saya%20mau%20order`"
        target="_blank"
        rel="noopener noreferrer"
        class="inline-flex items-center gap-3 px-8 py-4 bg-green-500 hover:bg-green-600 text-white rounded-2xl font-semibold text-lg transition-all duration-300 hover:shadow-lg hover:shadow-green-500/25 hover:scale-105"
      >
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        Order via WhatsApp
      </a>
    </div>
  </section>
</template>
