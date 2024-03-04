<template>
    <button class="btn btn-outline-primary" @click="trigger">
        <fa-icon class="icon" :icon="blockIcon"></fa-icon>
        <span v-if="showLabel">{{ blockText }}</span>
    </button>
</template>

<script>
import ActionIcon from "./ActionToggleIcon";
import AcceptTermsBlockList from "../groups/AcceptTermsBlockList";
import AddToBlockList from "../groups/AddToBlockList";
import RemoveFromBlockList from "../groups/RemoveFromBlockList";
import { mapGetters } from "vuex";

export default {
    name: "IconBlocklist",
    components: { ActionIcon },
    props: {
        userId: Number,
        userName: String,
        isBlocked: {
            type: Boolean,
            default: false,
        },
        showLabel: {
            type: Boolean,
            default: true,
        },
    },

    data: function () {
        return {
            elementName: "hide_user",
            blockStatus: this.isBlocked,
        };
    },
    computed: {
        ...mapGetters("currentUser", {
            user: "getObject",
            hasSeenElement: "hasSeenElement",
        }),
        isFirstHide() {
            return !this.hasSeenElement(this.elementName);
        },
        blockIcon() {
            return this.blockStatus === true ? "eye" : "eye-slash";
        },
        blockText() {
            return this.blockStatus === true
                ? "Profil einblenden"
                : "Profil ausblenden";
        },
        modalProps() {
            return {
                userId: this.userId,
                userName: this.userName,
            };
        },
    },
    mounted() {
        this.$eventBus.on("blocklist-accept-terms", (event) => {
            if (event.userId === this.userId) {
                if (this.blockStatus === false) this.add();
                else this.remove();
            }
        });
        this.$eventBus.on("blocklist-added", (event) => {
            if (event.userId === this.userId) {
                this.blockStatus = true;
            }
        });
        this.$eventBus.on("blocklist-removed", (event) => {
            if (event.userId === this.userId) {
                this.blockStatus = false;
            }
        });
    },
    methods: {
        trigger() {
            if (this.isFirstHide && this.blockStatus === false) {
                this.acceptTerms();
            } else if (this.blockStatus === false) {
                this.add();
            } else {
                this.remove();
            }
        },
        acceptTerms() {
            this.$eventBus.emit("modal-requested", {
                component: AcceptTermsBlockList,
                props: this.modalProps,
            });
        },
        add() {
            this.$eventBus.emit("modal-requested", {
                component: AddToBlockList,
                props: this.modalProps,
            });
        },
        remove() {
            this.$eventBus.emit("modal-requested", {
                component: RemoveFromBlockList,
                props: this.modalProps,
            });
        },
        beforeDestroy() {
            this.$eventBus.off("blocklist-accept-terms");
            this.$eventBus.off("blocklist-added");
            this.$eventBus.off("blocklist-removed");
        },
    },
};
</script>
