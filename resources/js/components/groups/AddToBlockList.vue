<template>
    <div class="wrapper rounded">
        <BlockFriendElement
            v-if="isAFriend"
            :loading="loading"
            :user-id="userId"
            :user-name="userName"
            @reject="close"
            @accept="confirm"
        />
        <BlockUserElement
            v-else
            :loading="loading"
            :user-id="userId"
            :user-name="userName"
            @reject="close"
            @accept="confirm"
        />
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import BlockFriendElement from "./BlockFriendElement";
import BlockUserElement from "./BlockUserElement";

const BEFRIENDED = 2;

export default {
    name: "AddToBlockList",
    props: {
        userId: Number,
        userName: String,
    },
    components: {
        BlockFriendElement,
        BlockUserElement,
    },
    data() {
        return {
            loading: false,
            isAFriend: false,
        };
    },
    computed: {
        ...mapGetters("partner", {
            partnerStatus: "friendStatus",
        }),
    },
    mounted() {
        this.isAFriend = this.partnerStatus(this.userId) === BEFRIENDED;
    },
    methods: {
        close() {
            this.$emit("close");
        },
        confirm(event) {
            if (this.loading) return;
            this.loading = true;

            if (this.isAFriend) this.removeFriendship(event);
            else this.blockUser();
        },
        removeFriendship({ message }) {
            this.$store
                .dispatch("partner/remove", {
                    partnerId: this.userId,
                    message: message,
                })
                .then(() => {
                    this.blockUser();
                })
                .catch((err) => {
                    console.error(err);
                });
        },
        blockUser() {
            this.$store
                .dispatch("blockList/add", this.userId)
                .then(async (response) => {
                    await this.$store.dispatch("partner/init");
                    this.$store.dispatch("chat/init");
                    this.$store.dispatch("matomo/blocklistAdd");
                    this.$store.dispatch("partner/fetchUnreadFriendRequestCount");
                    this.$eventBus.emit("blocklist-added", {
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
