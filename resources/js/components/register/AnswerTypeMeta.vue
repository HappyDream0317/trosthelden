<template>
    <div class="form-group">
        <p>
            {{ label }}
        </p>
        <div v-for="answer in answerScales" :key="answer.id">
            <answer-type-select
                :id="answer.id"
                :answers="answer.scale"
                :label="answer.label"
                :value="scaleValues[answer.id]"
                @input="(val) => changeAnswer(val, answer.id)"
            ></answer-type-select>
        </div>
    </div>
</template>

<script>
import AnswerTypeSelect from "./AnswerTypeSelect";
export default {
    name: "AnswerTypeMeta",
    emits: ["input"],
    components: { AnswerTypeSelect },
    props: {
        label: String,
        answers: Array,
        questionId: Number,
        value: Object,
    },
    data() {
        let answerScales = [];
        this.answers.forEach((answer) => {
            answerScales.push({
                id: answer.id,
                label: answer.answer,
                scale: this.getRange(
                    answer.numeric_min,
                    answer.numeric_max
                ).map(function (val) {
                    return {
                        answer: val,
                        id: val,
                    };
                }),
            });
        });
        return {
            answerScales,
        };
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
    computed: {
        scaleValues() {
            let mockObj = {};
            this.answers.forEach((answer) => {
                let val = null;
                if (
                    typeof this.value !== "undefined" &&
                    this.value.hasOwnProperty(answer.id)
                ) {
                    val = this.value[answer.id];
                }
                let mockAnswers = {};
                this.getRange(answer.numeric_min, answer.numeric_max).forEach(
                    (n) => {
                        mockAnswers[n] = val == n;
                    }
                );
                mockObj[answer.id] = mockAnswers;
            });
            return mockObj;
        },
    },
    methods: {
        getRange(start, stop) {
            return new Array(stop - start).fill(start).map((n, i) => n + i);
        },
        changeAnswer(val, answerId) {
            let value = this.value;
            let scaleVal = null;
            for (let n in val) {
                if (val[n] === true) {
                    scaleVal = n;
                    break;
                }
            }
            value[answerId] = scaleVal;

            this.$emit("input", value);
        },
    },
};
</script>

<style scoped></style>
