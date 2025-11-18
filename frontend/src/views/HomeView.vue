<script setup>
import SubmitButton from '@/components/SubmitButton.vue';
import TextArea from '@/components/TextArea.vue';
import TextInput from '@/components/TextInput.vue';
import useAuthStore from '@/stores/AuthStore';
import axios from 'axios';

import { ref, reactive, computed, onMounted } from 'vue'
import { useToast } from 'vue-toastification';

const authStore = useAuthStore()

const toast = useToast()

const todos = ref([])

const completed = computed(() => todos.value.filter(td => td.completed))
const pending = computed(() => todos.value.filter(td => !td.completed))

const creating = ref(false)
const editing = ref(false)

const data = reactive({
    title: "",
    description: ""
})
const editData = reactive({
    id: null,
    title: "",
    description: "",
    completed: false
})

const createTodo = async () => {
    const res = await axios.post('http://localhost:8000/api/todos', data, {
        withCredentials: true
    })
        .then(response => response.data)
        .catch(error => error.response.data)

    if (res?.code == 200) {
        todos.value.push(res.data)
        data.title = ""
        data.description = ""
        creating.value = false

        toast.success(res.message)
        return
    }

    if (!res?.data) {
        toast.error(res.message)
        return
    }

    for (const field in res.data) {
        toast.error(res.data[field].join("\n"))
    }
}

const editTodo = (todo) => {
    editData.id = todo.id
    editData.title = todo.title
    editData.description = todo.description
    editData.completed = !!todo.completed
    editing.value = true
}

const updateTodo = async () => {
    const res = await axios.patch(`http://localhost:8000/api/todos/${editData.id}`, editData, {
        withCredentials: true
    })
        .then(response => response.data)
        .catch(error => error.response.data)

    if (res?.code == 200) {
        todos.value.map(todo => {
            if (todo.id === editData.id) {
                todo.title = res.data.title
                todo.description = res.data.description
                todo.completed = res.data.completed
            }
        })

        editData.id = null
        editData.title = ""
        editData.description = ""
        editData.completed = false
        editing.value = false

        toast.success(res.message)
        return
    }

    if (!res?.data) {
        toast.error(res.message)
        return
    }

    for (const field in res.data) {
        toast.error(res.data[field].join("\n"))
    }
}

const updateTodoStatus = async (id, status) => {
    const res = await axios.patch(`http://localhost:8000/api/todos/${id}`, { completed: status }, {
        withCredentials: true
    })
        .then(response => response.data)
        .catch(error => error.response.data)

    if (res?.code == 200) {
        todos.value.map(td => {
            if (td.id === id) {
                td.completed = res.data.completed
            }
        })

        toast.success(res.message)
        return
    }

    if (!res?.data) {
        toast.error(res.message)
        return
    }

    for (const field in res.data) {
        toast.error(res.data[field].join("\n"))
    }
}

const deleteTodo = async (id) => {
    const res = await axios.delete(`http://localhost:8000/api/todos/${id}`, {
        withCredentials: true
    })
        .then(response => response.data)
        .catch(error => error.response.data)

    if (res?.code == 200) {
        todos.value = todos.value.filter(todo => todo.id !== id)

        toast.info(res.message)
    } else {
        toast.error(res.message)
    }
}

