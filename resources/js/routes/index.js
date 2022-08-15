import { createRouter, createWebHistory } from "vue-router/dist/vue-router"

import TasksIndex from "../components/task/Index.vue"
import TaskCreate from "../components/task/Create.vue"

const routes = [
    { path: '/tasks', name: 'task.index', component: TasksIndex},
    { path: '/tasks/create', name: 'task.create', component: TaskCreate }
]

export default createRouter({
    history: createWebHistory(),
    routes
})


