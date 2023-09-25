import { createRouter, createWebHistory } from "vue-router/dist/vue-router"

import App from "../App.vue"
import TasksIndex from "../components/task/Index.vue"
import TaskEdit from "../components/task/Edit.vue"
import Task from "../components/task/Task.vue"
import Home from "../components/Home.vue"
import MenuRight from "../components/MenuRight.vue"
import ForgotPassword from "../components/user/ForgotPassword.vue"
import Login from "../components/user/Login.vue";
import SignUp from "../components/user/SignUp.vue";
import ResetPassword from "../components/user/ResetPassword.vue";
import Empty from "../components/Empty.vue";
import Base from "../consts/base.js";

const routes = [
    {
        name: 'home',
        component: Home,
        meta: {
            requiresNoneAuth: true
        },
    },
    {
        name: 'callback',
        path: '/callback',
        component: Empty,
        meta: {
            requiresNoneAuth: true
        },
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta: {
            requiresNoneAuth: true
        },
    },
    {
        name: 'register',
        path: '/register',
        component: SignUp,
        meta: {
            requiresNoneAuth: true
        },
    },
    {
        name: 'forgot-password',
        path: '/forgot-password',
        component: ForgotPassword,
        meta: {
            requiresNoneAuth: true
        },
    },
    {
        name: 'reset-password',
        path: '/reset-password/:token',
        component: ResetPassword,
        props: true,
        meta: {
            requiresNoneAuth: true
        },
    },
    {
        component: Task,
        children: [
        {
            path: '/tasks',
            name: 'task.index',
            components: {
               left: MenuRight,
                default: TasksIndex,
            },
            meta: {
                requiresAuth: true
            },
        }, {
            path: '/tasks/:taskId/edit',
            name: 'task.edit',
            components: {
                left: MenuRight,
                default: TasksIndex,
                right: TaskEdit
            },
            meta: {
                requiresAuth: true
            },
        }]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})
// Add a global navigation guard to check if the user is authenticated
router.beforeEach((to, from, next) => {
  const isLoggedIn = localStorage.getItem(Base.TOKEN)

  if (to.matched.some(record => record.meta.requiresAuth) && !isLoggedIn) {
    next('/login')
  }
  else if (to.matched.some(record => record.meta.requiresNoneAuth) && isLoggedIn) {
    next({ name: 'task.index' })
  }
  else {
    next()
  }
});

export default router


