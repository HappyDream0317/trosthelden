<template>
    <div>
        <div class="mb-2">
            <p class="color--primary font-weight-not-bold h6">Profilfragen</p>
        </div>
        <ForeignProfileQuestion
            class="my-4"
            v-for="question in questions"
            :question="question.question"
            :answer="question.answer"
            :key="question.id"
        ></ForeignProfileQuestion>
    </div>
</template>

<script>
import ForeignProfileQuestion from "./ForeignProfileQuestion";
export default {
    name: "ForeignProfileQuestions",
    components: { ForeignProfileQuestion },
    props: ["questions"],
    mounted: function () {
        //console.log(this.questions);
    },
};
</script>

<style scoped></style>
