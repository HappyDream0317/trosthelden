<template>
    <DefaultLayout>
        <h1>Testseite für Icons</h1>
        <AbstractModal></AbstractModal>
        <IconBlocklist :user="user"></IconBlocklist>
        <IconWatchlist :user="user"></IconWatchlist>
        <FriendshipAction :user="user"></FriendshipAction>
    </DefaultLayout>
</template>

<script>
import IconBlocklist from "../components/action_icons/IconBlocklist";
import IconWatchlist from "../components/action_icons/IconWatchlist";
import FriendshipAction from "../components/action_icons/FriendshipAction";
import DefaultLayout from "../layouts/DefaultLayout";
import AbstractModal from "../components/action_icons/AbstractModal";
export default {
    name: "iconTestPage",
    components: {
        IconBlocklist,
        DefaultLayout,
        IconWatchlist,
        AbstractModal,
        FriendshipAction,
    },
    props: ["user_id"],
    data: function () {
        return {
            user: {
                id: this.user_id,
                nickname: "User",
            },
        };
    },
};
</script>

<style scoped></style>
