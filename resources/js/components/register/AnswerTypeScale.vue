<template>
    <div>
        <div class="form-group rounded-box input--range">
            <label :for="'skala_' + id"
                ><span class="min">{{ minLabel }}</span
                ><span class="max">{{ maxLabel }}</span></label
            >
            <input
                type="range"
                v-model="scaleVal"
                :class="'form-control-range question_' + questionId"
                :id="'skala_' + id"
                :min="min"
                :max="max"
                step="1"
            />
            <span class="scale">
                <span @click="scaleVal = n" v-for="n in max">{{ n }}</span>
            </span>
        </div>
        <div v-if="ignore" class="form-check mt-2 p-0">
            <div @click="doesNotMeet">
                <input
                    type="radio"
                    :checked="ignoreChecked"
                    :name="'question_' + questionId"
                    :id="'ignore_' + id"
                    :class="'question_' + questionId"
                />
                <label
                    :for="'ignore_' + questionId"
                    class="form-check-label check-label"
                >
                    {{ ignore }}
                </label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AnswerTypeScale",
    emits: ["input"],
    props: {
        id: Number,
        questionId: Number,
        minLabel: String,
        maxLabel: String,
        value: Number,
        min: Number,
        max: Number,
        ignore: String,
    },
    data() {
        return {
            ignoreChecked: false,
        };
    },
    methods: {
        doesNotMeet() {
            this.ignoreChecked = true;
            this.$emit("input", -1);
        },
    },
    computed: {
        scaleVal: {
            get() {
                if (this.ignoreChecked) return;
                if (typeof this.value === "undefined") {
                    let val = Math.ceil(this.max / 2);
                    this.$emit("input", val);
                    return val;
                }
                return this.value;
            },
            set(val) {
                let intVal = parseInt(val);
                if (isNaN(intVal)) {
                    this.$emit("input", Math.ceil(this.max / 2));
                    return;
                }
                this.$emit("input", intVal);
                this.ignoreChecked = false;
            },
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

input[type="range"] {
    -webkit-appearance: none; /* Hides the slider so that custom slider can be made */
    width: 100%; /* Specific width is required for Firefox. */
    background: transparent; /* Otherwise white in Chrome */
    margin: 1rem 0;
}

/* Styles need to be duplicated because chrome does not like to share*/
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    border: 0.2rem solid $brand-color-primary;
    background: $brand-color-base;
    border-radius: 50%;
    width: 1.2rem;
    height: 1.2rem;
    margin-top: -0.45rem;
}
input[type="range"]::-webkit-slider-runnable-track {
    width: 100%;
    height: 0.4rem;
    cursor: pointer;
    background: $dark-grey-background;
    border-radius: 0.2rem;
}

input[type="range"]::-moz-range-thumb {
    border: 0.2rem solid $brand-color-primary;
    background: $brand-color-base;
    border-radius: 50%;
    width: 1.2rem;
    height: 1.2rem;
    margin-top: -0.45rem;
}
input[type="range"]::-moz-range-track {
    width: 100%;
    height: 0.4rem;
    cursor: pointer;
    background: $dark-grey-background;
    border-radius: 0.2rem;
}

input[type="range"]::-ms-thumb {
    border: 0.2rem solid $brand-color-primary;
    background: $brand-color-base;
    border-radius: 50%;
    width: 1.2rem;
    height: 1.2rem;
    margin-top: -0.45rem;
}
input[type="range"]::-ms-track {
    width: 100%;
    height: 0.4rem;
    cursor: pointer;
    background: $dark-grey-background;
    border-radius: 0.2rem;
}

input[type="range"]:focus {
    outline: none; /* Removes the blue border. You should probably do some kind of focus styling for accessibility reasons though. */
}

input[type="range"]::-ms-track {
    width: 100%;
    cursor: pointer;

    /* Hides the slider so custom styles can be added */
    background: transparent;
    border-color: transparent;
    color: transparent;
}

.form-group {
    padding: 0.625rem;
}
label,
.scale {
    display: flex;
    justify-content: space-between;
}
label > :first-child {
    text-align: left;
}
label > :last-child {
    text-align: right;
}
.scale > span {
    cursor: pointer;
    text-align: center;
    width: 1.5rem;
}
</style>
