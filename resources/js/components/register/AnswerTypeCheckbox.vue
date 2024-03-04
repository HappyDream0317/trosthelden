<template>
    <div class="form-check">
        <input
            v-model="checked"
            class="form-check-input"
            :class="'question_' + questionId"
            type="checkbox"
            :id="'checkbox_' + id"
        />
        <label class="form-check-label check-label" :for="'checkbox_' + id">
            {{ label }}
            <tooltip
                v-if="tooltipContent"
                :title="tooltipTitle"
                :content="tooltipContent"
            ></tooltip>
        </label>
    </div>
</template>

<script>
import Tooltip from "../Tooltip";
export default {
    name: "AnswerTypeCheckbox",
    emits: ["input"],
    components: { Tooltip },
    props: {
        id: Number,
        questionId: Number,
        label: String,
        value: Boolean,
        tooltipTitle: { default: null },
        tooltipContent: { default: null },
    },
    computed: {
        checked: {
            get() {
                if (typeof this.value === "undefined") {
                    return false;
                }
                return this.value;
            },
            set(val) {
                this.$emit("input", val);
            },
        },
    },
};
</script>
