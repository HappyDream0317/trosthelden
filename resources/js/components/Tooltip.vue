<template>
    <div
        class="th-tooltip"
        @mouseenter="showText"
        @mouseleave="hideText"
        :class="addClass"
    >
        <fa-icon class="icon" icon="question-circle" />
        <div class="th-tooltip-window" :class="textClass">
            <div class="th-tooltip-content" ref="text">
                <h3 v-if="title">{{ title }}</h3>
                <p class="color--darker-grey">{{ content }}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Tooltip",
    props: {
        title: {
            type: String,
            default: null,
        },
        content: {
            type: String,
            default: null,
        },
        addClass: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            visible: false,
            textPos: "right",
            visibilityCheck: null,
        };
    },
    methods: {
        showText() {
            this.visible = true;
            this.$nextTick().then(() => {
                this.adjustPosition();
            });
        },
        hideText() {
            this.visible = false;
            this.textPos = "left";
        },
        adjustPosition() {
            let bounding = this.$refs.text.getBoundingClientRect();
            if (
                bounding.right >
                (window.innerWidth || document.documentElement.clientWidth)
            ) {
                this.textPos = "bottom";
                return;
            }
        },
    },
    computed: {
        textClass() {
            return {
                visible: this.visible,
            };
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.th-tooltip {
    display: inline-block;
    position: relative;

    & > .icon {
        color: $brand-color-primary;
    }

    &.chat-message {
        cursor: pointer;
    }

    &.checked--icon-white {
        .icon {
            color: white !important;
        }
    }
}
.th-tooltip-window {
    display: none;
    position: absolute;
    width: 20rem;
    background-color: $brand-color-base;
    padding: 0.625rem;
    border: 0.0625rem solid $dark-grey-background;
    border-radius: $border-radius;
    z-index: 110;
    top: 20px;
    right: 0;
    text-align: left !important;

    &.visible {
        display: block;
    }

    @media (max-width: 440px) {
        font-size: 12px;
        width: 16rem;
    }

    @media (max-width: 340px) {
        right: -24px;
    }
}
</style>
