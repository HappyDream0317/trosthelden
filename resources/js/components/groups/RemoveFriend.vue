<template>
    <div class="wrapper rounded">
        <p class="header">
            <fa-icon icon="user-minus" class="icon"></fa-icon>
            <span>{{ userName }} als Trauerfreund entfernen</span>
        </p>
        <p class="description">
            Du möchtest diese Trauerfreundschaft beenden? Gib hier bitte einen
            kurzen Abschiedstext für deinen Trauerfreund ein.
        </p>

        <ValidationProvider as="div" rules="required" v-model="message" v-slot="{ errorMessage, field }" :validateOnModelUpdate="false" :validateOnInput="true">
            <textarea
                class="rounded mb-0"
                v-bind="field"
                :disabled="loading"
                :placeholder="placeholder"
            ></textarea>

            <span class="validation-error">
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
                    Trauerfreund entfernen
                </button>
            </div>
        </ValidationProvider>
    </div>
</template>

<script>
export default {
    name: "RemoveFriend",
    props: {
        userId: Number,
        userName: String,
    },
    data() {
        return {
            loading: false,
            message: "",
            placeholder: "Ich möchte unsere Trauerfreundschaft beenden, denn…",
        };
    },
    methods: {
        async confirm() {
            if (this.loading) return;
            this.loading = true;

            this.$store
                .dispatch("partner/remove", {
                    partnerId: this.userId,
                    message: this.message,
                })
                .catch((err) => {
                    console.log(err);
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
