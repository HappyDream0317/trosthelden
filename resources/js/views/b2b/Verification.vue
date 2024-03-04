<template>
  <default-layout>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card bg-transparent border-0 rounded-box">
          <spinner
              v-if="spinnerActive"
              color="color--primary"
              message="Wir überprüfen Ihre E-Mail, bitte haben Sie einen Moment Geduld."
          />
          <div
              class="card-body verified d-flex flex-column justify-content-center align-items-center color--primary"
              v-else-if="!spinnerActive && !hashVerified"
          >
            <p>
              Leider ist dein Link abgelaufen.. Bitte fordere eine neuen
              Verifizierungslink an.
            </p>
            <button
                class="btn btn-primary btn-sm text-white unset-min-width mb-1 me-lg-1 font-weight-not-bold p-1 pe-sm-4 ps-sm-4"
                @click="resendEmail"
            >
              Neuen Link verschicken
            </button>
          </div>
          <div class="card-body" v-else-if="!spinnerActive && hashVerified">
            <div class="text-center mb-4">
              Bitte gib dein neues Passwort ein
            </div>
            <ValidationObserver v-slot="{ meta: {valid, dirty} }" tag="form" @submit="resetPassword">
              <div class="form-group row px-3">
                <div class="col-md-12">
                  <label for="password">Passwort (mind. 8 Zeichen, inkl. einem Buchstaben und einer Zahl)</label>
                  <ValidationProvider
                      as="div"
                      name="password"
                      rules="required|min:8"
                      v-model="password"
                      v-slot="{ errorMessage, field }"
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
                      v-model="password_confirm"
                      v-slot="{ errorMessage, field }"
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
                    v-model="age16_verified"
                    v-slot="{ errorMessage, field, handleChange }"
                    type="checkbox"
                    :value="true"
                    :unchecked-value="false"
                >
                  <div>
                    <div class="form-check">
                      <input v-bind="field" id="age16_verified" type="checkbox"
                             @input="handleChange"
                             class="form-check-input form-check-input--lg" name="age16_verified" required/>
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
                    v-model="agb_privacy_accepted"
                    v-slot="{ errorMessage, field, handleChange }"
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
              <div class="box--message000 mt-3">
                <div class="layerbox--container000">
                  <div class="">
                    <h3>Fehler</h3>
                    <p>
                      Die von Ihnen eingegebenen Daten sind falsch
                    </p>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </default-layout>
</template>

<script>
import Spinner from "../../components/groups/Spinner.vue";
import InformationMessage from "../../components/groups/InformationMessage.vue";

export default {
  name: "B2BVerification",
  components: {
    Spinner,
  },
  props: ["user_id", "hash"],
  data() {
    return {
      errored: false,
      spinnerActive: true,
      hashVerified: null,
      password: "",
      password_confirm: "",
      agb_privacy_accepted: false,
      age16_verified: false,
    };
  },
  mounted() {
    if (this.hash)
      this.verifyToken();
  },
  methods: {
    async verifyToken() {
      try {
        const {data} = await axios.post(`/api/b2b/verify/hash`, {
          user_id: this.user_id,
          hash: this.hash,
        });

        if (!data.success) return Promise.reject();
        this.hashVerified = true;
        this.spinnerActive = false;
      } catch (e) {
        this.hashVerified = false;
        this.spinnerActive = false;
      }
    },
    async resendEmail() {
      try {
        await axios.post(`/api/b2b/${this.user_id}/verify/resend`, {
          user_id: this.user_id
        });

        if (!data.success) return Promise.reject();

        this.$router.push({name: "login"});

        this.$eventBus.emit("modal-requested", {
          component: InformationMessage,
          props: {
            title: "Bestätigung deiner E-Mail-Adresse",
            message:
                "Wir haben dir eine E-Mail mit Bestätigungslink geschickt. Bitte klicke auf den Link zur Bestätigung deiner E-Mail-Adresse.",
          },
        });
      } catch (e) {
        console.log(e);
      }
    },
    async resetPassword() {

      try {
        this.errored = false;

        const {data} = await axios.post(`/api/b2b/verify/reset`, {
          user_id: this.user_id,
          hash: this.hash,
          password: this.password,
          password_confirm: this.password_confirm,
        });

        if (!data.success) return Promise.reject();

        const {user} = await this.$store.dispatch("currentUser/login", {
          email: data.email,
          password: this.password,
          force: true,
        });

        if (!user) return Promise.reject();

        this.$router.push({ path: "/b2b/dashboard"});

      } catch (e) {
        this.errored = true;
      }
    },
  }
}
</script>

<style scoped>

</style>