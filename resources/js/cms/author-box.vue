<template>
    <div v-if="show" :id="id" class="author-box" :class="className">
        <div class="row m-0">
            <div
                v-if="imageUrl"
                class="col-sm-3 text-center mb-3 d-flex justify-content-center align-items-center"
            >
                <img class="img-circle" :src="imageUrl" />
            </div>
            <div class="p-0" :class="imageUrl ? 'col-sm-9' : ''">
                <p>{{ text }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import { StoryblokImageDelivery } from "./mixins/storyblok-image-delivery";
import { mapGetters } from "vuex";

export default {
    name: "author-box",
    props: ["blok"],
    mixins: [StoryblokImageDelivery],
    data() {
        return {
            id: "",
            text: "",
            className: "",
            image: null,
        };
    },
    mounted() {
        this.id = this.blok.id;
        this.text = this.blok.text;
        this.className = this.blok.className;
        this.image = this.blok.avatar;
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
.author-box {
    margin: 2rem auto;
    @media screen and (min-width: 768px) {
        max-width: 630px;
    }
}
img {
    border-radius: 50%;
    width: 80px;
    height: 80px;
    object-fit: cover;
}
p {
    font-weight: normal;
    font-size: 18px;
    line-height: 160%;
    letter-spacing: 0.05em;
    color: #0c0c0c;
}
</style>
