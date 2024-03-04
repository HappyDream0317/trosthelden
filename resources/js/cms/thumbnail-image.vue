<template>
    <div v-if="show" class="thumbnail-image" :id="id" :class="className">
        <img :src="image.filename" />

        <div class="thumbnail-image__meta">
            <span class="caption" v-if="caption">{{ caption }}</span>
            <span class="caption" v-else-if="image.title">{{
                image.title
            }}</span>
            <span class="copyright" v-if="copyright"
                >&copy; {{ copyright }}</span
            >
            <span class="copyright" v-else-if="image.copyright"
                >&copy; {{ image.copyright }}</span
            >
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "thumbnail-image",
    props: ["blok"],
    data() {
        return {
            id: "",
            image: "",
            caption: "",
            copyright: "",
            className: "",
        };
    },
    mounted() {
        this.id = this.blok.id;
        this.image = this.blok.src;
        this.caption = this.blok.caption;
        this.copyright = this.blok.copyright;
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

<style scoped lang="scss">
.thumbnail-image {
    margin-bottom: 1rem;
    &__meta {
        margin: 0;
        display: block;
        text-align: left;
        margin-top: 0.25rem;
        .caption {
            color: #818181;
        }
        .copyright {
            color: #b5b5b5;
        }
    }
}
img {
    margin: 0;
    display: block;
    max-width: 250px;
}
caption {
    width: 100%;
    display: block;
    margin: 0;
    font-style: normal;
    font-weight: normal;
    font-size: 16px;
    line-height: 140%;
    text-align: left;
    color: #818181;
}
</style>
