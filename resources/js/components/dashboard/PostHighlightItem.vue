<template>
    <highlight-frame :user="user" :post-id="postId">
        <span class="title color--primary">{{ postTitle }}</span>
        <div
            class="comment only-text hide--blockquote mb-0 pt-0"
            v-html="postContent"
        ></div>
        <template v-slot:footer>
            <div class="footer__content__thema color--dark-grey">Thema</div>
            <span class="footer__content__category color--dark-grey">{{
                groupCategory
            }}</span>
            <span class="footer__content__group color--primary">{{
                groupName
            }}</span>
        </template>
    </highlight-frame>
</template>

<script>
import HighlightFrame from "./HighlightFrame";
export default {
    name: "PostHighlightItem",
    components: { HighlightFrame },
    props: {
        user: {
            default: null,
        },
        postTitle: String,
        groupCategory: String,
        groupName: String,
        postContent: String,
        postId: Number,
    },
};
</script>

<style lang="scss" scoped></style>
