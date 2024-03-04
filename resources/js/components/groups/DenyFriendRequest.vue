<template>
    <div class="wrapper rounded">
        <p class="header">
            <fa-icon icon="user-minus" class="icon"></fa-icon>
            <span>Anfrage von {{ userName }} ablehnen</span>
        </p>
        <p class="description">
          Du kannst jederzeit eine Trauerfreund-Anfrage ablehnen, wenn ein Vorschlag nicht passt. Das ist vollkommen okay.
          Kleine Bitte: Schreibe der Person doch kurz eine entsprechende Nachricht. So wartet sie nicht unnötig auf
          Antwort.
        </p>
        <ValidationProvider as="div" rules="required" v-model="message" v-slot="{ errorMessage, field }" :validateOnModelUpdate="false" :validateOnInput="true">
            <textarea
                class="rounded mb-0"
                v-bind="field"
                :disabled="loading"
                :placeholder="placeholder"
            ></textarea>

            <span v-if="errorMessage" class="validation-error">
                {{ errorMessage }}
            </span>

            <div class="dialog-actions mt-2">
                <button
                    class="btn btn-outline-primary btn-sm unset-min-width font-weight-not-bold"
                    :disabled="loading"
                    @click="close"
                >
                    Abbrechen
                </button>
                <button
                    class="btn btn-primary btn-sm text-white unset-min-width font-weight-not-bold"
                    :disabled="
                        loading || errorMessage || message.length === 0
                    "
                    @click="confirm"
                >
                    Trauerfreund ablehnen
                </button>
            </div>
        </ValidationProvider>
    </div>
</template>

<script>
export default {
    name: "DenyFriendRequest",
    props: {
        userId: Number,
        userName: String,
    },
    data() {
        return {
            loading: false,
            message: "",
            placeholder:
                "Hallo,\nich habe deine Trauerfreund-Anfrage abgelehnt, denn ... ",
        };
    },
    methods: {
        confirm() {
            if (this.loading) return;
            this.loading = true;

            this.$store
                .dispatch("partner/deny", {
                    partnerId: this.userId,
                    message: this.message,
                })
                .then(async () => {
                    this.$store.dispatch("partner/fetchUnreadFriendRequestCount");
                })
                .catch((err) => {
                    console.error(err);
                })
                .finally(() => {
                    this.message = "";
                    this.loading = false;
                    this.$emit("close");
                });
        },
        close() {
            this.$emit("close");
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
