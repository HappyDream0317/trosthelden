<template>
    <div
        class="rounded message"
        :class="{
            own: isOwnMessage,
            partner: !isOwnMessage,
            'message--new': isNew && !message.read_at,
        }"
    >
        <p>{{ message.message }}</p>
        <span class="timestamp" :title="message.send_at">{{
            formattedDate
        }}</span>
    </div>
</template>

<script>
import formatDate from "../../utils/format-date";
import { mapGetters } from "vuex";

export default {
    name: "ChatBubble",
    props: {
        message: Object,
        highlight: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            isNew: false,
        };
    },
    mounted() {
        const isNewMessage = this.compareDates(this.message);
        const isForeignMessage = this.currentUserId !== this.message.user_id;
        if (isNewMessage && isForeignMessage && this.highlight) {
            this.isNew = true;
        }
    },
    methods: {
        compareDates(message) {
            return Date.parse(message.send_at) > this.lastMessageDate;
        },
    },
    computed: {
        isOwnMessage() {
            return this.message.user_id === this.currentUserId;
        },
        nickname() {
            return this.isOwnMessage
                ? this.currentUserNickname
                : this.message.author.nickname;
        },
        formattedDate() {
            return formatDate(this.message.send_at);
        },
        ...mapGetters("currentUser", {
            currentUserId: "getId",
            currentUserNickname: "getNickname",
        }),
        ...mapGetters("chat", {
            lastMessageDate: "lastMessageDate",
        }),
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.message {
    color: $dark-grey-text;
    background-color: $brand-color-secondary;
    width: 80%;
    clear: both;
    position: relative;
    margin: 0.8rem;
    padding: 0.6rem;
    display: flex;
    flex-direction: column;
    transition: background-color 1s linear;
    &--new {
        background-color: $primary;
        color: $white;

        &.partner::after {
            border-color: transparent $primary transparent transparent !important;
        }
    }
}
.message > p {
    margin-bottom: 0.4rem;
    max-width: 100%;
    word-break: break-word;
    white-space:pre-wrap;
}

.message::after {
    border-style: solid;
    border-width: 0.4rem 1rem 0.4rem 1rem;
    content: " ";
    position: absolute;
    top: 0.4rem;
    transition: border-color 1s linear;
}
.message.own {
    float: right;
    margin-right: 1.8rem;
}
.message.own::after {
    right: -2rem;
    border-color: transparent transparent transparent $brand-color-secondary;
}
.message.partner {
    float: left;
    margin-left: 1.8rem;
}
.message.partner::after {
    left: -2rem;
    border-color: transparent $brand-color-secondary transparent transparent;
}
.timestamp {
    float: right;
    text-align: right;
}
</style>
