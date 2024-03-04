<template>
    <div v-bind:class="classes">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "MainContent",
    props: {
        hasSidebar: {
            type: Boolean,
            default: true,
        },
    },
    computed: {
        classes: function () {
            return {
                "col-lg-8 col-sm-12 col-md-12": this.hasSidebar,
                "col-12": !this.hasSidebar,
            };
        },
    },
};
</script>

<style scoped></style>
