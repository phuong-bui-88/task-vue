<template>
    <ul>
        <li>Home</li>
        <li><router-link :to="{name: 'task.index'}">Tasks</router-link></li>
        <li><router-link :to="{name: 'task.create'}">Create Task</router-link> </li>
    </ul>
</template>

<script>
    export default {
      props: ['title']
    }
</script>
