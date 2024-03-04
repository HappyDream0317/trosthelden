<template>
    <div v-if="show" class="video-embed">
        <video-embed :src="src" :id="id" :class="className"></video-embed>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "video-player",
    props: ["blok"],
    data() {
        return {
            id: "",
            src: "",
            className: "",
        };
    },
    mounted() {
        this.id = this.blok.id;
        this.src = this.blok.src;
        this.className = this.blok.className;
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

<style scoped>
.video-embed {
    margin: 1rem auto;
    display: block;
    max-width: 800px;
}
</style>
