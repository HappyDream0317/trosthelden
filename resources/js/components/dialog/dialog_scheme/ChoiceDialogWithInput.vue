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
            <DialogOptionChoice
                :options="options"
                @action="handleAction"
            ></DialogOptionChoice>
        </div>
    </DialogLayout>
</template>

<script>
import DialogLayout from "../dialog_layout/DialogLayout";
import DialogInput from "../dialog_components/DialogInput";
import DialogOptionChoice from "../dialog_components/DialogOptionChoice";
export default {
    name: "ChoiceDialogWithInput",
    components: { DialogLayout, DialogInput, DialogOptionChoice },
    props: {
        dialog: Object,
        options: Object,
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
        handleAction(action) {
            this.callback(action, this.input_text);
        },
    },
};
</script>

<style scoped></style>
