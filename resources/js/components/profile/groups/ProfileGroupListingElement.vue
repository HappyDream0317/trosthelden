<template>
    <div>
        <router-link :to="{ name: '' }" class="nav-link">{{
            group_name
        }}</router-link
        ><br />
    </div>
</template>

<script>
export default {
    name: "ForeignProfileGroupListingElement",
    props: ["group_id", "group_name"],
};
</script>

<style scoped></style>
