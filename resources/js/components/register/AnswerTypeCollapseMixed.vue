<template>
    <div class="form-group">
        <accordion :id="id" :label="label">
            <div class="description mx-1" v-if="description">
                {{ description }}
            </div>
            <div
                v-for="answer in answerTypes"
                :key="answer.id"
                class="form-check"
            >
                <div class="form-group">
                    <component
                        :is="answer.component"
                        v-bind="answer.props"
                        :value="model[answer.id]"
                        @input="(val) => changeAnswer(answer.id, val)"
                    ></component>
                </div>
            </div>
        </accordion>
    </div>
</template>

<script>
import Accordion from "../Accordion";
import { TYPE_SELECT, TYPE_TEXT } from "./models/AnswerModel";
import AnswerTypeText from "./AnswerTypeText";
import AnswerTypeSelect from "./AnswerTypeSelect";

export default {
    name: "AnswerTypeCollapseMixed",
    emits: ["input"],
    components: { Accordion },
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
        answerTypes() {
            return this.answers.map((answer) => {
                let props = {
                    id: answer.id,
                    questionId: this.questionId,
                    label: answer.answer,
                    tooltipTitle: answer.tooltip_title,
                    tooltipContent: answer.tooltip_content,
                    placeholder: answer.label,
                };
                switch (true) {
                    case answer.isAnswerType(TYPE_SELECT):
                        props.isScale = true;
                        props.min = answer.numeric_min;
                        props.max = answer.numeric_max;
                        return {
                            id: answer.id,
                            component: AnswerTypeSelect,
                            props,
                        };
                    default:
                    case answer.isAnswerType(TYPE_TEXT):
                        return {
                            id: answer.id,
                            component: AnswerTypeText,
                            props,
                        };
                }
            });
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
                model[answer.id] = null;
            });
            this.$emit("input", model);
            return model;
        },
    },
};
</script>

<style scoped></style>
