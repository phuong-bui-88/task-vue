<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <h1 class="text-center mb-4">Reset Password</h1>
                <form @submit.prevent="resetPasswordUser">
                    <input type="hidden" name="token" :value="token">
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter new password" v-model="user.password">
                        <Error :error-message="userErrors" key-name="password" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation"
                               placeholder="Confirm new password" v-model="user.password_confirmation">
                        <Error :error-message="userErrors" key-name="password_confirmation" />
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

                <div class="d-flex justify-content-between mt-4">
                    <router-link :to="{name: 'login'}">Login</router-link>
                    <router-link :to="{name: 'register'}">Register</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import useUsers from "../../composables/users.js"
    import { useRouter } from "vue-router"
    import Error from "../template/Error.vue"
    import Index from "../task/Index.vue"
    import { useToast } from "vue-toastification"

    export default {
        props: ['token'],
        components: {Index, Error},
        setup() {
            const user = {
                token: '',
                password: '',
                password_confirmation: ''
            }

            const router = useRouter()
            const toast = useToast()

            const { resetPassword, userErrors } = useUsers()

            return { user, resetPassword, userErrors, router, toast }
        },
        methods: {
            resetPasswordUser() {
                this.user.token = this.token

                this.resetPassword(this.user)
                    .then((response) => {
                        if (response === true) {
                            this.router.push({ name: 'login' })
                            this.toast.info('Your password has been successfully reset. You can now log in using your new password.')
                        }
                    })
            }
        }
    }
</script>
