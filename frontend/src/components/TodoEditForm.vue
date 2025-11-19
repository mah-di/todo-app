<script setup>
import TextInput from './TextInput.vue'
import TextArea from './TextArea.vue'
import SubmitButton from './SubmitButton.vue'

import { computed } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { handleValidationErrorToast } from '@/utils/helpers'

const { todo } = defineProps({
    todo: Object,
})

const emit = defineEmits(['updatedTodo'])

const toast = useToast()

const data = computed(() => {
    return {
        id: todo.id,
        title: todo.title,
        description: todo.description,
        completed: todo.completed
    }
})

const updateTodo = async () => {
    const res = await axios.patch(`todos/${data.value.id}`, data.value)
        .then(response => response.data)
        .catch(error => error.response.data)

    if (res?.code == 200) {
        toast.success(res.message)
        return emit('updatedTodo', res.data)
    }

    handleValidationErrorToast(res, toast)
}
</script>

<template>
    <form
        @submit.prevent="updateTodo"
    >
        <input type="hidden"
            :value="data.id"
        />
        <TextInput
            v-model="data.title"
            inputName="title"
            inputType="text"
            placeholder="Todo Titile"
            :required="true" />
        <TextArea
            v-model="data.description"
            inputName="description"
            name="description"
            :rows="10"
            placeholder="Todo Description"
            :required="true"
        />
        <div class="flex justify-start gap-x-2 items-center mb-4">
            <input type="checkbox" id="completed"
                class="cursor-pointer h-4 w-4 rounded-lg border-gray-200 text-violet-600 focus:ring-violet-600"
                v-model="data.completed" />
            <label class="text-sm text-gray-600 cursor-pointer font-semibold"
                for="completed"
            >Completed</label>
        </div>
        <SubmitButton
            label="Edit Todo"
        />
    </form>
</template>
