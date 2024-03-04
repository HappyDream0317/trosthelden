<template>
    <div>
        <input
            class="form-check-input"
            type="radio"
            :name="'question_' + questionId"
            :class="'question_' + questionId"
            :id="'questionRadios_' + id"
            :checked="checked"
            @change="checked = !checked"
        />
        <span class="check-label">
            <label class="form-check-label" :for="'questionRadios_' + id">{{
                label
            }}</label>
            <tooltip
                v-if="tooltipContent"
                :title="tooltipTitle"
                :content="tooltipContent"
                :add-class="tooltipClass"
            ></tooltip
            ><br />
            <span v-show="checked">
                <ValidationProvider as="div" rules="max:255" v-model="model" v-slot="{ errorMessage, field }">
                    <input
                        :placeholder="additionalLabel"
                        class="form-control"
                        type="text"
                        v-bind="field"
                        :id="'radio_text_' + id"
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
    name: "AnswerTypeRadioText",
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
            tooltipClass: "",
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
                this.$emit("radio-activated", this.id);
                this.$emit("input", val);
            },
        },
    },
    watch: {
        checked(val) {
            if (val && !this.value) {
                this.$emit("radio-activated", this.id);
                this.$emit("input", "");
            }

            if (this.tooltipContent)
                this.tooltipClass = val ? "checked--icon-white" : "";
        },
    },
};
</script>

<style lang="scss" scoped>
input[type="text"] {
    margin-top: 0.5rem;
}
</style>
