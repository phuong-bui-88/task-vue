import { ref } from 'vue'
import axios from "axios"
import { useRouter} from 'vue-router'
import routes from "../routes";

export default function useTasks() {
    const tasks = ref({})
    const task = ref({})
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(null)

    const getTasks = async () => {
        axios.get('/api/tasks')
            .then(response => {
                tasks.value = response.data.data
            })
    }

    const getTask = async (taskId) => {
        axios.get('/api/tasks/' + taskId)
            .then(response => {
                task.value = response.data.data
            })
    }

    const storeTask = async (task, reload) => {
        if (isLoading.value) return

        isLoading.value = true
        validationErrors.value = {}

        axios.post('/api/tasks', task)
            .then(response => {
                if (reload) {
                    routes.go(0)
                }
                router.push({ name: 'task.index'})
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

     const updateTask = async (task, title) => {
        console.log(task.id)
        console.log(title)
        if (title) {
            task.title = title
        }

        axios.put('/api/tasks/' + task.id, task)
            .then(response => {
                router.push({ name: 'task.index'})
            })
    }

    const destroyTask = async (taskId) => {
        axios.delete('/api/tasks/' + taskId)
            .then(response => {
                task.value = response.data.data
            })
    }

    return { tasks, task, getTask, getTasks, storeTask, updateTask, destroyTask, validationErrors, isLoading }
}
