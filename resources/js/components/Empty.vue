<template>
    <div>CallBack</div>
</template>

<script>
    import useUsers from "../composables/users.js";

    export default {
        setup() {
            console.log('before me')
            const { googleAuth } = useUsers()
            googleAuth()
                .then(response => {
                    localStorage.setItem(Base.TOKEN, response)
                })
            console.log('test me')
        }
    }
</script>
