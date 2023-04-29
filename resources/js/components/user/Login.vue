<template>
    <div class="col-8 mx-auto">
        <form @submit.prevent="loginUser">
            <h2 class="mb-4 mt-4">Login</h2>
            <div class="mb-3">
                <label for="name" class="form-label">User Name</label>
                <input class="form-control" type="text" name="name" placeholder="Type your username or email" v-model="user.name">
                <Error :error-message="userErrors" key-name="name"/>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Password</label>
                <input class="form-control" type="text" name="password" placeholder="Type your password" v-model="user.password">
                <Error :error-message="userErrors" key-name="password"/>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <router-link :to="{name: 'forgot-password'}" class="col-md-4 float-start">Forgot password?</router-link>

                    <button class="btn btn-primary col-md-4 float-end">Login</button>
                </div>
            </div>

            <div class="txt1 text-center p-t-54 p-b-20 mb-3">
                <span>Or Sign Up Using</span>
            </div>
            <div class="flex-c-m">
                <a href="#" class="login100-social-item bg1">
                    <i class="fa fa-facebook"></i>
                </a>
                <a href="#" class="login100-social-item bg3" @click="loginGoogle">
                    <i class="fa fa-google"></i>
                    Google
                </a>

            </div>
            <div class="text-center">
                <span class="pe-2">Or Sign Up Using</span>
                <router-link :to="{name: 'register'}">Sign Up</router-link>
            </div>
        </form>
    </div>
</template>

<script>
import useUsers from "../../composables/users.js"
import Error from "../template/Error.vue";
import Base from "../../consts/base.js";

export default {
    components: {Error},
    data() {
        const user = {
            'name': '',
            'password': ''
        }

        return { user }
    },
    setup() {
        const { login, googleAuth, userErrors } = useUsers()

        return { login, googleAuth, userErrors }
    },
    methods: {
        loginUser() {
            this.login(this.user)
                .then(response => {
                    if (response !== false) {
                        localStorage.setItem(Base.TOKEN, response.data.token)
                        this.$router.push({name: 'task.index'})
                    }
                })
        },
        loginGoogle() {
            window.location = '/auth/google'
        }
    },
    mounted() {
        const urlParams = new URLSearchParams(window.location.search)
        const token = urlParams.get('token')

        if (token) {
            localStorage.setItem(Base.TOKEN, token)
            this.$router.push({name: 'task.index'})
        }
    }
}
</script>
