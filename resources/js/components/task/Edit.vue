<template>

    <div v-for="message in validationErrors?.title">
        {{ message }}
    </div>

    <div class="d-none" data-bs-toggle="offcanvas" href="#edit-task" ref="firstUserName">First</div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="edit-task" :item="task.id" data-bs-scroll="true" data-bs-backdrop="false" ref="username">
        <div class="offcanvas-body">
            <div class="col-md-12">
                <a class="d-none" data-bs-dismiss="offcanvas" ref="close">Close</a>
                 <button class="btn btn-secondary dropdown-toggle" type="button" id="edit-task-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    ...
                 </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"  @click="onDeleteTask()" data-bs-dismiss="offcanvas">Delete</a></li>
                </ul>
            </div>
            <div class="mb-3">
                <label for="task-title" class="form-label">Title</label>
                <input v-model="task.title" id="task-title" class="form-control" type="text" @keyup="$emit('changedTitle', indexItem, $event.target.value)">

                <div v-for="message in validationErrors?.title">
                    {{ message }}
                </div>
            </div>

            <div class="mb-3">
                <label for="task-description" class="form-label">Description</label>
                <QuillEditor id="task-description" ref="quillEditor" />
            </div>

            <div class="mb-3">
                <file-pond
                        name="test"
                        ref="pond"
                        label-idle="Drop files here or <span class='filepond--label-action'>Browse</span>"
                        allow-multiple="true"
                        v-bind:files="myFiles"
                        :server="{load, process}"
                />
            </div>

        </div>
    </div>
</template>

<script>
import {onMounted, onUpdated, reactive, ref} from "vue";
import useTasks from "../../composables/tasks.js";
import { useRoute } from "vue-router";
import task from "./Task.vue";
import axios from "axios";

import {uploadFilePond} from "../../composables/file_pond.js";
// import {QuillEditor} from "@vueup/vue-quill";
// import { ImageDrop } from "quill-image-drop-module";

export default {
    // components: {QuillEditor},
    props: {
        isSamePage: Boolean,
        taskId: String,
        indexItem: Number,
    },
    emits: ['changedTitle', 'deletedTask', 'resetIsSamePage'],

    data() {
        let myFiles = []

        return { myFiles }
    },
    setup(props) {

        const { task, getTask, updateTask, destroyTask, validationErrors, isLoading } = useTasks()
        const route = useRoute()
        // isOpenCloseTaskStatus is open or close task status
        const {isClosedTask, isOpenTaskStatus} = ref(false)

        onMounted(() => {
            if (props.taskId !== undefined) {
                getTask(props.taskId)
            }
        })

        return { task, route, isClosedTask, isOpenTaskStatus, getTask, updateTask, destroyTask, validationErrors, isLoading }
    },

    watch: {
        task: function (newVal, oldVal) {
            if (newVal) {
                this.$refs.quillEditor.setHTML(newVal.description)

                let myFiles = []
                newVal.documents.map(function(value, key) {
                    myFiles.push({
                        source: value,
                        options: { type: 'local'}
                    })
                })

                this.myFiles = myFiles
            }
        },
        isSamePage: function (newVal, oldVal) {
            if (newVal === true) {
                // case open status is true then
                if (this.isOpenTaskStatus === true) {
                    this.onCloseTaskAction()
                }
                else {
                    this.isOpenTaskStatus = true
                }

                this.$emit('resetIsSamePage', false)
            }

        },
        $route(to, from) {
            if (to.params.taskId) {
                this.getTask(to.params.taskId)

                this.isOpenTaskStatus = true
            }
        }
    },
    methods:{
        closingTask(event) {
            if (this.isClosedTask == true) {
                return
            }

            if (this.isSamePage == false) {
                event.preventDefault()
            }

            this.task.description = this.$refs.quillEditor.getHTML()
            this.updateTask(this.task)
        },
        onDeleteTask() {
            this.destroyTask(this.task.id)
            this.$emit('deletedTask')
            this.onCloseTaskAction()
        },
        onCloseTaskAction() {
            this.isClosedTask=true
            this.$refs.close.click()
            this.isClosedTask=false
            this.isOpenTaskStatus = false
        },
        load(url, load, error, progress, abort, headers) {
            fetch(url)
                .then(res => {
                    res.blob().then(load)
                })
            // https://zelen-co.com/articles/file-upload-with-vuejs-filepond-and-firebase/
        },
        process(fileName, file, metadata, load, error, progress, abort) {
            const field = {
                name: 'task',
                id: this.task.id
            }

            uploadFilePond(file, progress, field)
                .then(url => {
                    load(url)
                })
        },
    },
    mounted() {
        this.$refs.firstUserName.click()
        this.$refs.username.addEventListener('hide.bs.offcanvas', this.closingTask.bind())
    }
}
</script>

