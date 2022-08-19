import './bootstrap';

import { createApp } from "vue"
import App from "./App.vue"

import router from "./routes/index";

const app = createApp(App).use(router)

app.mount("#app");
