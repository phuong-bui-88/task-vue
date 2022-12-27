<template>
    <div v-for="(item) in tasks" class="list-group-item">
        <router-link :to="{name: 'task.edit', params: { taskId: item.id }}" @click="clickedItem(item.id)">
            <div data-bs-toggle="offcanvas" href="#edit-task" :class="'row task-' + item.id"  :item-id='item.id'>
                <div class="col-2">{{ item.id }}</div>
                <div class="col-10">{{ item.title }}</div>
            </div>
        </router-link>
    </div>

<!--    // init task edit is empty in /tasks-->
    <div v-if="isTasksRoute" class="d-none">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="edit-task" data-bs-scroll="true" data-bs-backdrop="false">
        </div>
    </div>

    <div class="row">
        <div class="col-2"></div>
        <div class="col-4">
            <input v-model="initCreateTask.title" @blur="createTask" id="task-title" class="title form-control" type="text" placeholder="create a new task">
        </div>
    </div>
</template>

<script>
import useTasks from "../../composables/tasks.js"
import { useRoute } from "vue-router";
import TaskEdit from "./Edit.vue"
import {onMounted, reactive, ref} from "vue"


export default {
    props: {
        editInit: Boolean,
        taskId: String,
    },
    inheritAttrs: false,
    setup() {
        const initCreateTask = {
            title: '',
            description: ''
        }

        const {tasks, getTasks, storeTask, isSamePage } = useTasks()
        const isTasksRoute = ref(false)
        onMounted(getTasks)

        const route = useRoute()
        if (route.name === 'task.index') {
            isTasksRoute.value = true
        }

        return {route, tasks, initCreateTask, storeTask, isTasksRoute, isSamePage}
    },
    watch: {
        $route (to, from) {
            if (to.name === 'task.index') {
                this.isTasksRoute = true
            }
            else {
                this.isTasksRoute = false
            }
        },
    },
    methods: {
        createTask() {
            if (this.initCreateTask.title.trim() == '') {
                return true
            }

            this.storeTask(this.initCreateTask)
                .then(result => {
                    this.initCreateTask = {}
                })
        },
        clickedItem(itemId) {
            this.isSamePage = (itemId == this.route.params.taskId)
        }
    }
}
</script>
