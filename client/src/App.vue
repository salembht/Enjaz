<template>
  <div class="container">
    <header
      class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <div class="col-md-3 mb-2 mb-md-0">
        إنجاز
      </div>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-start mb-md-0">
        <li> <router-link class="nav-link px-2 link-secondary" to="/">الصفحة الرئسية</router-link> </li>
        <li v-if="isLoggedIn"> <router-link class="nav-link px-2 link-secondary" to="/UserHome"> مهماتي </router-link>
        </li>
      </ul>

      <div class="col-md-3 text-end">
        <button v-if="!isLoggedIn" type="button" class="btn btn-outline-primary me-2">
          <router-link class="nav-link px-2 " to="/login">تسجيل دخول</router-link>
        </button>
        <button v-if="!isLoggedIn" type="button" class="btn btn-primary m-3">
          <router-link class="nav-link px-2 " to="/Sighnup">تسجيل </router-link>
        </button>
        <button v-if="isLoggedIn" type="button" class="btn btn-outline-danger me-2" @click="logout">
          تسجيل خروج
        </button>
      </div>
    </header>

    <div v-if="showLoginMessage" class="login-message">
      تحتاج لستجيل الدخول اولا
    </div>
  </div>
  <router-view />
</template>

<script>

import { mapGetters, mapActions } from 'vuex'

export default {
  data() {
    return {
      loading: true,
      showLoginMessage: false
    }
  },
  created() {
    this.fetchUser()
    this.checkAuthentication()
  },
  computed: {
    ...mapGetters(['getUser', 'isLoggedIn'])
  },
  methods: {
    ...mapActions(['logout']),
    async fetchUser() {
      try {
        await this.$store.dispatch('fetchUser')
      } catch (error) {
        if (error.response.status === 401) {
          this.showLoginMessage = true
          this.$router.push('/login')
        } else {
          console.error(error)
        }
      } finally {
        this.loading = false
      }
    },
    navigateTo(route) {
      if (this.$store.getters.isLoggedIn) {
        this.$router.push(route)
      } else {
        this.showLoginMessage = true
        this.$router.push('/login')
      }
    },
    checkAuthentication() {
      const authToken = localStorage.getItem('authToken')
      if (authToken) {
        // Dispatch an action to set the user's authenticated state
        this.$store.dispatch('setUserAuthenticated', { authToken })
      }
    }
  },
  mounted() {

  },

}
</script>