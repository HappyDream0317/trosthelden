<template>
    <div>
        <div class="placeholder_modal_design_upper_dialog">
            <DialogTitle
                style="float: left"
                :title="title"
                :icon="icon"
            ></DialogTitle>
            <DialogExit style="float: right" @close="close"></DialogExit>
        </div>
        <div class="placeholder_modal_design_lower_dialog">
            <DialogDescription
                v-if="showDescription"
                :description="description"
            ></DialogDescription>
        </div>
    </div>
</template>

<script>
import DialogTitle from "./DialogTitle";
import DialogExit from "./DialogExit";
import DialogDescription from "./DialogDescription";
import DialogInput from "./DialogInput";
export default {
    name: "Dialog",
    components: { DialogTitle, DialogExit, DialogDescription, DialogInput },
    props: {
        title: String,
        icon: String,
        description: String,
    },
    computed: {
        showDescription() {
            return this.description !== "";
        },
    },
    methods: {
        close() {
            this.$emit("close");
        },
    },
};
</script>

<style scoped>
.placeholder_modal_design_upper_dialog {
    display: block;
    height: 2rem;
    width: 100%;
    background-color: lightgray;
    position: relative;
}

.placeholder_modal_design_dialog_title {
}
</style>
