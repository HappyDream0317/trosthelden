<template>
    <div class="wrapper rounded">
        <p class="header">
            <fa-icon icon="user-plus" class="icon"></fa-icon>
            <span>Trauerfreund-Anfrage an {{ userName }} senden</span>
        </p>
        <p class="description">
            Gib deiner Trauerfreund-Anfrage einen persönlichen Charakter, indem
            du eine Nachricht dazu schreibst.
        </p>
        <ValidationProvider as="div" v-model="message" rules="required" v-slot="{ errorMessage, field }" :validateOnModelUpdate="false" :validateOnInput="true">
            <textarea
                class="rounded mb-0"
                v-bind="field"
                :disabled="loading"
                :placeholder="placeholder"
            ></textarea>
            <span class="validation-error">
                {{ errorMessage }}
            </span>

            <div class="dialog-actions right">
                <button
                    class="btn btn-primary btn-sm text-white unset-min-width font-weight-not-bold mt-2"
                    :disabled="
                        loading || errorMessage || message.length === 0
                    "
                    @click="send"
                >
                    Anfrage senden
                </button>
            </div>
        </ValidationProvider>
    </div>
</template>

<script>
export default {
    name: "SendFriendRequest",
    props: {
        userName: String,
        userId: Number,
    },
    data() {
        return {
            loading: false,
            message: "",
            placeholder: "Schreibe hier deine Nachricht.",
        };
    },
    methods: {
        send() {
            if (this.loading) return;
            this.loading = true;

            this.$store
                .dispatch("partner/invite", {
                    partnerId: this.userId,
                    message: this.message,
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
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
