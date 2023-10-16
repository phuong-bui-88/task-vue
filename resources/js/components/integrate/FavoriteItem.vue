<template>
    <div>
        <button @click="toggleFavorite" class="bg-font">
            <font-awesome-icon :icon="favoriteIcon" class="star"/>
        </button>
    </div>
</template>

<script>

export default {
    props: ['item'],
    data() {
        return {
            isFavorite: this.item.is_favorite,
        }
    },
    computed: {
        favoriteIcon() {
            return this.isFavorite ? ['fas', 'star'] : ['far', 'star'];
        },
    },
    methods: {
        toggleFavorite() {
            this.isFavorite = !this.isFavorite
            this.$emit('toggle-favorite', this.item, this.isFavorite)
        },
    },
};

</script>
