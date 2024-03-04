<template>
    <div class="wrapper rounded">
        <p class="header">
            <fa-icon icon="eye-slash" class="icon"></fa-icon>
            <span>Profil ausblenden</span>
        </p>
        <p class="description">
            <span>
                Du hast die Funktion <i>Profil ausblenden</i> zum ersten Mal
                benutzt. <br />
                Das Profil dieses Mitglieds wird dir von nun an nicht mehr
                angezeigt. Dies gilt auch für die Trauerfreund-Vorschläge und
                die Nachrichten-Ansicht, wenn ihr bereits miteinander
                geschrieben habt.<br /><br />
                Um das Profil eines Mitglieds wieder einzublenden, klicke im
                Menü auf <i>Trauerfreunde</i> und anschließend auf
                <i>Ausgeblendete Mitglieder</i>.
            </span>
        </p>

        <div class="dialog-actions mt-2">
            <button
                class="btn btn-primary btn-sm text-white unset-min-width font-weight-not-bold ms-auto"
                :disabled="loading"
                @click="confirm"
            >
                Okay
            </button>
        </div>
    </div>
</template>

<script>

export default {
    name: "AcceptTermsBlockList",
    props: {
        userId: Number,
        userName: String,
    },
    data() {
        return {
            elementName: "hide_user",
            loading: false,
        };
    },
    methods: {
        close() {
            this.$emit("close");
        },
        acceptTerms() {
            this.$eventBus.emit("blocklist-accept-terms", {
                userId: this.userId,
            });
        },
        addLastSeenElement() {
            axios
                .post("/api/user/has-seen", { element: this.elementName })
                .then(() => {
                    this.$store.dispatch(
                        "currentUser/addLastSeenElement",
                        this.elementName
                    );
                    this.acceptTerms();
                })
                .catch((err) => {
                    console.error(err);
                })
                .finally(() => {
                    this.loading = false;
                    this.close();
                });
        },
        confirm() {
            if (this.loading) return;
            this.loading = true;
            this.addLastSeenElement();
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
