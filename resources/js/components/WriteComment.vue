<template>
    <input v-model="comment" v-on:keyup.enter="sendComment" />
</template>

<script>
export default {
    name: "WriteComment",
    props: ["post_id", "comment_parent_id"],
    data: () => ({
        comment: null,
        loading: false,
    }),
    mounted() {},
    methods: {
        sendComment() {
            this.loading = true;
            axios
                .post("/api/post/" + this.post_id + "/comments", {
                    comment_parent: this.comment_parent_id,
                    comment: this.comment,
                })
                .then((response) => {
                    this.$emit("newComment", response.data);
                })
                .catch((err) => {
                    console.log(err);
                    this.errored = true;
                })
                .finally(() => {
                    this.comment = "";
                    this.loading = false;
                });
        },
    },
};
</script>

<style scoped></style>
