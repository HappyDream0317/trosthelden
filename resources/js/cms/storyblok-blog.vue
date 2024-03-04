<template>
    <default-layout>
        <div class="container blog p-0">
            <h1>Impulse</h1>
            <p class="subline mb-lg-4">
                Hier findest du Anregungen, Tipps, hilfreiche Ideen und ganz
                praktische Übungen für trauernde Menschen wie dich. Als
                Unterstützung! Um den lähmenden Schmerz ein wenig zu lindern und
                den emotionalen Stress des Traueralltags besser zu bewältigen.

                <br /><br />

                Anregungen und Beiträge für unsere Rubrik Impulse sind herzlich
                willkommen! <br />
                Melde dich gern bei uns unter
                <a href="mailto:daniela@trosthelden.de"
                    >daniela@trosthelden.de</a
                >
            </p>

            <h2 class="categories">Unsere Kategorien</h2>

            <div class="category-wrapper" v-if="categories.length">
                <button
                    class="btn btn-primary category me-1 mb-2"
                    @click="
                        onFetchCategory();
                        activeBtn = 'all';
                    "
                    :class="{ active: activeBtn === 'all' }"
                >
                    Alle Impulse
                </button>
                <button
                    class="btn btn-primary category me-2 mb-2"
                    :key="category.name"
                    v-for="category in categories"
                    @click="
                        onFetchCategory(category.name);
                        activeBtn = category.name;
                    "
                    :class="{ active: activeBtn === category.name }"
                >
                    {{ category.name }}
                </button>
            </div>

            <router-link
                v-if="latestPost"
                class="router-link"
                :to="{ path: '/' + latestPost.full_slug }"
            >
                <div class="latest-post__wrapper">
                    <div v-if="latestPost" class="latest-post">
                        <div class="latest-post__image">
                            <img v-if="imageUrl" :src="imageUrl" />
                        </div>
                        <div class="latest-post__kicker">
                            {{ latestPost.content.kicker }}
                        </div>
                        <div class="latest-post__title">
                            {{ latestPost.content.title }}
                        </div>
                        <div class="latest-post__intro">
                            {{ latestPost.content.intro }}
                        </div>
                    </div>
                </div>
            </router-link>

            <related-articles
                v-if="stories.length > 1 && latestPost"
                :stories="stories"
                :latestPost="latestPost"
                :isIndex="true"
            ></related-articles>
        </div>
    </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import StoryblokClient from "storyblok-js-client";
import { StoryblokImageDelivery } from "./mixins/storyblok-image-delivery";
import { mapGetters } from "vuex";

export default {
    name: "StoryblokBlog",
    components: { DefaultLayout },
    mixins: [StoryblokImageDelivery],
    data() {
        return {
            activeBtn: "all",
            postStatus: "draft",
            latestPost: null,
            elements: [],
            stories: [],
            categories: [],
            categoriesOrder: [
                "Inspirationen",
                "Meditation",
                "Interviews",
                "Helden-Challenge",
                "Zitate",
                "Thema der Woche",
            ],
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
            this.getStory("home", "draft");
        });
        window.storyblok.pingEditor(() => {
            if (window.storyblok.isInEditor()) {
                this.$store.dispatch("storyblok/setVersion", "draft");
            }
            this.getStories();
            this.getTags();
        });
    },
    methods: {
        onFetchCategory(tagName = null) {
            this.storyapi
                .get(`cdn/stories/`, {
                    with_tag: tagName,
                    version: this.version,
                    sort_by: "first_published_at:desc",
                    per_page: 100,
                })
                .then(({ data }) => {
                    this.stories = data.stories;
                    this.latestPost = data.stories[0];
                    this.image = this.latestPost.content.image;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getTags() {
            this.storyapi
                .get("cdn/tags/", {
                    version: this.version,
                })
                .then(({ data }) => {
                    this.categories = this.mapOrder(
                        data.tags,
                        this.categoriesOrder,
                        "name"
                    );
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getStories() {
            this.storyapi
                .get("cdn/stories/?starts_with=impulse/", {
                    version: this.version,
                    sort_by: "first_published_at:desc",
                    per_page: 100,
                })
                .then(({ data }) => {
                    const stories = data.stories;
                    this.latestPost = stories[0];
                    this.image = this.latestPost.content.image;
                    this.stories = stories;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        mapOrder(array, order, key) {
            // https://gist.github.com/ecarter/1423674
            array.sort(function (a, b) {
                var A = a[key],
                    B = b[key];
                if (
                    order.indexOf(A) > order.indexOf(B) ||
                    order.indexOf(A) === -1 ||
                    order.indexOf(B) === -1
                ) {
                    return 1;
                } else {
                    return -1;
                }
            });
            return array;
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../sass/setup/variables";

.btn-primary.category {
    background-color: $white;
    color: $primary;
    &.active {
        box-shadow: none !important;
        background-color: $primary;
        color: $white;
    }
}

h2.categories {
    font-family: $font-family-headings;
    font-style: normal;
    font-weight: normal;
    font-size: 16px;
    line-height: 19px;
    color: $brand-color-primary;
}

.blog {
    overflow: hidden;
    h1 {
        font-weight: bold;
        font-size: 32px;
        line-height: 37px;
        color: $brand-color-primary;
    }

    p.subline {
        font-size: 16px;
        line-height: 19px;
        color: #818181;
    }

    .latest-post__wrapper {
        padding-bottom: 1rem;
        @media screen and (min-width: 768px) {
            padding: 0 2.5rem;
        }
        @media screen and (min-width: 1024px) {
            max-width: 800px;
            margin: 0 auto;
        }
    }

    .latest-post {
        cursor: pointer;
        padding-bottom: 1rem;
        background-color: #fff;
        border-radius: 5px;
        transition: box-shadow 0.3s;
        &:hover {
            box-shadow: 0 0 16px rgba(33, 33, 33, 0.2);
        }
        @media screen and (min-width: 768px) {
            margin-bottom: 3rem;
        }
        &__kicker {
            font-style: normal;
            font-weight: normal;
            font-size: 24px;
            line-height: 100%;
            text-align: center;
            text-transform: uppercase;
            color: $brand-color-highlight;
            margin: 1rem 0 1rem 0;
            @media screen and (min-width: 768px) {
                margin: 1rem 0 2rem 0;
            }
        }

        &__title {
            font-style: normal;
            font-weight: normal;
            line-height: 100%;
            font-size: 1.5rem;
            text-align: center;
            color: $brand-color-primary;
            padding: 0 0.5rem;
            margin-bottom: 1rem;
            @media screen and (min-width: 768px) {
                font-size: 2rem;
                margin-bottom: 2rem;
            }
        }

        &__intro {
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            line-height: 170%;
            text-align: center;
            color: #000000;
            max-width: 630px;
            margin: 0 auto;
            padding: 0 0.5rem;
        }

        &__image {
            margin: 1.5rem 0;
            img {
                width: 100%;
                object-fit: cover;
            }
        }
    }
    .router-link {
        color: inherit;
        text-decoration: none;
    }
}
</style>
