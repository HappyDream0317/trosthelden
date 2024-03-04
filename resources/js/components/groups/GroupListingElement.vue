<template>
    <router-link
        :to="{ name: 'group', params: { id: group_id } }"
        class="nav-link"
    >
        {{ group_name }}
        <fa-icon class="icon" icon="door-open"></fa-icon>
    </router-link>
</template>

<script>
export default {
    name: "GroupListingElement",
    props: ["group_name", "group_id"],
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";
.nav-link {
    display: flex;
    flex: 0 0 100%;
    position: relative;
    color: $brand-color-primary;
    font-weight: 500;

    border-radius: $border-radius;

    background-color: $brand-color-secondary;

    margin: 0 0.625rem 0.625rem 0;
    padding: 0.625rem;

    height: 103px;

    @media (max-width: 992px) {
        height: 60px;
        align-items: center;

        & > .icon {
            bottom: unset !important;
        }
    }

    @media (min-width: 992px) {
        flex-basis: 23%;
    }

    & > .icon {
        color: $brand-color-primary;
        font-size: 1.2rem;
        position: absolute;
        bottom: 0.625rem;
        right: 0.625rem;
    }
}
</style>
