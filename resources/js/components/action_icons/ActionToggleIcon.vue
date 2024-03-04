<template>
    <div>
        <span v-on:click="openAbstractModal"
            ><fa-icon v-if="hasIcon" :icon="icon"></fa-icon
            >{{ icon_text }}</span
        >
    </div>
</template>

<script>
import AbstractModal from "./AbstractModal";

export default {
    name: "ActionIcon",
    components: { AbstractModal },
    props: {
        toggle_icons: Object,
        user: Object,
        component: Object,
    },
    data: function () {
        return {
            toggle_status: this.component.props.status,
        };
    },
    mounted: function () {},
    computed: {
        icon() {
            if (this.toggle_status) {
                return this.toggle_icons.toggle_true.icon;
            }

            return this.toggle_icons.toggle_false.icon;
        },

        icon_text() {
            if (this.toggle_status) {
                return this.toggle_icons.toggle_true.text;
            }

            return this.toggle_icons.toggle_false.text;
        },
    },
    methods: {
        toggle() {
            this.toggle_status = !this.toggle_status;
            this.$emit("toggle");
        },

        openAbstractModal() {
            this.component.props.status = this.toggle_status;
            this.component.props.callback = this.toggle;

            this.$eventBus.emit("abstract-modal-requested", this.component);
        },
    },
};
</script>

<style scoped></style>
