<template>
    <div class="container">
        <div class="row">
            <div class="sidebar-right col-4">
                <MenuRight />
            </div>
            <div class="col-8">
                <RouterView/>
            </div>
        </div>
    </div>
</template>

<script>
    import MenuRight from "./components/MenuRight.vue"

    export default {
        components: {
            MenuRight
        }
    }
</script>
