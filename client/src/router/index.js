import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
  {
    path: '/login',
    name: 'login',
    component: () => import( '../views/LoginView.vue')
  },
  {
    path: '/Sighnup',
    name: 'Sighnup',
    component: () => import( '../views/SighnupView.vue'),
    
  },
  {
    path: '/UserHome',
    name: 'UserHome',
    component: () => import( '../views/UserHomeView.vue'),
  //   meta: {
  //     requiresAuth: true
  // }
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})


router.beforeEach((to, from, next) => {
    // if (to.matched.some(record => record.meta.requiresAuth) ) {
        const token = localStorage.getItem('authToken');
        if (token) {
            next();
            return;
        } else {
            router.push('/login');
        }
    // }
    //  else {
    //     next();
    // }
});
export default router
