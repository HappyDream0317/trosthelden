<template>
    <div class="interaction-modal-box__wrapper">
        <div class="interaction-modal-box__header">
            <fa-icon icon="star" class="icon"></fa-icon>
            <p class="copy">
                Möchtest du {{ userName }} zu deiner Merkliste hinzufügen?
            </p>
        </div>
        <div class="interaction-modal-box__content">
            <p class="copy">
                Auf deiner Merkliste kannst du Personen hinzufügen, denen du
                vielleicht später eine Trauerfreund-Anfrage senden möchtest.
            </p>
            <div class="cta-wrapper">
                <button
                    @click="$emit('close')"
                    class="btn btn-outline btn-outline-primary"
                >
                    Abbrechen
                </button>
                <button @click="confirm" class="btn btn-primary">
                    Person hinzufügen
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
    name: "AddToWatchList",
    props: {
        userId: Number,
        userName: String,
    },
    methods: {
        confirm() {
            axios
                .put("/api/user/watchlist/watch/" + this.userId)
                .then((response) => {
                    this.$store.dispatch("matomo/watchlistAdd");
                    this.$eventBus.emit("watchlist-added", {
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
