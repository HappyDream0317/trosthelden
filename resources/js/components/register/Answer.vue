<template>
    <component
        :is="answerTypeComponent.component"
        v-bind="answerTypeComponent.props"
        :value="readModel"
        @input="changeAnswer"
        @radio-activated="(val) => $emit('radio-activated', val)"
    ></component>
</template>

<script>
import AnswerTypeText from "./AnswerTypeText";
import AnswerTypeScale from "./AnswerTypeScale";
import AnswerTypeMeta from "./AnswerTypeMeta";
import AnswerTypeCollapseCheckbox from "./AnswerTypeCollapseCheckbox";
import AnswerTypeCollapseSelect from "./AnswerTypeCollapseSelect";
import AnswerTypeRadio from "./AnswerTypeRadio";
import AnswerTypeCheckbox from "./AnswerTypeCheckbox";
import AnswerTypeDate from "./AnswerTypeDate";
import AnswerModel, {
    TYPE_RADIO,
    TYPE_CHECKBOX,
    TYPE_SCALE,
    TYPE_TEXT,
    TYPE_META,
    TYPE_COLLAPSE_RADIO,
    TYPE_COLLAPSE_SELECT,
    TYPE_COLLAPSE_CHECKBOX,
    TYPE_DATE,
    TYPE_COLLAPSE_MIXED,
} from "./models/AnswerModel";
import AnswerTypeRadioText from "./AnswerTypeRadioText";
import AnswerTypeCheckboxText from "./AnswerTypeCheckboxText";
import AnswerTypeCollapseRadio from "./AnswerTypeCollapseRadio";
import AnswerTypeCollapseMixed from "./AnswerTypeCollapseMixed";

export default {
    name: "Answer",
    emits: ["input"],
    props: {
        question: {
          type: Object,
          default: {}
        },
        answer: AnswerModel,
        value: {
          type: Object,
          default: {}
        },
    },
    methods: {
        initModel() {
            let val = {};
            if (this.answer.isScalar()) {
                val[this.answer.id] = null;
            }
            this.$emit("input", val);
            return val;
        },
        changeAnswer(val) {
            let model = this.value;
            if (this.answer.isScalar()) {
                model[this.answer.id] = val;
            } else {
                model = val;
            }
            this.$emit("input", model);
            if (!this.answer.isRadio()) {
                this.$emit("normal-activated");
            }
        },
    },
    computed: {
        readModel() {
            let val = {};
            if (typeof this.value === "undefined") {
                val = this.initModel();
            } else {
                val = this.value;
            }
            if (this.answer.isScalar()) {
                return val[this.answer.id];
            }
            return val;
        },
        answerTypeComponent() {
            let component = null;
            let props = {
                id: this.answer.id,
                questionId: this.question.id,
                label: this.answer.answer,
                tooltipTitle: this.answer.tooltip_title,
                tooltipContent: this.answer.tooltip_content,
            };
            switch (true) {
                case this.answer.isAnswerType(TYPE_TEXT):
                    props.label = this.answer.answer;
                    props.placeholder = this.answer.label;
                    return {
                        component: AnswerTypeText,
                        props,
                    };
                case this.answer.isAnswerType(TYPE_SCALE):
                    let labels = JSON.parse(this.answer.answer);
                    props.minLabel = labels.min;
                    props.maxLabel = labels.max;
                    props.min = this.answer.numeric_min;
                    props.max = this.answer.numeric_max;
                    props.ignore = labels.ignore || "";
                    return {
                        component: AnswerTypeScale,
                        props,
                    };
                case this.answer.isAnswerType(TYPE_META):
                    props.answers = this.answer.getChildren();
                    return {
                        component: AnswerTypeMeta,
                        props,
                    };
                case this.answer.isAnswerType(TYPE_COLLAPSE_CHECKBOX):
                    props.answers = this.answer.getChildren();
                    props.description = this.answer.description;
                    return {
                        component: AnswerTypeCollapseCheckbox,
                        props,
                    };
                case this.answer.isAnswerType(TYPE_COLLAPSE_SELECT):
                    props.answers = this.answer.getChildren();
                    props.description = this.answer.description;
                    return {
                        component: AnswerTypeCollapseSelect,
                        props,
                    };
                case this.answer.isAnswerType(TYPE_COLLAPSE_RADIO):
                    props.answers = this.answer.getChildren();
                    props.description = this.answer.description;
                    return {
                        component: AnswerTypeCollapseRadio,
                        props,
                    };
                case this.answer.isAnswerType(TYPE_COLLAPSE_MIXED):
                    props.answers = this.answer.getChildren();
                    props.description = this.answer.description;
                    return {
                        component: AnswerTypeCollapseMixed,
                        props,
                    };
                case this.answer.isAnswerType(TYPE_RADIO):
                    component = AnswerTypeRadio;
                    if (this.answer.additional_text) {
                        component = AnswerTypeRadioText;
                        props.additionalLabel = this.answer.label;
                    }
                    return {
                        component,
                        props,
                    };
                case this.answer.isAnswerType(TYPE_CHECKBOX):
                    component = AnswerTypeCheckbox;
                    if (this.answer.additional_text) {
                        component = AnswerTypeCheckboxText;
                        props.additionalLabel = this.answer.label;
                    }
                    return {
                        component,
                        props,
                    };
                case this.answer.isAnswerType(TYPE_DATE):
                    if (this.answer.numeric_min) {
                        props.minYearsAgo = this.answer.numeric_min;
                    }
                    return {
                        component: AnswerTypeDate,
                        props,
                    };
            }
        },
    },
};
</script>

<style scoped></style>
