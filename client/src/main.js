import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import VCalendar from 'v-calendar';
import 'v-calendar/style.css';
import axios from 'axios';
import VueAxios from 'vue-axios'
createApp(App).use(VCalendar, {}).use(VueAxios, axios).use(store).use(router).mount('#app')
