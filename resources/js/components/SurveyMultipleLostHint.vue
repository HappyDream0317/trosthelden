<template>
    <div class="rounded-box mb-2 p-3">
        <p>Du trauerst um mehrere Personen gleichzeitig?</p>
        <p>
            Bei einer Mehrfachbelastung und somit einer möglichen, erschwerten
            Trauer sehen wir eine besondere Verantwortung auf unserer Seite. In
            erster Linie empfehlen wir bei einer Mehrfachtrauer, professionelle
            Hilfe direkt an deinem Wohnort oder in der näheren Umgebung zu
            suchen. Zudem steht die Telefonseelsorge rund um die Uhr für
            Beratungen zur Verfügung (<a
                href="https://www.telefonseelsorge.de"
                target="_blank"
                >telefonseelsorge.de</a
            >).
        </p>
        <p>
            Wenn Dein Bauch Dir sagt, dass Du Dich für einen hauptsächlichen
            Trauerfall bei TrostHelden anmelden möchtest, weil er gerade sehr
            akut ist, dann fülle bitte unseren Frageboten erneut aus.
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

<script>
import ResetFrabo from "./ResetFrabo";

export default {
    name: "SurveyMultipleLostHint",
    methods: {
        onResetAccount() {
            this.$eventBus.emit("modal-requested", {
                component: ResetFrabo,
            });
        },
    },
};
</script>

<style scoped></style>
