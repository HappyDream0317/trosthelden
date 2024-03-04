<template>
  <default-layout>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card bg-transparent border-0 rounded-box">
          <div class="card-header bg-transparent text-center pt-4">
            <h1>Neues Passwort festlegen</h1>
          </div>
          <div class="text-center p-2">
            Bitte gib dein neues Passwort ein.
          </div>
          <ValidationObserver v-if="showResetForm" tag="form" class="card-body" @submit="onResetPassword"
                              v-slot="{ meta: {valid, dirty} }">
            <div class="form-group row px-3">
              <div class="col-md-12">
                <label for="password">Passwort (mind. 8 Zeichen, inkl. einem Buchstaben und einer Zahl)</label>
                <ValidationProvider
                    as="div"
                    name="password"
                    rules="required|min:8"
                    v-slot="{ errorMessage, field }"
                    v-model="password"
                >
                  <div>
                    <input
                        v-bind="field"
                        id="password"
                        type="password"
                        class="form-control"
                        name="password"
                    />
                    <span class="validation-error">{{
                        errorMessage
                      }}</span>
                  </div>
                </ValidationProvider>
              </div>
              <div class="col-md-12">
                <label for="password_confirm">Passwort bestätigen</label>
                <ValidationProvider
                    as="div"
                    name="password_confirm"
                    rules="required|min:8|equal_passwords:@password"
                    v-slot="{ errorMessage, field }"
                    v-model="password_confirm"
                >
                  <div>
                    <input
                        v-bind="field"
                        id="password_confirm"
                        type="password"
                        class="form-control"
                        name="password_confirm"
                    />
                    <span class="validation-error">{{
                        errorMessage
                      }}</span>
                  </div>
                </ValidationProvider>
              </div>
            </div>

            <div class="form-group row rounded m-3 bg--secondary">
              <ValidationProvider
                  as="div"
                  name="age16_verified"
                  rules="required"
                  v-slot="{ errorMessage, field, handleChange }"
                  v-model="age16_verified"
                  type="checkbox"
                  :value="true"
                  :unchecked-value="false"
              >
                <div>
                  <div class="form-check">
                    <input v-bind="field" id="age16_verified" type="checkbox"
                           class="form-check-input form-check-input--lg" name="age16_verified"
                           @input="handleChange"   required/>
                    <label for="age16_verified"
                           class="form-check-label check-label w-auto font-weight-bold color--primary ms-3 mt-2">
                      Ich bestätige, 16 Jahre oder älter zu sein.
                    </label>
                    <span class="validation-error">{{ errorMessage }}</span>
                  </div>
                </div>
              </ValidationProvider>
              <ValidationProvider
                  as="div"
                  name="agb_privacy_accepted"
                  rules="required"
                  v-slot="{ errorMessage, field, handleChange }"
                  v-model="agb_privacy_accepted"
                  type="checkbox"
                  :value="true"
                  :unchecked-value="false"
              >
                <div>
                  <div class="form-check">
                    <input v-bind="field" id="agb_privacy_accepted" type="checkbox"
                           @input="handleChange"
                           class="form-check-input form-check-input--lg" name="agb_privacy_accepted" required/>
                    <label for="agb_privacy_accepted"
                           class="form-check-label check-label w-auto font-weight-bold color--primary ms-3 mt-2">
                      Ich akzeptiere dien
                      <router-link :to="{ name: 'conditions' }" target="_blank" exact>AGB</router-link>
                      und
                      <router-link :to="{ name: 'privacy' }" target="_blank" exact>Datenschutzhinweise</router-link>
                      .
                    </label>
                    <span class="validation-error">{{ errorMessage }}</span>
                  </div>
                </div>
              </ValidationProvider>
            </div>

            <div class="form-group row mb-0">
              <div class="col text-center py-2 mx-3">
                <button type="submit" class="btn btn-primary w-100 text-uppercase" :disabled="!valid">
                  Neues Passwort verwenden
                  <i class="icon-arrow-next-light "></i>
                </button>
              </div>
            </div>
          </ValidationObserver>
          <section v-if="errored">
            <div class="box--message000">
              <div class="layerbox--container000">
                <div class="">
                  <h3>Fehler</h3>
                  <p>
                    Die von dir eingegebenen Daten sind
                    fehlerhaft oder der Link ist
                    abgelaufen.
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
        </div>
      </div>
    </div>
  </default-layout>
</template>

<script>
import Spinner from "../components/groups/Spinner";

export default {
  name: "ForgotPasswordReset",
  components: {
    Spinner,
  },
  props: ["hash"],
  data() {
    return {
      errored: false,
      showResetForm: false,
      password: "",
      password_confirm: "",
      agb_privacy_accepted: false,
      age16_verified: false,
    };
  },
  mounted() {
    this.email = this.$route.query.email;
    if (this.email && this.hash) this.showResetForm = true;
  },
  methods: {
    onResetPassword() {
      axios
          .post(`/api/reset-password`, {
            email: this.email,
            hash: this.hash,
            password: this.password,
            password_confirm: this.password_confirm,
          })
          .then(({data}) => {
            if (data.success) {
              this.$store
                  .dispatch("currentUser/login", {
                    email: this.email,
                    password: this.password,
                    force: true,
                  })

                  .then(({user}) => {
                    if (user?.is_business_account)
                      this.$router.push({ path: "/b2b/dashboard"});
                    else
                      this.$router.push({ path: "/dashboard"});
                  })
                  .catch((err) => {
                    console.error(err);
                    this.errored = true;
                  });
            } else {
              this.errored = true;
            }
          })
          .catch((err) => {
            this.errored = true;
            console.error(err);
          });
    },
  },
};
</script>

<style lang="scss" scoped>
#verification {
  width: 100%;
  height: 100%;
  position: absolute;
  font-size: 24px;
}
</style>
