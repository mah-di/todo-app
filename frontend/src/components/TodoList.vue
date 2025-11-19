<script setup>
import TodoListItem from './TodoListItem.vue'

defineProps({
    heading: String,
    data: Array,
    color: String,
    mb: {
        type: Boolean,
        default: false
    }
})

defineEmits(['editTodo', 'deletedTodo', 'updatedTodo'])
</script>

<template>
    <div
        :class="`${mb ? 'mb-8' : ''}`"
    >
        <h2 class="text-lg font-semibold mb-4">{{ heading }}</h2>
        <ul role="list">
            <TodoListItem
                v-for="todo in data"
                :key="todo.id"
                :todo="todo"
                :color="color"
                @editTodo="todo => $emit('editTodo', todo)"
                @updatedTodo="(id, todo) => $emit('updatedTodo', id, todo)"
                @deletedTodo="id => $emit('deletedTodo', id)"
            />
        </ul>
    </div>
</template>
