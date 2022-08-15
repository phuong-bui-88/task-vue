<template>
    <div class="offcanvas offcanvas-end" tabindex="-1" :id="'task-' + items[indexItem].id" data-bs-scroll="true" data-bs-backdrop="false" ref="username">
        <div class="offcanvas-body">
            <form @submit.prevent="updateTask(task, items[indexItem].title)">
                <div class="col-md-12">
                     <button class="btn btn-secondary dropdown-toggle" type="button" id="edit-task-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        ...
                     </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"  @click="deleteTask()">Delete</a></li>
                    </ul>
                </div>
                <div class="mb-3">
                    <label for="task-title" class="form-label">Title</label>
                    <input v-model="items[indexItem].title" id="task-title" class="form-control" type="text">

                    <div v-for="message in validationErrors?.title">
                        {{ message }}
                    </div>
                </div>

                <div class="mb-3">
                    <label for="task-description" class="form-label">Description</label>
                    <textarea v-model="task.description" id="task-description" type="text" class="form-control" rows="3"></textarea>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {onMounted, reactive} from "vue";
import useTasks from "../../composables/tasks.js";

export default {
    props: {
        items: Object,
        indexItem: Number,
        task: Object
    },
    setup() {
        const { updateTask, destroyTask, validationErrors, isLoading } = useTasks()
        return { updateTask, destroyTask, validationErrors, isLoading }
    },
     methods:{
          editTask() {
              this.updateTask(this.task, this.items[this.indexItem].title)
          },
          deleteTask() {
              // this.destroyTask(this.task.id)
              this.items.splice(this.indexItem, 1)
              // this.$refs.username.classList.remove("show");
              // this.$refs.username.classList.add("hidden");
          }
    },
    mounted() {
        this.$refs.username.addEventListener('hidden.bs.offcanvas', this.editTask.bind())
    }
}
</script>

