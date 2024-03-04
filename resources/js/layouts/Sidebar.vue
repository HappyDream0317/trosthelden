<template>
    <div v-bind:class="classes">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "Sidebar",
    props: {
        mobileOnTop: {
            default: true,
            type: Boolean,
        },
    },
    data: function () {
        return {
            defaultClasses: "col-lg-4 col-sm-12 col-md-12",
            mobileOnTopClasses: "order-first order-lg-0",
        };
    },
    computed: {
        classes: function () {
            if (this.mobileOnTop) {
                return `${this.defaultClasses} ${this.mobileOnTopClasses}`;
            } else {
                return this.defaultClasses;
            }
        },
    },
};
</script>

<style scoped></style>
