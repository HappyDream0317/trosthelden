<template>
    <div class="form-group">
        <accordion :id="id" :label="label">
            <div class="description mx-1" v-if="description">
                {{ description }}
            </div>
            <div
                v-for="answer_sub in answers"
                :key="answer_sub.id"
                class="form-check"
            >
                <div class="form-group">
                    <component
                        :is="getComponent(answer_sub)"
                        v-bind="getProps(answer_sub)"
                        :value="model[answer_sub.id]"
                        @input="(val) => changeAnswer(answer_sub.id, val)"
                        @radio-activated="
                            (val) => $emit('radio-activated', val)
                        "
                    ></component>
                </div>
            </div>
        </accordion>
    </div>
</template>

<script>
import AnswerTypeRadio from "./AnswerTypeRadio";
import Accordion from "../Accordion";
import AnswerTypeRadioText from "./AnswerTypeRadioText";
export default {
    name: "AnswerTypeCollapseRadio",
    emits: ["input"],
    components: { Accordion, AnswerTypeRadio },
    props: {
        id: Number,
        questionId: Number,
        answers: Array,
        label: String,
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
        getComponent(answer) {
            if (answer.additional_text) {
                return AnswerTypeRadioText;
            } else {
                return AnswerTypeRadio;
            }
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
        changeAnswer(answerId, val) {
            let value = this.value;
            value[answerId] = val;
            if (val === "" || val) {
                this.resetValues(value, answerId);
            }
            this.$emit("input", value);
        },
        initModel() {
            let model = {};
            this.resetValues(model);
            this.$emit("input", model);
            return model;
        },
        resetValues(model, skipAnswer = null) {
            this.answers.forEach((answer) => {
                if (answer.id === skipAnswer) {
                    return;
                }
                if (answer.additional_text) {
                    model[answer.id] = null;
                    return;
                }
                model[answer.id] = false;
            });
        },
    },
};
</script>

<style scoped></style>
