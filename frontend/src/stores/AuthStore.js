import axios from "axios"
import { defineStore } from "pinia"
import { reactive, computed } from "vue"
import { useRouter } from "vue-router"
import { useToast } from "vue-toastification"

const useAuthStore = defineStore("auth", () => {
    const router = useRouter()
    const toast = useToast()

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
            toast.success(res.message)
            return true
        }

        if (!res?.data) {
            toast.error(res.message)
            return false
        }

        for (const field in res.data) {
            toast.error(res.data[field].join("\n"))
        }

        return false
    }

    async function login() {
        const res = await axios.post("http://localhost:8000/api/login", state.credentials, {
            withCredentials: true
        })
            .then(response => response.data)
            .catch(error => error.response.data)

        state.credentials.password = ""

        if (res?.code == 200) {
            state.user = res.data
            state.credentials.email = ""
            toast.info(res.message)
            router.push({ name: "home" })
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
            toast.info(res.message)
            router.push({ name: "login" })
        } else {
            toast.error(res.message)
        }
    }

    return { state, isAuthenticated, register, login, check, logout }
})

export default useAuthStore
