<template>
    <div class="mb-4">
        <label class="h4 font-weight-normal profile-label--center">
            {{ question }}
            <span
                ref="btn_answer_edit"
                v-if="!editText"
                v-on:click="edit"
                class="icon--edit-direct"
            >
                <span class="pencil-edit-button" @click="editText = true">
                    <fa-icon icon="pencil-alt" />
                </span>
            </span>
        </label>
        <ValidationProvider as="div" rules="max:350" v-model="temp_answer" v-slot="{ errorMessage, field }" :validateOnModelUpdate="false" :validateOnInput="true">
            <textarea
                class="rounded w-100 question-textarea p-2 border-0"
                ref="answer"
                v-bind="field"
                type="text"
                :disabled="!editText"
            ></textarea>

            <div v-if="editText" class="profile-edit-actions">
                <button
                    @click="save"
                    class="btn btn-primary btn-save"
                    :disabled="isDisabled"
                >
                    <fa-icon icon="save"></fa-icon>
                    Speichern
                </button>
                <button
                    @click="cancel"
                    class="btn btn-outline-primary btn-edit-cancel"
                >
                    Abbrechen
                </button>
            </div>

            <span
                ref="btn_answer_save"
                v-on:click="save"
                class="icon--edit-direct"
            >
                <fa-icon ref="icon_save" icon="save" hidden></fa-icon>
            </span>
            <span class="validation-error">{{ errorMessage }}</span>
        </ValidationProvider>
    </div>
</template>

<script>
export default {
    props: ["question", "answer", "question_id"],
    name: "ProfileQuestion",
    data: function () {
        return {
            temp_answer: "",
            prevAnswer: "",
            editText: false,
        };
    },
    computed: {
        isDisabled() {
            return this.temp_answer?.length > 350;
        },
    },
    mounted: function () {
        this.temp_answer = this.answer;
        this.prevAnswer = this.answer;
        this.update_answer_status();

        if (
            navigator.platform.match("iPhone") ||
            navigator.platform.match("iPod") ||
            navigator.platform.match("iPad") ||
            navigator.platform.match("Mac") ||
            navigator.platform.match("Pike")
        ) {
            let css =
                    ".question-textarea, textarea.question-textarea:disabled { color: #121212; -webkit-text-fill-color: #121212; -webkit-opacity: 1, opacity:1 }; .question-textarea, textarea.question-textarea::placeholder {color: #121212; -webkit-text-fill-color: #121212; -webkit-opacity: 1; opacity:1 }",
                head =
                    document.head || document.getElementsByTagName("head")[0],
                style = document.createElement("style");

            head.appendChild(style);

            style.type = "text/css";
            if (style.styleSheet) {
                // This is required for IE8 and below.
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }
        }
    },
    methods: {
        edit() {
            this.editText = true;
            this.$refs.answer.focus();
        },
        cancel() {
            this.temp_answer = this.prevAnswer;
            this.editText = false;
        },
        save() {
            if (this.temp_answer.length > 350) {
                return;
            }
            if (this.update_answer_status()) {
                axios
                    .put(`/api/profile/questions/${this.question_id}/answer`, {
                        answer_text: this.temp_answer,
                    })
                    .then(() => {
                        this.prevAnswer = this.temp_answer;
                    })
                    .finally(() => {
                        this.editText = false;
                    });
            }
        },
        update_answer_status() {
            if (typeof this.temp_answer === "string") {
                this.$emit("input", 0);
                return true;
            }
            return false;
        },
    },
};
</script>

<style lang="scss" scoped>
.question-textarea,
textarea.question-textarea:disabled {
    min-height: 110px;
    box-sizing: border-box;
    background: #ededed;
    color: #121212;
    -webkit-text-fill-color: #121212;
    -webkit-opacity: 1;
}

.question-textarea {
    &:not(:disabled) {
        &:focus {
            border: 2px solid #b5b5b5 !important;
            outline: none;
        }
    }
}
</style>
