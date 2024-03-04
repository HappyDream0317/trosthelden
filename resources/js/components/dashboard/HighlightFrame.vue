<template>
    <div class="reply-highlight">
        <div class="header">
            <user-avatar class="header__avatar" :user="user" />
            <div class="header__user-info">
                <user-identifier
                    v-if="user"
                    class="identifier"
                    :user="user"
                    :with-icon="true"
                    :with-link="true"
                />
                <user-location :user="user" />
            </div>
        </div>
        <div class="reply-content">
            <slot></slot>
        </div>
        <div class="footer">
            <div class="footer__content">
                <slot name="footer"></slot>
            </div>
            <router-link
                :to="{ name: 'post', params: { id: postId } }"
                class="link"
            >
                <fa-icon icon="chevron-right" />
            </router-link>
        </div>
    </div>
</template>

<script>
import UserIdentifier from "../UserIdentifier";
import UserLocation from "../UserLocation";
import UserAvatar from "../UserAvatar";

export default {
    name: "HighlightFrame",
    components: {
        UserIdentifier,
        UserLocation,
        UserAvatar,
    },
    props: {
        user: {
            default: null,
        },
        postId: Number,
    },
};
</script>

<style lang="scss" scoped>
.reply-highlight {
    .thema {
        font-size: 12px;
    }

    .title {
    }
}
</style>
