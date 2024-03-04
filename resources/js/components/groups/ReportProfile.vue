<template>
    <div class="interaction-modal-box__wrapper">
        <div class="interaction-modal-box__header">
            <fa-icon icon="exclamation-circle" class="icon"></fa-icon>
            <p class="copy">Warum möchtest du dieses Profil melden?</p>
        </div>
        <div class="interaction-modal-box__content">
            <p class="copy">
                Bitte erläutere in einer kurzen Nachricht warum du dieses Profil
                melden möchtest.
            </p>
            <textarea
                class="rounded"
                v-model="reason"
                :disabled="loading"
                required
            ></textarea>
            <span class="error-message" v-if="form.reason.isValid === false">
                {{ form.reason.errorMessage }}
            </span>
            <div class="cta-wrapper">
                <button
                    class="btn btn-outline btn-outline-primary"
                    :disabled="loading"
                    @click="cancel"
                >
                    Abbrechen
                </button>
                <button
                    class="btn btn-primary"
                    :disabled="loading"
                    @click="send"
                >
                    Profil melden
                </button>
            </div>
        </div>
        <div class="interaction-modal-box__footer">
            <p class="copy"></p>
        </div>
    </div>
</template>

<script>
import InformationMessage from "./InformationMessage";
import ReportedMessage from "./ReportedMessage";

export default {
    name: "ReportPost",
    props: {
        userId: Number,
        itemId: Number,
        type: {
            type: String,
            default: "post",
        },
    },
    data() {
        return {
            loading: false,
            reason: "",
            form: {
                reason: {
                    required: true,
                    errorMessage: "Der Grund ist erforderlich.",
                    isValid: true,
                },
            },
        };
    },
    methods: {
        send() {
            if (!this.validateForm()) {
                return;
            }
            this.loading = true;

            let url = "";
            if (this.type === "post") {
                url = "/api/post/" + this.itemId + "/report";
            } else if (this.type === "comment") {
                url = "/api/comment/" + this.itemId + "/report";
            } else if (this.type === "profile") {
                url = "/api/profile/" + this.userId + "/report";
            } else {
                return;
            }

            axios
                .post(url, {
                    reason: this.reason,
                })
                .then(() => {
                    this.loading = false;
                    this.$emit("close");

                    this.$eventBus.emit("modal-requested", {
                        component: ReportedMessage,
                        props: {
                            title: "Profil wurde gemeldet",
                            message:
                                "Das Profil wurde erfolgreich gemeldet. Vielen Dank für deinen Hinweis!",
                        },
                    });
                })
                .catch((err) => {
                    console.error(err);
                });
        },
        validateForm() {
            if (this.reason === "" || this.reason.trim() === "") {
                this.form.reason.isValid = false;
            } else {
                this.form.reason.isValid = true;
            }

            return (
                Object.keys(this.form).filter(
                    (key) => this.form[key].isValid === false
                ).length === 0
            );
        },
        cancel() {
            this.$emit("close");
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
