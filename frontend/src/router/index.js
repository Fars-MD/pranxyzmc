import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('@/views/Home.vue'),
  },
  {
    path: '/products',
    name: 'Products',
    component: () => import('@/views/Products.vue'),
  },
  {
    path: '/products/:slug',
    name: 'ProductDetail',
    component: () => import('@/views/ProductDetail.vue'),
  },
  {
    path: '/portal',
    name: 'PortalLogin',
    component: () => import('@/views/admin/PortalLogin.vue'),
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: () => import('@/views/admin/Dashboard.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/products',
    name: 'AdminProducts',
    component: () => import('@/views/admin/Products.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/categories',
    name: 'AdminCategories',
    component: () => import('@/views/admin/Categories.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/testimonials',
    name: 'AdminTestimonials',
    component: () => import('@/views/admin/Testimonials.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/settings',
    name: 'AdminSettings',
    component: () => import('@/views/admin/Settings.vue'),
    meta: { requiresAuth: true },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  },
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/portal')
  } else {
    next()
  }
})

export default router
