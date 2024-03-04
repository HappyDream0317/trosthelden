<template>
    <div
        class="chat-log"
        ref="log"
        role="tabpanel"
        @wheel="markAsRead"
        @click="markAsRead"
        @mouseover="markAsRead"
    >
        <template v-if="messages.length">
            <chat-bubble
                v-for="message in messages"
                :highlight="true"
                :message="message"
                :key="message.id"
            ></chat-bubble>
        </template>
    </div>
</template>

<script>
import ChatBubble from "./ChatBubble";
import { mapGetters, mapState } from "vuex";

export default {
    name: "Log",
    components: { ChatBubble },
    data() {
        return {
            dispatching: false,
        };
    },
    methods: {
        scrollToBottom() {
            this.$nextTick(() => {
                this.$refs.log.scrollTop = this.$refs.log.scrollHeight;
                this.$refs.log.focus();
            });
        },
        markAsRead() {
            if (
                !this.dispatching &&
                this.unreadCountPerChat[this.activeChatPartnerId]
            ) {
                console.log("dispatch");
                this.dispatching = true;
                this.$store.dispatch("chat/markAsRead").then(() => {
                    setTimeout(() => {
                        this.dispatching = false;
                        console.log("dispatch end");
                    }, 2500);
                });
            }
        },
    },
    computed: {
        ...mapState("chat", {
            messages: "messages",
            unreadCountPerChat: "unreadCountPerChat",
        }),
        ...mapGetters("chat", {
            activeChatPartnerId: "activeChatPartnerId",
        }),
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";
@import "../../../sass/scrollbar";

.chat-log {
    overflow-y: scroll;
    overflow-x: hidden;
    max-height: 600px;
    @include scroll-bar(transparent, $brand-color-primary);
}
</style>
