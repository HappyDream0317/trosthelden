<template>
    <default-layout>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-transparent border-0 rounded-box">
                    <div class="card-header bg-transparent text-center pt-4">
                        <h1>Login</h1>
                    </div>
                    <div class="text-center p-2">
                        Hier kannst du dich mit deinen vorhandenen Daten
                        einloggen. Wenn du noch keine Login-Daten hast, melde
                        dich bitte zuerst
                        <router-link :to="{ name: 'register' }" exact
                            >hier</router-link
                        >
                        an.
                    </div>

                    <form class="card-body" v-on:submit.prevent="submit">
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
                            </div>
                        </div>

                        <div class="form-group row px-3">
                            <div class="col-md-12">
                                <label for="password">Dein Passwort</label>
                                <ValidationProvider
                                    as="div"
                                    name="password"
                                    rules="required|min:8"
                                    v-model="password"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div
                                        class="toggle-password-wrapper"
                                    >
                                        <input
                                            v-bind="field"
                                            id="password"
                                            type="password"
                                            class="form-control"
                                            name="password"
                                        />
                                        <fa-icon
                                            v-on:click="togglePassword"
                                            class="moveMe"
                                            :icon="pwIcon"
                                            id="togglePassWord"
                                        ></fa-icon>
                                        <span class="validation-error">{{
                                            errorMessage
                                        }}</span>
                                    </div>
                                </ValidationProvider>
                                <div class="form-action-link">
                                    <router-link
                                        :to="{ name: 'forgot-password' }"
                                        exact
                                        >Passwort vergessen</router-link
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col text-center py-2 mx-3">
                                <button
                                    type="submit"
                                    class="btn w-100 btn-primary text-uppercase"
                                >
                                    Anmelden
                                    <i class="icon-arrow-next-light"></i>
                                </button>
                            </div>
                        </div>

                        <section v-if="errored">
                            <div class="box--message000">
                                <div
                                    class="form-group row rounded m-3 bg--secondary layerbox--container000"
                                >
                                    <div class="">
                                        <h3>Ein Fehler ist aufgetreten</h3>
                                        <p>
                                            Mögliche Gründe sind ein Tippfehler
                                            in der E-Mail-Adresse oder ein
                                            falsches Passwort. <br />
                                            Bitte überprüfe auch, dass du den
                                            Link in der Bestätigungsmail nach
                                            der Registierung angeklickt hast.
                                        </p>
                                    </div>
                                    <div
                                        class="btn btn-submit-var-3"
                                        id="msgokay"
                                        style="display: none"
                                    >
                                        Okay
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
            </div>
        </div>
    </default-layout>
</template>

<script>
import PageHeader from "../components/PageHeader";
import DefaultLayout from "../layouts/DefaultLayout";

export default {
    name: "Login",
    components: { DefaultLayout, PageHeader },
    data: () => ({
        email: null,
        password: null,
        errored: false,
        loading: false,
        pwIcon: "eye-slash",
    }),
    methods: {
        mounted() {},
        submit($event) {
            this.loading = true;
            this.$store
                .dispatch("currentUser/login", {
                    email: this.email,
                    password: this.password,
                })
                .then(({user}) => {
                  if(user?.is_business_account)
                    this.$router.push({ path: "/b2b/dashboard"});
                  else
                    this.$router.push({ path: "/dashboard"});
                })
                .catch((err) => {
                    console.error(err);
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        togglePassword(event) {
            const pwInput = document.querySelector("#password");

            if (this.pwIcon === "eye") {
                this.pwIcon = "eye-slash";
            } else if (this.pwIcon === "eye-slash") {
                this.pwIcon = "eye";
            } else {
                this.pwIcon = "eye";
            }

            // console.log(this.pwIcon);

            if (pwInput.type === "password") {
                pwInput.type = "text";
            } else if (pwInput.type === "text") {
                pwInput.type = "password";
            }
        },
    },
};
</script>

<style scoped>
.form-action-link {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.form-action-link label {
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-action-link label input {
    margin-right: 0.5rem;
}

#togglePassWord {
    position: absolute;
    top: 0.75rem;
    right: 1.5rem;
    z-index: 9999;
}
.toggle-password-wrapper {
    position: relative;
}

.box--message {
    position: absolute;
    top: 0;
    width: 80%;
    margin: 10% 7%;
    background: #ffffff;
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
