<template>
    <button @click="trigger">
        <fa-icon :icon="theFriendStatus.icon" class="icon"></fa-icon>
        <span> Anfrage ablehnen </span>
    </button>
</template>

<script>
import DenyFriendRequest from "../groups/DenyFriendRequest";
import { mapGetters } from "vuex";

export default {
    name: "DenyFriendshipAction",
    components: { DenyFriendRequest },
    props: {
        userId: Number,
    },
    computed: {
        theFriendStatus() {
            return {
                icon: "user-minus",
                label: "Anfrage ablehnen",
            };
        },
        ...mapGetters("partner", {
            partnerName: "name",
        }),
    },
    methods: {
        trigger() {
            this.$eventBus.emit("modal-requested", {
                component: DenyFriendRequest,
                props: {
                    userId: this.userId,
                    userName: this.partnerName(this.userId),
                },
            });
        },
    },
};
</script>

<style scoped lang="scss"></style>
