<template>
    <span v-on:click="updateImpression"
        ><img
            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/120/apple/198/thumbs-up-sign_1f44d.png"
        />
        {{ counter }}</span
    >
</template>

<script>
export default {
    name: "Impression",
    props: ["type", "type_id", "counter"],
    methods: {
        updateImpression() {
            axios
                .get("/api/" + this.type + "/" + this.type_id + "/impression")
                .then((response) => {
                    this.counter = response.data.impressions;
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => (this.loading = false));
        },
    },
};
</script>

<style scoped>
img {
    cursor: pointer;
    width: 20px;
    transition: all 0.2s ease-in-out;
}
img:hover {
    transform: scale(1.4);
}
</style>
