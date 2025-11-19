import { reactive, computed } from "vue"
import { useRouter } from "vue-router"
import { useToast } from "vue-toastification"
import { defineStore } from "pinia"
import axios from "axios"
import { handleValidationErrorToast } from "@/utils/helpers"

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
        const res = await axios.post("register", data)
            .then(response => response.data)
            .catch(error => error.response.data)

        if (res?.code == 200) {
            state.user = res.data
            router.push({ name: "home" })
            toast.success(res.message)
            return true
        }

        handleValidationErrorToast(res, toast)

        return false
    }

    async function login() {
        const res = await axios.post("login", state.credentials)
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

        handleValidationErrorToast(res, toast)
    }

    async function check() {
        const res = await axios.get("user")
            .then(response => response.data)
            .catch(error => error.response.data)

        if (res?.code == 200) {
            state.user = res.data
        } else {
            state.user = null
        }
    }

    async function logout() {
        const res = await axios.post('logout')
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
