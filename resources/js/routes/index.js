import { createRouter, createWebHistory } from "vue-router/dist/vue-router"

import App from "../App.vue"
import TasksIndex from "../components/task/Index.vue"
import TaskCreate from "../components/task/Create.vue"
import TaskEdit from "../components/task/Edit.vue"
import Task from "../components/task/Task.vue"
import Home from "../components/Home.vue"

const routes = [
    {
        name: 'home',
        component: Home,
    },
    {
        component: Task,
        children: [
        {
            path: '/tasks',
            name: 'task.index',
            components: {
                default: TasksIndex
            },
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


