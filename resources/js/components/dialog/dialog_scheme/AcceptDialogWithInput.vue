<template>
    <DialogLayout>
        <Dialog
            :title="dialog.title"
            :icon="dialog.icon"
            :description="dialog.description"
            @close="invokeClose"
        ></Dialog>
        <div class="placeholder_modal_design_dialog_input">
            <div class="placeholder_modal_design_dialog_input_title">
                {{ input_title }}
            </div>
            <div class="placeholder_modal_design_dialog_input_input">
                <textarea v-model="input_text"></textarea>
            </div>
        </div>
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
import DialogInput from "../dialog_components/DialogInput";
import DialogOptionAccept from "../dialog_components/DialogOptionAccept";
export default {
    name: "AcceptDialog",
    components: { Dialog, DialogLayout, DialogInput, DialogOptionAccept },
    props: {
        dialog: Object,
        option: Object,
        input_title: String,
        callback: Function,
    },
    data: function () {
        return {
            input_text: "",
        };
    },
    methods: {
        invokeClose() {
            this.$emit("close");
        },
        handleAction() {
            this.callback(this.input_text);
        },
    },
};
</script>

<style scoped></style>
