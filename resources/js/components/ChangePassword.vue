<template>
    <div class="p-4">
        <h2 class="text-center">Passwort ändern</h2>
        <p></p>
        <form v-on:submit.prevent="changePassword">
            <ValidationProvider as="div" name="currentPassword" v-model="currentPassword" rules="required" v-slot="{ errorMessage, field }" >
                <div class="form-group">
                    <label for="currentPassword">Aktuelles Passwort</label>
                    <input
                        id="currentPassword"
                        v-bind="field"
                        class="form-control"
                        name="currentPassword"
                        type="password"
                    />
                    <div class="validation-error">{{ errorMessage }}</div>
                </div>
            </ValidationProvider>

            <ValidationProvider as="div" name="newPassword" rules="required|min:8" v-model="newPassword" v-slot="{ errorMessage, field }" >
                <div class="form-group">
                    <label for="newPassword">Neues Passwort</label>
                    <input
                        id="newPassword"
                        v-bind="field"
                        class="form-control"
                        name="newPassword"
                        type="password"
                    />
                    <div class="validation-error">{{ errorMessage }}</div>
                </div>
            </ValidationProvider>

            <ValidationProvider
                as="div"
                name="confirmNewPassword"
                rules="required|min:8"
                v-slot="{ errorMessage, field }"
                v-model="confirmNewPassword"
            >
                <div class="form-group">
                    <label for="confirmNewPassword"
                        >Neues Passwort bestätigen</label
                    >
                    <input
                        id="confirmNewPassword"
                        v-bind="field"
                        class="form-control"
                        name="confirmNewPassword"
                        type="password"
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
                Passwort ändern
            </button>
        </form>
    </div>
</template>

<script>
export default {
    name: "ChangePassword",
    methods: {
        changePassword() {
            this.loading = true;
            axios
                .post("/api/settings/change-password", {
                    "current-password": this.currentPassword,
                    "new-password": this.newPassword,
                    "new-password_confirmation": this.confirmNewPassword,
                })
                .then((response) => {
                    if (response.status === 200) {
                        this.$emit("close");
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
            currentPassword: null,
            newPassword: null,
            confirmNewPassword: null,
        };
    },
    computed: {
        formIsValid() {
            if (this.currentPassword === null) {
                return false;
            }

            if (this.newPassword === null) {
                return false;
            }

            if (this.confirmNewPassword === null) {
                return false;
            }

            const oldAndNewAreNotTheSame =
                this.newPassword !== this.currentPassword;
            const newPasswordsAreSame =
                this.confirmNewPassword === this.newPassword;

            return oldAndNewAreNotTheSame && newPasswordsAreSame;
        },
    },
};
</script>

<style scoped></style>
