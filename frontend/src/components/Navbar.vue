<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)

let lastScroll = 0

function handleScroll() {
  isScrolled.value = window.scrollY > 50
  lastScroll = window.scrollY
}

onMounted(() => window.addEventListener('scroll', handleScroll))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))

const navLinks = [
  { name: 'Beranda', path: '/' },
  { name: 'Produk', path: '/products' },
]

function closeMobile() {
  isMobileMenuOpen.value = false
}
</script>

<template>
  <nav
    :class="[
      'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
      isScrolled
        ? 'bg-background/80 backdrop-blur-xl border-b border-border shadow-lg'
        : 'bg-transparent'
    ]"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 md:h-20">
        <router-link
          to="/"
          class="text-xl md:text-2xl font-heading font-bold text-white tracking-tight"
        >
          Japran<span class="text-primary">MCEH</span>
        </router-link>

        <div class="hidden md:flex items-center gap-8">
          <router-link
            v-for="link in navLinks"
            :key="link.name"
            :to="link.path"
            class="text-text-secondary hover:text-white transition-colors duration-200 text-sm font-medium"
          >
            {{ link.name }}
          </router-link>
          <a
            href="https://sociabuzz.com/japranmc_"
            target="_blank"
            rel="noopener noreferrer"
            class="px-5 py-2.5 bg-primary hover:bg-secondary text-white rounded-xl text-sm font-semibold transition-all duration-300 hover:shadow-lg hover:shadow-primary/25 hover:scale-105"
          >
            Bio
          </a>
        </div>

        <button
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          class="md:hidden text-white p-2"
        >
          <svg v-if="!isMobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    <div
      v-if="isMobileMenuOpen"
      class="md:hidden bg-surface border-t border-border animate-fade-up"
    >
      <div class="px-4 py-4 space-y-3">
        <router-link
          v-for="link in navLinks"
          :key="link.name"
          :to="link.path"
          @click="closeMobile"
          class="block text-text-secondary hover:text-white transition-colors py-2"
        >
          {{ link.name }}
        </router-link>
        <a
          href="https://sociabuzz.com/japranmc_"
          target="_blank"
          rel="noopener noreferrer"
          @click="closeMobile"
          class="block text-center px-5 py-2.5 bg-primary hover:bg-secondary text-white rounded-xl text-sm font-semibold transition-all"
        >
          Bio
        </a>
      </div>
    </div>
  </nav>
</template>
