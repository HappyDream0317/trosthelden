<template>
    <default-layout>
        <button
            class="d-lg-none d-xl-none btn btn-primary w-100 mb-2"
            type="button"
            @click="openContactPicker"
        >
            Kontakt auswählen
        </button>
        <div class="container">
            <div>
                <div class="wrapper d-flex align-items-start rounded-box">
                    <div class="contacts h-100">
                        <contacts class="w-100"></contacts>
                    </div>
                    <div class="chat-window">
                        <template v-if="activeChatPartner === null">
                            <p
                                class="d-flex flex-column flex-md-row justify-content-center text-center align-items-center h-100"
                                style="margin-top: 1rem"
                            >
                                Wähle einen Kontakt aus, um eine Konversation zu
                                starten.
                            </p>
                        </template>
                        <template v-else-if="!chatIsLoading">
                            <div
                                v-if="activeChatPartner"
                                class="user shadow-sm"
                            >
                                <div class="user-identifier px-3">
                                    <user-identifier
                                        :with-link="true"
                                        :user="activeChatPartner"
                                    ></user-identifier>
                                </div>

                                <div class="user-actions p-2">
                                    <FriendshipAction
                                        class="btn btn-sm btn-primary mb-1"
                                        :show-labels="true"
                                        :user="activeChatPartner"
                                    ></FriendshipAction>

                                    <DenyFriendshipAction
                                        class="btn btn-sm btn-outline-transparent mb-1"
                                        v-if="
                                            isReceivingRequest(
                                                activeChatPartner.id
                                            ) && !activeChatPartner.is_blocked
                                        "
                                        :user-id="activeChatPartner.id"
                                    ></DenyFriendshipAction>

                                    <GoToProfileAction
                                        class="btn btn-sm btn-outline-transparent"
                                        :user-id="activeChatPartner.id"
                                    >
                                    </GoToProfileAction>
                                    <IconBlocklist
                                        class="btn btn-sm btn-outline-transparent"
                                        :user-id="activeChatPartner.id"
                                        :user-name="activeChatPartner.nickname"
                                        :is-blocked="
                                            activeChatPartner.is_blocked
                                        "
                                    />
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div
                                class="d-flex justify-content-center text-center align-items-center w-100 h-100"
                            >
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden-focusable">Lädt...</span>
                                </div>
                            </div>
                        </template>

                        <template v-if="activeChatPartner">
                            <Log
                                v-show="!chatIsLoading"
                                ref="log"
                                class="log"
                            ></Log>
                            <div
                                class="input-wrapper"
                                :class="
                                    !disableInput &&
                                    'position-relative'
                                "
                            >
                                <MessageInput
                                    :disabled="disableInput"
                                    class="input"
                                    @newMessage="updateMessages($event)"
                                ></MessageInput>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </default-layout>
</template>

<script>
import MessageInput from "../components/chat/MessageInput";
import Log from "../components/chat/Log";
import PageHeader from "../components/PageHeader";
import DefaultLayout from "../layouts/DefaultLayout";
import Contacts from "../components/chat/Contacts";
import SelectContactModal from "../components/SelectContactModal";
import UserLocation from "../components/UserLocation";
import AvatarPlaceholder from "../components/AvatarPlaceholder";
import UserIdentifier from "../components/UserIdentifier";
import UserTopic from "../components/UserTopic";
import moment from "moment";
import FriendshipAction from "../components/action_icons/FriendshipAction";
import DenyFriendshipAction from "../components/action_icons/DenyFriendshipAction";
import BlockBanner from "../components/premium/BlockBanner";
import { mapGetters, mapState } from "vuex";

