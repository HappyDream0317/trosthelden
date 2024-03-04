<template>
    <div class="placeholder_modal_design_dialog_option_accept">
        <div class="placeholder_modal_design_dialog_option_description">
            {{ description }}
        </div>
        <span
            class="placeholder_modal_design_dialog_option"
            v-on:click="handleAction"
            >{{ option }}</span
        >
    </div>
</template>

<script>
import DialogOption from "./DialogOption";
export default {
    name: "DialogOptionAccept",
    props: {
        description: String,
        option: String,
        action: String,
    },
    components: { DialogOption },
    methods: {
        handleAction() {
            this.$emit("action", this.action);
        },
    },
};
</script>

<style scoped>
.placeholder_modal_design_dialog_option_description {
    text-align: center;
    height: 30px;
    margin: 30px 20px 30px 30px;
}
.placeholder_modal_design_dialog_option_accept {
    height: 3rem;
    width: 100%;
}
.placeholder_modal_design_dialog_option {
    display: block;
    margin: 30px 20px 30px 30px;
    float: right;
    background-color: lightgrey;
    position: relative;
}
</style>
