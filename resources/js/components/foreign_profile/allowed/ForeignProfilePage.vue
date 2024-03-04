<template>
    <div
        id="foreign-profile"
        class="page page--foreign-profile"
        v-if="hasLoaded"
    >
        <ForeignProfileHeader :nickname="user.nickname" />
        <div class="row">
            <MainContent>
                <div class="profile rounded-box">
                    <div class="profile__avatar">
                        <user-avatar :user="user" class="avatar" />
                        <button
                            class="btn btn--report btn--report-user"
                            @click="reportProfile"
                        >
                            Nutzer melden
                            <fa-icon icon="exclamation-circle"></fa-icon>
                        </button>
                    </div>
                    <div class="profile__info">
                        <user-identifier :user="user" class="identifier" />
                        <user-location :user="user" class="location" />
                    </div>
                    <div class="profile__action">
                        <user-actions
                            :show-label="true"
                            :user="user"
                            class="user-actions"
                        />
                    </div>
                </div>

                <ForeignProfileQuestions
                    class="rounded-box mb-2 p-3"
                    v-if="toggleQuestions"
                    :questions="profile_questions"
                />
            </MainContent>
            <Sidebar :mobile-on-top="false">
                <template v-if="user.is_mourner">
                    <ForeignProfileReason
                        class="mb-2 rounded-box bg--blue-200 color--white"
                        v-if="toggleReason"
                        :reason="profile_reason"
                    />
                    <ForeignProfileGroups
                        class="rounded-box mb-2"
                        v-if="toggleGroups"
                        :groups="profile_groups"
                        :nickname="user.nickname"
                    />
                    <ForeignProfileInfo
                        class="rounded-box mb-2"
                        :info="profile_info"
                    />
                </template>

                <template v-else>
                    <ProfileReason
                        reason="SUPPORTER"
                        class="mb-2 rounded-highlight-box"
                    />
                </template>
            </Sidebar>
        </div>
    </div>
</template>
<script>
import DefaultLayout from "../../../layouts/DefaultLayout";
import ForeignProfileReason from "./ForeignProfileReason";
import ForeignProfileInfo from "./ForeignProfileInfo";
import ForeignProfileQuestions from "./ForeignProfileQuestions";
import ForeignProfilePortrait from "./ForeignProfilePortrait";
import ForeignProfileGroups from "./ForeignProfileGroups";
import ForeignProfileHeader from "./ForeignProfileHeader";
import UserAvatar from "../../UserAvatar";
import UserIdentifier from "../../UserIdentifier";
import UserLocation from "../../UserLocation";
import UserActions from "../../groups/UserActions";
import Sidebar from "../../../layouts/Sidebar";
import MainContent from "../../../layouts/MainContent";
import MembershipBadge from "../../shared_profile_components/MembershipBadge";
import ReportProfile from "../../groups/ReportProfile";
import QuickMessage from "../../groups/QuickMessage";
import ReportPost from "../../groups/ReportPost";

export default {
    components: {
        MainContent,
        Sidebar,
        UserActions,
        UserLocation,
        UserIdentifier,
        UserAvatar,
        ForeignProfileHeader,
        ForeignProfileGroups,
        ForeignProfilePortrait,
        ForeignProfileReason,
        ForeignProfileInfo,
        ForeignProfileQuestions,
        DefaultLayout,
        MembershipBadge,
    },
    props: ["user_id"],
    data: function () {
        return {
            hasLoaded: false,
            user: null,
            profile_reason: {
                text: "",
                death: "",
                year: "",
                relation: "",
            },
            profile_info: {
                nickname: "",
                sex: "",
                age: "",
                country: "",
                postal: "",
                marital_status: "",
                job: "",
                religion: "",
                number_of_children: "",
                children_in_household: "",
                children_affected: "",
            },
            profile_groups: [],
            profile_questions: [],
        };
    },
    mounted: function () {
        axios
            .get("/api/profile/" + this.user_id + "/init")
            .then((response) => {
                const {
                    user,
                    profile_questions,
                    groups,
                    mourning,
                    reason,
                    info,
                } = response.data;

                this.user = user;
                this.profile_info = { ...info };
                this.profile_questions = profile_questions.sort(
                    (a, b) => a.position - b.position
                );

              this.profile_reason = {
                ...this.profile_reason,
                text: mourning.text,
                death: reason.death_reason,
                relation: reason.death_relation,
                year: JSON.parse(reason.death_date).year,
              };

                this.profile_groups = groups;
                this.hasLoaded = true;
            })
            .catch((err) => {
                console.log(err);
            });
    },
    computed: {
        toggleGroups() {
            if (this.hasLoaded) {
                return Object.keys(this.profile_groups).length;
            }
            return false;
        },
        toggleReason() {
            if (this.hasLoaded) {
                return this.profile_reason.relation !== "";
            }
            return false;
        },
        toggleQuestions() {
            if (this.hasLoaded) {
                return this.profile_questions.length > 0;
            }
            return false;
        },
    },
    methods: {
        reportProfile() {
            this.$eventBus.emit("modal-requested", {
                component: ReportProfile,
                props: {
                    userId: this.user.id,
                    type: "profile",
                    itemId: this.id,
                },
            });
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../../../sass/setup/variables";

#foreign-profile {
    .profile {
        display: grid;
        grid-template-areas: "avatar avatar info info info" "action action action action action";
        padding: 1rem;
        margin-bottom: 1rem;

        @media only screen and (min-width: 768px) {
            grid-template-areas:
                "avatar info info info info"
                "avatar action action action action";
        }

        &__avatar {
            grid-area: avatar;

            .avatar {
                margin: 0 auto;
            }

            .btn--report {
                text-align: center;
                width: 100%;
                font-size: 12px;
            }
        }

        &__info {
            grid-area: info;
            display: flex;
            flex-direction: column;

            .identifier {
                color: $brand-color-primary;
            }
        }

        &__action {
            grid-area: action;
            .user-actions {
            }
        }
    }
}

.user-banner {
    padding: 0.625rem;
    display: flex;

    .avatar {
        width: 6rem;
        height: 6rem;
        margin-right: 0.625rem;

        @media (max-width: 360px) {
            width: 4.2rem;
            height: 4.2rem;
        }
    }

    .user-info {
        flex: 1;
        color: $dark-grey-text;

        .identifier,
        .location {
            line-height: 1rem;
        }

        .identifier {
            color: $brand-color-primary;
            font-weight: 500;
        }

        @media screen and (min-width: 768px) {
            //padding-left: 16%;
        }
    }

    & > .user-actions {
        display: flex;
        flex-direction: column;

        & ::v-deep {
            & .icon {
                font-size: 1.2rem;
                margin-right: 1rem;
            }

            & .icon + span {
                font-size: 0.8rem;
                color: $dark-grey-text;
                display: block;
                margin-bottom: 0.2rem;
            }
        }
    }

    .membership {
        display: flex;
        @media screen and (min-width: 768px) {
            position: absolute;
            right: 40px;
            top: 10px;
        }
    }
}

.motto {
    @media screen and (min-width: 768px) {
        margin-left: 16%;
    }
}
</style>
