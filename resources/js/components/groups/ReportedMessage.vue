<template>
    <div class="interaction-modal-box__wrapper">
        <div class="interaction-modal-box__header">
            <fa-icon icon="star" class="icon"></fa-icon>
            <p class="copy">{{ title }}</p>
        </div>
        <div class="interaction-modal-box__content">
            <p class="copy">
                {{ message }}
            </p>
            <div class="cta-wrapper">
                <button @click="$emit('close')" class="btn btn-primary">
                    Fertig
                </button>
            </div>
        </div>
        <div class="interaction-modal-box__footer">
            <p class="copy"></p>
        </div>
    </div>
</template>

<script>
export default {
    name: "InformationMessage",
    props: {
        title: {
            type: String,
            default: null,
        },
        message: String,
        buttonTitle: {
            type: String,
            default: "Schließen",
        },
        messageClass: {
            type: String,
            default: null,
        },
    },
    data() {
        return {};
    },
    methods: {
        close() {
            this.$emit("close");
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
