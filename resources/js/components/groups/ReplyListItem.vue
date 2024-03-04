<template>
    <div class="reply rounded-bottom p-2" :class="'level-' + depth">
        <div class="d-flex flex-column flex-lg-row">
            <div class="relation-arrow text-primary" v-if="depth > 0">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 25 25"
                    width="25"
                    height="25"
                    id="replySvg"
                >
                    <path
                        id="reply"
                        class="shp0"
                        d="M7.3 6.43L7.3 8.42C7.09 8.42 6.9 8.46 6.73 8.54C6.54 8.62 6.37 8.73 6.22 8.87C6.08 9 5.97 9.17 5.9 9.37C5.82 9.55 5.78 9.75 5.78 9.96C5.78 10.16 5.82 10.35 5.9 10.53C5.99 10.75 6.1 10.92 6.22 11.04C6.37 11.18 6.54 11.29 6.73 11.36C6.9 11.44 7.09 11.48 7.3 11.48L18.07 11.48L15.34 8.75L16.75 7.34L21.35 11.95L21.35 13L16.75 17.61L15.34 16.2L18.07 13.47L7.3 13.47C6.79 13.47 6.35 13.39 5.97 13.21C5.51 13.01 5.13 12.75 4.83 12.44C4.48 12.08 4.22 11.7 4.06 11.29C3.88 10.87 3.8 10.43 3.8 9.96C3.8 9.48 3.88 9.03 4.06 8.61C4.23 8.16 4.49 7.78 4.83 7.46C5.2 7.11 5.57 6.85 5.97 6.69C6.37 6.52 6.82 6.43 7.3 6.43Z"
                    />
                </svg>
            </div>

            <div class="user-info">
                <user-avatar :user="author"></user-avatar>
                <div class="user-info__text">
                    <user-identifier
                        :user="author"
                        class="primary-user-info color--primary font-bold"
                    ></user-identifier>
                    <span class="user-info__location">{{ location }}</span>
                </div>
                <user-actions
                    :user="author"
                    :location="author.location"
                    class="d-none d-lg-block"
                />
            </div>

            <div class="post-wrapper flex-grow-1 flex-column">
                <div>
                    <div
                        class="reply-author color--primary font-bold"
                        @click="onGoToProfile(author.id)"
                    >
                        {{
                            author.nickname
                                ? author.nickname
                                : "Ehemaliges Mitglied (" + author.id + ")"
                        }}
                        <span>schreibt:</span>
                    </div>
                    <span class="timestamp">{{ formattedDate }}</span>
                    <p class="comment only-text p-0" v-html="content"></p>
                </div>
                <post-actions
                    @report="emitReportEvent(id)"
                    @reply="emitReplyEvent(id)"
                    @quote="emitQuoteEvent"
                ></post-actions>
            </div>
        </div>
        <reply-list-item
            class="replies"
            v-if="!isMaxDepth"
            :depth="isMaxDepth ? depth : depth + 1"
            v-for="reply in replies"
            :key="'r-' + reply.id"
            :id="reply.id"
            :father-id="id"
            :created="reply.created_at"
            :content="reply.comment"
            :author="reply.author"
            :replies="reply.comments"
            @report="emitReportEvent(reply.id)"
            @reply="emitReplyEvent(reply.id)"
            @quote="emitQuoteEvent"
        />
    </div>
</template>

<script>
import UserInfo from "./UserInfo";
import PostActions from "./PostActions";
import formatDate from "../../utils/format-date";
import UserActions from "./UserActions";
import UserIdentifier from "../UserIdentifier";
import UserAvatar from "../UserAvatar";

const MAX_NESTING_DEPTH = 1;

export default {
    name: "ReplyListItem",
    components: {
        UserActions,
        PostActions,
        UserInfo,
        UserIdentifier,
        UserAvatar,
    },
    props: {
        id: null,
        fatherId: null,
        content: String,
        created: String,
        author: Object,
        replies: Array,
        depth: {
            default: 0,
        },
    },
    methods: {
        onGoToProfile(userId) {
            this.$router.push({
                name: "foreignProfile",
                params: { user_id: userId },
            });
        },
        emitReportEvent(id) {
            if (id === undefined) {
                id = this.id;
            }

            this.$emit("report", id);
        },

        emitReplyEvent(id) {
            this.$emit("reply", this.getParentId(id));
        },

        emitQuoteEvent(quote) {
            if (typeof quote === "undefined") {
                quote = {
                    id: this.getParentId(),
                    quote: {
                        user: this.author,
                        comment: this.content,
                        date: this.created,
                    },
                };
            }
            this.$emit("quote", quote);
        },
        getParentId(id) {
            if (id === undefined && this.isMaxDepth) {
                id = this.fatherId;
            } else if (typeof id === "undefined") {
                id = this.id;
            }
            return id;
        },
    },
    computed: {
        formattedDate() {
            return formatDate(this.created);
        },
        isMaxDepth() {
            return this.depth >= MAX_NESTING_DEPTH;
        },
        location() {
            if (this.author.location && this.author.protected_postal_code) {
                return (
                    this.author.location +
                    ", " +
                    this.author.protected_postal_code
                );
            }
            return "";
        },
    },
    mounted() {
        //console.log(this.author);
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.reply {
    background-color: $brand-color-base;
    padding: 0.625rem 0;

    & > .post-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;

        & > :first-child {
            flex: 1;
        }
    }

    & > .replies {
        flex-basis: 100%;
    }
}

.relation-arrow {
    color: $normal-grey-text;
    font-size: 1.5rem;

    @media (min-width: 992px) {
        font-size: 2.5rem;
        line-height: 150px;
        padding-right: 0.625rem;
    }
}

.level-0 > :first-child {
    margin-left: 0.625rem;
}

.post-wrapper {
    @media screen and (min-width: 992px) {
        margin-left: 1rem;
    }
}

.level-1 > :first-child {
    //margin-left: 0.625rem * 2;
}

.level-2 > :first-child {
    //margin-left: 0.625rem * 4;
}

.level-0 {
    border-top-right-radius: $border-radius;
    border-top-left-radius: $border-radius;
    margin-bottom: 1rem;
}

.level-1,
.level-2 {
    margin-top: 0.625rem;
    border-top: 0.2rem solid $normal-grey-background;
}

.user-info {
    display: flex;

    &__text {
        display: flex;
        flex-direction: column;
        margin-top: 0.3rem;
    }

    &__location {
        margin-bottom: 0.2rem;
        @media screen and (min-width: 992px) {
            display: none;
        }
    }
}

.timestamp {
    display: block;
    margin-bottom: 0.2rem;
}

.reply-author {
    display: none;
    margin-top: 0.2rem;
}

.reply__location {
    display: none;
    margin-bottom: 0.2rem;
    @media screen and (min-width: 992px) {
        display: block;
    }
}
</style>
