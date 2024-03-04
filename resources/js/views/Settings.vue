<template>
  <default-layout>
    <div class="row">
      <MainContent :has-sidebar="true">
        <h2>Nutzerkonto</h2>
        <div class="rounded-box p-4 mb-1">
          <h3>Zugangsdaten</h3>
          <!-- E-Mail -->
          <table>
            <tr>
              <td class="pe-2 color--primary py-1">
                E-Mail-Adresse:
              </td>
              <td class="wBreak py-1">
                {{ user.email }}
                <fa-icon
                    icon="edit"
                    @click="openChangeEmail"
                    class="color--primary me-2"
                ></fa-icon>
              </td>
            </tr>
            <tr>
              <td class="pe-2 py-1 color--primary">Nutzername:</td>
              <td class="wBreak py-1">
                {{ user.nickname }}
                <fa-icon
                    icon="edit"
                    @click="openChangeNickname"
                    class="color--primary me-2"
                ></fa-icon>
              </td>
            </tr>
            <tr>
              <td class="pe-2 py-1 color--primary">Passwort:</td>
              <td
                  class="py-1"
                  style="display: flex; align-items: center"
              >
                <span>*********</span>
                <fa-icon
                    icon="edit"
                    @click="openChangePassword"
                    class="color--primary me-2"
                    style="margin-left: 1rem"
                ></fa-icon>
              </td>
            </tr>
          </table>
          <div v-if="!user.requested_to_delete_at">
            <div v-if="!showDeleteMsg">
                            <span
                                class="delete-account"
                                @click="showDeleteMsg = !showDeleteMsg"
                            >Profil löschen</span
                            >
            </div>
            <div v-if="showDeleteMsg">
              <div class="delete-account__message">
                Bist du sicher? Alle deine Angaben gehen
                verloren.
                <div
                    class="delete-account__message__link"
                    @click="onDeleteAccount"
                >
                  ja, bitte löscht meinen Account
                </div>
              </div>
            </div>
          </div>
          <div v-if="user.requested_to_delete_at">
            <div class="delete-account__message">
              Dein Profil wird innerhalb von 30 Tagen gelöscht.
            </div>
          </div>
        </div>
        <template v-if="!isBusinessAccount">
          <div class="rounded-box p-4 mb-2" id="premium">
            <h3>Mitgliedschaft</h3>
            <template v-if="isPremium && !hasTerminationDate">
              <p>
                Du bist aktuell TrostHelden-Mitglied und kannst daher alle Funktionen von TrostHelden nutzen.
                Mit dem Button kannst du deine Mitgliedschaft kündigen.
              </p>
              <div class="d-flex justify-content-end">
                <button
                    class="btn btn-sm btn-outline-transparent"
                    @click="openCancelPremium"
                >
                  Mitgliedschaft kündigen
                </button>
              </div>
            </template>
            <template v-else-if="!isPremium">
              <p>
                Du möchtest deinen Trauerfreund kennenlernen?
                Geh den nächsten Schritt in deiner Trauerarbeit und werde TrostHelden-Mitglied.
              </p>
              <div class="d-flex justify-content-end">
                <router-link
                    class="btn btn-sm btn-primary"
                    :to="{ name: 'premium' }"
                    exact
                >
                  Mitglied werden
                </router-link>
              </div>
            </template>
            <template v-else-if="isPremium && hasTerminationDate">
              <p>
                Du hast uns eine Kündigungsanfrage gesendet oder deine TrostHelden-Mitgliedschaft ist bereits
                gekündigt. Solltest du Fragen zu deiner Mitgliedschaft haben, sende uns bitte eine E-Mail an
                <a href="mailto:abo@trosthelden.de">abo@trosthelden.de</a>
              </p>
            </template>
          </div>

          <h2 class="mt-4">Daten aus Fragebogen</h2>
          <div class="rounded-box p-4 mb-2">
            <h3>Fragebogen erneut ausfüllen</h3>
            <p>
              Du kannst den Fragebogen erneut ausfüllen, wenn du deine
              Angaben ändern möchtest. Dies kann beispielsweise
              sinnvoll sein, wenn dir beim ersten Ausfüllen ein Fehler
              unterlaufen ist.
            </p>
            <div class="d-flex justify-content-end">
              <button
                  class="btn btn-sm btn-outline-transparent"
                  @click="onResetAccount"
              >
                Fragebogen erneut ausfüllen
              </button>
            </div>
          </div>
        </template>
      </MainContent>
    </div>
  </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import ChangePassword from "../components/ChangePassword";
import ChangeEmail from "../components/ChangeEmail";
import MainContent from "../layouts/MainContent";
import ResetFrabo from "../components/ResetFrabo";
import ChangeNickname from "../components/ChangeNickname";
import CancelPremium from "../components/CancelPremium";
import {mapGetters} from "vuex";

export default {
  name: "Settings",
  components: {DefaultLayout, MainContent},
  data() {
    return {
      user: {},
      newEmail: null,
      showDeleteMsg: false,
      loading: false,
    };
  },
  computed: {
    ...mapGetters("currentUser", {
      isPremium: "isPremium",
      currentUser: "getObject",
      isBusinessAccount: "isBusinessAccount"
    }),
    hasTerminationDate: function () {
      return this.currentUser?.cancellation_at !== undefined && this.currentUser?.cancellation_at !== null;
    }
  },
  mounted() {
    this.getCurrentUser();
  },
  created() {
    this.$eventBus.on("settings-refresh-data", (data) => {
      this.getCurrentUser();
    });
  },
  methods: {
    getCurrentUser() {
      this.loading = true;
      axios
          .get("/api/settings/me")
          .then((response) => {
            this.user = response.data;
            this.loading = false;
          })
          .catch((err) => {
            console.error(err);
          });
    },
    openChangePassword() {
      this.$eventBus.emit("modal-requested", {
        component: ChangePassword,
      });
    },
    openChangeEmail() {
      this.$eventBus.emit("modal-requested", {
        component: ChangeEmail,
      });
    },
    openChangeNickname() {
      this.$eventBus.emit("modal-requested", {
        component: ChangeNickname,
      });
    },
    openCancelPremium() {
      this.$eventBus.emit("modal-requested", {
        component: CancelPremium,
      });
    },
    onDeleteAccount() {
      axios
          .post("/api/user/delete")
          .then(({data}) => {
            const {success, requested_to_delete_at} = data;
            if (success)
              this.user.requested_to_delete_at = requested_to_delete_at;
          })
          .catch((err) => {
            console.error(err);
          });
    },
    onRevokeDeleteAccount() {
      axios
          .post("/api/user/revoke-delete")
          .then(({data}) => {
            const {success, requested_to_delete_at} = data;
            if (success)
              this.user.requested_to_delete_at = requested_to_delete_at;
          })
          .catch((err) => {
            console.error(err);
          });
    },
    onResetAccount() {
      this.$eventBus.emit("modal-requested", {
        component: ResetFrabo,
      });
    },
  },
  beforeDestroy() {
    this.$eventBus.off("settings-refresh-data");
  },
};
</script>

<style scoped lang="scss">
.wBreak {
  word-break: break-word;
}

.delete-account {
  font-size: 0.8rem;
  text-decoration: underline;
  cursor: pointer;

  &__message {
    font-size: 0.8rem;

    &__link {
      cursor: pointer;
      font-style: italic;
      font-size: 0.8rem;
      text-decoration: underline;
    }
  }
}
</style>
