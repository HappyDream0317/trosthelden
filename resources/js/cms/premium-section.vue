<template>
    <div v-if="isInEditor" class="premium-section row text-center p-4">
        <p>
            Hier wird die Premium Komponente auf der Marketing Seite angezeigt.
        </p>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "premium-section",
    props: ["blok"],
    computed: {
        ...mapGetters("storyblok", {
            isInEditor: "isInEditor",
        }),
    },
};
</script>

<style scoped lang="scss">
.premium-section {
    background-color: #efefef;
    color: #000;
    margin: 2rem auto;
    p {
        width: 100%;
        margin: 0;
    }
}
</style>
