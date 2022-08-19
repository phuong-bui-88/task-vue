import { createRouter, createWebHistory } from "vue-router/dist/vue-router"

import App from "../App.vue"
import TasksIndex from "../components/task/Index.vue"
import TaskCreate from "../components/task/Create.vue"
import TaskEdit from "../components/task/Edit.vue"
import Task from "../components/task/Task.vue"
import SubTask from "../components/task/SubTask.vue";

const routes = [
    {
        path: '/',
        name: 'home',
        component: Task,
        children: [{
            path: '/tasks',
            name: 'task.index',
            components: {
                default: TasksIndex
            },
        }, {
            path: '/tasks/create',
            name: 'task.create',
            components: {
                default: SubTask
            }
        }, {
            path: '/tasks/:taskId/edit',
            name: 'task.edit',
            components: {
                default: TasksIndex,
                right: TaskEdit
            },
            props: true
        }]
    }
]

export default createRouter({
    history: createWebHistory(),
    routes
})


