<template>
    <div class="card text-center col-8 mt-3 mx-auto">
        <div class="card-header h5 text-white bg-primary">Password Reset</div>
        <div class="card-body px-5">
            <p class="card-text py-2">
                Enter your email address and we'll send you an email with instructions to reset your password.
            </p>

            <div class="form-outline">
                <input type="email" id="typeEmail" class="form-control my-3" v-model="user.email"/>
            </div>

            <a @click="forgotPasswordUser" class="btn btn-primary w-100">Reset password</a>

            <div class="d-flex justify-content-between mt-4">
                <router-link :to="{name: 'login'}">Login</router-link>
                <router-link :to="{name: 'register'}">Register</router-link>
            </div>
        </div>
    </div>
</template>
<script>
    import useUsers from "../../composables/users.js"
    import { useToast } from "vue-toastification"

    export default {
        setup() {
            const user = {
                email: ''
            }

            const { forgotPassword } = useUsers()
            const toast = useToast()

            return { user, forgotPassword, toast }
        },
        methods: {
            forgotPasswordUser() {
                this.forgotPassword(this.user)
                    .then((response) => {
                        if (response === true) {
                            this.toast.info('Forgot Password is sent to your emails')
                        }
                    })
            }
        }
    }
</script>
