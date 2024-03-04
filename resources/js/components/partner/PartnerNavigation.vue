<template>
    <ul class="container">
        <li>
            <router-link :to="{ name: 'partner' }" exact
                >Deine Trauerfreunde</router-link
            >
        </li>
        <li>
            <router-link :to="{ name: 'matchings' }" exact
                >Trauerfreund-Vorschläge</router-link
            >
        </li>
        <li>
            <router-link :to="{ name: 'watchlist' }" exact
                >Merkliste</router-link
            >
        </li>
        <li>
            <router-link :to="{ name: 'hidden' }" exact
                >Ausgeblendete Mitglieder</router-link
            >
        </li>
    </ul>
</template>

<script>
export default {
    name: "PartnerNavigation",
};
</script>

<style scoped>
ul.container {
    padding: 0 15px;
}
</style>
