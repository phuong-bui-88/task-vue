<template>

    <div v-for="message in validationErrors?.title">
        {{ message }}
    </div>
<!--:id="'task-' + task.id"-->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="edit-task" :item="task.id" data-bs-scroll="true" data-bs-backdrop="false" ref="username">
        <div class="offcanvas-body">
            <div class="col-md-12">
                <a class="hidden" data-bs-dismiss="offcanvas" ref="close">Close</a>
                 <button class="btn btn-secondary dropdown-toggle" type="button" id="edit-task-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    ...
                 </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"  @click="deleteTask()" data-bs-dismiss="offcanvas">Delete</a></li>
                </ul>
            </div>
            <div class="mb-3">
                <label for="task-title" class="form-label">Title</label>
                <input v-model="task.title" id="task-title" class="form-control" type="text">

                <div v-for="message in validationErrors?.title">
                    {{ message }}
                </div>
            </div>

            <div class="mb-3">
                <label for="task-description" class="form-label">Description</label>
                <textarea v-model="task.description" id="task-description" type="text" class="form-control" rows="3"></textarea>
            </div>

            {{ samePage }}
        </div>
    </div>
</template>

<script>
import {onMounted, onUpdated, reactive, ref} from "vue";
import TaskIndex from "./Index.vue";
import useTasks from "../../composables/tasks.js";
import { useRoute } from "vue-router";

export default {
    props: {
        samePage: String,
        // item: Object,
        // tasks: Object,
    //     indexItem: Number
    },

    setup() {

        const { task, getTask, updateTask, destroyTask, validationErrors, isLoading } = useTasks()
        const route = useRoute()
        const toTaskId = ref({})

        onMounted(() => {
            getTask(route.params.taskId)
        })

        return { toTaskId, task, route, getTask, updateTask, destroyTask, validationErrors, isLoading }
    },

    watch: {
        samePage: function (newVal, oldVal) {
            if (newVal == true) {
                this.$refs.close.click()
            }
        },
        $route(to, from) {
            this.toTaskId.value = to.params.taskId
            this.getTask(this.toTaskId.value)
        }
    },
    methods:{
        editTask(event) {
            if (this.samePage == false) {
                event.preventDefault()
            }
        }
      // deleteTask() {
      //     this.destroyTask(this.items[this.indexItem].id)
      //     this.items.splice(this.indexItem, 1)
      // }
    },
    mounted() {
        this.$refs.username.addEventListener('hide.bs.offcanvas', this.editTask.bind())
    }
}
</script>

