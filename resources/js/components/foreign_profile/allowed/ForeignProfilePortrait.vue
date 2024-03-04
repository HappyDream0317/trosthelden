<template>
    <div class="col-12 rounded px-3 py-2">
        <div class="row mb-2">
            <div class="col-3 my-auto text-center">
                <img v-if="hasAvatar" class="avatar" :src="avatar" />
                <AvatarPlaceholder class="avatar" v-else></AvatarPlaceholder>
            </div>
            <div class="col-9 my-auto text-left">
                <h3>
                    <UserIdentifier :user="currentUser"></UserIdentifier>
                </h3>
                <div class="rounded bg--lightgrey" v-if="toggleMotto">
                    {{ motto }}
                </div>
                <div></div>
            </div>
            <div>
                <FriendshipAction :user="currentUser"></FriendshipAction>
                <IconWatchlist :user="currentUser"></IconWatchlist>
                <iconBlocklist :user="currentUser"></iconBlocklist>
            </div>
        </div>
    </div>
</template>

<script>
import UserIdentifier from "../../UserIdentifier";
import FriendshipAction from "../../action_icons/FriendshipAction";
import IconWatchlist from "../../action_icons/IconWatchlist";
import IconBlocklist from "../../action_icons/IconBlocklist";
import { mapGetters } from "vuex";

export default {
    name: "ForeignProfilePortrait",
    components: {
        IconBlocklist,
        FriendshipAction,
        IconWatchlist,
        UserIdentifier,
    },
    props: ["motto", "avatar"],
    data: function () {
        return {};
    },
    methods: {},
    computed: {
        hasAvatar() {
            return this.avatar !== null;
        },
        toggleMotto() {
            return this.motto !== "";
        },
        ...mapGetters("currentUser", {
            currentUser: "getObject",
        }),
    },
};
</script>

<style scoped>
.avatar {
    display: block;
    width: 6rem;
    height: 6rem;
    border-radius: 50%;
    margin-bottom: 0.625rem;
}
</style>
