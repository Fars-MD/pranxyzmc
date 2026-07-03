<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  settings: { type: Object, default: null }
})

const particlesLoaded = ref(false)

onMounted(async () => {
  try {
    const { tsParticles } = await import('@tsparticles/engine')
    const { loadSlim } = await import('@tsparticles/slim')
    const { loadEmittersPlugin } = await import('@tsparticles/plugin-emitters')
    await loadSlim(tsParticles)
    await loadEmittersPlugin(tsParticles)
    particlesLoaded.value = true
  } catch (e) {
    console.log('tsParticles not loaded')
  }
})
</script>

<template>
  <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
    <div
      id="tsparticles"
      class="absolute inset-0 z-0"
    />

    <div class="absolute inset-0 bg-gradient-to-b from-primary/5 via-transparent to-background z-[1]" />

    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 text-accent text-sm font-medium mb-6 animate-fade-up">
        <span class="w-2 h-2 rounded-full bg-accent animate-pulse" />
        Premium Minecraft Store
      </div>

      <h1 class="text-4xl md:text-6xl lg:text-7xl font-heading font-bold text-white leading-tight mb-6 animate-fade-up">
        {{ settings?.hero_title || 'Premium Minecraft Store' }}
      </h1>

      <p class="text-lg md:text-xl text-text-secondary max-w-2xl mx-auto mb-10 animate-fade-up">
        {{ settings?.hero_subtitle || 'Top up Minecraft, Minecoins, Gift Card & Jasa Pembuatan Skin dengan harga terbaik dan proses cepat.' }}
      </p>

      <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-up">
        <router-link
          to="/products"
          class="px-8 py-4 bg-primary hover:bg-secondary text-white rounded-2xl font-semibold text-base transition-all duration-300 hover:shadow-lg hover:shadow-primary/30 hover:scale-105"
        >
          Lihat Produk
        </router-link>
        <a
          :href="`https://wa.me/${settings?.whatsapp_number || '6281234567890'}?text=Halo%20JapranMCEH%2C%20saya%20mau%20order`"
          target="_blank"
          rel="noopener noreferrer"
          class="px-8 py-4 bg-transparent border border-border hover:border-primary text-white rounded-2xl font-semibold text-base transition-all duration-300 hover:bg-primary/10"
        >
          Hubungi WhatsApp
        </a>
      </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-background to-transparent z-[1]" />
  </section>
</template>
