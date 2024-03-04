<template>
    <div class="wrapper rounded">
        <p class="header">
            <fa-icon icon="user-tag" class="icon"></fa-icon>
            <span>Trauerfreund-Anfrage von {{ userName }} beantworten</span>
        </p>
        <p class="description">
            Wenn du die Trauerfreund-Anfrage annehmen möchtest, dann mach das
            doch mit einer kleinen Nachricht!<br />
            Das ist eine gute Hilfe, um eure Trauerfreundschaft zu beginnen!
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

            <div class="dialog-actions flex justify-content-end">
                <button
                    class="btn btn-primary btn-sm text-white unset-min-width font-weight-not-bold mt-2"
                    :disabled="
                        loading || errorMessage || message.length === 0
                    "
                    @click="accept"
                >
                    Anfrage bestätigen
                </button>
            </div>
        </ValidationProvider>
    </div>
</template>

<script>
export default {
    name: "ReactToFriendRequest",
    props: {
        userName: String,
        userId: Number,
    },
    data() {
        return {
            loading: false,
            message: "",
            placeholder:
                "Hallo,\nich freue mich auf unsere Trauerfreundschaft.",
        };
    },
    methods: {
        accept() {
            if (this.loading) return;
            this.loading = true;

            this.$store
                .dispatch("partner/accept", {
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
                    this.loading = false;
                    this.$emit("close");
                });
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
