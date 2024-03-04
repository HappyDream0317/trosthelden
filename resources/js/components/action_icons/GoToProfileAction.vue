<template>
    <button @click="trigger">
        <span> Profil anzeigen </span>
    </button>
</template>

<script>
export default {
    name: "GoToProfileAction",
    props: {
        userId: Number,
    },
    methods: {
        trigger() {
            this.$router.push({
                path: "/profile/" + this.userId,
            });
        },
    },
};
</script>

<style scoped lang="scss"></style>
