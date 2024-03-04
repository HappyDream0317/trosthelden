<template>
    <default-layout>
        <div
            class="row only--desktop"
            v-if="canParticipate && status !== 'paused_matches'"
        >
            <div class="col-12">
                <h4>Deine Trauerfreund-Vorschläge</h4>
            </div>
        </div>
        <div class="row" v-if="isAllowedTo('view supporter_profile')">
            <div class="col-12">
                <h4>Herzlichen Dank für dein Interesse an TrostHelden.</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-12 only--mobile py-4 mt-2">
                <h2 class="message__title">Deine Trauerfreund-Vorschläge</h2>
                <p>
                  Hier sind deine passendsten Trauerfreund-Vorschläge. Weitere
                  findest du unter "Weitere Vorschläge anzeigen".
                  <br/><br/>
                  Unser Tipp: Schau dich in Ruhe um und sende
                  mutig eine Trauerfreund-Anfrage an andere TrostHelden. In
                  der Einzelkommunikation findet ihr heraus, ob ihr zueinander
                  passt!
                  <br/><br/>
                  Kleine Bitte, wenn dich eine Trauerfreundin oder ein Trauerfreund anschreibt, diese Person für dich aber
                  nicht passt: Sende ihr eine kurze Absage. Das ist völlig in Ordnung. Und dein Gegenüber hofft nicht
                  vergeblich auf Antwort.
                  <br/><br/>
                  Übrigens: Mit dem Button „Trauerfreund ablehnen“ geht das ganz einfach.
                </p>
            </div>

            <MainContent
                :class="
                    showSpinner
                        ? 'd-flex justify-content-center align-items-center'
                        : ''
                "
            >
                <template v-if="isAllowedTo('view mourner_profile')">
                    <partner-list-countdown
                        v-if="isFirstView"
                        :countdown="countdown"
                    />

                    <spinner
                        v-if="showSpinner"
                        color="color--primary"
                        message="Deine Matches werden gerade berechnet."
                    />

                    <paused-matches-hint v-if="status === 'paused_matches'" />

                    <survey-multiple-lost-hint
                        v-if="status === 'multiple_lost'"
                    />

                    <survey-completion-hint
                        v-else-if="status === 'survey_not_completed'"
                    />

                    <slot
                        v-if="
                            !isLoading &&
                            status === 'survey_completed_with_matches'
                        "
                    >
                        <partner-list
                            id="top_matches_list"
                            :use-resources="topMatches.data"
                            class="list-wrapper"
                            :show-pagination="false"
                            :show-last-page-message-hint="false"
                        />
                        <div
                            class="d-flex justify-content-center align-items-center mb-2"
                        >
                            <router-link
                                :to="{ name: 'matchings' }"
                                class="btn btn-primary"
                                >Weitere Vorschläge anzeigen
                            </router-link>
                        </div>
                    </slot>
                </template>

                <template v-else>
                    <p>
                        Dir als Trauerbegleiter möchten wir gern einen
                        besonderen Zugang zu unserem vielfältigen
                        TrostHelden-Angebot geben. So kannst du dir ein
                        umfassendes Bild von TrostHelden machen
                    </p>
                    <ul>
                        <li>Wie wir Trauernde unterstützen</li>
                        <li>
                            Welche inspirierenden Inhalte trauernde Menschen bei
                            TrostHelden finden
                        </li>
                        <li>
                            Was es mit dem weltweit ersten Trauerfreund-Matching
                            auf sich hat und selbstverständlich auch
                        </li>
                        <li>
                            <a
                                href="https://www.trosthelden.de/ueber-trosthelden"
                                target="_blank"
                                >Wer wir sind.</a
                            >
                        </li>
                    </ul>
                    <p>
                        Da du persönlich keinen Trauerfreund suchst, erhältst du
                        auch keine Vorschläge für einen Trauerfreund.
                    </p>
                    <p>
                        Du möchtest aber wissen, wie die Trauerfreund-Suche
                        genau funktioniert und es dir konkret einmal anschauen?
                        Sehr gern!
                    </p>
                    <p>
                        Eine kurze Mail genügt. Wir freuen uns, es dir zu
                        zeigen!
                    </p>
                    <button class="btn btn-primary btn-with-link">
                        <a
                            href="mailto:service@trosthelden.de?subject=Frage%20zu%20Trosthelden"
                            >Kurze Mail schicken</a
                        >
                    </button>
                </template>
            </MainContent>

            <Sidebar>
                <user-info-panel class="mb-2" />
            </Sidebar>
        </div>
    </default-layout>
