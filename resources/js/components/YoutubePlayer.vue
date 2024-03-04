<template>
    <div class="video-embed">
        <iframe
            :id="id"
            :class="className"
            :src="`${src}?rel=0&controls=0`"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
        >
        </iframe>
    </div>
</template>

<script>
export default {
    name: "youtube-player",
    props: ["src", "id", "className"],
};
</script>

<style scoped lang="scss">
.video-embed {
    margin: 1rem auto;
    display: block;
    max-width: 800px;
    iframe {
        width: 100%;
        &.frabo {
            @media screen and (min-width: 768px) {
                min-height: 248px;
            }
        }
        &.frabo-not-complete {
            @media screen and (min-width: 768px) {
                min-height: 348px;
            }
        }
    }
}
</style>
