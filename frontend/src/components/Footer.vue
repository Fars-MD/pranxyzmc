<script setup>
const year = new Date().getFullYear()
</script>

<template>
  <footer class="bg-surface border-t border-border py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-xl font-heading font-bold text-white">
          Japran<span class="text-primary">MCEH</span>
        </div>
        <div class="flex items-center gap-6">
          <a
            v-for="social in [
              { name: 'Discord', url: '#', icon: 'M' },
              { name: 'YouTube', url: '#', icon: 'Y' },
              { name: 'TikTok', url: '#', icon: 'T' },
              { name: 'Instagram', url: '#', icon: 'I' },
            ]"
            :key="social.name"
            :href="social.url"
            target="_blank"
            rel="noopener noreferrer"
            class="w-10 h-10 rounded-xl bg-card border border-border flex items-center justify-center text-text-secondary hover:text-accent hover:border-accent transition-all duration-300 hover:scale-110"
            :title="social.name"
          >
            <span class="text-sm font-bold">{{ social.icon }}</span>
          </a>
        </div>
        <p class="text-text-secondary text-sm">
          &copy; {{ year }} JapranMCEH. All rights reserved.
        </p>
      </div>
    </div>
  </footer>
</template>
