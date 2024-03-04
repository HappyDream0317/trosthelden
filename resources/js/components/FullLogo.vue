<template>
    <div class="logo">
        <img src="/img/trosthelden-logo-white.png" alt="Logo" />
    </div>
</template>

<script>
export default {
    name: "FullLogo",
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";
.logo {
    line-height: 1.75rem;
    padding-top: 1rem;
    & > img {
        display: inline-block;
        width: 100%;
        height: auto;
        max-width: 300px;
        margin: -1.5rem 0.625rem -0.625rem 0;
    }

    & > .title {
        font-size: 1.75rem;
        vertical-align: bottom;
        color: $brand-color-primary;
    }
}
</style>
