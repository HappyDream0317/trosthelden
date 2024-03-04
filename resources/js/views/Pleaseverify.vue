<template>
    <default-layout>
        <main class="container">
            <div class="rounded-box p-3">
                <p>
                    Lieber TrostHeld, <br /><br />
                    bitte bestätige noch deine E-Mail-Adresse. Dann kann deine
                    Trauerfreund-Suche richtig losgehen! Den entsprechenden Link
                    findest du in deinem
                    <span class="break-desktop">E-Mail-Eingang.</span>
                    <br /><br />
                    Du hast keinen Bestätigungs-Link von uns erhalten? Klicke
                    <button
                        v-if="from === '/pleaseverify'"
                        class="a"
                        @click="resendChangeMail"
                    >
                        hier
                    </button>
                    <button v-else class="a" @click="resendEmail">hier</button>
                </p>
            </div>
        </main>
    </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import InformationMessage from "../components/groups/InformationMessage";
export default {
    name: "Pleaseverify",
    components: { DefaultLayout },
    data: () => {
        return {
            from: null,
        };
    },
    mounted() {
        this.from = this.$router.history._startLocation;
    },
    methods: {
        resendEmail() {
            axios.post("/api/resend-verification-email").then((response) => {
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
        resendChangeMail() {
            axios.post("/api/resend-change-email").then((response) => {
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

<style scoped lang="scss">
.break-desktop {
    @media only screen and (min-width: 1199px) {
        display: block;
    }
}
</style>
