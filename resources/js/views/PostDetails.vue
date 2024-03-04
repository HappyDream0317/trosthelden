<template>
    <default-layout>
        <Comment
            :author="author"
            :title="title"
            :content="content"
            :location="location"
            :replies="replies"
            :date="created"
            :key="'c-' + id"
            :views="views"
            :comments="comments"
            :currentUserID="currentUserID"
            :id="id"
            @report="reportPost"
            @reply="replyToComment"
            @quote="quoteComment"
        />
        <div class="writing-wrapper">
            <new-post
                target-type="comment"
                :post-id="this.id"
                :target-id="replyTargetId"
                :quote="quote"
                @newPost="replyToPost($event)"
            ></new-post>
        </div>
    </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import UserInfo from "../components/groups/UserInfo";
import PostActions from "../components/groups/PostActions";
import UserActions from "../components/groups/UserActions";
import ReplyListItem from "../components/groups/ReplyListItem";
import NewPost from "../components/groups/NewPost";
import formatDate from "../utils/format-date";
import Modal from "../components/Modal";
import ReportPost from "../components/groups/ReportPost";
import { mapGetters } from "vuex";

import UserAvatar from "../components/UserAvatar";
import UserIdentifier from "../components/UserIdentifier";
import Comment from "../components/post/Comment";

export default {
    name: "PostDetails",
    components: {
        Comment,
        Modal,
        NewPost,
        ReplyListItem,
        PostActions,
        UserInfo,
        DefaultLayout,
        UserActions,
        UserAvatar,
        UserIdentifier,
    },
    data() {
        return {
            hasLoaded: false,
            id: 0,
            author: {},
            title: "",
            content: "",
            created: "",
            replies: [],
            views: 0,
            comments: 0,
            replyTargetId: null,
            groupId: null,
            refreshKey: 0,
            quote: null,
            channelName: "",
            isListening: false,
        };
    },
    methods: {
        setData(data) {
            this.id = data.id;
            this.author = data.author;
            this.title = data.title;
            this.content = data.message;
            this.created = data.created_at;
            this.views = data.impressions_count;
            this.comments = data.comments_count;
            this.groupId = data.group_id;
        },
        scrollToNewCommentBoxAndFocus() {
            const newPostBox = document.getElementById("neuer_beitrag");
            window.scrollTo(
                0,
                document.documentElement.scrollTop +
                    newPostBox.getBoundingClientRect().top
            );
            this.$eventBus.emit("new-post-focus");
        },
        resetReply() {
            this.replyTargetId = null;
            this.scrollToNewCommentBoxAndFocus();
        },
        replyToPost(newCommentEvent) {
            this.resetReply();
            this.addReply(newCommentEvent, newCommentEvent.parent_comment_id);
            // this.getComments();
        },
        replyToComment(commentId) {
            if (commentId !== this.id) {
                this.replyTargetId = commentId;
            }
            this.scrollToNewCommentBoxAndFocus();
        },
        addReply(reply, parentId, viaWebsocket = false) {
            if (
                viaWebsocket &&
                this.$store.getters["currentUser/getId"] === reply.author.id
            )
                return;

            if (typeof parentId === "undefined" || parentId === null) {
                this.replies.push(reply);
                return;
            }
            let parent = this.findReply(this.replies, parentId);
            if (parent === null) {
                return;
            }
            if (!parent.hasOwnProperty("comments")) {
                parent["comments"] = [];
            }
            parent.comments.push(reply);
        },
        findReply(replies, id) {
            for (let reply of replies) {
                if (reply.id === id) {
                    return reply;
                }
                if (reply.comments) {
                    let search = this.findReply(reply.comments, id);
                    if (search !== null) {
                        return search;
                    }
                }
            }
            return null;
        },
        quotePost() {
            this.quote = {
                title: this.title,
                date: this.created,
                comment: this.content,
                user: this.author,
            };
            this.resetReply();
        },
        quoteComment(quoteComment) {
            this.quote = quoteComment.quote;
            const id = quoteComment.id;
            this.replyToComment(id);
        },
        reportPost() {
            this.$eventBus.emit("modal-requested", {
                component: ReportPost,
                props: {
                    userId: this.currentUserId,
                    type: "post",
                    itemId: this.id,
                },
            });
        },
        reportComment(id) {
            this.$eventBus.emit("modal-requested", {
                component: ReportPost,
                props: {
                    userId: this.currentUserId,
                    type: "comment",
                    itemId: id,
                },
            });
        },
        getComments() {
            let postRequest = axios.get("/api/post/" + this.$route.params.id);
            let commentRequest = axios.get(
                "/api/post/" + this.$route.params.id + "/comments"
            );

            postRequest
                .then((response) => {
                    this.setData(response.data.data);
                    this.listenToSocket();
                })
                .catch((err) => {
                    console.log(err);
                });

            commentRequest
                .then((response) => {
                    this.replies = response.data.data;
                    this.hasLoaded = true;
                })
                .catch((err) => {
                    console.log(err);
                });

            Promise.all([postRequest, commentRequest]).finally(() => {
                this.loading = false;
            });
        },
        onGoToProfile(userId) {
            this.$router.push({
                name: "foreignProfile",
                params: { user_id: userId },
            });
        },
        listenToSocket() {
            if (!this.isListening) {
                this.channelName =
                    "group_" + this.groupId + "_post_" + this.id + "_comments";
                let channel = Echo.channel(this.channelName);
                channel.listen("CommentNew", (data) => {
                    this.addReply(
                        data.comment,
                        data.comment.parent_comment_id,
                        true
                    );
                });
            }
        },
    },
    computed: {
        formattedDate() {
            return formatDate(this.created);
        },
        ...mapGetters("currentUser", {
            currentUserId: "getId",
        }),
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
        currentUserID() {
            return this.$store.getters["currentUser/getId"];
        },
    },
    mounted() {
        this.getComments();
    },
    destroyed() {
        Echo.leave(this.channelName);
    },
};
</script>
<style lang="scss" scoped>
@import "../../sass/setup/variables";

