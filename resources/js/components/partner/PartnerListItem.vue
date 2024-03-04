<template>
    <div class="partner-wrapper">
        <div v-if="isNew" class="new-partner-flag">Neu</div>
        <div class="partner-card d-flex flex-column p-0625">
            <div class="d-flex flex-row">
                <user-avatar :user="partner" class="me-2 flex-shrink-0" />

                <div class="user-info d-flex flex-column flex-fill">
                    <user-identifier
                        class="username font-weight-not-bold color--primary"
                        :user="partner"
                    />
                    <user-location :user="partner" />
                </div>
            </div>
            <p class="mt-2 font-italic pe-sm-2 mt-3 m-0" v-if="partner.motto">
                {{ partner.motto }}
            </p>
            <chat-bubble
                v-if="partnerHasChatMessage"
                :message="partner.friend_request.chat_message"
            ></chat-bubble>
            <div class="partner-item-action">
                <div
                    class="btn btn-outline-primary w-100 partner-item-action-btn"
                    @click="onViewProfile"
                >
                    <fa-icon class="icon" icon="id-card-alt"></fa-icon>
                    Zum Profil
                </div>
                <DenyFriendshipAction
                    class="btn btn-outline-transparent w-100 partner-item-action-btn"
                    v-if="isReceivingRequest(partner.id) && !partner.is_blocked"
                    :user-id="partner.id"
                ></DenyFriendshipAction>
                <FriendshipAction
                    v-if="!partner.is_blocked"
                    :show-labels="true"
                    :user="partner"
                    class="btn btn-primary text-white w-100 partner-item-action-btn"
                >
                </FriendshipAction>
                <IconBlocklist
                    class="btn btn-outline-transparent w-100 partner-item-action-btn"
                    :user-id="partner.id"
                    :user-name="partner.nickname"
                    :is-blocked="partner.is_blocked"
                />
            </div>
        </div>
        <div class="bg-white p-0 px-2 py-1 text-right color--blue-200">
            Trauer um {{ partner.mourning_text }}
        </div>
        <hr class="m-0" />
        <div
            v-if="partner.death_reason !== null"
            class="bg-white p-0 px-2 py-1 text-right color--blue-200"
        >
            {{
                (partner.death_reason.startsWith("Tod ") ? "" : "Todesart: ") +
                partner.death_reason
            }}
        </div>
    </div>
</template>

<script>
import UserInfo from "../groups/UserInfo";
import UserAvatar from "../UserAvatar";
import UserIdentifier from "../UserIdentifier";
import UserActions from "../groups/UserActions";
import UserLocation from "../UserLocation";
import FriendshipAction from "../action_icons/FriendshipAction";
import DenyFriendshipAction from "../action_icons/DenyFriendshipAction";
import IconWatchlist from "../action_icons/IconWatchlist";
import ChatBubble from "../chat/ChatBubble";
import MembershipBadge from "../shared_profile_components/MembershipBadge";
import IconBlocklist from "../action_icons/IconBlocklist";

import { mapGetters } from "vuex";

export default {
    name: "PartnerListItem",
    components: {
        ChatBubble,
        FriendshipAction,
        DenyFriendshipAction,
        IconWatchlist,
        UserLocation,
        UserActions,
        UserIdentifier,
        UserAvatar,
        UserInfo,
        MembershipBadge,
        IconBlocklist,
    },
    props: {
        // The resource does not have to be user object. It can be whatever you want.
        // If the resource isn't a user, specify where the user object can be found
        // inside the resource using partnerAttrName.
        resource: Object,
        partnerAttrName: String,
        isNew: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters("partner", {
            isReceivingRequest: "isReceivingRequest",
        }),
        partner() {
            // If no partnerAttrName is provided then the resource is the partner... hopefully
            if (!this.partnerAttrName) {
                return this.resource;
            }

            // else check if the partnerAttrName exists on the resource and return it.
            if (this.resource.hasOwnProperty(this.partnerAttrName)) {
                return this.resource[this.partnerAttrName];
            }
        },
        partnerHasFriendRequest() {
            return (
                this.partner.friend_status === 1 &&
                this.partner.hasOwnProperty("friend_request") &&
                this.partner.friend_request !== null
            );
        },
        partnerHasChatMessage() {
            return (
                this.partnerHasFriendRequest &&
                this.partner.friend_request.hasOwnProperty("chat_message") &&
                this.partner.friend_request.chat_message
            );
        },
    },
    methods: {
        onViewProfile() {
            localStorage.setItem("viewingProfile", JSON.stringify(true));
            this.$router.push({
                name: "foreignProfile",
                params: { user_id: this.partner.id },
            });
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.partner-wrapper {
    background-color: $brand-color-base;
    border-radius: $border-radius;
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
    //box-shadow: 0 0 3px 0 rgb(0, 0, 0, 0.5);

    & .new-partner-flag {
        background-color: $brand-color-highlight;
        color: $brand-color-base;
        position: absolute;
        z-index: 12;
        transform: rotate(-45deg);
        width: 4.8rem;
        text-align: center;
        text-transform: uppercase;
        top: -0.4rem;
        left: -1.5rem;
        font-size: 0.75rem;
        padding: 1rem 0.5rem 0.25rem 0.5rem;
    }

    & > .partner-card {
        & > .link {
            font-size: 2.5rem;
        }
    }

    & .relation,
    & .reason {
        min-height: 1.2rem;
        margin-top: 0.1rem;
        color: $brand-color-base;
        background-color: $brand-color-highlight;
        padding: 0.2rem 0.625rem;
        text-align: right;
    }

    .p-0625 {
        padding: 0.625rem;
    }

    & .avatar {
        margin: 0;
    }

    & .action {
        display: inline-block;
    }

    & > :last-child {
        border-bottom-left-radius: $border-radius;
        border-bottom-right-radius: $border-radius;
    }

    .user-info {
        overflow: hidden;

        .username {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .membership {
            position: relative;
            display: flex;
            @media screen and (min-width: 768px) {
                position: absolute;
                top: 10px;
                right: 10px;
            }
        }
    }

    .partner-item-action {
        display: inline-flex;
        flex-wrap: wrap;
        margin: 0 0 0 -10px;
        width: calc(100% + 10px);
        &-btn {
            min-width: unset;
            font-weight: 400;
            width: calc(100% - 10px);
            margin: 10px 0 0 10px;
            @media screen and (min-width: 992px) {
                width: calc(50% - 10px);
            }
        }
    }
}

.new-friend-request {
    border-bottom: 2.5px $brand-color-primary solid;
    font-weight: 500;
}
</style>
