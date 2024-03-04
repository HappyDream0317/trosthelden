<template>
    <default-layout>
        <div class="group-wrapper rounded-box">
            <div class="group-details">
                <span class="category">{{ category }}</span
                ><br />
                <h2>{{ groupName }}</h2>
                <p>{{ description }}</p>
            </div>
            <div class="post-list">
                <post-list-item
                    class="flex-column flex-lg-row"
                    v-for="post in posts.data"
                    :key="post.id"
                    :author="post.author"
                    :comments="post.comments_count"
                    :created="post.created_at"
                    :post-id="post.id"
                    :title="post.title"
                    :views="post.impressions_count"
                >
                </post-list-item>
                <paginator
                    v-if="posts.lastPage > 1"
                    :total-pages="posts.lastPage"
                    :total="posts.totalPosts"
                    :per-page="posts.perPage"
                    :current-page="posts.currentPage"
                    @page="loadPosts"
                />
            </div>
        </div>
        <div class="writing-wrapper rounded-box mt-4">
            <new-post
                class="flex-column flex-lg-row"
                :target-id="groupId"
                target-type="post"
                @newPost="newPost"
            />
        </div>
    </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import NewPost from "../components/groups/NewPost";
import PostListItem from "../components/groups/PostListItem";
import Modal from "../components/Modal";
import Paginator from "../components/Paginator";

export default {
    name: "GroupDetails",
    components: { Modal, PostListItem, NewPost, DefaultLayout, Paginator },
    data() {
        return {
            category: "Test-Kategorie",
            groupId: null,
            groupName: "Gruppenname",
            description: "Bescheibung",
            loading: false,
            posts: {
                data: [],
                lastPage: 0,
                totalPosts: 0,
                perPage: 0,
                currentPage: 0,
            },
        };
    },
    mounted() {
        this.loadGroupInfo();
        this.loadPosts();
    },
    methods: {
        loadGroupInfo() {
            axios
                .get(`/api/group/${this.$route.params.id}`)
                .then((response) => {
                    let group = response.data.data;
                    this.category = group.category.name;
                    this.groupId = group.id;
                    this.groupName = group.name;
                    this.description = group.description;
                })
                .catch((err) => {
                    console.log(err);
                    this.errored = true;
                });
        },

        loadPosts(page) {
            if (typeof page === "undefined" || page === null) {
                page = 1;
            }

            this.loading = true;
            return axios
                .get(`/api/post/${this.$route.params.id}?page=${page}`)
                .then((response) => {
                    this.posts.data = response.data.data;
                    this.posts.lastPage = parseInt(
                        response.data.meta.last_page
                    );
                    this.posts.totalPosts = parseInt(response.data.meta.total);
                    this.posts.perPage = parseInt(response.data.meta.per_page);
                    this.posts.currentPage = parseInt(
                        response.data.meta.current_page
                    );
                    this.loading = false;
                })
                .catch((err) => {
                    console.log(err);
                    this.loading = false;
                    this.errored = true;
                });
        },

        newPost() {
            this.loadPosts(this.posts.currentPage);
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.group-wrapper {
    background-color: $normal-grey-background;
}

.post-list,
.loading-wrapper,
.writing-wrapper,
.group-details {
    padding: 0.625rem;
}
</style>
