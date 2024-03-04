<template>
    <blockquote v-if="show" :id="id" :class="className">
        <div class="row m-0">
            <fa-icon class="quote-icon" icon="quote-left"></fa-icon>
            <p>{{ text }}</p>
            <div class="d-flex align-items-center">
                <img v-if="avatar" class="me-3" :src="avatar" />
                <small v-if="author">- {{ author }}</small>
            </div>
        </div>
    </blockquote>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "quote",
    props: ["blok"],
    data() {
        return {
            id: "",
            text: "",
            author: "",
            avatar: "",
            className: "",
        };
    },
    mounted() {
        this.id = this.blok.id;
        this.text = this.blok.text;
        this.author = this.blok.author;
        this.avatar = this.blok.avatar ? this.blok.avatar.filename : "";
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
@import "../../sass/setup/variables";
blockquote {
    margin: 2rem auto;
    @media screen and (min-width: 768px) {
        max-width: 630px;
    }
}
.quote-icon {
    color: $brand-color-primary;
}
img {
    border-radius: 50%;
    max-width: 50px;
    max-height: 50px;
    object-fit: cover;
}
p,
small {
    font-style: italic;
    font-weight: normal;
    font-size: 18px;
    line-height: 160%;
    letter-spacing: 0.05em;
    color: #0c0c0c;
}
p {
    width: 100%;
}
small {
    font-style: normal;
}
</style>
