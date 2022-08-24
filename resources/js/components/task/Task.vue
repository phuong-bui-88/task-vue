<template>
  <div class="row parent">
      <div class="col-md-9 list-group">
        <router-view @clicked-item="onTaskClick" :changedTitle="changedTitle" :indexItem="indexItem" :isDeletedItem="isDeletedItem" @reset-is-deleted-item="isDeletedItem = $event"/>
      </div>

      <div class="col-md-3">
        <router-view name="right" ref="childComponent" :isSamePage="isSamePage" :indexItem="indexItem" @changed-title="onChangeTitle" @deleted-task="onDeletedTask" @reset-is-same-page="isSamePage = $event"/>
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
            isSamePage: false,
            isDeletedItem: false,
            indexItem: 0,
            changedTitle: '',
        }
    },
    methods: {
        onTaskClick(id, index) {
            this.isSamePage = (id == this.route.params.taskId)
            this.indexItem = index
        },
        onChangeTitle(index, title) {
            this.changedTitle = title
        },
        onDeletedTask() {
            this.isDeletedItem = true
        }
    }
}

</script>
