<template>
    <div class="form-check">
        <input
            v-model="checked"
            class="form-check-input"
            :class="'question_' + questionId"
            type="checkbox"
            :id="'checkbox_' + id"
        />
        <span class="check-label">
            <label class="form-check-label" :for="'checkbox_' + id">{{
                label
            }}</label>
            <tooltip
                v-if="tooltipContent"
                :title="tooltipTitle"
                :content="tooltipContent"
            ></tooltip
            ><br />
            <span v-show="checked">
                <ValidationProvider as="div" rules="max:255" v-model="model" v-slot="{ errorMessage, field }">
                    <input
                        :placeholder="additionalLabel"
                        class="form-control"
                        type="text"
                        v-bind="field"
                        :id="'checkbox_text_' + id"
                    />
                    <span v-if="errorMessage" class="validation-error">
                        {{ errorMessage }}
                    </span>
                </ValidationProvider>
            </span>
        </span>
    </div>
</template>

<script>
export default {
    name: "AnswerTypeCheckboxText",
    emits: ["input"],
    props: {
        id: Number,
        questionId: Number,
        label: String,
        additionalLabel: String,
        value: String,
        tooltipTitle: { default: null },
        tooltipContent: { default: null },
    },
    data() {
        return {
            checked: false,
        };
    },
    computed: {
        model: {
            get() {
                if (typeof this.value === "undefined") {
                    return "";
                }
                if (this.value !== "" && !this.value) {
                    this.checked = false;
                }
                return this.value;
            },
            set(val) {
                this.$emit("input", val);
            },
        },
    },
    watch: {
        checked(val) {
            if (val && (typeof this.value === "undefined" || !this.value)) {
                this.$emit("input", "");
            }
        },
    },
};
</script>

<style scoped>
input[type="text"] {
    margin-top: 0.5rem;
}
</style>
