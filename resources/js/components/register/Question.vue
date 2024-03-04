<template>
    <div>
        <slot name="before"></slot>

        <div class="mb-2 ms-1 question-text">
            <slot></slot>
        </div>

        <div
            class="form-check"
            v-if="question.answer_type.name !== 'select'"
            v-for="answer in answers"
            v-bind:key="answer.id"
        >
            <answer
                :ref="answer.id"
                :answer="answer"
                :question="question"
                :value="model"
                @input="changeModel"
                @radio-activated="handleRadio"
                @normal-activated="handleNormal"
            />
        </div>
        <answer-type-select
            :id="question.id"
            :answers="question.answers"
            :placeholder="question.label"
            v-if="question.answer_type.name === 'select'"
            :value="model"
            @input="changeModel"
        ></answer-type-select>

        <slot name="after"></slot>
    </div>
</template>

<script>
import AnswerModel from "./models/AnswerModel";
import Answer from "./Answer";
import AnswerTypeSelect from "./AnswerTypeSelect";

export default {
    name: "Question",
    emits: ["input"],
    components: { Answer, AnswerTypeSelect },
    props: {
        question: {
          type: Object,
          default: {}
        },
        value: {
          type: Object,
          default: {}
        },
    },
    data() {
        let answers = {};
        let childAnswers = {};
        if (this.question.answers && Array.isArray(this.question.answers)) {
            this.question.answers.forEach((answer) => {
                this.buildAnswerModels(answer, answers, childAnswers);
            });
        } else if (
            this.question.answers &&
            typeof this.question.answers === "object"
        ) {
            for (let answerIndex in this.question.answers) {
                this.buildAnswerModels(
                    this.question.answers[answerIndex],
                    answers,
                    childAnswers
                );
            }
        }
        return {
            answers,
            childAnswers,
        };
    },
    computed: {
        model: {
            get() {
                if (typeof this.value === "undefined") {
                    let val = {};
                    this.$emit("input", val);
                    return val;
                }
                return this.value;
            },
            set(val) {
                this.$emit("input", val);
            },
        },
    },
    methods: {
        changeModel(value) {
          this.model = {...this.model, ...value};
        },
        buildAnswerModels(answer, answerList, childList) {
            answerList[answer.id] = new AnswerModel(this.question, answer);
            if (answer.parent) {
                answer.parent.forEach((subAnswer) => {
                    childList[subAnswer.id] = new AnswerModel(
                        this.question,
                        subAnswer,
                        answer
                    );
                });
            }
        },
        getAnswer(id) {
            return this.answers[id] || this.childAnswers[id];
        },
        handleRadio(answerId) {
            // A radio has been activated, so disable all other options belonging to this question
            let model = this.value;
            for (let currentAnswerId in model) {
                if (currentAnswerId == answerId) {
                    continue;
                }
                if (typeof model[currentAnswerId] === "boolean") {
                    model[currentAnswerId] = false;
                    continue;
                }
                model[currentAnswerId] = null;
            }
            this.$emit("input", model);
        },
        handleNormal() {
            // A normal input event occurred, so look for radio options and disable them
            let model = this.value || {};
            for (let currentAnswerId in model) {
                let answer = this.getAnswer(currentAnswerId);
                if (typeof answer !== "undefined" && answer.isRadio()) {
                    let val = answer.additional_text ? null : false;
                    model[currentAnswerId] = val;
                }
            }
            this.$emit("input", model);
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.question-text {
    color: $brand-color-primary;
    font-size: 1.1rem;
}
</style>
