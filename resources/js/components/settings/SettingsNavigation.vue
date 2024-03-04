<template>
    <ul class="container">
        <li>
            <router-link :to="{ name: 'settings' }"
                >Nutzerkonto</router-link
            >
        </li>
        <li>
            <router-link :to="{ name: 'notifications' }" exact
                >Benachrichtigung</router-link
            >
        </li>
    </ul>
</template>

<script>
export default {
    name: "SettingsNavigation",
};
</script>

<style scoped></style>
