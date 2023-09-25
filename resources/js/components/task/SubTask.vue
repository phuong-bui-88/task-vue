<template>
    <div>221121</div>
    <div @click="sendTask(2)">Send message from subtask</div>

    <button @click="(e) => $emit('increaseBy', 1)">
      Increase by 1
    </button>
</template>

<script>

    import {onMounted, reactive} from "vue";

    export default {
        data() {
            return {
                message: 'init',
                count: 0,
            }
        },
        emits: ['printMessage'],
        methods: {
            sendTask(id) {
                this.$emit('printMessage', id)
            }
        }
    }
</script>
