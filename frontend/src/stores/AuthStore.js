import axios from "axios"
import { defineStore } from "pinia"
import { reactive, computed } from "vue"
import { useRouter } from "vue-router"

const useAuthStore = defineStore("auth", () => {
    const router = useRouter()
    const state = reactive({
        credentials: {
            email: "",
            password: ""
        },
        user: null
    })

    const isAuthenticated = computed(() => !!state.user)

    async function register(data) {
        const res = await axios.post("http://localhost:8000/api/register", data, {
            withCredentials: true
        })
            .then(response => response.data)
            .catch(error => error.response.data)

        if (res?.code == 200) {
            state.user = res.data
            router.push({ name: "home" })
            return true
        }

        return false
    }

    async function login() {
        const result = await axios.post("http://localhost:8000/api/login", state.credentials, {
            withCredentials: true
        })
            .then(response => response.data)
            .catch(error => error.response.data)

        state.credentials.password = ""
        if (result?.code == 200) {
            state.user = result.data
            state.credentials.email = ""
            router.push({ name: "home" })
        }
    }

    async function check() {
        const res = await axios.get("http://localhost:8000/api/user", {
            withCredentials: true
        })
            .then(response => response.data)
            .catch(error => error.response.data)

        if (res?.code == 200) {
            state.user = res.data
        } else {
            state.user = null
        }
    }

    async function logout() {
        const res = await axios.post('http://localhost:8000/api/logout', {}, {
            withCredentials: true
        })
            .then(response => response.data)
            .catch(error => error.response.data)

        if (res?.code == 200 || res?.code == 401) {
            state.user = null
            router.push({ name: "login" })
        }
    }

    return { state, isAuthenticated, register, login, check, logout }
})

export default useAuthStore
