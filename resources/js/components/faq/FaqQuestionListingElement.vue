<template>
    <Accordion
        class="wow"
        :id="question.id"
        :key="question.id"
        :label="question.question"
    >
        <p v-html="question.answer"></p>
    </Accordion>
</template>

<script>
import Accordion from "../Accordion";
export default {
    name: "FaqQuestionListingElement",
    components: { Accordion },
    props: {
        question: Object,
    },
};
</script>

<style lang="scss" scoped></style>
