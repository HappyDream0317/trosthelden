<template>
    <AcceptDialog
        :dialog="dialog"
        :option="option"
        @close="invokeClose"
        @action="handleAction"
    ></AcceptDialog>
</template>

<script>
import AcceptDialog from "../dialog_scheme/AcceptDialog";
export default {
    name: "DialogActionStandardPopup",
    components: { AcceptDialog },
    props: {
        title: String,
        icon: String,
        description: String,
        button_text: String,
    },
    data: function () {
        return {
            dialog: {
                title: this.title,
                icon: this.icon,
                description: this.description,
            },
            option: {
                description: "",
                option: this.button_text,
                action: "close",
            },
            actions: {
                close: this.invokeClose,
            },
        };
    },
    methods: {
        invokeClose() {
            this.$emit("close");
        },
        handleAction(action) {
            this.actions[action]();
        },
    },
};
</script>

<style scoped></style>
