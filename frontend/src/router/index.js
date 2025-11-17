import useAuthStore from '@/stores/AuthStore'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../views/HomeView.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/LoginView.vue'),
            meta: {
                requiresGuest: true
            }
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('../views/RegisterView.vue'),
            meta: {
                requiresGuest: true
            }
        },
    ],
})

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore()

    await authStore.check()

    if (to.meta.requiresAuth && !useAuthStore().isAuthenticated)
        return next({ name: 'login' })

    else if (to.meta.requiresGuest && useAuthStore().isAuthenticated)
        return next({ name: 'home' })

    return next()
})

export default router
