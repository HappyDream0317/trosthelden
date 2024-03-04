<template>
    <div class="wrapper rounded">
        <h4 v-if="title !== null" class="color--primary">{{ title }}</h4>
        <p class="header pe-2" :class="messageClass" v-html="message"></p>
        <div class="dialog-actions">
            <button @click="close">{{ buttonTitle }}</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "InformationMessage",
    props: {
        title: {
            type: String,
            default: null,
        },
        message: String,
        buttonTitle: {
            type: String,
            default: "Schließen",
        },
        messageClass: {
            type: String,
            default: null,
        },
    },
    data() {
        return {};
    },
    methods: {
        close() {
            this.$emit("close");
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
