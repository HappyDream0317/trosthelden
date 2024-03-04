<template>
    <div
        class="interaction-modal-wrapper"
        tabindex="-1"
        role="dialog"
        v-if="isModalActivated"
    >
        <div>
            <div class="interaction-modal-box rounded">
                <component :is="component" v-bind="props" @close="close" />
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "AbstractModal",
    props: {},
    data() {
        return {
            isModalActivated: false,
            component: null,
            props: {},
        };
    },
    mounted() {
        this.$eventBus.on("abstract-modal-requested", (modalContext) => {
            this.component = modalContext.component;
            this.props = modalContext.props;
            this.isModalActivated = true;
        });
    },
    methods: {
        modalAction(params) {
            this.$emit("modalAction", params);
        },

        close() {
            this.isModalActivated = false;
            this.component = null;
            this.props = {};
        },
    },
    beforeDestroy() {
        this.$eventBus.off("abstract-modal-requested");
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.interaction-modal-wrapper {
    background-color: rgba(0, 0, 0, 0.2);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 0.625rem;
    z-index: 200;
}
.interaction-modal-box {
    position: relative;
    background-color: #fff;
    margin: 10vh auto;
    width: 50%;
    overflow: auto;

    & > button {
        color: $brand-color-primary;
        font-size: 1.5rem;
        border: none;
        background: none;
        float: right;
    }
}
</style>
