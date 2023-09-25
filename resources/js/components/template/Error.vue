<template>
    <div>
        <div v-if="errorMessage && errorMessage[keyName]">
            <div class="alert alert-danger mt-3">{{ errorMessage[keyName][0] }}</div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        errorMessage: Object,
        keyName: String,
    }
}

</script>
