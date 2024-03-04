<template>
    <div class="reply">
        <!-- comment -->
        <div class="comment">
            <div class="comment__user-info">
                <UserAvatar :user="author" />
                <UserIdentifier :user="author" />
                <div class="user-info__location">
                    {{ location }}
                </div>
                <UserActions :user="author" />
            </div>
            <div class="comment__content">
                <p v-html="content"></p>
            </div>
        </div>
        <!-- end of comment -->

        <!-- replies -->
        <div class="replies">
            <Replies :replies="replies" />
        </div>
        <!-- end of replies -->
    </div>
</template>

<script>
import UserAvatar from "../UserAvatar";
import UserIdentifier from "../UserIdentifier";
import UserActions from "../groups/UserActions";
import Comment from "../post/Comment";
export default {
    name: "Reply",
    components: { Comment, UserActions, UserIdentifier, UserAvatar },
    props: {
        reply: Object,
        author: Object,
        location: String,
        content: String,
        replies: Array,
    },
};
</script>

<style></style>