onMounted(async () => {
    await axios.get('http://localhost:8000/api/todos', {
        withCredentials: true
    }).then(response => {
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

            <button @click="creating = true"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded cursor-pointer">Create A
                Todo</button>
        </div>
    </section>

    <section class="px-8 py-16 bg-gray-50">
        <div v-if="todos.length == 0">
            <h2 class="text-lg font-semibold">Nothing To do!</h2>
        </div>

        <div v-if="pending.length > 0" :class="`${completed.length > 0 ? 'mb-8' : ''}`">
            <h2 class="text-lg font-semibold mb-4">Pending To-dos</h2>
            <ul role="list">
                <li v-for="todo in pending" :key="todo.id"
                    class="flex justify-between gap-x-6 py-5 bg-blue-50 px-4 hover:bg-blue-100 border border-blue-200 rounded-xl mb-2">
                    <div class="flex min-w-0 gap-x-4">
                        <div class="min-w-0 flex-auto">
                            <input type="checkbox"
                                class="mt-1 cursor-pointer h-4 w-4 rounded-lg border-gray-200 text-violet-600 focus:ring-violet-600"
                                :checked="todo.completed" @change="updateTodoStatus(todo.id, true)" />
                        </div>
                        <div class="min-w-0 flex-auto">
                            <p class=" text-sm/6 font-semibold text-gray-900">{{ todo.title }}</p>
                            <p class=" mt-1 text-xs/5 text-gray-500">{{ todo.description }}</p>
                        </div>
                    </div>
                    <div class="hidden shrink-0 sm:flex sm:items-start gap-x-4">
                        <button @click="editTodo(todo)"
                            class="px-3 py-2 bg-white text-xs text-gray-900 hover:bg-gray-200 hover:text-blue-500 border border-gray-300 rounded cursor-pointer">Edit</button>
                        <button @click="deleteTodo(todo.id)"
                            class="px-3 py-2 bg-white text-xs text-gray-900 hover:bg-gray-200 hover:text-red-500 border border-gray-300 rounded cursor-pointer">Delete</button>
                    </div>
                </li>
            </ul>
        </div>

        <div v-if="completed.length > 0">
            <h2 class="text-lg font-semibold mb-4">{{ pending.length > 0 ? 'Completed To-dos' : 'All To-dos Completed!'
                }}</h2>
            <ul role="list">
                <li v-for="todo in completed" :key="todo.id"
                    class="flex justify-between gap-x-6 py-5 bg-green-50 px-4 hover:bg-green-100 border border-green-200 rounded-xl mb-2">
                    <div class="flex min-w-0 gap-x-4">
                        <div class="min-w-0 flex-auto">
                            <input type="checkbox"
                                class="mt-1 cursor-pointer h-4 w-4 rounded-lg border-gray-200 text-violet-600 focus:ring-violet-600"
                                :checked="todo.completed" @change="updateTodoStatus(todo.id, false)" />
                        </div>
                        <div class="min-w-0 flex-auto">
                            <p class="line-through text-sm/6 font-semibold text-gray-900">{{ todo.title }}</p>
                            <p class="line-through mt-1 truncate text-xs/5 text-gray-500">{{ todo.description }}</p>
                        </div>
                    </div>
                    <div class="hidden shrink-0 sm:flex sm:items-start gap-x-4">
                        <button @click="editTodo(todo)"
                            class="px-3 py-2 bg-white text-xs text-gray-900 hover:bg-gray-200 hover:text-blue-500 border border-gray-300 rounded cursor-pointer">Edit</button>
                        <button @click="deleteTodo(todo.id)"
                            class="px-3 py-2 bg-white text-xs text-gray-900 hover:bg-gray-200 hover:text-red-500 border border-gray-300 rounded cursor-pointer">Delete</button>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    <div v-if="creating || editing"
        class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
        <div
            class="fixed inset-0 bg-gray-500/75 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in">
        </div>

        <div tabindex="0"
            class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ creating ? 'Create Todo' :
                        'Edit Todo' }}</h3>
                    <hr class="mb-8 border-gray-300">
                    <form v-if="creating" @submit.prevent="createTodo">
                        <TextInput v-model="data.title" inputName="title" inputType="text" placeholder="Todo Titile"
                            :required="true" />
                        <TextArea v-model="data.description" inputName="description" name="description" :rows="10"
                            placeholder="Todo Description" :required="true" />
                        <SubmitButton label="Create Todo" />
                    </form>
                    <form v-if="editing" @submit.prevent="updateTodo">
                        <input type="hidden" :value="editData.id" />
                        <TextInput v-model="editData.title" inputName="title" inputType="text" placeholder="Todo Titile"
                            :required="true" />
                        <TextArea v-model="editData.description" inputName="description" name="description" :rows="10"
                            placeholder="Todo Description" :required="true" />
                        <div class="flex justify-start gap-x-2 items-center mb-4">
                            <input type="checkbox"
                                id="completed"
                                class="cursor-pointer h-4 w-4 rounded-lg border-gray-200 text-violet-600 focus:ring-violet-600"
                                v-model="editData.completed" />
                            <label class="text-sm text-gray-600 cursor-pointer font-semibold" for="completed">Completed</label>
                        </div>
                        <SubmitButton label="Edit Todo" />
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" @click="creating = editing = false"
                        class="mt-3 inline-flex w-full text-red-700 justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-300 sm:mt-0 sm:w-auto cursor-pointer">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>
