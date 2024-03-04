<template>
    <div
        v-if="show"
        class="richtext"
        :id="id"
        :class="className"
        v-html="richtext"
    ></div>
</template>

<script>
import Storyblok from "storyblok-js-client";
import { mapGetters } from "vuex";
const Api = new Storyblok({ region: "de" });

export default {
    name: "richtext",
    props: ["blok"],
    data() {
        return {
            id: "",
            content: "",
            className: "",
        };
    },
    mounted() {
        this.id = this.blok.id;
        this.content = this.blok.text.content;
        this.className = this.blok.className;
    },
    computed: {
        show() {
            return (
                this.blok.destination === this.destination ||
                this.blok.destination === "global"
            );
        },
        richtext() {
            return this.blok.text
                ? Api.richTextResolver.render(this.blok.text)
                : "";
        },
        ...mapGetters("storyblok", {
            destination: "destination",
        }),
    },
};
</script>

<style lang="scss">
.richtext {
    font-style: normal;
    font-weight: normal;
    font-size: 18px;
    line-height: 170%;
    color: #000000;
    /*max-width: 66.66%;*/
    margin: 0 auto;
    img {
        width: 100%;
        object-fit: contain;
    }
    @media screen and (min-width: 768px) {
        max-width: 630px;
    }
    i {
        color: #000;
    }
}
</style>
