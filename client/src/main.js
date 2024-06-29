import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import VCalendar from 'v-calendar';
import 'v-calendar/style.css';
// import axios from 'axios';

// const app =createApp(App);
// app.config.productionTip = false;

// axios.defaults.withCredentials = true;

// const token = localStorage.getItem('token');
// if (token) {
//   axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
// }
createApp(App).use(VCalendar, {}).use(store).use(router).mount('#app')
