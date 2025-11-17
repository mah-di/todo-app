<script setup>
import useAuthStore from '@/stores/AuthStore'

const authStore = useAuthStore()
</script>

<template>
    <nav class="w-full bg-green-50 py-8">
        <ul class="w-7xl mx-auto flex flex-row-reverse gap-8">
            <li v-if="authStore.isAuthenticated"><router-link :to="{ name: 'home' }">Home</router-link></li>
            <li v-if="!authStore.isAuthenticated"><router-link :to="{ name: 'login' }">Login</router-link></li>
            <li v-if="!authStore.isAuthenticated"><router-link :to="{ name: 'register' }">Register</router-link></li>
            <li v-if="authStore.isAuthenticated"><button class="cursor-pointer"
                    @click="authStore.logout">Logout</button></li>
        </ul>
    </nav>
</template>
