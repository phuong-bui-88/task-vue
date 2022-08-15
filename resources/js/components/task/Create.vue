<template>
    <h1>Create a new post</h1>
    <form @submit.prevent="storeTask(task)">
        <div>
            <label for="task-title">Title</label>
            <input v-model="task.title" id="task-title" type="text">

            <div v-for="message in validationErrors?.title">
                {{ message }}
            </div>
        </div>

        <div>
            <label for="task-description">Description</label>
            <textarea v-model="task.description" id="task-description" type="text"></textarea>
        </div>

        <div>
            <button :disabled="isLoading">
                <span v-if="isLoading">Processing...</span>
                <span v-else>Save</span>
            </button>
        </div>
    </form>
</template>

<script>
import {onMounted, reactive} from "vue";
import useTasks from "../../composables/tasks.js";

export default {
    setup() {
        const task = reactive({
            title: '',
            description: ''
        })

        const { storeTask, validationErrors, isLoading } = useTasks()
        // onMounted(getTasks)

        return {task, storeTask, validationErrors, isLoading }
    }
}
</script>

