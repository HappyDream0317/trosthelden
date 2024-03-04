<template>
    <Accordion :id="id" :label="topic" class="smaller-button">
        <FaqQuestionListingElement
            v-for="question in questions"
            :question="question"
            :key="question.id"
        ></FaqQuestionListingElement>
    </Accordion>
</template>

<script>
import Accordion from "../Accordion";
import FaqQuestionListingElement from "./FaqQuestionListingElement";
export default {
    name: "FaqCategoryListingElement",
    props: {
        questions: Array,
        topic: String,
    },
    components: { FaqQuestionListingElement, Accordion },
    computed: {
        id() {
            var hash = 0,
                i,
                chr;

            for (i = 0; i < this.topic.length; i++) {
                chr = this.topic.charCodeAt(i);
                hash = (hash << 5) - hash + chr;
            }
            return hash;
        },
    },
};
</script>

<style scoped>
.smaller-button ::v-deep button.accordion_row__trigger {
    min-height: unset;
    background-color: #ffffff;
}
</style>
