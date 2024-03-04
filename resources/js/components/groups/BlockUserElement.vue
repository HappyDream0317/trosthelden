<template>
    <div class="wrapper rounded">
        <p class="header">
            <fa-icon icon="eye-slash" class="icon"></fa-icon>
            <span>{{ userName }} ausblenden</span>
        </p>
        <p class="description">
            <span>Nutzer ausblenden?</span>
        </p>
        <div class="dialog-actions mt-2">
            <button
                class="btn btn-outline-primary btn-sm unset-min-width font-weight-not-bold"
                :disabled="loading"
                @click="reject"
            >
                Nein
            </button>
            <button
                class="btn btn-primary btn-sm text-white unset-min-width font-weight-not-bold"
                :disabled="loading"
                @click="accept"
            >
                Ja
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "BlockUserElement",
    emmit: ["accept", "reject"],
    props: {
        userId: Number,
        userName: String,
        loading: Boolean,
    },
    methods: {
        reject() {
            this.$emit("reject");
        },
        accept() {
            this.$emit("accept");
        },
    },
};
</script>
<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
