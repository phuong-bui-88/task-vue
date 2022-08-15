<template>
    <div v-for="(item, index) in tasks">
        <div data-bs-toggle="offcanvas" :href="'#task-' + item.id" class="row" @click="getTask(item.id)">
            <div class="col-2">{{ item.id }}</div>
            <div class="col-4">{{ item.title }}</div>
        </div>
        <TaskEdit :items="tasks" :indexItem="index" :task="task"></TaskEdit>
    </div>

    <div class="row">
        <div class="col-2"></div>
        <div class="col-4">
            <input v-model="createTask.title" @blur="storeTask(createTask, true)" id="task-title" class="title form-control" type="text" placeholder="create a new task">
        </div>
    </div>
</template>

<script>
import useTasks from "../../composables/tasks.js"
import TaskEdit from "./Edit.vue"
import {onMounted, reactive} from "vue"

export default {
    setup() {
        const createTask = reactive({
            title: '',
            description: ''
        })

        const {tasks, getTasks, task, getTask, storeTask } = useTasks()

        onMounted(getTasks)

        return {tasks, task, createTask, getTask, storeTask}
    },
    components: {
        TaskEdit
    }
    // data() {
    //     return {
    //         tasks: []
    //     }
    // },
    // mounted() {
    //     this.fetchTasks()
    // },
    // methods: {
    //     fetchTasks() {
    //         axios.get('/api/tasks')
    //             .then(response => this.tasks = response.data)
    //             .catch(error => console.log(error))
    //     }
    // }
}
</script>
