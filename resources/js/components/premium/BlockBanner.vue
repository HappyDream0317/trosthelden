<template>
<div class="overlay">
    <div class="banner">
        <p class="banner__text text-white">Du benötigst eine TrostHelden-Mitgliedschaft, um Nachrichten zu versenden.</p>
        <router-link :to="{ name: 'premium' }" exact class="btn btn-primary a text-uppercase banner__button">
            Mitgliedschaft abschlieSSen&nbsp;&nbsp;<i class="icon-arrow-next-light "></i>
        </router-link>
    </div>
</div>
</template>

<script>
export default {
    name: "BlockBanner"
}
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.overlay {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(180deg, rgba(#F0F0F0, 0.31) 0%, #ECECEC 100%);
}
.banner {
    position: relative;
    background: $brand-color-primary;
    max-width: 727px;
    width: 100%;
    margin: 71px auto auto;
    padding: 24px 16px;
    text-align: center;
    border-radius: 12px;
    &__text {
        font-weight: 300;
        font-size: 14px;
        margin-bottom: 12px;
    }
    &__button {
        font-size: 13px;
        padding: 12px 24px;
    }
}

</style>