</template>

<script>
import PageHeader from "../components/PageHeader";
import DefaultLayout from "../layouts/DefaultLayout";
import PartnerList from "../components/partner/PartnerList";
import UserInfoPanel from "../components/UserInfoPanel";
import ReplyHighlightItem from "../components/dashboard/ReplyHighlightItem";
import PostHighlightItem from "../components/dashboard/PostHighlightItem";
import SurveyCompletionHint from "../components/SurveyCompletionHint";
import PausedMatchesHint from "../components/PausedMatchesHint";
import SurveyMultipleLostHint from "../components/SurveyMultipleLostHint";
import MainContent from "../layouts/MainContent";
import Sidebar from "../layouts/Sidebar";
import Paginator from "../components/Paginator";
import Spinner from "../components/groups/Spinner";
import { mapGetters } from "vuex";
import PartnerListCountdown from "../components/PartnerListCountdown";

export default {
    components: {
        SurveyMultipleLostHint,
        SurveyCompletionHint,
        MainContent,
        Sidebar,
        Spinner,
        PostHighlightItem,
        ReplyHighlightItem,
        UserInfoPanel,
        PartnerList,
        DefaultLayout,
        PageHeader,
        Paginator,
        PartnerListCountdown,
        PausedMatchesHint,
    },
    data() {
        return {
            breakpoint: {
                tablet: 991,
                mobile: 767,
            },
            topMatches: {
                data: [],
                loading: false,
                per_page: 3,
            },
            latestReplies: {
                data: [],
                last_page: 0,
                current_page: 0,
                per_page: 3,
                total: 0,
                loading: false,
            },
            friendsPosts: {
                data: [],
                last_page: 0,
                current_page: 0,
                per_page: 3,
                total: 0,
                loading: false,
            },
            similarMourningPosts: {
                data: [],
                last_page: 0,
                current_page: 0,
                per_page: 3,
                total: 0,
                loading: false,
            },
            defaultCountdown: 30,
            countdown: 0,
        };
    },
    watch: {
        isFirstView: {
            handler: function (value, oldValue) {
                if (!value && oldValue) this.getTopMatches();
            },
        },
    },
    mounted() {
        this.checkFirstView();
        this.checkPerPageLists();
        this.getSimilarMourningPosts();
        if (!this.isFirstView) {
            this.getTopMatches();
        }
    },
    created() {
        window.addEventListener("resize", this.checkPerPageLists);
    },
    destroyed() {
        window.removeEventListener("resize", this.checkPerPageLists);
    },
    computed: {
        ...mapGetters("currentUser", {
            isFirstView: "isFirstView",
            currentUser: "getObject",
            isAllowedTo : "isAllowedTo",
            canParticipate: "canParticipate",
        }),
        showSpinner() {
            return (
                this.isLoading &&
                !this.isFirstView &&
                this.status !== "multiple_lost" &&
                this.status !== "survey_not_completed" &&
                this.status !== "paused_matches"
            );
        },
        isLoading() {
            return (
                this.latestReplies.loading ||
                this.friendsPosts.loading ||
                this.topMatches.loading
            );
        },
        status() {
            const matchingStatus = Boolean(this.currentUser.matching_status);
            const matchingStep = parseInt(this.currentUser.matching_step);

            let status = "survey_nohighlight-bannert_completed";

            if (!matchingStatus) {
                status = "paused_matches";
            } else if (matchingStep === -2) {
                status = "multiple_lost";
            } else if (matchingStep > -1) {
                status = "survey_not_completed";
            } else if (this.topMatches?.data?.length === 0 || this.isFirstView) {
                status = "survey_completed_with_no_matches";
            } else {
                status = "survey_completed_with_matches";
            }

            return status;
        },
    },
    methods: {
        checkPerPageLists() {
            if (window.innerWidth > this.breakpoint.tablet) {
                this.latestReplies.per_page = 3;
                this.friendsPosts.per_page = 3;
            } else if (window.innerWidth > this.breakpoint.mobile) {
                this.latestReplies.per_page = 2;
                this.friendsPosts.per_page = 2;
            } else {
                this.latestReplies.per_page = 1;
                this.friendsPosts.per_page = 1;
            }
        },
        getTopMatches() {
            this.topMatches.loading = true;
            axios
                .get("/api/user/matching", {
                    params: {
                        perPage: this.topMatches.per_page,
                        page: 1,
                    },
                })
                .then((response) => {
                    this.topMatches = {
                        ...response.data,
                        per_page: parseInt(response.data.per_page),
                    };
                    this.$eventBus.emit("fetch-new-matches-count");
                })
                .catch((err) => {
                    console.error(err);
                    this.errored = true;
                })
                .finally(() => (this.topMatches.loading = false));
        },
        getReplies(page) {
            if (this.latestReplies.loading) return;

            this.latestReplies.loading = true;
            axios
                .get("/api/comment/user/latest", {
                    params: {
                        perPage: this.latestReplies.per_page,
                        page: page,
                    },
                })
                .then((response) => {
                    this.latestReplies = {
                        ...response.data,
                        per_page: parseInt(response.data.per_page),
                    };
                })
                .catch((err) => {
                    console.error(err);
                    this.errored = true;
                })
                .finally(() => (this.latestReplies.loading = false));
        },
        getFriendPosts(page) {
            if (this.friendsPosts.loading) return;

            this.friendsPosts.loading = true;
            axios
                .get("/api/post/user/friends/latest", {
                    params: {
                        perPage: this.friendsPosts.per_page,
                        page: page,
                    },
                })
                .then((response) => {
                    this.friendsPosts = {
                        ...response.data,
                        per_page: parseInt(response.data.per_page),
                    };
                })
                .catch((err) => {
                    console.error(err);
                    this.errored = true;
                })
                .finally(() => (this.friendsPosts.loading = false));
        },
        getSimilarMourningPosts(page) {
            if (this.similarMourningPosts.loading) return;

            this.similarMourningPosts.loading = true;
            axios
                .get("/api/post/user/similar-mourning/latest", {
                    params: {
                        perPage: this.similarMourningPosts.per_page,
                        page: page,
                    },
                })
                .then((response) => {
                    this.similarMourningPosts = {
                        ...response.data,
                        per_page: parseInt(response.data.per_page),
                    };
                })
                .catch((err) => {
                    console.error(err);
                    this.errored = true;
                })
                .finally(() => (this.similarMourningPosts.loading = false));
        },
        checkFirstView() {
            let isFirstView = localStorage.getItem("firstView");
            if (isFirstView)
                this.$store.dispatch("currentUser/firstViewCounterInit");
        },
    },
};
</script>
<style lang="scss" scoped>
@import "../../sass/setup/variables";
@import "../../sass/micro-clearfix";

