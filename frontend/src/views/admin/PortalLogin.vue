<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

async function handleLogin() {
  if (!email.value || !password.value) {
    error.value = 'Email dan password wajib diisi'
    return
  }

  loading.value = true
  error.value = ''

  try {
    await authStore.login(email.value, password.value)
    router.push('/admin')
  } catch (e) {
    error.value = e.response?.data?.message || 'Email atau password salah'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-background relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-accent/5" />

    <div class="relative z-10 w-full max-w-md px-4">
      <div class="text-center mb-8">
        <h1 class="text-2xl font-heading font-bold text-white">
          Japran<span class="text-primary">MCEH</span>
        </h1>
        <p class="text-text-secondary text-sm mt-2">Admin Panel</p>
      </div>

      <form @submit.prevent="handleLogin" class="bg-card border border-border rounded-2xl p-8 space-y-5">
        <div>
          <label class="block text-sm font-medium text-text-secondary mb-2">Email</label>
          <input
            v-model="email"
            type="email"
            placeholder="admin@japranmceh.com"
            class="w-full px-4 py-3 bg-surface border border-border rounded-xl text-white placeholder-text-secondary focus:outline-none focus:border-primary transition-colors"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-text-secondary mb-2">Password</label>
          <input
            v-model="password"
            type="password"
            placeholder="••••••••"
            class="w-full px-4 py-3 bg-surface border border-border rounded-xl text-white placeholder-text-secondary focus:outline-none focus:border-primary transition-colors"
          />
        </div>

        <p v-if="error" class="text-red-400 text-sm text-center">{{ error }}</p>

        <button
          type="submit"
          :disabled="loading"
          class="w-full px-6 py-3 bg-primary hover:bg-secondary text-white rounded-xl font-semibold transition-all duration-300 hover:shadow-lg hover:shadow-primary/25 disabled:opacity-50"
        >
          {{ loading ? 'Memuat...' : 'Masuk' }}
        </button>
      </form>
    </div>
  </div>
</template>
