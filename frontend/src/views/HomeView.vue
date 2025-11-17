<script setup>
import useAuthStore from '@/stores/AuthStore';

const authStore = useAuthStore()
</script>

<template>
    <section class="p-8 bg-gray-50">
        <h1>Welcome, {{ authStore.state.user?.name }}!</h1>
    </section>
</template>
