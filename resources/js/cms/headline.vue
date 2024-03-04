<template>
    <component v-if="show" :is="type" :id="id" :class="className">
        {{ text }}
    </component>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "headline",
    props: ["blok"],
    data() {
        return {
            id: "",
            type: "",
            text: "",
            className: "",
            colored: false,
        };
    },
    mounted() {
        this.id = this.blok.id;
        this.type = this.blok.type;
        this.text = this.blok.text;
        this.className = "headline " + this.blok.className;
        this.className = this.blok.colored
            ? this.className + " colored"
            : this.className;
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
