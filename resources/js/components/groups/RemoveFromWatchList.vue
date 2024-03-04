<template>
    <div class="interaction-modal-box__wrapper">
        <div class="interaction-modal-box__header">
            <fa-icon icon="star" class="icon"></fa-icon>
            <p class="copy">
                Möchtest du {{ userName }} von deiner Merkliste enfernen?
            </p>
        </div>
        <div class="interaction-modal-box__content">
            <p class="copy">
                Aktivitäten von dieser Personen werden nicht mehr auf deiner
                Startseite angezeigt.
            </p>
            <div class="cta-wrapper">
                <button
                    @click="$emit('close')"
                    class="btn btn-outline btn-outline-primary"
                >
                    Abbrechen
                </button>
                <button @click="confirm" class="btn btn-primary">
                    Person entfernen
                </button>
            </div>
        </div>
        <div class="interaction-modal-box__footer">
            <p class="copy">
                Du kannst deine Merkliste jederzeit bearbeiten. Die Merkliste
                findest du unter dem Menüpunkt Trauerfreunde.
            </p>
        </div>
    </div>
</template>

<script>

export default {
    name: "RemoveFromWatchList",
    props: {
        userName: String,
        userId: Number,
    },
    methods: {
        confirm() {
            axios
                .put("/api/user/watchlist/remove/" + this.userId)
                .then((response) => {
                    this.$store.dispatch("matomo/watchlistRemove");
                    this.$eventBus.emit("watchlist-removed", {
                        userId: this.userId,
                    });
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    this.$emit("close");
                });
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
