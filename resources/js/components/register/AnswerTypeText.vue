<template>
    <div>
        <div class="form-group">
            <label v-if="label" :for="'questionText_' + id">{{ label }}</label>
            <ValidationProvider
                as="div"
                :rules="`${isText ? 'max:255' : 'max_value:120'}`"
                v-slot="{ errorMessage, field }"
                v-model="text"
            >
                <input
                    v-bind="field"
                    :type="isText ? 'text' : 'number'"
                    :placeholder="placeholder"
                    :class="'form-control question_' + questionId"
                    :id="'questionText_' + id"
                />
                <span v-if="errorMessage" class="validation-error">
                    {{ errorMessage }}
                </span>
            </ValidationProvider>
        </div>
    </div>
</template>

<script>
export default {
    name: "AnswerTypeText",
    emits: ["input"],
    props: {
        id: Number,
        questionId: Number,
        label: { type: String, default: "" },
        placeholder: { type: String, default: "" },
        value: String,
    },
    beforeMount() {
        if (typeof this.value === "undefined") {
            this.$emit("input", null);
        }
    },
    computed: {
        isText() {
            const typeNumberFieldIds = [
                45, // Anzahl Jahre,
                147, // Alter des jüngsten Kindes
                148, // Alter des ältesten Kindes
            ];
            return !typeNumberFieldIds.includes(this.id);
        },
        text: {
            get() {
                if (typeof this.value === "undefined" || this.value === '') {
                    let val = null;
                    this.$emit("input", val);
                    return val;
                }
                return this.value;
            },
            async set(val) {
                this.$emit("input", val);
            },
        },
    },
};
</script>
