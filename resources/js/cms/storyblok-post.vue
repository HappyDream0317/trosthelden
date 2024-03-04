<template>
    <default-layout>
        <div class="container post">
            <h2 v-if="content.kicker" class="post__kicker">
                {{ content.kicker }}
            </h2>
            <h1>{{ content.title }}</h1>
            <p v-if="content.abstract" class="post__abstract">
                {{ content.abstract }}
            </p>
            <div class="post__image">
                <img v-if="imageUrl" :src="imageUrl" />
                <div v-if="image" class="post__image__meta">
                    <span class="caption" v-if="content.image_caption">{{
                        content.image_caption
                    }}</span>
                    <span class="caption" v-else-if="image.title">{{
                        image.title
                    }}</span>
                    <span class="copyright" v-if="content.image_copyright"
                        >&copy; {{ content.image_copyright }}</span
                    >
                    <span class="copyright" v-else-if="image.copyright"
                        >&copy; {{ image.copyright }}</span
                    >
                </div>
            </div>
            <component
                v-for="el in elements"
                :key="el._uid"
                :blok="el"
                :is="el.component"
            ></component>
        </div>

        <related-articles
            v-if="stories.length > 1 && latestPost"
            :stories="stories"
            :latestPost="latestPost"
            :isIndex="false"
        ></related-articles>
    </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import StoryblokClient from "storyblok-js-client";
import { StoryblokImageDelivery } from "./mixins/storyblok-image-delivery";
import { mapGetters } from "vuex";

export default {
    name: "StoryblokPage",
    components: { DefaultLayout },
    mixins: [StoryblokImageDelivery],
    data() {
        return {
            url: "",
            slug: "",
            content: {
                body: [],
            },
            elements: [],
            stories: [],
            image: null,
            storyapi: null,
        };
    },
    computed: {
        ...mapGetters("storyblok", {
            version: "version",
            token: "token",
        }),
    },
    created() {
        this.storyapi = new StoryblokClient({
            accessToken: this.token,
        });
        window.storyblok.init({
            accessToken: this.token,
        });
        window.storyblok.on("change", () => {
            this.getStory(this.$route.params.pathMatch, "draft");
        });
        window.storyblok.pingEditor(() => {
            const url = this.$route.params.pathMatch;
            if (window.storyblok.isInEditor()) {
                this.$store.dispatch("storyblok/setIsInEditor", true);
                this.version = "draft";
            }
            this.init(url);
        });
    },
    watch: {
        $route(to) {
            const url = to.params.pathMatch;
            if (window.storyblok.isInEditor()) {
                this.$store.dispatch("storyblok/setVersion", "draft");
            }
            this.init(url);
        },
    },
    methods: {
        init(url) {
            this.getStory(url);
            this.getStories();
        },
        getStory(slug) {
            this.storyapi
                .get("cdn/stories/impulse/" + slug, {
                    version: this.version,
                })
                .then(({ data }) => {
                    this.image = data.story.content.image;
                    this.content = data.story.content;
                    this.latestPost = data.story; // the current post
                    this.elements = data.story.content.elements;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getStories() {
            this.storyapi
                .get("cdn/stories/?starts_with=impulse/", {
                    sort_by: "first_published_at:desc",
                    version: this.version,
                    per_page: 100,
                })
                .then(({ data }) => {
                    this.stories = data.stories;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../sass/setup/variables";

.post {
    background: $brand-color-base;
    border-radius: 10px;
    overflow: hidden;
    h1 {
        font-style: normal;
        font-weight: normal;
        line-height: 100%;
        text-align: center;
        color: $brand-color-primary;
        font-size: 1.5rem;
        margin: 1rem 0 1.5rem 0;
        @media screen and (min-width: 768px) {
            /*max-width: 66.66%;*/
            /*margin: 0 auto;*/
        }
        @media screen and (min-width: 1024px) {
            font-size: 3rem;
            margin-top: 1.4rem;
        }
    }

    &__kicker {
        font-style: normal;
        font-weight: normal;
        font-size: 1rem;
        line-height: 100%;
        text-align: center;
        text-transform: uppercase;
        color: $brand-color-highlight;
        padding-top: 1.4rem;
        @media screen and (min-width: 768px) {
            max-width: 800px;
            margin: 0 auto;
        }
        @media screen and (min-width: 1024px) {
            font-size: 24px;
        }
    }

    &__abstract {
        /*max-width: 60%;*/
        display: block;
        margin: 0 auto;
        text-align: center;
        color: #000;
        font-style: normal;
        font-weight: normal;
        font-size: 18px;
        line-height: 170%;
        margin-top: 1rem;
        @media screen and (min-width: 768px) {
            max-width: 800px;
        }
    }

    &__image {
        margin: 1.5rem 0;
        &__meta {
            margin: 0 auto;
            display: block;
            max-width: 800px;
            text-align: center;
            margin-top: 0.25rem;
            .caption {
                color: #818181;
            }
            .copyright {
                color: #b5b5b5;
            }
        }
        img {
            width: 100%;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            max-width: 800px;
        }
    }
}
::v-deep {
    .related-articles {
        padding-top: 2rem !important;
    }
    hr {
        display: none;
    }
}
</style>
