import { ref } from 'vue'
import axios from "axios"
import Base from "./../consts/base.js"
import api from "./api.js"


const tasks = ref(false)
const remainCount = ref(0)
const allCount = ref(0)
const overDateCount = ref(0)
const isSamePage = ref(false)
const taskStatus = ref(Base.ALL_STATUS)

export default function useTasks() {
    const task = ref({})

    const getTasks = async (queryTask = '', hasIndex = true) => {
        let status = ''
        status = (Base.ALL_STATUS == taskStatus.value) ? '?status=0' : status
        status = (Base.REMAIN_STATUS == taskStatus.value) ? '?status=1' : status
        status = (Base.OVER_DATE_STATUS == taskStatus.value) ? '?status=2' : status

        hasIndex = (hasIndex) ? '' : '&index=0'
        queryTask = queryTask + status + hasIndex

        let result = await api.get('/tasks' + queryTask)

        tasks.value = result.data.data
        allCount.value = result.data.allCount
        remainCount.value = result.data.remainCount
        overDateCount.value = result.data.overDateCount
    }

    const getTask = async (taskId) => {
        let result = await api.get('/tasks/' + taskId)

        task.value = result.data.data
    }

    const storeTask = async (task) => {
        let result = await api.post('/tasks', task)
        result = result.data.data
        tasks.value.push(result)

        return result
    }

     const updateTask = async (task, includeTasks = false) => {
        let result = await api.put('/tasks/' + task.id, task)

        if (includeTasks && result.status === 200) {
            tasks.value.forEach((item, index) => {
                if (item.id === task.id) {
                    tasks.value[index] = task
                    return true
                }
            })
        }
    }

    const destroyTask = async (taskId, includeTasks = false) => {
        let result = await api.delete('/tasks/' + taskId)
        return result
    }

    return { tasks, task, getTask, getTasks, storeTask, updateTask, destroyTask, isSamePage, allCount, remainCount, overDateCount, taskStatus }
}