.post {
    padding: 0.625rem;
    display: flex;

    & > .user-info {
        padding-left: 1rem;
    }

    & > .post-info {
        flex: 1;
        display: flex;
        flex-wrap: wrap;

        & > .post-content {
            flex: 1;
        }

        & > :last-child {
            flex-basis: 100%;
        }

        & > .stats {
            padding-right: 1rem;
            display: flex;
            flex-direction: column;
            text-align: center;

            & > .icon {
                color: $brand-color-primary;
            }
        }
    }
}

.user-info-box {
    display: flex;
    @media (min-width: 992px) {
        &.full-width {
            width: 100%;
        }
    }

    .user-info {
        display: flex;

        &__text {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 992px) {
            flex-direction: column;
            align-items: center;
            min-width: 92px;
            ::v-deep {
                .btn {
                    width: auto;
                    border: none;
                    background: none;
                    color: $brand-color-primary;
                    margin: 0;
                    padding: 0 !important;
                }
            }
        }

        &__location {
            @media (min-width: 992px) {
                display: none;
            }
        }
    }

    ::v-deep {
        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            margin: 0 0.625rem 0.625rem 0;
            @media screen and (min-width: 992px) {
                width: 3.5rem;
                height: 3.5rem;
            }
        }

        @media screen and (min-width: 992px) {
            .primary-user-info {
                display: none;
            }
        }
    }

    .post-author {
        display: none;
        @media screen and (min-width: 992px) {
            display: block;
        }
    }

    .post-content {
        margin-left: 0.4rem;
    }
}

.status-symbols {
    & > span {
        display: table-cell;
        text-align: center;
        padding: 0 0.2rem;
    }

    & .icon {
        color: $brand-color-primary;
    }

    & a {
        width: 100%;
        display: inline-block;
        text-align: right;
    }
}

.replies {
    padding: 0.625rem;

    ::v-deep {
        .user-info {
            @media screen and (min-width: 992px) {
                flex-direction: column;
                align-items: center;
            }

            .avatar {
                margin-right: 10px;
            }

            .primary-user-info {
                @media screen and (min-width: 992px) {
                    display: none;
                }
            }
        }

        .user-actions {
            .btn {
                width: auto;
                border: none;
                background: none;
                color: $brand-color-primary;
                margin: 0;
                padding: 0 !important;
            }
        }

        .reply-author {
            @media screen and (min-width: 992px) {
                display: block;
            }
        }
    }
}

.replies,
.writing-wrapper {
    background-color: $normal-grey-background;
}

.writing-wrapper {
    padding: 0.625rem;
}

::v-deep .timestamp {
    color: $normal-grey-text;
    font-size: 0.8rem;
}

.timestamp {
    display: block;
    margin-bottom: 0.2rem;
}
</style>
