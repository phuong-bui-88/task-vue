<template>
    <div v-for="(item, index) in tasks">
        <router-link :to="{name: 'task.edit', params: { taskId: item.id }}" @click="$emit('itemClick', item.id)">
<!--            @click="getTask(item.id)"    :href="'#task-' + item.id"-->
            <div data-bs-toggle="offcanvas" href="#edit-task" :class="'row task-' + item.id"  :item-id='item.id'>
                <div class="col-2">{{ item.id }}</div>
                <div class="col-4">{{ item.title }}</div>
            </div>
        </router-link>

<!--        <TaskEdit :items="tasks" :indexItem="index" :task="task"></TaskEdit>-->
    </div>


<!--    <div class="row">-->
<!--        <div class="col-2"></div>-->
<!--        <div class="col-4">-->
<!--            <input v-model="createTask.title" @blur="storeTask(createTask, true)" id="task-title" class="title form-control" type="text" placeholder="create a new task">-->
<!--        </div>-->
<!--    </div>-->
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

        const {tasks, getTasks, storeTask } = useTasks()

        onMounted(getTasks)

        return {tasks, storeTask}
    },
    // components: {
    //     TaskEdit
    // }
    // data() {
    //     return {
    //         tasks: []
    //     }
    // },
    // mounted() {
    //     this.fetchTasks()
    // },
    methods: {
        // click(id) {
        //     console.log('click me')
        //      this.$refs.childComponent.item(id);
        // }
    }
}
</script>
