<template>
    <div v-if="show" v-html="html"></div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "htmlElement",
    props: ["blok"],
    data() {
        return {
            html: "",
        };
    },
    mounted() {
        this.html = this.blok.html;
    },
    computed: {
        show() {
            return (
                this.blok.destination === this.destination ||
                this.blok.destination === "global"
            );
        },
        ...mapGetters("storyblok", {
            destination: "destination",
        }),
    },
};
</script>

<style scoped lang="scss">
@import "../../sass/setup/variables";
.headline {
    display: block;
    margin: 0 auto;
    font-style: normal;
    font-weight: normal;
    font-size: 32px;
    line-height: 160%;
    @media screen and (min-width: 768px) {
        max-width: 630px;
    }
}
.colored {
    color: $brand-color-primary;
}
</style>
