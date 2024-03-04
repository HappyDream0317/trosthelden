<template>
    <div class="rounded-box text-center py-3 px-2 mb-2 bg--blue-250 only--desktop">
        <img class="w-100 px-4" src="/img/memberships-banner-cover.svg" alt="cover"/>
        <p class="text-white mb-4">Möchtest du deinen Trauerfreund kennenlernen?</p>
        <router-link :to="{ name: 'premium' }" exact class="btn btn-primary w-100 a text-uppercase">
            Jetzt Mitglied werden&nbsp;&nbsp;<i class="icon-arrow-next-light "></i>
        </router-link>
    </div>
</template>

<script>
export default {
    name: "MembershipsBanner"
}
</script>

<style lang="scss" scoped>

.only--desktop {
    display: none;
    @media (min-width: 767px) {
        display: block;
    }
}

</style>