<template>
    <div class="p-4">
        <h2 class="text-center mb-4">Fragebogen erneut ausfüllen</h2>
        <p><strong>Bitte beachte:</strong></p>
        <p>
            Nach Klick auf dem Button werden deine bisherigen
            Fragebogen-Antworten gelöscht. Du wirst anschließend zur Startseite
            weitergeleitet und kannst den Fragebogen erneut auszufüllen.
        </p>
        <p>
            Deine bisherigen Trauerfreund-Vorschläge werden gelöscht. Nach
            Abschluss des Fragebogens suchen wir mit deinen neuen Angaben
            passende Trauerfreund-Vorschläge für dich.
        </p>
        <p>
            Solltest du schon einen oder mehrere Trauerfreunde gefunden haben,
            bleiben diese erhalten. Dies gilt auch für deine Nachrichten.
        </p>
        <div class="d-flex justify-content-end">
            <button class="btn btn-danger text-white" @click="onResetAccount">
                Fragebogen-Antworten löschen und Fragebogen erneut ausfüllen
            </button>
        </div>
    </div>
</template>

<script>

export default {
    name: "ResetFrabo",
    emits: ["close"],
    methods: {
        onResetAccount() {
            axios
                .post("/api/user/reset-matching")
                .then(({ data }) => {
                    const { success } = data;
                    if (success) {
                        localStorage.removeItem("firstView");
                        this.redirect();
                    }
                })
                .catch((err) => {
                    console.error(err);
                })
                .finally(() => {
                    this.$emit("close");
                });
        },
        redirect() {
            let path = "/guide";
            if (this.$route.path === path) {
                this.$eventBus.emit("reset-frabo");
            } else {
                this.$router.push({ path });
            }
        },
    },
};
</script>

<style scoped></style>
