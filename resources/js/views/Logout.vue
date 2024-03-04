<template>
  <p>You have been logged out!</p>
</template>

<script>
import PartnerHelpers from "../helpers/PartnerHelpers";

export default {
  name: "Logout",
  mounted() {
    PartnerHelpers.clearPartnerListCache();
    this.$store
        .dispatch("currentUser/logout")
        .then(() => this.$router.push({path: "/login"}));
  },
};
</script>

<style scoped></style>
