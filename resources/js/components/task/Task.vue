<template>
  <div class="col-md-12">
    <h2>Tasks</h2>
      <div class="row">
          <div class="col-md-9">
            <router-view class="col-9" @item-click="taskClick" />
          </div>
          <div class="col-md-3">
            <router-view name="right" ref="childComponent" :samePage="samePage" />
          </div>
      </div>
  </div>
</template>

<script>

import { useRoute } from "vue-router";

export default {
    setup() {
         const route = useRoute()
        return {route}
    },
    data() {
        return {
            samePage: ''
        }
    },
    methods: {
        taskClick(id) {
            console.log('t')
            this.samePage = (id == this.route.params.taskId)
        }

    }
}

</script>
