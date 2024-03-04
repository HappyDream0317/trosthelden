<template>
    <div class="p-4">
        <h2 class="text-center">E-Mail-Adresse ändern</h2>
        <p></p>
        <form v-on:submit.prevent="changeEmail">
            <ValidationProvider as="div" name="newEmail" rules="required|email" v-model="newEmail" v-slot="{ errorMessage, field }" >
                <div class="form-group">
                    <label for="newEmail">Neue E-Mail-Adresse</label>
                    <input
                        type="email"
                        required
                        v-bind="field"
                        name="newEmail"
                        class="form-control"
                        :class="{ error: errorMessage }"
                        id="newEmail"
                    />
                  <p class="error" style="margin-top: 1rem" v-if="errorMessage">
                    {{ errorMessage }}
                  </p>
                </div>
            </ValidationProvider>
            <ValidationProvider as="div" name="confirmNewEmail" rules="required|email" v-model="confirmNewEmail" v-slot="{ errorMessage, field }" >
                <div class="form-group">
                    <label for="confirmNewEmail"
                        >Neue E-Mail-Adresse bestätigen</label
                    >
                    <input
                        type="email"
                        required
                        v-bind="field"
                        name="confirmNewEmail"
                        class="form-control"
                        :class="{ error: errorMessage }"
                        id="confirmNewEmail"
                    />
                    <p class="error" style="margin-top: 1rem" v-if="errorMessage">
                        {{ errorMessage }}
                    </p>
                </div>
            </ValidationProvider>

            <button
                class="btn btn-primary"
                :disabled="!formIsValid || loading"
                type="submit"
            >
                <span
                    class="spinner-grow spinner-grow-sm"
                    v-if="loading"
                    role="status"
                    aria-hidden="true"
                ></span>
                E-Mail-Adresse ändern
            </button>
        </form>
    </div>
</template>

<script>
import user from "../store/modules/user";

export default {
    name: "ChangeEmail",
    methods: {
        changeEmail() {
            this.loading = true;

            axios
                .post("/api/settings/change-email", {
                    "new-email": this.newEmail,
                    "new-email_confirmation": this.confirmNewEmail,
                })
                .then((response) => {
                    console.log(response);
                    if (response.status === 200 && !response.data.error) {
                        this.$router.go();
                    }
                    if (response.data.error) {
                        this.error = response.data.error;
                    }

                    this.loading = false;
                })
                .catch((err) => {
                    console.error(err);
                });
        },
    },
    data() {
        return {
            loading: false,
            newEmail: null,
            confirmNewEmail: null,
            error: "",
        };
    },
    computed: {
        formIsValid() {
            if (this.newEmail === null) {
                return false;
            }

            if (this.confirmNewEmail === null) {
                return false;
            }

            return this.confirmNewEmail === this.newEmail;
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
