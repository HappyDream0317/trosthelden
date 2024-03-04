<template>
    <div>
        <input
            class="form-check-input"
            type="radio"
            :name="'question_' + questionId"
            :class="'question_' + questionId"
            :id="'questionRadios_' + id"
            :checked="checked"
            @change="changeAnswer"
        />
        <label
            class="form-check-label check-label"
            :for="'questionRadios_' + id"
        >
            {{ label }}
            <tooltip
                v-if="tooltipContent"
                :title="tooltipTitle"
                :content="tooltipContent"
                :add-class="tooltipClass"
            ></tooltip>
        </label>
    </div>
</template>

<script>
import Tooltip from "../Tooltip";
export default {
    name: "AnswerTypeRadio",
    emits: ["input"],
    emits: ['input'],
    components: { Tooltip },
    props: {
        id: Number,
        questionId: Number,
        label: String,
        value: Boolean,
        tooltipTitle: { default: null },
        tooltipContent: { default: null },
    },
    data() {
        return {
            tooltipClass: "",
        };
    },
    computed: {
        checked() {
            let val = false;
            if (typeof this.value === "undefined") {
                this.$emit("input", val);
            } else {
                val = this.value;
            }

            if (this.tooltipContent) {
                this.tooltipClass = val ? "checked--icon-white" : "";
            }

            return val;
        },
    },
    methods: {
        changeAnswer() {
            this.$emit("input", true);
            this.$emit("radio-activated", this.id);
        },
    },
};
</script>
