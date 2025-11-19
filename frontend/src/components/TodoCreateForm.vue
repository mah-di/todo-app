<script setup>
import TextInput from './TextInput.vue'
import TextArea from './TextArea.vue'
import SubmitButton from './SubmitButton.vue'

import { reactive } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { handleValidationErrorToast } from '@/utils/helpers'

const emit = defineEmits(['createdTodo'])

const toast = useToast()

const data = reactive({
    title: "",
    description: ""
})

const createTodo = async () => {
    const res = await axios.post('todos', data)
        .then(response => response.data)
        .catch(error => error.response.data)

    if (res?.code == 200) {
        toast.success(res.message)

        return emit('createdTodo', res.data)
    }

    handleValidationErrorToast(res, toast)
}
</script>

<template>
    <form
        @submit.prevent="createTodo"
    >
        <TextInput
            v-model="data.title"
            inputName="title"
            inputType="text"
            placeholder="Todo Titile"
            :required="true"
        />
        <TextArea
            v-model="data.description"
            inputName="description"
            name="description"
            :rows="10"
            placeholder="Todo Description" :required="true"
        />
        <SubmitButton
            label="Create Todo"
        />
    </form>
</template>
