<template>
    <div class="wrapper rounded">
        <p class="header">
            <fa-icon icon="user-minus" class="icon"></fa-icon>
            <span>{{ userName }} als Trauerfreund entfernen</span>
        </p>
        <p class="description">
            Du möchtest deine Trauerfreundanfrage zurückziehen? Hier kannst du
            optional eine Begründung hinzufügen.
        </p>
        <textarea
            class="rounded"
            v-model="message"
            :disabled="loading"
        ></textarea>
        <div class="dialog-actions">
            <button
                class="btn btn-outline-primary btn-sm unset-min-width font-weight-not-bold"
                :disabled="loading"
                @click="close"
            >
                Abbrechen
            </button>
            <button
                class="btn btn-primary btn-sm text-white unset-min-width font-weight-not-bold"
                :disabled="loading"
                @click="confirm"
            >
                Trauerfreund zurückziehen
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "RetractFriendRequest",
    props: {
        userId: Number,
        userName: String,
    },
    data() {
        return {
            loading: false,
            message: "",
        };
    },
    methods: {
        confirm() {
            if (this.loading) return;
            this.loading = true;

            this.$store
                .dispatch("partner/retract", {
                    partnerId: this.userId,
                    message: this.message,
                })
                .catch((err) => {
                    console.error(err);
                })
                .finally(() => {
                    this.message = "";
                    this.loading = false;
                    this.close();
                });
        },
        close() {
            this.$emit("close");
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
