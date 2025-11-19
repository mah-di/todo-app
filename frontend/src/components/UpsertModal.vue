<script setup>
import TodoEditForm from './TodoEditForm.vue'
import TodoCreateForm from './TodoCreateForm.vue'

defineProps({
    todo: Object,
    creating: {
        type: Boolean,
        default: false,
    },
    editing: {
        type: Boolean,
        default: false,
    }
})

defineEmits(['closeModal', 'createdTodo', 'updatedTodo'])
</script>

<template>
    <div class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
        <div
            class="fixed inset-0 bg-gray-500/75 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in">
        </div>

        <div tabindex="0"
            class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                        {{ creating ? 'Create Todo' :'Edit Todo' }}
                    </h3>
                    <hr class="mb-8 border-gray-300">

                    <TodoCreateForm
                        v-if="creating"
                        @createdTodo="todo => $emit('createdTodo', todo)"
                    />

                    <TodoEditForm
                        v-if="editing"
                        :todo="todo"
                        @updatedTodo="todo => $emit('updatedTodo', todo)"
                    />
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button"
                        class="mt-3 inline-flex w-full text-red-700 justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-300 sm:mt-0 sm:w-auto cursor-pointer"
                        @click="$emit('closeModal')"
                    >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>
