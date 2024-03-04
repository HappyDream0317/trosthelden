<template>
    <div
        class="interaction-modal-wrapper"
        tabindex="-1"
        role="dialog"
        v-if="isModalActivated"
    >
        <div class="interaction-modal-box rounded">
            <button @click="close" class="closebtn">
                <fa-icon icon="times-circle" />
            </button>
            <component :is="component" v-bind="props" @close="close" />
        </div>
    </div>
</template>

<script>

export default {
    name: "Modal",
    data() {
        return {
            isModalActivated: false,
            component: null,
            props: {},
        };
    },
    mounted() {
        this.$eventBus.on("modal-requested", (modalContext) => {
            this.component = modalContext.component;
            this.props = modalContext.props;
            this.isModalActivated = true;
        });
    },
    methods: {
        close() {
            this.isModalActivated = false;
            this.component = null;
            this.props = {};
        },
    },
    beforeDestroy() {
        this.$eventBus.off("modal-requested");
    },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.interaction-modal-wrapper {
    background-color: rgba(0, 0, 0, 0.2);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 0.625rem;
    z-index: 666;
}
.interaction-modal-box {
    position: relative;
    background-color: #fff;
    margin: 10vh auto;
    @media screen and (max-width: 768px) {
        max-height: 80vh;
        overflow: hidden;
        overflow-y: scroll;
    }
    @media (min-width: 992px) {
        width: 75%;
        max-width: 800px;
    }

    & > button {
        position: absolute;
        right: 20px;
        top: 20px;
        color: $brand-color-primary;
        font-size: 2rem;
        border: none;
        background: none;
        z-index: 999;
        padding-right: 0;

        @media (max-width: 425px) {
            font-size: 1.3rem;
        }
    }
}
</style>
