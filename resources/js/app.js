import './bootstrap';

import { createApp, defineComponent } from "vue"
import { QuillEditor } from "@vueup/vue-quill"
import { setupQuill } from "./composables/vue_quill.js"
import { setupFilePond } from "./composables/file_pond.js"

import 'v-calendar/dist/style.css'
import { SetupCalendar, Calendar, DatePicker } from 'v-calendar'
import DateFilter from  "./filters/date.js"

import App from "./App.vue"
import router from "./routes/index"
import GlobalConst from "./consts/base.js"

setupQuill(QuillEditor)
const FilePond = setupFilePond()

const app = createApp(App).use(router)
app.use(SetupCalendar, {masks: { input: GlobalConst.DATE_FORMAT }})

app.component('QuillEditor', QuillEditor)
app.component('FilePond', FilePond)
app.component('Calendar', Calendar)
app.component('DatePicker', DatePicker)
// app.filter('date', DateFilter)

app.mount("#app");
