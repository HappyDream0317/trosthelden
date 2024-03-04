<template>
    <div class="wrapper rounded">
        <p class="header">
            <fa-icon icon="eye-slash" class="icon"></fa-icon>
            <span
                >{{ userName }} als Trauerfreund entfernen und Nutzer
                ausblenden</span
            >
        </p>
        <p class="description">
            <span>Trauerfreundschaft beenden und Nutzer ausblenden?</span>
        </p>
        <template v-if="confirm">
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
                        @click="reject"
                    >
                        Abbrechen
                    </button>
                    <button
                        class="btn btn-primary btn-sm text-white unset-min-width font-weight-not-bold"
                        :disabled="
                            loading || errorMessage || message.length === 0
                        "
                        @click="submit"
                    >
                        Trauerfreund entfernen
                    </button>
                </div>
            </ValidationProvider>
        </template>
        <template v-else>
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
        </template>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

const BEFRIENDED = 2;

export default {
    name: "BlockFriendElement",
    emmit: ["accept", "reject"],
    props: {
        userId: Number,
        userName: String,
        loading: Boolean,
    },
    data() {
        return {
            confirm: false,
            message: "",
            placeholder: "Ich möchte unsere Trauerfreundschaft beenden, denn…",
        };
    },
    methods: {
        reject() {
            if (this.confirm) this.confirm = false;
            if (this.message.length > 0) this.message = "";
            this.$emit("reject");
        },
        accept() {
            this.confirm = true;
        },
        submit() {
            this.$emit("accept", { message: this.message });
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
