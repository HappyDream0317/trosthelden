<template>
    <div class="survey-layout">
        <header>
            <full-logo></full-logo>
        </header>
        <main class="container p-3" v-if="!displayAdvice">
            <modal></modal>
            <div
                class="questions-header"
                v-if="stepHeader"
                v-html="stepHeader"
            ></div>
            <div
                class="question-wrapper rounded-box p-3 my-2"
                v-if="status === 'running'"
                :key="step"
            >
                <question
                    v-for="question in questions"
                    class="question"
                    :key="question.id"
                    :question="question"
                    :value="userAnswers[question.id]"
                    @input="(val) => changeUserAnswers(question.id, val)"
                >
                    {{ question.question }}
                    <template
                        v-slot:after
                        v-if="question.additional_steps.content_after"
                    >
                        <div
                            v-html="question.additional_steps.content_after"
                        ></div>
                    </template>
                </question>

                <div class="submit-bar">
                    <button
                        v-on:click="nextStep"
                        type="button"
                        class="btn btn-primary float-right"
                    >
                        Weiter
                    </button>
                </div>
            </div>
        </main>
        <main v-else class="container">
            <modal></modal>
            <component
                :is="adviceComponent"
                @confirmed="nextStep"
                @confirmedAsSupporter="registerAsSupporter"
                @confirmedAsMourner="confirmedAsMourner"
            />
        </main>
        <footer>
            <div class="container">
                <img src="/img/crest.svg" />
                <nav>
                    <ul>
                        <li>
                            <router-link
                                :to="{ name: 'conditions' }"
                                class="nav-link"
                                exact
                                >AGB</router-link
                            >
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'privacy' }"
                                class="nav-link"
                                exact
                                >Datenschutz</router-link
                            >
                        </li>
                        <li>
                            <router-link
                                :to="{ name: 'revocation' }"
                                class="nav-link"
                                exact
                                >Impressum</router-link
                            >
                        </li>
                    </ul>
                </nav>
            </div>
        </footer>
    </div>
</template>

<script>
import Question from "./Question";
import FullLogo from "../FullLogo";
import Termination from "./descriptions/Termination";
import Finish from "./descriptions/Finish";
import Intro from "./descriptions/Intro";
import AboutYou from "./descriptions/AboutYou";
import NearlyDone from "./descriptions/NearlyDone";
import Modal from "../Modal";
import ErrorModal from "./ErrorModal";

const moderationSteps = {
    1: Intro,
    5: AboutYou,
    13: NearlyDone,
};

