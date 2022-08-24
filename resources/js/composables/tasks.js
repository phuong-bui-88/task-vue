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

    const storeTask = async (task) => {
        if (isLoading.value) return

        // isLoading.value = true
        validationErrors.value = {}

        let result = await axios.post('/api/tasks', task)

        return result.data.data
    }

     const updateTask = async (task) => {
        axios.put('/api/tasks/' + task.id, task)
            .then(response => {})
    }

    const destroyTask = async (taskId) => {
        axios.delete('/api/tasks/' + taskId)
            .then(response => {})
    }

    return { tasks, task, getTask, getTasks, storeTask, updateTask, destroyTask, validationErrors, isLoading }
}
