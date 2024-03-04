<template>
    <span class="location" v-if="location">{{ location }}</span>
</template>

<script>
export default {
    name: "UserLocation",
    props: {
        user: Object,
    },
    data() {
        let location = null;
        if (this.user.location) {
            location = [this.user.location];
            if (this.user.protected_postal_code) {
                location.push(this.user.protected_postal_code);
            }
            location = location.join(", ");
        }
        return {
            location,
        };
    },
};
</script>

<style scoped></style>
