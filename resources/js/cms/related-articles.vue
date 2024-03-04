<template>
    <div>
        <div v-if="stories.length" class="related-articles">
            <hr />
            <h2 v-if="!isIndex">Weitere Impulse</h2>
            <ul>
                <router-link
                    :key="article.uuid"
                    v-for="article in stories"
                    v-if="article.content._uid !== latestPost.content._uid"
                    :to="`/${article.full_slug}`"
                >
                    <li class="related-articles__item">
                        <img
                            class="related-articles__image"
                            v-if="
                                article.content.image &&
                                article.content.image.filename
                            "
                            :src="getImageUrl(article.content.image)"
                        />
                        <div class="related-articles__kicker">
                            {{ article.content.kicker }}
                        </div>
                        <div class="related-articles__title">
                            {{ article.content.title }}
                        </div>
                    </li>
                </router-link>
            </ul>
        </div>
        <div v-else style="text-align: center; padding-top: 3rem">
            No Related Articles Yet
        </div>
    </div>
</template>

<script>
export default {
    name: "related-articles",
    props: ["stories", "latestPost", "isIndex"],
    methods: {
        getImageUrl(image) {
            if (!image) return "";
            const filePath = image.filename.replace(
                "https://a.storyblok.com/",
                ""
            );
            return image.focus
                ? `https://img2.storyblok.com/500x375/filters:focal(${image.focus})/${filePath}`
                : `https://img2.storyblok.com/500x375/${filePath}`;
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../sass/setup/variables";
.related-articles {
    @media screen and (min-width: 768px) {
        padding: 0 6%;
    }
    @media screen and (min-width: 1024px) {
        max-width: 800px;
        margin: 0 auto;
    }
    hr {
        border-top: 2px solid $brand-color-primary;
        margin-bottom: 2.5rem;
    }
    h2 {
        text-align: center;
        font-style: normal;
        font-size: 1.6rem;
        margin-bottom: 1rem;
        color: $brand-color-primary;
        @media screen and (min-width: 768px) {
            font-size: 44px;
            line-height: 52px;
        }
    }
    ul {
        padding: 0;
    }
    &__item {
        display: inline-block;
        padding-bottom: 1rem;
        background-color: $brand-color-base;
        border-radius: 5px;
        transition: box-shadow 0.3s;
        margin-bottom: 1.5rem;
        &:hover {
            box-shadow: 0 0 16px rgba(33, 33, 33, 0.2);
        }
        @media screen and (min-width: 768px) {
            width: calc(50% - 2rem);
            margin: 1rem;
        }
    }
    &__image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    &__kicker {
        font-size: 16px;
        line-height: 120%;
        text-align: center;
        text-transform: uppercase;
        color: $brand-color-highlight;
        margin: 1rem 0 0.33rem;
    }
    &__title {
        font-style: normal;
        font-weight: normal;
        font-size: 22px;
        line-height: 120%;
        text-align: center;
        color: $brand-color-primary;
        padding: 0 1rem;
        height: 52px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
}
</style>
