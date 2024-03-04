<template>
    <div class="form-group questionSelect">
        <label v-if="label" :for="'questionSelect' + id">{{ label }}</label>
        <select
            v-model="selectedAnswer"
            class="form-control single"
            :id="'questionSelect_' + id"
        >
            <option v-if="placeholder" :value="nullValue" disabled>
                {{ placeholder }}
            </option>
            <option
                v-for="answer in options"
                :value="answer.id"
                :key="answer.id"
            >
                {{ answer.answer }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    name: "AnswerTypeSelect",
    emits: ["input"],
    props: {
        id: Number,
        answers: Array,
        label: {
            type: String,
            default: null,
        },
        placeholder: {
            type: String,
            default: null,
        },
        value: {},
        isScale: {
            type: Boolean,
            default: false,
        },
        min: {
            type: Number,
            default: null,
        },
        max: {
            type: Number,
            default: null,
        },
    },
    data() {
        return {
            nullValue: null,
        };
    },
    computed: {
        options() {
            if (!this.isScale) {
                return this.answers;
            }
            return this.getRange(this.min, this.max).map((val) => {
                return {
                    id: val,
                    answer: val,
                };
            });
        },
        selectedAnswer: {
            set(answer) {
                if (this.isScale) {
                    this.$emit("input", answer);
                } else {
                    let val = this.value;

                    Object.keys(val).forEach((key) => {
                        val[key] = false;
                    });
                    val[answer] = true;

                    this.$emit("input", val);
                }
            },
            get() {
                if (this.isScale) {
                    if (typeof this.value === "undefined") {
                        return null;
                    }
                    return this.value;
                }
                for (let id in this.value) {
                    if (this.value[id] === true) {
                        return id;
                    }
                }
                return null;
            },
        },
    },
    methods: {
        getRange(start, stop) {
            return new Array(stop + 1 - start).fill(start).map((n, i) => n + i);
        },
    },
};
</script>

<style scoped>
.question-wrapper .question .form-check .form-group.questionSelect label {
    display: flex !important;
}
</style>
