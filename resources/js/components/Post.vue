<template>
    <div class="card mb-2">
        <div class="card-header">
            #{{ details.id }}
            <span class="text-muted">von {{ details.author.nickname }}</span>
            <Impression
                type="post"
                :type_id="details.id"
                :counter="details.impressions_count"
            ></Impression>
            <button v-on:click="reportPost">Report</button>
        </div>
        <div class="card-body">
            <p class="card-text">{{ details.message }}</p>
            <hr />
            <div v-for="comment in details.comments" v-bind:key="comment.id">
                <div class="comment-head">
                    <p>
                        um {{ comment.created_at }} von
                        {{ comment.author.nickname }}
                        <Impression
                            type="comment"
                            :type_id="comment.id"
                            :counter="comment.impressions_count"
                        ></Impression>
                    </p>
                    <button v-on:click="reportComment(comment.id)">
                        Report
                    </button>
                </div>
                <div class="comment-text">
                    <p>{{ comment.comment }}</p>
                </div>
                <!--            <WriteComment v-bind:post_id="details.id" v-bind:comment_parent_id="comment.id" @newComment="updateComments"></WriteComment>-->
            </div>
        </div>
        <div class="card-footer text-muted">
            <WriteComment
                v-bind:post_id="details.id"
                @newComment="updateComments"
            ></WriteComment>
        </div>
    </div>
</template>

<script>
import WriteComment from "./WriteComment";
import Impression from "./Impression";
export default {
    name: "Post",
    components: { Impression, WriteComment },
    props: ["initialDetails"],
    data: function () {
        return {
            loading: false,
            details: this.initialDetails,
        };
    },
    mounted: function () {
        let channel = Echo.channel(
            "group_" +
                this.details.group_id +
                "_post_" +
                this.details.id +
                "_comments"
        );
        channel.listen("CommentNew", (data) => {
            this.updateComments(data.comment);
        });
        channel.listen("PostCommentDelete", (data) => {
            this.removeComment(data.id);
        });
    },
    methods: {
        updateComments(comment) {
            this.details.comments.push(comment);
        },
        removeComment(comment_id) {
            this.details.comments.splice(
                this.details.comments.findIndex(function (i) {
                    return i.id === comment_id;
                }),
                1
            );
        },
        reportPost(event) {
            axios
                .post("/api/post/" + this.details.id + "/report")
                .then((response) => {
                    //console.log(response);
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => (this.loading = false));
        },
        reportComment(comment_id) {
            axios
                .post("/api/comment/" + comment_id + "/report")
                .then((response) => {
                    //console.log(response);
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => (this.loading = false));
        },
    },
};
</script>

<style></style>