.message {
    &__title {
        font-size: 24px;
        margin-bottom: 12px;
    }
}

.highlight-banner {
    padding: 0.625rem;
    background-color: $normal-grey-background;
    border-radius: $border-radius;
    clear: both;
    margin-top: 0.625rem;
}

.content-highlight-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;

    @media (max-width: 991px) {
        justify-content: center;
    }

    & ::v-deep {
        & .reply-highlight {
            flex: 1;
            flex-basis: 30%;
            background-color: $brand-color-base;
            border-radius: $border-radius;
            margin: 0.625rem;
            min-width: 13rem;
            height: 18rem;
            display: flex;
            flex-direction: column;
            max-width: 340px;

            & > * {
                padding: 0.625rem;
            }

            .header {
                @include micro-clear;
                padding-bottom: 0;
                display: flex;
                flex-direction: row;
                justify-content: flex-start;

                &__avatar {
                    float: left;
                    margin-right: 0.625rem;
                }

                &__user-info {
                    display: flex;
                    flex-direction: column;

                    .identifier {
                        color: $brand-color-primary;
                        font-weight: 500;

                        &:hover {
                            text-decoration: none;
                        }

                        svg {
                            color: $brand-color-primary;
                            margin-left: 5px;
                        }
                    }
                }
            }

            & > .reply-content {
                flex: 1;
                color: $dark-grey-text;
                overflow: hidden;
                padding: 0 !important;
                border: 0.625rem solid transparent;

                & > .category {
                    color: $light-grey-text;
                }

                & > .group {
                    color: $brand-color-primary;
                }

                .comment {
                    padding-left: 0;
                    padding-right: 0;
                }
            }

            .footer {
                line-height: 1rem;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-content: center;

                &__content {
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-start;
                    align-content: center;
                    width: calc(100% - 30px);

                    &__title {
                        color: $brand-color-primary;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        font-size: 14px;
                    }

                    &__thema,
                    &__category,
                    &__group,
                    &__my-post {
                        font-size: 14px;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }

                    &__thema {
                        font-weight: 100;
                    }

                    &__category,
                    &__group,
                    &__my-post {
                        font-weight: 500;
                    }
                }

                .link {
                    display: block;
                    float: right;
                    font-size: 2rem;
                }
            }
        }
    }
}
</style>