export default {
    name: "Guide",
    components: {
        Modal,
        FullLogo,
        Question,
    },
    data: () => ({
        questions: [],
        userAnswers: {},
        loading: false,
        status: "running",
        step: 1,
    }),
    mounted: function () {
        window.addEventListener("beforeunload", this.preventNavigation);
        this.$eventBus.on("reset-frabo", () => this.reset());
        this.init();
    },
    beforeDestroy() {
        window.removeEventListener("beforeunload", this.preventNavigation);
        this.$eventBus.off("reset-frabo");
    },
    beforeRouteLeave() {},
    computed: {
        stepHeader() {
            if (
                this.questions &&
                this.questions.length > 0 &&
                this.questions[0].hasOwnProperty("additional_steps")
            ) {
                return this.questions[0].additional_steps.content_before;
            }
            return null;
        },
        displayAdvice() {
            return (
                this.status === "terminate" ||
                this.status === "finished" ||
                moderationSteps.hasOwnProperty(this.step)
            );
        },
        adviceComponent() {
            switch (true) {
                case this.status === "terminate":
                    return Termination;
                case this.status === "finished":
                    return Finish;
                case moderationSteps.hasOwnProperty(this.step):
                    return moderationSteps[this.step];
            }
        },
    },
    methods: {
        changeUserAnswers(questionId, val){
          this.userAnswers[questionId] = {
            ...this.userAnswers[questionId],
            ...val
          };
        },
        init() {
            axios
                .get("/api/guide")
                .then((response) => {
                    this.prepareForStep(
                        response.data.step,
                        response.data.questions
                    );
                })
                .catch((err) => {
                    let status =
                        err.response !== undefined ? err.response.status : null;
                    if (status === 403) {
                        if (!this.$store.getters["currentUser/isVerified"]) {
                            this.$router.push({ name: "pleaseverify" });
                        } else {
                            this.$router.push({ name: "dashboard" });
                        }
                        return;
                    }
                    console.log(err);
                })
                .finally(() => (this.loading = false));
        },
        reset() {
            this.questions = [];
            this.userAnswers = {};
            this.loading = false;
            this.status = "running";
            this.step = 1;

            this.init();
        },
        preventNavigation(event) {
            if (this.status !== "finished") {
                event.preventDefault();
                event.returnValue = "";
            }
            return;
        },
        getCleanUserAnswers() {
            let clean = {};
            for (let questionId in this.userAnswers) {
                for (let answerId in this.userAnswers[questionId]) {
                    let userAnswer = this.userAnswers[questionId][answerId];
                    if (userAnswer === "") {
                        userAnswer = true;
                    }
                    if (userAnswer === false || userAnswer === null) {
                        continue;
                    }
                    if (typeof clean[questionId] === "undefined") {
                        clean[questionId] = {};
                    }
                    clean[questionId][answerId] = userAnswer;
                }
            }
            return clean;
        },
        prepareForStep(step, questions) {
            this.step = step;
            this.questions = questions;
            this.userAnswers = {};
        },
        displayErrors(data) {
            let missingQuestions = [];
            if (Array.isArray(this.questions)) {
                missingQuestions = this.questions.filter((question) => {
                    return data.missingQuestionIds.indexOf(question.id) !== -1;
                });
            } else {
                for (let qIndex in this.questions) {
                    let question = this.questions[qIndex];
                    if (data.missingQuestionIds.indexOf(question.id) !== -1) {
                        missingQuestions.push(question);
                    }
                }
            }
            this.$eventBus.emit("modal-requested", {
                component: ErrorModal,
                props: {
                    missingQuestions,
                    validationErrors: data.validationErrors,
                },
            });
        },
        registerAsSupporter() {
            axios
                .post(`/api/guide/register-as-supporter`)
                .then(() => {
                    this.$router.push({ path: "/dashboard"});
                    this.$store.dispatch("currentUser/fetch");
                })
                .catch((err) => console.log(err));
        },
        confirmedAsMourner() {
            axios
                .post(`/api/guide/confirm-as-mourner`)
                .then(() => {
                    this.nextStep();
                })
                .catch((err) => console.log(err));
        },
        nextStep() {
            this.loading = true;

            axios
                .post(
                    `/api/guide/${this.step}`,
                    { user_answers: this.getCleanUserAnswers() },
                    { headers: { "Cache-Control": "no-cache" } }
                )
                .then((response) => {
                    window.scrollTo(0, 0);

                    gtag('event', `questionnaire_step_${this.step}`);

                    let step = null,
                        stepName;

                    // retrieve the name of the step by parsing it, cause it's html.
                    const header = document.createElement("div");
                    header.innerHTML = this.stepHeader;
                    if (
                        header.firstElementChild &&
                        header.firstElementChild.innerHTML
                    ) {
                        stepName = header.firstElementChild.innerHTML;
                    } else {
                        stepName = "Custom Step";
                    }

                    if (this.step) {
                        step = this.step;
                    }

                    // category, action, name, value
                    if (this.$matomo !== undefined) {
                        const name = this.displayAdvice
                            ? this.adviceComponent.name
                            : stepName + (step ? " Schritt: " + step : "");

                        this.$store.dispatch("matomo/guideNextStep", name);
                    }

                    if (response.data.terminate) {
                        this.status = "terminate"; //Maybe index.go?
                    } else if (response.data.finished) {
                        // Reload user info in local storage
                        this.$store.dispatch("currentUser/fetch");
                        this.status = "finished"; //Maybe index.go?
                        localStorage.setItem("firstView", JSON.stringify(true));
                    } else {
                        this.prepareForStep(
                            response.data.step,
                            response.data.questions
                        );
                    }
                })
                .catch((err) => {
                    let status =
                        err.response !== undefined ? err.response.status : null;
                    if (status === 400) {
                        this.displayErrors(err.response.data);
                        return;
                    }

                    console.log(err.response);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        logDebug() {
            console.log(this.getCleanUserAnswers());
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";
@import "../../../sass/micro-clearfix";

.survey-layout {
    height: 100%;
    display: flex;
    flex-direction: column;

    & .container > .questions-header ::v-deep h2 {
        text-transform: uppercase;
    }
}

header {
    width: 100%;
    background-color: $brand-color-base;
    background-image: url("/img/wolken.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    border-top: solid $header-deco-bar-size $brand-color-primary;
    border-bottom: solid 0.25rem $brand-color-base;
    margin-bottom: $header-footer-spacing;
    padding: 0.5rem 1.5rem 1rem 1.625rem;
}

.survey-layout ::v-deep main {
    flex: 1;
    max-width: 35rem !important;

    .advice {
        padding: 1.5rem 1.25rem 1.625rem 1.25rem;
    }
}

.survey-layout .container .question-wrapper {
    background-color: #cdcdcd;

    & > .question {
        margin-bottom: 1.5rem;
    }
}

::v-deep {
    .description {
        color: $dark-grey;
    }

    & .th-tooltip {
        float: right;
    }

    & .form-group {
        margin-bottom: 0 !important;

        & .accordion_row__trigger {
            font-weight: initial;
            color: $guide-text-color;
            min-height: unset;
            background-color: #ffffff;
        }

        & .collapse,
        & .collapsing {
            & .form-group {
                [type="radio"]:checked + .check-label,
                [type="checkbox"]:checked + .check-label {
                    background-color: $brand-color-primary;
                }

                [type="radio"] + .check-label,
                [type="checkbox"] + .check-label {
                    background-color: #efefef;
                }
            }
        }
    }

    & .form-check {
        color: $guide-text-color !important;
        padding-left: 0.25rem;
        padding-right: 0.25rem;
        margin-bottom: 0.5rem;
    }

    & .form-control {
        color: $guide-text-color;
        padding: 0.625rem;
        height: 2.8rem;
    }

    & [type="radio"],
    & [type="checkbox"] {
        display: none;

        & + .check-label {
            display: inline-block;
            padding: 0.625rem;
            min-height: 2.8rem;
            width: 100%;
            background-color: #ffffff;
            border-radius: $border-radius;

            & > label {
                width: 100%;
            }
        }

        &:checked + .check-label {
            background-color: $brand-color-primary;
            color: $white;
        }
    }

    .card-body {
        & .check-label {
            background-color: $brand-color-secondary;
        }

        & .description {
            font-size: 0.8rem;
            color: $light-grey-text;
        }
    }
}

.submit-bar {
    width: 100%;
    margin-top: 2.375rem;

    @include micro-clear;
}

footer {
    width: 100%;
    height: 10.5rem;
    background-color: $brand-color-primary;
    margin-top: $header-footer-spacing;

    & > .container > img {
        width: 7.5rem;
        display: block;
        margin: auto;
    }

    & > .container > nav {
        display: block;
        margin: 0 auto;
    }

    & ul,
    & li {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    & ul {
        display: flex;
        flex-direction: row;
        justify-content: center;

        & > li .nav-link {
            text-transform: uppercase;
            color: $brand-color-base;
        }
    }
}
</style>
