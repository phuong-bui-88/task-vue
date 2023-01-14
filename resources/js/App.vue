<template>
    <div class="container">
        <div class="row">
            <div class="sidebar-right col-3">
                <MenuRight />
                <DotCalendar />
            </div>
             <div class="col-9">
                 <router-view />
             </div>
        </div>
    </div>
</template>

<script>
    import MenuRight from "./components/MenuRight.vue"
    import DotCalendar from "./components/integrate/DotCalendar.vue"

    export default {
        components: {
            MenuRight,
            DotCalendar
        }
    }
</script>
