import './bootstrap';

import { createApp, defineComponent } from "vue"
import { QuillEditor } from "@vueup/vue-quill"
import { setupQuill } from "./composables/vue_quill.js"
import { setupFilePond } from "./composables/file_pond.js"
import draggable from 'vuedraggable'

import 'v-calendar/dist/style.css'
import { Calendar, DatePicker } from 'v-calendar'
// import  { SetupCalendar } from './calendar.js'
import DateFilter from  "./filters/date.js"

import Toast from "vue-toastification"
// Import the CSS or use your own!
import "vue-toastification/dist/index.css"

import App from "./App.vue"
import router from "./routes/index"
import GlobalConst from "./consts/base.js"
const options = {
    // You can set your default options here
}


setupQuill(QuillEditor)
const FilePond = setupFilePond()

const app = createApp(App).use(router)
// app.use(SetupCalendar, {masks: { input: GlobalConst.DATE_FORMAT }})

app.component('QuillEditor', QuillEditor)
app.component('FilePond', FilePond)
app.component('VCalendar', Calendar)
app.component('DatePicker', DatePicker)
// app.component('draggable', draggable)
app.use(Toast, options)

app.mount("#app");
