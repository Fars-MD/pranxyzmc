<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppLayout from '@/layouts/AppLayout.vue'

const route = useRoute()
const isAdmin = computed(() => route.path.startsWith('/admin') || route.path === '/portal')
</script>

<template>
  <AppLayout v-if="!isAdmin">
    <router-view />
  </AppLayout>
  <router-view v-else />
</template>
