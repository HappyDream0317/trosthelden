<template>
    <div class="form-group">
        <accordion :id="id" :label="label">
            <div class="p-1">
                <div class="description mx-1" v-if="description">
                    {{ description }}
                </div>
                <div class="form-check">
                    <answer-type-select
                        class="mt-3"
                        v-for="answer in answers"
                        :key="answer.id"
                        :id="answer.id"
                        :label="answer.answer"
                        :placeholder="answer.label"
                        :min="answer.numeric_min"
                        :max="answer.numeric_max"
                        :value="value[answer.id]"
                        @input="(val) => changeAnswer(answer.id, val)"
                        is-scale
                    />
                </div>
            </div>
        </accordion>
    </div>
</template>

<script>
import AnswerTypeSelect from "./AnswerTypeSelect";
import Accordion from "../Accordion";
export default {
    name: "AnswerTypeCollapseSelect",
    emits: ["input"],
    components: { Accordion, AnswerTypeSelect },
    props: {
        id: Number,
        label: String,
        answers: Array,
        questionId: Number,
        value: Object,
        description: {
            type: String,
            default: null,
        },
    },
    beforeMount() {
        if (typeof this.value === "undefined") {
            let value = {};
            this.answers.forEach((answer) => {
                value[answer.id] = null;
            });
            this.$emit("input", value);
        }
    },
    methods: {
        changeAnswer(answerId, val) {
            let value = this.value;
            value[answerId] = val;
            this.$emit("input", value);
        },
    },
};
</script>

<style scoped></style>
