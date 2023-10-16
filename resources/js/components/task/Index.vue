<template>
    <div class="row">
        <div class="col-12 my-3">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link position-relative" :class="[(taskStatus === ALL_STATUS) ? 'active' : '']" aria-current="page" href="#" @click.prevent="taskTabActive(ALL_STATUS)">
                        All
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ allCount }}
                </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" :class="[(taskStatus === OVER_DATE_STATUS) ? 'active' : '']" aria-current="page" href="#" @click.prevent="taskTabActive(OVER_DATE_STATUS)">
                        Over Date
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ overDateCount }}
                </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" :class="[(taskStatus === REMAIN_STATUS) ? 'active' : '']"  aria-current="page" href="#" @click.prevent="taskTabActive(REMAIN_STATUS)">
                        Remain
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ remainCount }}
                </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" :class="[(taskStatus === FAVORITE_STATUS) ? 'active' : '']"  aria-current="page" href="#" @click.prevent="taskTabActive(FAVORITE_STATUS)">
                        Favorite
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ favoriteCount }}
                </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="index-wrapper scroll-y">
        <div v-for="(element, index) in tasks">
            <router-link :to="{name: 'task.edit', params: { taskId: element.id }}" @click="clickedItem(element.id)">
                <div data-bs-toggle="offcanvas" href="#edit-task" :class="'row m-0 task-' + element.id"
                     :item-id='element.id'>
                    <div class="col-2 p-0">{{ element.id }}</div>
                    <div class="col-md-6 col-sm-4 p-0">{{ element.title }}</div>
                    <div class="col-md-2 col-sm-4 p-0">{{ formatDate(element.start_date) }}</div>
                    <div class="col-md-2 col-sm-2 p-0"><FavoriteItem :key="element.id" :item="element" @toggle-favorite="toggleFavorite"/></div>
                </div>
            </router-link>
        </div>
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
import FavoriteItem from "../integrate/FavoriteItem.vue";
import {onMounted, reactive, ref} from "vue"
import DateFilter from "../../filters/date.js"
import GlobalConst from "../../consts/base.js"

import lo from 'lodash'

export default {
    components: {FavoriteItem},
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

        const {tasks, allCount, remainCount, overDateCount, favoriteCount, taskStatus, getTasks, storeTask, isSamePage, addFavoriteTask, destroyFavoriteTask } = useTasks()
        const isTasksRoute = ref(false)

        const route = useRoute()
        if (route.name === 'task.index') {
            isTasksRoute.value = true
        }

        return {route, tasks, allCount, remainCount, overDateCount, favoriteCount, taskStatus, initCreateTask, getTasks, storeTask, isTasksRoute, isSamePage, addFavoriteTask, destroyFavoriteTask}
    },
    created() {
        this.ALL_STATUS = GlobalConst.ALL_STATUS
        this.REMAIN_STATUS = GlobalConst.REMAIN_STATUS
        this.OVER_DATE_STATUS = GlobalConst.OVER_DATE_STATUS
        this.FAVORITE_STATUS = GlobalConst.FAVORITE_STATUS
    },
    watch: {
        $route (to, from) {
            this.isTasksRoute = (to.name === 'task.index')
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
                    this.getTasks()
                })
        },
        clickedItem(itemId) {
            this.isSamePage = (itemId === this.route.params.taskId)
        },
        formatDate(date) {
            return DateFilter(date, GlobalConst.DATE_FORMAT)
        },
        taskTabActive(status) {
            this.taskStatus = status
            this.getTasks()
        },
        toggleFavorite(item, isFavorite) {
            (isFavorite)
                ? this.addFavoriteTask(item)
                : this.destroyFavoriteTask(item)
            this.getTasks()
        }
    }
}
</script>
