<template>
    <div>
        <DialogActionStandardPopup
            :title="title"
            :icon="icon"
            :description="description"
            :button_text="button_text"
            @close="invokeClose"
        ></DialogActionStandardPopup>
    </div>
</template>

<script>
import DialogActionStandardPopup from "../dialog/dialog_actions/DialogActionStandardPopup";
export default {
    name: "PopUp",
    components: { DialogActionStandardPopup },
    data: function () {
        return {
            title: "Bild-Upload fehlgeschlagen",
            description: "Das Hochladen deines Bildes ist leider gescheitert.",
            button_text: "OK",
            icon: "exclamation-circle",
        };
    },
    mounted: function () {},
    methods: {
        invokeClose() {
            this.$emit("close");
        },
    },
};
</script>

<style scoped></style>
