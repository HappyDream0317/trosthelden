<template>
    <div class="reply-list">
        <Reply
            v-for="reply in replies"
            :key="reply.id"
            :author="reply.author"
            :title="reply.title"
            :content="reply.comment"
            :location="reply.location"
            :replies="reply.comments"
        />
    </div>
</template>

<script>
import Comment from "../post/Comment";
import Reply from "./Reply";
export default {
    name: "Replies",
    components: { Reply, Comment },
    props: {
        replies: Array,
    },
};
</script>

<style></style>
