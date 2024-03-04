<template>
    <div class="banner">
        <p class="banner__text text-white">Möchtest du deinen Trauerfreund kennenlernen?</p>
        <router-link :to="{ name: 'premium' }" exact class="btn btn-primary a text-uppercase banner__button">
            Jetzt Mitglied werden&nbsp;&nbsp;<i class="icon-arrow-next-light "></i>
        </router-link>
    </div>
</template>

<script>
export default {
    name: "BlockBanner"
}
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.banner {
    width: 100%;
    padding: 24px 15px;
    text-align: center;
    margin-bottom: 43px;
    &__text {
        font-weight: 300;
        font-size: 14px;
        margin-bottom: 16px;
    }
    &__button {
        font-size: 13px;
        padding: 12px 24px;
    }
}

</style>