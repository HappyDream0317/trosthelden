<template>
    <default-layout>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-transparent border-0 rounded-box">
                    <div class="card-header bg-transparent text-center pt-4">
                        <h1>Passwort vergessen</h1>
                        <p>
                            Bitte gib deine E-Mail-Adresse ein. Du erhältst dann
                            einen E-Mail mit einem Link. Mit diesem Link kannst
                            du ein neues Passwort festlegen.
                        </p>
                    </div>

                    <form
                        v-if="!success"
                        class="card-body"
                        v-on:submit.prevent="submit"
                    >
                        <div class="form-group row px-3">
                            <div class="col-md-12">
                                <label for="email">Deine E-Mail-Adresse</label>
                                <ValidationProvider
                                    as="div"
                                    name="email"
                                    rules="required|email"
                                    v-model="email"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div>
                                        <input
                                            v-bind="field"
                                            id="email"
                                            type="email"
                                            class="form-control"
                                            name="email"
                                        />
                                        <span class="validation-error">{{
                                            errorMessage
                                        }}</span>
                                    </div>
                                </ValidationProvider>
                                <div class="form-action-link">
                                    <router-link :to="{ name: 'login' }" exact
                                        >Zurück zum Login</router-link
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col text-center py-2 mx-3">
                                <button type="submit" class="btn w-100 btn-primary text-uppercase">
                                    E-Mail anfordern
                                    <i class="icon-arrow-next-light "></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div v-else class="form-group row px-3">
                        <div class="col-md-12 text-center">
                            Wir haben dir soeben eine E-Mail gesendet. <br />
                            Bitte prüfe dein E-Mail-Postfach.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </default-layout>
</template>

<script>
import PageHeader from "../components/PageHeader";
import DefaultLayout from "../layouts/DefaultLayout";

export default {
    name: "ForgotPassword",
    components: { DefaultLayout, PageHeader },
    data: () => ({
        email: null,
        password: null,
        errored: false,
        loading: false,
        success: false,
    }),
    methods: {
        submit($event) {
            this.loading = true;
            this.$store
                .dispatch("currentUser/forgotPassword", this.email)
                .then((data) => {
                    this.success = true;
                })
                .catch((err) => {
                    console.error(err);
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>

<style scoped>
.box--message {
    position: absolute;
    top: 0;
    width: 80%;
    margin: 10% 7%;
    background: #fff;
    z-index: 200;
    padding: 20px;
    text-align: center;
    opacity: 1;
    display: block;
    box-shadow: 0 0 40px rgba(0, 51, 90, 0.4);
    border-radius: 7px;
}
.bg--overlay {
    position: absolute;
    height: 100%;
    width: 100%;
    z-index: 100;
    background-color: rgba(235, 235, 235, 0.6);
    display: block;
}
.box--message {
    text-align: center;
}
#msgokay {
    margin: auto;
    position: relative;
}
</style>
