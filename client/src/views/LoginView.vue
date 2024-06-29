<template>
  <div class="container py-5" style="min-height: 90vh">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <h1 class="text-center mb-5">تسجيل دخول</h1>
        <form @submit.prevent="submitForm" class="needs-validation" novalidate ref="form">
          <div class="form-group mb-3">
            <label for="email">الايميل</label>
            <input type="email" class="form-control" id="email" name="email" v-model="email" required />
            <div class="invalid-feedback">
              فضلا ادخل الايميل </div>
          </div>
          <div class="form-group mb-3">
            <label for="password">كلمة المرور</label>
            <input type="password" class="form-control" id="password" name="password" v-model="password" required />
            <div class="invalid-feedback">يرجى ادخال كلمه المرور.</div>
          </div>
          <div class="form-group form-check mb-3">
            <input type="checkbox" class="form-check-input border-2" id="remember-me" name="remember-me"
              v-model="rememberMe" />
            <label class="form-check-label" for="remember-me">تذكرني</label>
          </div>
          <label class="form-check-label" for="remember-me">ليس لدي حساب ؟ <router-link
              to="/Sighnup">تسجيل</router-link></label>
          <button type="submit" class="btn btn-primary w-100" :disabled="isSubmitting">
            {{ isSubmitting ? "Logging in..." : "Login" }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
// eslint-disable-next-line no-unused-vars
import axios from "axios";
import Swal from "sweetalert2";
// eslint-disable-next-line no-unused-vars
import { mapGetters, mapActions } from 'vuex'
import Cookies from "js-cookie";
export default {
  data() {
    return {
      email: "",
      password: "",
      rememberMe: false,
      isSubmitting: false,

    };
  },
  methods: {
    ...mapActions(['login']),
    // this.$refs.form.checkValidity()
    async submitForm() {
      if (true) {
        this.isSubmitting = true
        try {
          // eslint-disable-next-line no-unused-vars
          const response = await this.login({
            email: this.email,
            password: this.password,
          })
          if (this.rememberMe) {
              Cookies.set("userTokens", response.data.authorisation.token, { expires: 30 });
            }else{
              Cookies.set("userTokens", response.data.authorisation.token, { expires: null });
            }
           localStorage.setItem('authToken', response.data.authorisation.token);
           axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.authorisation.token}`;
           this.$router.push('/UserHome')
          Swal.fire({
            icon: 'success',
            title: 'اهلا بك',
            text: `لقد تم تسجيل دخولك بنجاح`
          })
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error
          })
        } finally {
          this.isSubmitting = false
        }
      } else {
        this.$refs.form.classList.add('was-validated')
      }
    },

  },
  mounted() {

  },
};
</script>