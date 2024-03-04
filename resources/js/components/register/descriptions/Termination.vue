<template>
    <div class="rounded-box text-center advice my-2">
        <div class="alert alert-info" role="alert">
            <p>Du trauerst um mehrere Personen gleichzeitig?</p>
            <p>
                Bei einer Mehrfachbelastung und somit einer möglichen,
                erschwerten Trauer sehen wir eine besondere Verantwortung auf
                unserer Seite. In erster Linie empfehlen wir bei einer
                Mehrfachtrauer, professionelle Hilfe direkt an deinem Wohnort
                oder in der näheren Umgebung zu suchen. Zudem steht die
                Telefonseelsorge rund um die Uhr für Beratungen zur Verfügung
                (<a href="https://www.telefonseelsorge.de" target="_blank"
                    >telefonseelsorge.de</a
                >).
            </p>
            <p>
                Wenn Dein Bauch Dir sagt, dass Du Dich für einen hauptsächlichen
                Trauerfall bei TrostHelden anmelden möchtest, weil er gerade
                sehr akut ist, dann fülle bitte unseren Frageboten erneut aus.
            </p>
            <div class="actions">
                <button
                    class="btn btn-outline-primary"
                    @click="onResetAccount"
                >
                    Fragebogen erneut ausfüllen
                </button>
                <router-link
                    :to="{ name: 'dashboard' }"
                    class="btn btn-primary"
                    exact
                    >Zur Startseite
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import ResetFrabo from "../../ResetFrabo";

export default {
    name: "Termination",
    methods: {
        onResetAccount() {
            this.$eventBus.emit("modal-requested", {
                component: ResetFrabo,
            });
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../../../sass/setup/variables";

.actions {
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    @media screen and (min-width: 768px) {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        .btn {
            width: auto;
            margin-bottom: 0;
        }
    }
}
</style>
