<template>
    <div class="form-group">
        <accordion :id="id" :label="label">
            <div class="description mx-1" v-if="description">
                {{ description }}
            </div>
            <div
                v-for="checkAnswer in answers"
                :key="checkAnswer.id"
                class="form-check"
            >
                <div class="form-group">
                    <component
                        :is="getAnswerComponent(checkAnswer)"
                        v-bind="getProps(checkAnswer)"
                        :value="model[checkAnswer.id]"
                        @input="(val) => changeAnswer(checkAnswer.id, val)"
                    ></component>
                </div>
            </div>
        </accordion>
    </div>
</template>

<script>
import Accordion from "../Accordion";
import AnswerTypeCheckbox from "./AnswerTypeCheckbox";
import AnswerTypeCheckboxText from "./AnswerTypeCheckboxText";

export default {
    name: "AnswerTypeCollapseCheckbox",
    emits: ["input"],
    components: { AnswerTypeCheckbox, Accordion },
    props: {
        id: Number,
        questionId: Number,
        label: String,
        answers: Array,
        value: Object,
        description: {
            type: String,
            default: null,
        },
    },
    computed: {
        model() {
            if (typeof this.value === "undefined") {
                return this.initModel();
            }
            return this.value;
        },
    },
    methods: {
        changeAnswer(answerId, val) {
            let value = this.value;
            value[answerId] = val;
            this.$emit("input", value);
        },
        initModel() {
            let model = {};
            this.answers.forEach((answer) => {
                model[answer.id] = false;
            });
            this.$emit("input", model);
            return model;
        },
        getAnswerComponent(answer) {
            if (answer.additional_text) {
                return AnswerTypeCheckboxText;
            }
            return AnswerTypeCheckbox;
        },
        getProps(answer) {
            let prop = {
                id: answer.id,
                questionId: this.questionId,
                label: answer.answer,
                tooltipTitle: answer.tooltip_title,
                tooltipContent: answer.tooltip_content,
            };
            if (answer.additional_text) {
                prop["additionalLabel"] = answer.label;
            }
            return prop;
        },
    },
};
</script>

<style scoped></style>
