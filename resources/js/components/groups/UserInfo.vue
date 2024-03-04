<template>
    <div class="user-info">
        <user-avatar :user="user"></user-avatar>
        <user-identifier
            :user="user"
            class="primary-user-info"
        ></user-identifier
        ><br />
        <span>{{ location }}</span>
        <slot v-if="!isYou" :user="user"></slot>
    </div>
</template>

<script>
import UserIdentifier from "../UserIdentifier";
import AvatarPlaceholder from "../AvatarPlaceholder";
import UserAvatar from "../UserAvatar";
import { mapGetters } from "vuex";
export default {
    name: "UserInfo",
    components: { UserAvatar, AvatarPlaceholder, UserIdentifier },
    props: {
        user: Object,
        location: String,
    },
    computed: {
        isYou() {
            if (!this.user) {
                return null;
            }

            return this.currentUserId === this.user.id;
        },
        ...mapGetters("currentUser", {
            currentUserId: "getId",
        }),
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.user-info {
    padding-right: 0.625rem;
}
.primary-user-info {
    color: $brand-color-primary;
    font-weight: 500;
    font-size: 15px;
}
</style>
