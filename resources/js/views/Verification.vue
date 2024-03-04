<template>
    <div
        id="verification"
        class="d-flex justify-content-center align-content-center"
    >
        <spinner
            v-if="spinnerActive"
            color="color--primary"
            message="Wir überprüfen Ihre E-Mail, bitte haben Sie einen Moment Geduld."
        />
        <div
            class="verified d-flex flex-column justify-content-center align-items-center color--primary"
            v-else-if="success"
        >
            <p>
                Deine E-Mail-Adresse wurde erfolgreich überprüft. Du wirst in
                {{ redirectCounter }} Sekunden zum Login weitergeleitet.
            </p>
        </div>
        <div
            class="verified d-flex flex-column justify-content-center align-items-center color--primary"
            v-else-if="!success"
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
    </div>
</template>

<script>
import Spinner from "../components/groups/Spinner";
import { mapGetters } from "vuex";
import InformationMessage from "../components/groups/InformationMessage";
export default {
    name: "Verification",
    components: {
        Spinner,
    },
    props: ["user_id", "hash"],
    data() {
        return {
            msg: "",
            spinnerActive: true,
            success: false,
            redirectCounter: 5,
        };
    },
    computed: {
        ...mapGetters("currentUser", {
            isLoggedIn: "isLoggedIn",
            isVerified: "isVerified",
        }),
    },
    watch: {
        redirectCounter: function (value) {
            if (value === 0) {
                if (this.isLoggedIn) {
                    this.$router.push({ name: "dashboard" });
                } else {
                    this.$router.push({ name: "login" });
                }
            }
        },
        success: function (value, oldValue) {
            if (oldValue === false && value === true) {
                setInterval(() => {
                    this.redirectCounter = this.redirectCounter - 1;
                }, 1000);
            } else {
                console.log("failed?");
            }
        },
    },
    mounted() {


      gtag('event', 'marketing_email_verified');

      this.$store
            .dispatch("currentUser/verifyUser", {
                user_id: this.user_id,
                hash: this.hash,
            })
            .then(() => {
                this.spinnerActive = false;
                this.success = true;
            })
            .catch((err) => {
                console.error(err);
            });
    },
    methods: {
        resendEmail() {
            axios.post("/api/resend-verification-email").then((response) => {
                this.$router.push({ name: "dashboard" });
                this.$eventBus.emit("modal-requested", {
                    component: InformationMessage,
                    props: {
                        title: "Bestätigung deiner E-Mail-Adresse",
                        message:
                            "Wir haben dir eine E-Mail mit Bestätigungslink geschickt. Bitte klicke auf den Link zur Bestätigung deiner E-Mail-Adresse.",
                    },
                });
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
.verified {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    p {
        line-height: 1.5;
        @media only screen and (max-width: 1023px) {
            text-align: center;
        }
    }
}
</style>
