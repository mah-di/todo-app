<script setup>
import SubmitButton from '@/components/SubmitButton.vue';
import TextInput from '@/components/TextInput.vue';
import useAuthStore from '@/stores/AuthStore.js'

const authStore = useAuthStore()

</script>

<template>
    <div class="min-h-[60vh] grid grid-cols-2">
        <div class="flex items-center justify-center bg-violet-50">
            <h1 class="text-2xl font-bold text-gray-900 ">Login</h1>
        </div>
        <div class="flex items-center bg-violet-100">
            <form class="w-full px-16" @submit.prevent="authStore.login">
                <TextInput v-model="authStore.state.credentials.email" inputName="email" inputType="email"
                    placeholder="example@mail.com" :required="true" />
                <TextInput v-model="authStore.state.credentials.password" inputName="password" inputType="password"
                    placeholder="Password" :required="true" />
                <SubmitButton label="Login" />
            </form>
        </div>
    </div>
</template>
