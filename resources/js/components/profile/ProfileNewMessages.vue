<template>
    <router-link
        :to="{ name: 'chat' }"
        class="d-flex align-items-center justify-content-between px-2 h-60"
    >
        <div class="d-flex align-items-center">
            <div class="w-64">
                <fa-icon
                    icon="envelope"
                    class="i-lg color--primary me-2 font-icon--big"
                ></fa-icon>
            </div>
            <template v-if="unreadCount === 1">
                <div>Du hast {{ unreadCount }} neue Nachricht</div>
            </template>
            <template v-else>
                <div>Du hast {{ unreadCount }} neue Nachrichten</div>
            </template>
        </div>

        <fa-icon
            icon="chevron-right"
            class="i-lg color--primary font-icon--big"
        />
    </router-link>
</template>

<script>
import { mapState } from "vuex";

export default {
    name: "ProfileNewMessages",
    computed: {
        ...mapState("chat", {
            unreadCount: "unreadCount",
        }),
    },
};
</script>

<style scoped></style>
