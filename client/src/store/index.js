import { createStore } from 'vuex'

export default createStore({
  state: {
    authenticated: false,
    user:null
  },
  getters: {
    isAuthenticated(state) {
      return state.user !== null
    }
  },
  mutations: {
  },
  actions: {
    login({ commit }, user) {
      // Perform login logic here
      commit('setUser', user)
    },
    logout({ commit }) {
      // Perform logout logic here
      commit('setUser', null)
    }
  },
  modules: {
  }
})
