<template>
    <div>Menu</div>
    <ul>
        <li><router-link :to="{name: 'home'}">Home</router-link></li>
        <li><router-link :to="{name: 'task.index'}">Tasks</router-link></li>
    </ul>
</template>
