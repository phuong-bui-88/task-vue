import './bootstrap';

import { createApp, defineComponent } from "vue"
import {Quill} from "@vueup/vue-quill"
import { ImageDrop } from "quill-image-drop-module"
import ImageUploader from "quill-image-uploader"
import { QuillEditor } from "@vueup/vue-quill"
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import axios from "axios"

import App from "./App.vue"
import router from "./routes/index"

// Import Vue FilePond
import vueFilePond from 'vue-filepond';

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import FilePond plugins
// Please note that you need to install these plugins separately

// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

const FilePond = vueFilePond(
      FilePondPluginFileValidateType,
      FilePondPluginImagePreview
)

Quill.register('modules/imageDrop', ImageDrop)
Quill.register('modules/imageUploader', ImageUploader)

const globalOptions = {
    modules: {
        toolbar: [
              ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
              ['blockquote', 'code-block'],

              // [{ 'header': 1 }, { 'header': 2 }],               // custom button values
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              // [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
              [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
              // [{ 'direction': 'rtl' }],                         // text direction

              [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
              // [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

              // [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
              // [{ 'font': [] }],
              [{ 'align': [] }],
              // ['clean'],
              ['image']
        ],
        // imageDrop: true,
        imageUploader: {
            upload: (file) => {
                return new Promise((resolve, reject) => {
                    // console.log('post', file)
                    const formData = new FormData()
                    formData.append("image", file)

                    axios.post('/upload-image', formData)
                        .then(res => {
                            resolve(res.data)
                        })
                        .catch(err => {
                            reject("Upload failed")
                            console.error("Error:", err)
                        })
                })
            }
        }
    },
    theme: 'snow'
}

QuillEditor.props.globalOptions.default = () => globalOptions

const app = createApp(App).use(router)

app.component('QuillEditor', QuillEditor)
app.component('FilePond', FilePond)

app.mount("#app");
