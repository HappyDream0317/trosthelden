<template>
    <div>
        <span v-on:click="chooseOption">{{ option }}</span>
    </div>
</template>

<script>
export default {
    name: "DialogOption",
    props: {
        option: String,
        action: String,
    },
    methods: {
        chooseOption() {
            this.$emit("action", this.action);
        },
    },
};
</script>

<style scoped></style>
