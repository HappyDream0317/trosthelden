<template>
    <default-layout>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-transparent border-0">
                    <div class="card-header text-center pt-4 bg-transparent">
                        <h1>Jetzt anmelden</h1>
                    </div>
                    <div class="text-center p-2">
                        Registriere dich mit deiner E-Mail-Adresse und deinem
                        Benutzernamen.
                    </div>

                    <div class="card-body">
                        <form v-on:submit.prevent="submit">
                            <div class="form-group row px-3">
                                <div class="col-md-12">
                                    <label for="nickname">Wähle deinen Benutzernamen</label>
                                    <input
                                        v-model="form.nickname.value"
                                        id="nickname"
                                        type="text"
                                        class="form-control"
                                        name="nickname"
                                        required
                                        autocomplete="name"
                                        placeholder="Wähle deinen Benutzernamen"
                                        autofocus
                                        :class="
                                            !form.nickname.valid ? 'error' : ''
                                        "
                                        @input="validate(form.nickname)"
                                    />

                                    <span
                                        class="error-message"
                                        v-if="!form.nickname.valid"
                                        v-for="error in form.nickname.errors"
                                    >
                                        {{ error }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row px-3">
                                <div class="col-12">
                                    <label for="email">Deine E-Mail-Adresse</label>
                                    <input
                                        v-model="form.email.value"
                                        id="email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        placeholder="Deine E-Mail-Adresse"
                                        :class="
                                            !form.email.valid ? 'error' : ''
                                        "
                                        @input="validate(form.email)"
                                    />

                                    <span
                                        class="error-message"
                                        v-if="!form.email.valid"
                                        v-for="error in form.email.errors"
                                    >
                                        {{ error }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row px-3">
                                <div class="col-12">
                                    <label for="password">Passwort (mind. 8 Zeichen, inkl. einem Buchstaben und einer Zahl)</label>
                                    <input
                                        v-model="form.password.value"
                                        id="password"
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        placeholder="Passwort"
                                        :class="
                                            !form.password.valid ? 'error' : ''
                                        "
                                    />
                                    <span
                                        class="error-message"
                                        v-if="!form.password.valid"
                                        v-for="error in form.password.errors"
                                    >
                                        {{ error }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row px-3">
                                <div class="col-12">
                                    <label for="password-confirm">Passwort bestätigen</label>
                                    <input
                                        v-model="
                                            form.password_confirmation.value
                                        "
                                        id="password-confirm"
                                        type="password"
                                        class="form-control"
                                        name="password_confirmation"
                                        placeholder="Passwort bestätigen"
                                        :class="
                                            !form.password_confirmation.valid
                                                ? 'error'
                                                : ''
                                        "
                                    />
                                    <span
                                        class="error-message"
                                        v-if="!form.password_confirmation.valid"
                                        v-for="error in form
                                            .password_confirmation.errors"
                                    >
                                        {{ error }}
                                    </span>
                                </div>
                            </div>

                            <div
                                class="form-group row rounded m-3 bg--secondary"
                            >
                                <div class="form-check my-2">
                                    <input v-model="form.age16_verified.value" id="age16_verified" type="checkbox" class="form-check-input form-check-input--lg" name="age16_verified" required/>
                                    <label for="age16_verified" class="form-check-label check-label w-auto font-weight-bold color--primary ms-3 mt-2">
                                           Ich bestätige, 16 Jahre oder älter zu sein.
                                    </label>
                                    <span
                                        class="error-message"
                                        v-if="!form.age16_verified.valid"
                                        v-for="error in form.age16_verified.errors"
                                    >
                                        {{ error }}
                                    </span>
                                </div>
                                <div class="form-check my-2">
                                    <input v-model="form.agb_privacy_accepted.value" id="agb_privacy_accepted" type="checkbox" class="form-check-input form-check-input--lg" name="agb_privacy_accepted" required/>
                                    <label for="agb_privacy_accepted" class="form-check-label check-label w-auto font-weight-bold color--primary ms-3 mt-2">
                                            Ich akzeptiere die
                                            <router-link
                                                :to="{ name: 'conditions' }"
                                                target="_blank"
                                                exact
                                                >AGB</router-link
                                            >
                                            und
                                            <router-link
                                                :to="{ name: 'privacy' }"
                                                target="_blank"
                                                exact
                                                >Datenschutzhinweise</router-link
                                            >.
                                    </label>
                                    <span
                                        class="error-message"
                                        v-if="!form.agb_privacy_accepted.valid"
                                        v-for="error in form.agb_privacy_accepted.errors"
                                    >
                                        {{ error }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col text-center py-2 mx-3">
                                    <button
                                        type="submit"
                                        class="btn w-100 btn-primary text-uppercase"
                                    >
                                        Weiter
                                        <i class="icon-arrow-next-light "></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </default-layout>
</template>

<script>
import PageHeader from "../PageHeader";
import DefaultLayout from "../../layouts/DefaultLayout";
import { trackMatomoEvent } from "../../matomo";
import { pixel_fbq } from "../../pixel";
import {checkReferrer, getReferrerCookie, deleteReferrerCookie} from "../../referrer";
import {getCookie} from "../../utils/cookie";

export default {
    name: "Details",
    components: { DefaultLayout, PageHeader },
    data: () => ({
        form: {
            nickname: {
                value: null,
                valid: true,
                name: "Benutzernamen",
                rules: ["required"],
                errors: [],
            },
            email: {
                value: null,
                valid: true,
                name: "E-Mail",
                rules: ["required", "email"],
                errors: [],
            },
            password: {
                value: null,
                valid: true,
                name: "Passwort",
                rules: [
                    "required",
                    "password",
                    "equalTo:password_confirmation",
                ],
                errors: [],
            },
            password_confirmation: {
                value: null,
                valid: true,
                name: "Passwort bestätigen",
                rules: ["required"],
                errors: [],
            },
            age16_verified: {
                value: null,
                valid: true,
                name: "",
                rules: [],
                errors: [],
            },
            agb_privacy_accepted: {
                value: null,
                valid: true,
                name: "",
                rules: [],
                errors: [],
            },
        },
        formValid: true,
        hasReferrer: false,
        loading: false,
        registered: false,
        errorMessages: {
            required: ":name ist erforderlich.",
            email: ":name muss eine gültige E-Mail sein.",
            password:
                "Bitte wähle ein sicheres Passwort aus. Das Passwort muss mindestens 8 Zeichen lang sein und mindestens einen Buchstaben sowie eine Zahl beinhalten",
            confirmPassword:
                "Deine eingegebenen Passwörter müssen übereinstimmen. Bitte überprüfe deine Eingabe",
        },
    }),
  mounted() {
    this.verifyReferrer();
  },
  methods: {
        submit() {
          if (this.loading) return;

            this.validateForm();
            if (false === this.formValid) return;

            this.loading = true;

            trackMatomoEvent("register", "Weiter");
            pixel_fbq("track", "CompleteRegistration");
            
            let partner = getCookie("partner") ?? null;

            axios
                .post("/api/register", {
                    nickname: this.form.nickname.value,
                    email: this.form.email.value,
                    password: this.form.password.value,
                    password_confirmation: this.form.password_confirmation.value,
                    partner: partner,
                })
                .then(async (response) => {
                    this.registered = true;
                    await this.referrer(this.form.email.value);
                    this.login(this.form.email.value, this.form.password.value);
                })
                .catch((err) => {
                    console.error(err);
                    this.setErrorsFromApi(err.response.data.errors);
                    this.$emit("status", false);
                    this.formValid = false;
                })
                .finally(() => (this.loading = false));
        },
        login(email, password) {
            this.$store
                .dispatch("currentUser/login", {
                    email: email,
                    password: password,
                    force: true,
                })
                .then(() => {
                    this.$router
                        .push({
                            name: "guide",
                        })
                        .catch(() => {});
                    this.$emit("status", true);
                })
                .catch((err) => {
                    console.error(err);
                })
                .finally(() => {});
        },
        setErrorsFromApi(errors) {
            Object.keys(errors).forEach((key) => {
                if (this.form[key] !== undefined) {
                    this.form[key].errors = errors[key];
                    this.form[key].valid = false;
                }
            });
        },
        validateForm() {
            Object.keys(this.form).forEach((key) => {
                const field = this.form[key];
                this.validate(field);
                this.formValid = this.formStatus();
            });
        },
        validate(field) {
            field.valid = true;
            field.errors = [];

            //required
            if (field.rules.includes("required")) {
                if (field.value.trim() === "") {
                    field.vavalidlue = false;
                    field.errors.push(
                        this.getErrorMessage("required", field.name)
                    );
                }
            }

            //email
            if (field.rules.includes("email")) {
                if (this.validateEmail(field.value) === false) {
                    field.valid = false;
                    field.errors.push(
                        this.getErrorMessage("email", field.name)
                    );
                }
            }

            //password
            if (field.rules.includes("password")) {
                if (this.validatePassword(field.value) === false) {
                    field.valid = false;
                    field.errors.push(
                        this.getErrorMessage("password", field.name)
                    );
                }
            }

            //equalTo
            if (
                field.rules.filter((rule) => rule.includes("equalTo")).length >
                0
            ) {
                const rule = field.rules.filter((rule) =>
                    rule.includes("equalTo")
                )[0];
                const compareWith = rule.split(":")[1];
                const compareWithField = this.form[compareWith];
                if (field.value !== compareWithField.value) {
                    field.valid = false;
                    field.errors.push(this.errorMessages["confirmPassword"]);
                }
            }
        },
        getErrorMessage(type, name, name2 = null) {
            let message = this.errorMessages[type].replace(":name", name);
            if (name2 !== null) {
                message = message.replace(":2name", name2);
            }
            return message;
        },
        validateEmail(text) {
            const mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            return text.match(mailformat);
        },
        validatePassword(text) {
            return (
                (/[A-Z]/.test(text) || /[a-z]/.test(text)) &&
                /[0-9]/.test(text) &&
                text.length >= 8
            );
        },
        formStatus() {
            return (
                Object.values(this.form).filter(
                    (field) => field.valid === false
                ).length === 0
            );
        },
      async verifyReferrer()  {
        this.hasReferrer = await checkReferrer();
      },
      async referrer(email) {
        if (this.hasReferrer) {
          let referrerCookie = getReferrerCookie();
          let referrerData = !referrerCookie ? {} : JSON.parse(referrerCookie);
          axios
              .post("/api/referrer", {
                email,
                referrer: referrerData?.referrer,
                referring_domain: referrerData?.referring_domain,
                content: referrerData?.utm_content,
                campaign: referrerData?.utm_campaign,
                medium: referrerData?.utm_medium,
                source: referrerData?.utm_source,
                keyword: referrerData?.utm_keyword,
              })
              .then((response) => {
                deleteReferrerCookie();
              })
              .catch((err) => {
                console.error(err);
                this.setErrorsFromApi(err.response.data.errors);
              });
        }
      },
    },
};
</script>

<style scoped>
.form-check-input--lg {
    width: 1.5rem;
    height: 1.5rem;
}
</style>
