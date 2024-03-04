<template>
    <default-layout>
        <div class="p-3">
            <h1>Häufige Fragen</h1>
            <div>
                Hier findest du schnelle Antworten auf deine Fragen rund um das
                TrostHelden-Angebot: kompakt und übersichtlich.
            </div>
            <FaqCategoryListingElement
                class="mt-2 rounded-box"
                v-for="(topic, index) in topics"
                :key="index"
                :topic="index"
                :questions="topic"
            ></FaqCategoryListingElement>
        </div>
    </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import FaqCategoryListingElement from "../components/faq/FaqCategoryListingElement";
export default {
    name: "Faq",
    components: { FaqCategoryListingElement, DefaultLayout },
    data: function () {
        return {
            topics: {},
        };
    },

    mounted: function () {
        axios
            .get("/api/faq")
            .then((response) => {
                //console.log(response.data);
                this.topics = response.data;
            })
            .catch((err) => {
                console.log(err);
            });
    },
};
</script>

<style scoped></style>