export default {
    name: "Chat",
    components: {
        Contacts,
        DefaultLayout,
        PageHeader,
        Log,
        MessageInput,
        UserLocation,
        UserTopic,
        AvatarPlaceholder,
        UserIdentifier,
        FriendshipAction,
        DenyFriendshipAction,
        BlockBanner,
    },
    props: {
        pre_selected_user: {
            type: Object,
            default: null,
        },
    },
    data: () => ({
        users: [],
        selected_user: null,
        chatIsLoading: false,
        contactChannel: null,
    }),
    created() {
        if (this.pre_selected_user !== null) {
            this.selectContact(this.pre_selected_user);
        }
    },
    mounted: function () {
        this.$store.dispatch("chat/enter", {
            ref: this.$refs,
        });

        this.$eventBus.on("selected-contact", (contact) => {
            this.selectContact(contact);
        });

        this.$eventBus.on("blocklist-added", (event) => {
            if (event.userId === this.activeChatPartnerId) {
                this.$store.dispatch("chat/leave");
            }
        });
    },
    beforeDestroy() {
        this.$eventBus.off("selected-contact");
        this.$eventBus.off("blocklist-added");
        this.$store.dispatch("chat/leave");
    },
    methods: {
        loadChat() {
            const partner = this.contacts.find(
                (p) => p.id === this.activeChatPartnerId
            );

            this.chatIsLoading = true;
            axios
                .get("/api/chat/info/" + partner.id)
                .then((response) => {
                    this.chatIsLoading = false;

                    const messages = response.data.history.reverse(
                        (a, b) => new moment(a).unix() - new moment(b).unix()
                    );

                    const lastMessage = messages.pop();
                    const lastMessageDate = Date.parse(lastMessage.send_at);
                    const mergedMessages = [...messages, ...[lastMessage]];

                    this.$store.dispatch("chat/selectChat", {
                        chatId: response.data.chat_id,
                        partner: partner,
                        lastMessageDate,
                        messages: mergedMessages,
                    });

                    if (this.$refs && this.$refs.log)
                        this.$refs.log.scrollToBottom();
                })
                .catch((err) => {
                    console.error(err);
                });
        },
        async selectContact(partner) {
            await this.$store.dispatch("chat/setActiveChatPartner", partner);
        },
        openContactPicker() {
            this.$eventBus.emit("modal-requested", {
                component: SelectContactModal,
            });
        },
        updateMessages(message) {
            console.log("updateMessages");
            console.log(message);
            this.$store.dispatch("chat/addMessage", message);
        },
    },
    computed: {
        ...mapGetters("currentUser", {
            currentUserId: "getId",
            isPremium: "isPremium",
        }),
        ...mapGetters("chat", {
            activeChatPartner: "activeChatPartner",
            activeChatPartnerId: "activeChatPartnerId",
        }),
        ...mapGetters("partner", {
            contacts: "allPartners",
            isActivePartner: "isActivePartner",
            isOpenPartnership: "isOpenPartnership",
            isReceivingRequest: "isReceivingRequest",
            isWaitingForAcceptance: "isWaitingForAcceptance",
            partnerWasDeleted: "partnerWasDeleted",
            isRejectedPartner: "isRejectedPartner",
        }),
        ...mapGetters("blockList", {
            isBlockedUser: "isBlockedUser",
        }),
        disableInput() {
            if (!this.activeChatPartner) {
                return "Wähle einen Kontakt aus, um mit jemandem zu schreiben.";
            }

            if (this.chatIsLoading) {
                return "Die Konversation wird geladen...";
            }

            if (this.activeChatPartner) {
                const userId = this.activeChatPartner.id;

                if (this.partnerWasDeleted(userId)) {
                    return "Ups. Dieses Mitglied ist nicht mehr Teil der TrostHelden-Community. Schau doch gleich in deinen Matches, ob du einen neuen Trauerfreund findest.";
                }

                if (this.isWaitingForAcceptance(userId)) {
                    return `${this.activeChatPartner.nickname} muss zuerst deine Trauerfreundanfrage annehmen.`;
                }

                if (this.isReceivingRequest(userId)) {
                    return "Diese Trauerfreund-Anfrage beruht auf Gegenseitigkeit? Dann bestätige sie – und schon steht der direkten Kommunikation nichts mehr im Wege!";
                }

                if (this.isRejectedPartner(userId)) {
                    return "Nicht immer besteht ein gegenseitiges Interesse an einer Trauerfreundschaft. Schau doch gleich in deiner Matching-Übersicht nach einem neuen Trauerfreund.";
                }
            }

            return null;
        },
    },
    watch: {
        activeChatPartnerId(value) {
            if (value) this.loadChat();
        },
    },
};
</script>

<style lang="scss" scoped>
.wrapper {
    background-color: #ffffff;
    min-height: 525px;
    height: auto;
    display: flex;
    flex-direction: row;
    //overflow: hidden;

    @media (max-width: 992px) {
        flex-direction: column;
    }

    .contacts {
        display: none;

        @media (min-width: 992px) {
            display: flex;
            flex-basis: 30%;
        }

        ::v-deep .contact-list {
            background-color: unset;
        }
    }

    .chat-window {
        display: flex;
        flex: 1 1 100%;
        flex-direction: column;
        min-height: 525px;

        height: 100%;
        width: 100%;
        border-left: 10px solid #f0f0f0;

        .user-info {
            height: 50px;
            z-index: 1;
            background-color: #f0f0f0;
            /*box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
                0 3px 1px -2px rgba(0, 0, 0, 0.12),
                0 1px 5px 0 rgba(0, 0, 0, 0.2); */
        }

        .avatar {
            width: 50px;
            height: 50px;
        }

        .user {
            display: flex;
            flex-direction: column;
            @media screen and (min-width: 768px) {
                flex-direction: row;
            }

            &-identifier {
                a {
                    color: #000000 !important;

                    &:hover {
                        color: #000000 !important;
                    }
                }
                width: 100%;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: flex;
                align-items: center;
                @media screen and (min-width: 768px) {
                    min-height: 100%;
                    width: 40%;
                }
            }
            &-actions {
                width: 100%;
                button {
                    width: 100%;
                    margin-bottom: 0.25rem;
                }
                @media screen and (min-width: 768px) {
                    width: 60%;
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-between;
                    button {
                        width: calc(50% - (0.25rem / 2));
                        margin: 0;
                    }
                }
            }
        }

        &-actions {
            @media screen and (min-width: 768px) {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                button {
                    width: 49%;
                    margin: 0;
                }
            }
        }
        &-actions {
            @media screen and (min-width: 768px) {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                button {
                    width: 49%;
                    margin: 0;
                }
            }
        }

        @media (min-width: 992px) {
            flex-basis: 70%;
        }

        & > .log {
            flex: 1;
        }

        > .input-wrapper .input {
            background-color: #f0f0f0;
        }
    }
}
</style>
