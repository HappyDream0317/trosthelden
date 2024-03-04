<template>
    <DialogLayout v-if="hasLoaded">
        <Dialog
            :title="dialog.title"
            :icon="dialog.icon"
            :description="dialog.description"
            @close="invokeClose"
        ></Dialog>
        <DialogOptionAccept
            :description="option.description"
            :option="option.option"
            :action="option.action"
            @action="handleAction"
        ></DialogOptionAccept>
    </DialogLayout>
</template>

<script>
import DialogLayout from "../dialog_layout/DialogLayout";
import Dialog from "../dialog_components/Dialog";
import DialogOptionAccept from "../dialog_components/DialogOptionAccept";
export default {
    name: "AcceptDialog",
    components: { Dialog, DialogLayout, DialogOptionAccept },
    props: {
        dialog: Object,
        option: Object,
    },
    data: function () {
        return {
            hasLoaded: false,
        };
    },
    mounted: function () {
        //console.log("ACCEPTDIALOG");
        this.hasLoaded = true;
    },
    methods: {
        invokeClose() {
            this.$emit("close");
        },

        handleAction(action) {
            this.$emit("action", action);
        },
    },
};
</script>

<style scoped></style>
