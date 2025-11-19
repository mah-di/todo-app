<script setup>
import Modal from '@/components/UpsertModal.vue'
import TodoList from '@/components/TodoList.vue'

import { ref, reactive, computed, onMounted } from 'vue'
import useAuthStore from '@/stores/AuthStore'
import axios from 'axios';
import { useToast } from 'vue-toastification'

const authStore = useAuthStore()
const toast = useToast()

const todos = ref([])
const completed = computed(() => todos.value.filter(td => td.completed))
const pending = computed(() => todos.value.filter(td => !td.completed))

const creating = ref(false)
const editing = ref(false)
const editData = reactive({
    id: null,
    title: "",
    description: "",
    completed: false
})

const createdTodo = async (todo) => {
    todos.value.push(todo)
    creating.value = false
}

const editTodo = (todo) => {
    editData.id = todo.id
    editData.title = todo.title
    editData.description = todo.description
    editData.completed = !!todo.completed
    editing.value = true
}

const updatedTodo = async (todo) => {
    todos.value.map(td => {
        if (td.id === todo.id) {
            td.title = todo.title
            td.description = todo.description
            td.completed = todo.completed
        }
    })

    editData.id = null
    editData.title = ""
    editData.description = ""
    editData.completed = false
    editing.value = false
}

const deletedTodo = async (id) => {
    todos.value = todos.value.filter(todo => todo.id !== id)
}

onMounted(async () => {
    await axios.get('todos')
        .then(response => {
            todos.value = response.data.data
        })
        .catch(error => {
            toast.error(error.response.data.message)
        })
})
</script>

<template>
    <section class="mb-16 px-8 py-8 bg-gray-50">
        <div class="flex justify-between items-center">
            <h1>Welcome, <b>{{ authStore.state.user?.name }}</b>!</h1>

            <button
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded cursor-pointer"
                @click="creating = true"
            >Create A Todo</button>
        </div>
    </section>

    <section class="px-8 py-16 bg-gray-50">
        <div
            v-if="todos.length == 0"
        >
            <h2 class="text-lg font-semibold">Nothing To do!</h2>
        </div>

        <TodoList
            v-if="pending.length > 0" heading="Pending To-dos"
            :data="pending"
            color="blue"
            :mb="completed.length > 0"
            @editTodo="editTodo"
            @updatedTodo="updatedTodo"
            @deletedTodo="deletedTodo"
        />

        <TodoList
            v-if="completed.length > 0"
            :heading="pending.length > 0 ? 'Completed To-dos' : 'All To-dos Completed!'"
            :data="completed"
            color="green"
            @editTodo="editTodo"
            @updatedTodo="updatedTodo"
            @deletedTodo="deletedTodo"
        />
    </section>

    <Modal
        v-if="creating || editing"
        :creating="creating"
        :editing="editing"
        :todo="creating ? null : editData"
        @createdTodo="createdTodo"
        @updatedTodo="updatedTodo"
        @closeModal="creating = editing = false"
    />
</template>
