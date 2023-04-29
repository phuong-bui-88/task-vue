<template>
    <div class="signup-form col-8 mx-auto">
        <h2 class="my-4">Sign Up</h2>
        <form @submit.prevent="signupUser" class="mb-3">
            <div class="mb-3">
                <label for="email" class="form-label">Name</label>
                <input type="text" class="form-control" id="email" v-model="user.name">
                <Error :error-message="userErrors" key-name="name"/>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" v-model="user.email">
                <Error :error-message="userErrors" key-name="email"/>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" v-model="user.password">
                <Error :error-message="userErrors" key-name="password"/>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation"
                       v-model="user.password_confirmation">
                <Error :error-message="userErrors" keyName="password_confirmation"/>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Sign Up</button>
            </div>
        </form>

        <div class="row mb-3">
            <div class="col-12">
                <router-link :to="{name: 'forgot-password'}" class="col-md-4 float-start">Forgot password?</router-link>
                <router-link :to="{name: 'login'}" class="float-end col-md-3 text-center">Login</router-link>
            </div>
        </div>
    </div>
</template>

<script>
import useUsers from "../../composables/users.js"
import Error from "../template/Error.vue"

export default {
    components: {Error},
    data() {
        const user = {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        }

        return {user}
    },
    setup() {
        const {signup, userErrors} = useUsers()

        return {signup, userErrors}
    },
    methods: {
        signupUser() {
            this.signup(this.user)
                .then(response => {
                    if (response === true) {
                        this.$router.push({name: 'login'})
                    }
                })
        }
    }
}

</script>
