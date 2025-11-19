<script setup>
import SubmitButton from '@/components/SubmitButton.vue'
import TextInput from '@/components/TextInput.vue'
import useAuthStore from '@/stores/AuthStore.js'

import { reactive } from 'vue'

const authStore = useAuthStore()

const data = reactive({
    name: "",
    email: "",
    password: "",
    password_confirmation: ""
})

async function register() {
    const res = await authStore.register(data)

    if (!res) {
        data.password = ""
        data.password_confirmation = ""
    }
}
</script>

<template>
    <div class="min-h-[60vh] grid grid-cols-2">
        <div class="flex items-center justify-center bg-violet-50">
            <h1 class="text-2xl font-bold text-gray-900 ">Register</h1>
        </div>

        <div class="flex items-center bg-violet-100">
            <form
                @submit.prevent="register"
                class="w-full px-16"
            >
                <TextInput
                    v-model="data.name"
                    inputName="name"
                    inputType="text"
                    placeholder="Your Full Name"
                    :required="true"
                />
                <TextInput
                    v-model="data.email"
                    inputName="email"
                    inputType="email"
                    placeholder="example@mail.com"
                    :required="true"
                />
                <TextInput
                    v-model="data.password"
                    inputName="password"
                    inputType="password"
                    placeholder="Password"
                    :required="true"
                />
                <TextInput
                    v-model="data.password_confirmation"
                    inputName="password_confirmation"
                    inputType="password"
                    placeholder="Password Confirmation"
                    :required="true"
                />
                <SubmitButton
                    label="Register"
                />
            </form>
        </div>
    </div>
</template>
