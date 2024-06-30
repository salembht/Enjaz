import { createStore } from 'vuex'
import axios from 'axios'
import router from '@/router' // Assuming you have a router instance
import Swal from "sweetalert2";
export default createStore({
  state: {
    user: null,
    token: null,
    isLoggedIn: this.isLoggedIn
  },
  getters: {
    isLoggedIn(state) {
      return state.token !== null
    },
    getUser(state) {
      return state.user
    },
  },
  mutations: {
    SET_USER(state, user) {
      state.user = user
    },
    SET_TOKEN(state, token) {
      state.token = token
    },
    CLEAR_USER_AND_TOKEN(state) {
      state.user = null
      state.token = null
    }
  },
  actions: {
    /* eslint-disable */
    
    async login({ commit }, { email, password  }) {
      try {
        const response = await axios.post('http://localhost:8000/api/login', {
          email,
          password,
        
        })
        commit('SET_USER', response.data.user)
        commit('SET_TOKEN', response.data.authorisation.token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.authorisation.token}`;
        return response
      } catch (error) {
        throw error
      }
    },
    async logout({ commit }) {
      try {
        // Make a POST request to the logout endpoint
       const response= await axios.post('http://localhost:8000/api/logout')
        // Clear the user and token from the state
        commit('CLEAR_USER_AND_TOKEN')
        localStorage.removeItem('authToken');
        delete axios.defaults.headers.common['Authorization'];
        // Redirect the user to the login page
        Swal.fire({
          icon: 'success',
          title: 'وداعا ',
          text: `لقد تم تسجيل خروجك بنجاح`
        })
        router.push('/login')
      } catch (error) {
        // Handle the error
        console.error(error)
      }
    },
    async fetchUser({ commit, state }) {
      try {
        if (state.token) {
          const response = await axios.get('http://localhost:8000/api/user', {
              headers: {      
                Authorization: `Bearer ${state.token}`
              }
          })
          commit('SET_USER', response.data)
        } else {
          // User is not authenticated, redirect to the login page
          commit('CLEAR_USER_AND_TOKEN')
          router.push('/login')
        }
      } catch (error) {
        if (error.response.status === 401) {
          // User is not authenticated, redirect to the login page
          commit('CLEAR_USER_AND_TOKEN')
          router.push('/login')
        } else {
          console.error(error)
        }
      }
    }
  }
})