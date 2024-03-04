<template>
    <div
        class="membership-badge"
        v-bind:class="{
            'membership-badge--premium': isPremium,
            'membership-badge--basic': !isPremium,
        }"
    >
        {{ membershipName }}
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "MembershipBadge",
    data() {
        return {
            membershipName: "",
        };
    },
    computed: {
        ...mapGetters("currentUser", {
            isPremium: "isPremium",
        }),
    },
    mounted() {
        // Premium users should be able to see the others membership
        if (this.isPremium) {
            this.membershipName = this.isPremium ? "Premium" : "Basis";
        }
    },
};
</script>

<style scoped lang="scss">
@import "../../../sass/setup/variables";

.membership-badge {
    padding: 0 4px;
    color: #fff;
    &--premium {
        background-color: $brand-color-primary;
    }
    &--basic {
        background-color: #b5b5b5;
    }
}
</style>
