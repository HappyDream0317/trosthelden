<template>
    <div>
        <span v-on:click="close"><fa-icon icon="times-circle"></fa-icon></span>
    </div>
</template>

<script>
export default {
    name: "DialogExit",
    mounted: function () {
        //console.log(this);
    },
    methods: {
        close() {
            this.$emit("close");
        },
    },
};
</script>

<style scoped></style>
