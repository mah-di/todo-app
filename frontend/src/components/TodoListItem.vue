<script setup>
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { handleValidationErrorToast } from '@/utils/helpers'

const toast = useToast()

defineProps({
    todo: Object,
    color: String
})

const emit = defineEmits(['editTodo', 'deletedTodo', 'updatedTodo'])

const colorVariants = {
    green: "bg-green-50 hover:bg-green-100 border-green-200",
    blue: "bg-blue-50 hover:bg-blue-100 border-blue-200",
}

const updateTodoStatus = async (id, status) => {
    const res = await axios.patch(`todos/${id}`, { completed: status })
        .then(response => response.data)
        .catch(error => error.response.data)

    if (res?.code == 200) {
        toast.success(res.message)

        return emit('updatedTodo', res.data)
    }

    handleValidationErrorToast(res, toast)
}

const deleteTodo = async (id) => {
    const res = await axios.delete(`todos/${id}`)
        .then(response => response.data)
        .catch(error => error.response.data)

    if (res?.code == 200) {
        toast.info(res.message)

        return emit('deletedTodo', id)
    } else {
        toast.error(res.message)
    }
}
</script>

<template>
    <li :class="`flex justify-between gap-x-6 py-5 px-4 ${colorVariants[color]} border rounded-xl mb-2`">
        <div class="flex min-w-0 gap-x-4">
            <div class="min-w-0 flex-auto">
                <input type="checkbox"
                    class="mt-1 cursor-pointer h-4 w-4 rounded-lg border-gray-200 text-violet-600 focus:ring-violet-600"
                    :checked="todo.completed"
                    @change="updateTodoStatus(todo.id, !todo.completed)"
                />
            </div>

            <div class="min-w-0 flex-auto">
                <p
                    :class="`${color === 'green' ? 'line-through' : ''} text-sm/6 font-semibold text-gray-900`"
                >{{ todo.title }}</p>
                <p
                    :class="`${color === 'green' ? 'line-through truncate' : ''} mt-1 text-xs/5 text-gray-500`"
                >{{ todo.description }}</p>
            </div>
        </div>

        <div class="hidden shrink-0 sm:flex sm:items-start gap-x-4">
            <button
                @click="$emit('editTodo', todo)"
                class="px-3 py-2 bg-white text-xs text-gray-900 hover:bg-gray-200 hover:text-blue-500 border border-gray-300 rounded cursor-pointer"
            >Edit</button>

            <button
                @click="deleteTodo(todo.id)"
                class="px-3 py-2 bg-white text-xs text-gray-900 hover:bg-gray-200 hover:text-red-500 border border-gray-300 rounded cursor-pointer"
            >Delete</button>
        </div>
    </li>
</template>
