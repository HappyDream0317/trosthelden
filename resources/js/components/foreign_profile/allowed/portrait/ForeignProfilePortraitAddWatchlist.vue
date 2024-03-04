<template>
    <fa-icon v-on:click="toggleWatchlist" icon="toggleWatchlistIcon"></fa-icon>
</template>

<script>
export default {
    name: "ForeignProfilePortraitAddWatchlist",
    data: function () {
        return {
            on_watchlist: false,
        };
    },
    computed: {
        toggleWatchlistIcon() {
            if (this.on_watchlist) {
                return "star-o";
            }
            return "star";
        },
    },
    methods: {
        toggleWatchlist() {},
    },
};
</script>

<style scoped></style>
