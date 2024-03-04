<template>
    <div v-if="show && fileName" class="player-wrapper">
        <audio
            id="audio-player"
            controls
            :src="fileName"
            type="audio/mp3"
            controlsList="nodownload"
        ></audio>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "audio-player",
    props: {
        blok: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            fileName: "",
        };
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
    mounted() {
        this.fileName = this.blok.file.filename;
    },
};
</script>

<style scoped lang="scss">
.player-wrapper {
    margin: 2rem 0;
}
#audio-player {
    display: block;
    margin: 0 auto;
    outline: none;
}
</style>
