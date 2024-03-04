<template>
    <div>
        <DashboardIntroWidget />
        <MatchingToggleBanner
            v-if="isPremium && hasFriends && user.matching_status"
        />
        <MembershipsBanner v-if="!isPremium" />
        <div class="rounded-box text-center p-4 mb-2 d-none d-lg-block">
            <div class="greeting mb-2">
                <p>
                    Hallo {{ nickname }},<br />
                    schön, dass du da bist!
                </p>
            </div>
            <user-avatar :user="user" class="avatar"></user-avatar>
            <div class="mb-2 text-center" v-if="user.motto">
                <span class="font-weight-not-bold">Dein Spruch</span>
                <br />
                {{ user.motto }}
            </div>
        </div>
        <div class="rounded-box mb-2">
            <ProfileNewMatchings />
            <hr class="m-0" />
            <ProfileNewMessages />
            <hr class="m-0" />
            <ProfileNewFriendrequests />
        </div>
    </div>
</template>

<script>
import ProfileNewMessages from "./profile/ProfileNewMessages";
import ProfileNewMatchings from "./profile/ProfileNewMatchings";
import ProfileNewFriendrequests from "./profile/ProfileNewFriendrequests";
import DefaultLayout from "../layouts/DefaultLayout";
import UserAvatar from "./UserAvatar";
import MembershipsBanner from "./premium/MembershipsBanner";
import MatchingToggleBanner from "./MatchingToggleBanner";

import { mapGetters } from "vuex";
import DashboardIntroWidget from "./introduction/dashboard-intro-widget";

export default {
    name: "UserInfoPanel",
    components: {
        DashboardIntroWidget,
        UserAvatar,
        DefaultLayout,
        ProfileNewMatchings,
        ProfileNewMessages,
        ProfileNewFriendrequests,
        MembershipsBanner,
        MatchingToggleBanner,
    },
    computed: {
        ...mapGetters("currentUser", {
            nickname: "getNickname",
            user: "getObject",
            isPremium: "isPremium",
        }),
        ...mapGetters("partner", {
            activePartners: "activePartners",
        }),
        hasFriends() {
            return (
                this.activePartners.length !== undefined &&
                this.activePartners.length !== null &&
                this.activePartners.length > 0
            );
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.greeting {
    text-align: center;
    font-weight: 500;
    color: $dark-grey-text;
}

.avatar {
    display: block;
    width: 8rem;
    height: 8rem;
    margin: 0.625rem auto;
}
</style>
