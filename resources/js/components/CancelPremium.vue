<template>
    <div class="p-4">
        <h2 class="text-center">Mitgliedschaft kündigen</h2>
        <br />
        <br />
        <div
            class="d-flex justify-content-center align-content-center"
            v-if="success"
        >
            <div
                class="mt-4 d-flex flex-column justify-content-center align-items-center color--primary"
            >
                <p>
                    Wir haben deine Kündigungsanfrage erhalten und dir eine
                    Bestätigung an deine E-Mail-Adresse gesendet.
                </p>
            </div>
        </div>
        <form v-on:submit.prevent="onSubmit" v-else-if="contract">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"
                    >Dein Benutzername:</label
                >
                <div class="col-sm-9">
                    <label class="col-form-label">{{ user.nickname }}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"
                    >Deine Mitgliedschaft:</label
                >
                <div class="col-sm-9">
                    <label class="col-form-label">{{
                        contract.planVariantName
                    }}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kündigung zum:</label>
                <div class="col-sm-9">
                    <label class="col-form-label">{{ formatedEndDate }}</label>
                </div>
            </div>
            <ValidationProvider
                as="div"
                name="form.regular"
                rules="required"
                v-model="form.regular"
                v-slot="{ errorMessage, field }"
            >
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label" for="form.regular"
                        >Art der Kündigung:</label
                    >
                    <div class="col-sm-9 py-2" id="form.regular">
                        <div
                            class="form-check"
                            v-for="item in options"
                            v-bind:key="item.value"
                        >
                            <input
                                v-bind="field"
                                class="form-check-input"
                                type="radio"
                                name="form.regular"
                                required
                                :id="`form.regular.${item.value}`"
                                :value="item.value"
                                :title="item.label"
                            />
                            <label
                                class="form-check-label"
                                :for="`form.regular.${item.value}`"
                            >
                                {{ item.label }}
                            </label>
                        </div>
                        <div class="validation-error">{{ errorMessage }}</div>
                    </div>
                </div>
            </ValidationProvider>

            <ValidationProvider
                as="div"
                name="form.reason"
                :rules="form.regular === false ? 'required|min:2' : 'min:2'"
                v-model="form.reason"
                v-slot="{ errorMessage, field }"
            >
                <div
                    class="form-group"
                    v-show="form.regular !== null"
                >
                    <label for="form.reason" v-if="form.regular === true"
                        >Magst du uns den Grund für deine Kündigung
                        nennen?</label
                    >
                    <label for="form.reason" v-else
                        >Bitte gib einen Grund für deine außerordentliche
                        Kündigung an:</label
                    >
                    <input
                        id="form.reason"
                        v-bind="field"
                        class="form-control"
                        name="form.reason"
                        type="text"
                        :required="form.regular === false"
                    />
                    <div class="validation-error">{{ errorMessage }}</div>
                </div>
            </ValidationProvider>
            <button
                :disabled="!formIsValid || loading"
                class="btn btn-primary"
                type="submit"
            >
                <span
                    v-if="loading"
                    aria-hidden="true"
                    class="spinner-grow spinner-grow-sm"
                    role="status"
                ></span>
                Mitgliedschaft jetzt kündigen
            </button>
        </form>
        <div
            v-else
            class="d-flex justify-content-center align-items-center py-4"
        >
            <div class="spinner-grow" role="status"></div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import moment from "moment";
import ChangePassword from "./ChangePassword";

export default {
    name: "CancelPremium",
    data() {
        return {
            loading: false,
            contract: null,
            error: null,
            success: null,
            form: {
                regular: null,
                reason: null,
            },
            options: [
                { value: 1, label: "fristgerechte Kündigung" },
                { value: 0, label: "außerordentliche Kündigung" },
            ],
        };
    },
    computed: {
        formIsValid() {
            if (this.form.regular === null) {
                return false;
            } else if (
                this.form.regular === 0 &&
                (this.form.reason === null || this.form.reason === "")
            ) {
                return false;
            }
            return true;
        },
        endDate() {
            return new Date(this.contract.contractEndDate);
        },
        formatedEndDate() {
            return moment(this.endDate).format("DD.MM.YYYY");
        },
        ...mapGetters("currentUser", {
            currentUserId: "getId",
            user: "getObject",
        }),
    },
    mounted() {
        this.getCurrentContrat();
    },
    methods: {
        getCurrentContrat() {
            this.loading = true;
            axios
                .get(
                    `/api/payments/contract-cancellation/preview/${this.currentUserId}`
                )
                .then(({ data }) => {
                    this.contract = data;
                })
                .catch((err) => {
                    console.error(err);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        onSubmit() {
            this.error = null;
            this.loading = true;
            axios
                .post("/api/payments/contract-cancellation", {
                    ...this.form,
                    ...this.contract,
                    userId: this.currentUserId,
                })
                .then(async (response) => {
                    if (response.status === 200 && !response.data.errors) {
                        await this.$store.dispatch("currentUser/fetch");
                        this.$eventBus.emit("settings-refresh-data");
                        this.success = true;
                    }
                })
                .catch((error) => {
                    let { response, message } = error;
                    this.error = response.data.message ?? message;
                    console.error(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>

<style scoped lang="scss">
.error {
    color: red;
}

input {
    &.error {
        border: 1px solid red;
    }
}
</style>
