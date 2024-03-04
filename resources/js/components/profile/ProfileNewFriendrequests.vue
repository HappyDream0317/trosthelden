<template>
    <router-link
        :to="{ name: 'partner' }"
        class="d-flex align-items-center justify-content-between px-2 h-60"
    >
        <div class="d-flex align-items-center">
            <div class="w-64">
                <fa-icon
                    icon="user-plus"
                    class="i-lg color--primary me-2 font-icon--big"
                />
            </div>
            <div>{{ new_friendrequests }} neue Trauerfreund-Anfragen</div>
        </div>
        <fa-icon
            icon="chevron-right"
            class="i-lg color--primary font-icon--big"
        />
    </router-link>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "ProfileNewFriendrequests",
    computed: {
        ...mapGetters("partner", {
            new_friendrequests: "getUnreadFriendRequestCount",
        }),
    },
};
</script>

<style scoped></style>
