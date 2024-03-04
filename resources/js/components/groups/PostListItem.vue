<template>
    <div class="post rounded flex-column">
        <div class="user-info-box">
            <user-info :user="author" class="d-flex d-lg-block flex-grow-1" />
            <span class="d-inline-block d-lg-none status-symbols">
                <span
                    ><fa-icon class="icon" icon="eye" /><br />{{ views }}</span
                >
                <span
                    ><fa-icon class="icon" icon="comment" /><br />{{
                        comments
                    }}</span
                >
            </span>
        </div>
        <div class="d-flex d-lg-inline-block">
            <div class="post-details">
                <span class="timestamp">{{ formattedCreationDate }}</span
                ><br />
                <span class="post-title">{{ title }}</span
                ><br />
            </div>
        </div>
        <div class="actions justify-content-between ms-lg-auto">
            <span class="status-symbols d-none d-lg-inline-block">
                <span
                    ><fa-icon class="icon" icon="eye" /><br />{{ views }}</span
                >
                <span
                    ><fa-icon class="icon" icon="comment" /><br />{{
                        comments
                    }}</span
                >
            </span>
            <div class="text-right">
                <router-link
                    :to="{ name: 'post', params: { id: postId } }"
                    class="fa-2x w-100"
                >
                    <fa-icon icon="chevron-right" />
                </router-link>
            </div>
        </div>
        <slot></slot>
    </div>
</template>

<script>
import UserInfo from "./UserInfo";
import UserActions from "./UserActions";
import formatDate from "../../utils/format-date";
export default {
    name: "PostListItem",
    components: { UserActions, UserInfo },
    props: {
        author: Object,
        title: String,
        created: String,
        views: Number,
        comments: Number,
        postId: Number,
    },
    computed: {
        formattedCreationDate() {
            return formatDate(this.created);
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.post {
    background-color: $brand-color-base;
    padding: 0.625rem;
    margin-bottom: 0.625rem;
    display: flex;
}
.user-info-box {
    width: 15%;
    min-width: 140px;

    @media (max-width: 992px) {
        display: flex;
        width: unset;
    }

    ::v-deep {
        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            margin: 0 0.625rem 0.625rem 0;
        }
    }
}

.post-details {
    flex: 1;

    & .post-title {
        font-weight: 500;
        color: $dark-grey-text;
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

.actions {
    display: flex;
    flex-direction: column;
}
.timestamp {
    color: $normal-grey-text;
    font-size: 0.8rem;
}
</style>
