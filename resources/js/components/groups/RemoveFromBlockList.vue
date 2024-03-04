<template>
    <div class="wrapper rounded">
        <p class="header">
            <fa-icon icon="eye" class="icon"></fa-icon>
            <span>Profil einblenden?</span>
        </p>
        <p class="description">
            Möchtest du das Profil von {{ userName }} wieder einblenden?
        </p>

        <div class="dialog-actions mt-2">
            <button
                class="btn btn-outline-primary btn-sm unset-min-width font-weight-not-bold"
                :disabled="loading"
                @click="close"
            >
                Nein
            </button>
            <button
                class="btn btn-primary btn-sm text-white unset-min-width font-weight-not-bold"
                :disabled="loading"
                @click="confirm"
            >
                Ja
            </button>
        </div>
    </div>
</template>

<script>

export default {
    name: "RemoveFromBlockList",
    props: {
        userId: Number,
        userName: String,
    },
    data() {
        return {
            loading: false,
        };
    },
    methods: {
        close() {
            this.$emit("close");
        },
        confirm() {
            if (this.loading) return;
            this.loading = true;

            this.$store
                .dispatch("blockList/remove", this.userId)
                .then(async (response) => {
                    await this.$store.dispatch("partner/init");
                    this.$store.dispatch("matomo/blocklistRemove");
                    this.$store.dispatch("partner/fetchUnreadFriendRequestCount");
                    this.$eventBus.emit("blocklist-removed", {
                        userId: this.userId,
                    });
                })
                .catch((err) => {
                    console.error(err);
                })
                .finally(() => {
                    this.loading = false;
                    this.close();
                });
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
