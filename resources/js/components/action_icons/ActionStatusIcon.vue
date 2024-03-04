<template>
    <div>
        <span v-on:click="openAbstractModal"
            ><fa-icon :icon="icon"></fa-icon
        ></span>
        {{ icon_text }}
    </div>
</template>

<script>
import AbstractModal from "./AbstractModal";

export default {
    name: "ActionStatusIcon",
    props: {
        status_icon: Object,
        user: Object,
        component: Object,
    },
    data: function () {
        return {
            status: this.component.props.status,
        };
    },
    computed: {
        icon() {
            return this.status_icon.icon;
        },

        icon_text() {
            return this.status_icon.text;
        },
    },
    methods: {
        invokeClose() {
            this.$emit("close");
        },
        openAbstractModal() {
            //console.log("OPEN");
            //console.log(this.component);
            this.$eventBus.emit("abstract-modal-requested", this.component);
        },
    },
};
</script>

<style scoped></style>
