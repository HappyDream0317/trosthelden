<template>
    <highlight-frame :user="user" :post-id="postId">
        <span v-if="groupVisibility" class="group">{{ groupName }}</span>
        <div
            class="comment only-text hide--blockquote mb-0 pt-0"
            v-html="reply"
        ></div>
        <template v-slot:footer>
            <span class="footer__content__my-post">Dein Beitrag</span>
            <span class="footer__content__title">{{ postTitle }}</span>
        </template>
    </highlight-frame>
</template>

<script>
import UserAvatar from "../UserAvatar";
import UserIdentifier from "../UserIdentifier";
import UserLocation from "../UserLocation";
import HighlightFrame from "./HighlightFrame";
export default {
    name: "ReplyHighlightItem",
    components: { HighlightFrame, UserLocation, UserIdentifier, UserAvatar },
    props: {
        user: Object,
        postTitle: String,
        groupCategory: String,
        groupName: String,
        groupVisibility: {
            default: false,
            type: Boolean,
        },
        reply: String,
        postId: Number,
    },
};
</script>

<style lang="scss" scoped></style>
