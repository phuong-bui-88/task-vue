<template>
    <div>Menu</div>
    <ul>
        <li><router-link :to="{name: 'home'}">Home</router-link></li>
        <li><router-link :to="{name: 'task.index'}">Tasks</router-link></li>
        <a @click="signOutUser" href="#">Sign Out</a>
    </ul>
    <div class="row mt-3">
        <DotCalendar/>
    </div>
</template>

<script>
    import DotCalendar from "./integrate/DotCalendar.vue"
    import useUsers from "../composables/users.js"
    import Base from "../consts/base.js"
    export default {
        components: {
            DotCalendar
        },
        setup() {
            const { logout } = useUsers()
            return { logout }
        },
        methods: {
            signOutUser() {
                this.logout()
                    .then((response) => {
                        if (response === true) {
                            localStorage.removeItem(Base.TOKEN)
                            this.$router.push({name: 'login'})
                        }
                    })
            }
        }
    }
</script>
