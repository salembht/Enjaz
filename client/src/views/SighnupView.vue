<template>
    <div class="container py-5" style="min-height: 90vh">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <h1 class="text-center mb-5">Sign Up</h1>
                <form @submit.prevent="submitForm" class="needs-validation" novalidate ref="form">
                    <div v-for="(field, index) in fields" :key="index" :class="field.width"
                        class="form-group was-validated mb-2">
                        <label class="form-label" :for="field.name">{{
                            field.label
                        }}</label>
                        <template v-if="field.type === 'text' ||
                            field.type === 'email' ||
                            field.type === 'password'
                        ">
                            <input :id="field.name" class="form-control" :type="field.type" :name="field.name"
                                :placeholder="field.placeholder" v-model="formData[field.name]"
                                :required="field.required" :accept="field.accept" />
                        </template>


                    </div>


                    <button type="submit" class="btn btn-primary w-100" :disabled="isSubmitting">
                        {{
                            isSubmitting
                                ? addUser
                                    ? "Adding User ..."
                                    : "Signing Up ..."
                                : addUser
                                    ? "Add New User"
                                    : "Sign Up"
                        }}
                    </button>
                </form>
                <label class="form-check-label" for="remember-me">لدي حساب <router-link to="/login">تسجيل
                        الدخول</router-link></label>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Cookies from "js-cookie";
import Swal from "sweetalert2";

export default {
    props: {
        addUser: Boolean,
    },
    data() {
        return {
            fields: [
                {
                    label: "اسم المستخدم",
                    name: "name",
                    type: "text",
                    width: "col-lg-12",
                    placeholder: "ادخل الاسم  ",
                    required: true,
                    validationMessage: "من فضلك ادخل الاسم ",
                },
                {
                    label: "البريد الالكتروني",
                    name: "email",
                    type: "email",
                    width: "col-lg-12",
                    placeholder: "ادخل الايميل",
                    required: true,
                    validationMessage: "ادخل الايميل من فضلك",
                },
                {
                    label: "كلمة المرور",
                    name: "password",
                    type: "password",
                    width: "col-lg-12",
                    placeholder: "اكتب كلمة المرور",
                    required: true,
                    validationMessage: "من فضلك اكتب كلمة المرور",
                },
                {
                    label: "كرر كلمة المرور",
                    name: "password2",
                    type: "password",
                    width: "col-lg-12",
                    placeholder: "اعد كتابة كلمه المرور",
                    required: true,
                    validationMessage: " من فضلك اعد كتابة كلمه المرور",
                },

            ],
            formData: {
                name: "",
                email: "",
                password: "",
                password2: "",
            },
            isSubmitting: false,
        };
    },
    methods: {
        storeUserCookies(tokens) {
            // first store them in the store state userTokens
            this.$store.state.userTokens = tokens;
            // then store them in the browser cookies
            return new Promise((resolve) => {
                Swal.fire({
                    title: "cookies?",
                    text: "clik on Yes to Allow us to store your tokens in your browser so you can enter our website another time without any need to re-enter your login info.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes, store my tokens!",
                    cancelButtonText: "No",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Cookies.set("userTokens", tokens, { expires: 30 });
                        Swal.fire(
                            "Saved!",
                            "تم تخزين التوكن في متصفحك لمدة ثلاثون يوما.",
                            "success"
                        ).then(() => {
                            resolve();
                        });
                    } else {
                        Cookies.set("userTokens", tokens, { expires: null });
                        resolve();
                    }
                });
            });
        },
        submitForm() {
            if (!this.$refs.form.checkValidity()) {
                Swal.fire({
                    icon: "error",
                    title: "خطاء في الادخال",
                    text: "تحتاج لمئ جميع الخانات",
                });
                return;
            }
            if (this.formData.password != this.formData.password2) {
                Swal.fire({
                    icon: "error",
                    title: "للاسف",
                    text: "كلمات المرور لاتتطابق",
                });
                return;
            }
            this.isSubmitting = true;
            // get all data inside formDataInstance
            // const formDataInstance = new FormData();
            axios
                .post("http://localhost:8000/api/register", this.formData, {
                    headers: {
                        "Content-Type": "application/json",
                    },
                })
                .then((response) => {
                    // Handle successful response
                    this.$router.push("/login");
                    console.log(response.data);
                })
                .catch((error) => {
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        Swal.fire({
                            icon: "danger",
                            title: "Error",
                            text: error.response.data.message,
                        });
                    } else if (error.request) {
                        // The request was made but no response was received
                        console.log(error.request);
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log("Error", error.message);
                    }
                });
        },
        async storeThisNewUserInfo(user, tokens) {
            this.$store.dispatch("login", user);
            await this.storeUserCookies(tokens);
            this.$router.push("/home");

            await Swal.fire({
                icon: "success",
                title: `اهلا ${user.name}`,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        },

    },
};
</script>