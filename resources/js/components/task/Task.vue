<template>
    <div class="row parent">
        <div class="col-md-3 list-group">
            <router-view name="left"/>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-9 list-group">
                    <router-view/>
                </div>

                <div class="col-md-3">
                    <router-view name="right" ref="childComponent"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

</script>
