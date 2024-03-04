<template>
    <button @click="trigger" :class="theFriendStatus.class">
        <fa-icon :icon="theFriendStatus.icon" class="icon"></fa-icon
        ><span v-if="showLabels"> {{ theFriendStatus.label }}</span>
    </button>
</template>

<script>
import ActionIcon from "./ActionStatusIcon";
import SendFriendRequest from "../groups/SendFriendRequest";
import ReactToFriendRequest from "../groups/ReactToFriendRequest";
import RemoveFriend from "../groups/RemoveFriend";
import RetractFriendRequest from "../groups/RetractFriendRequest";
import { mapGetters } from "vuex";

const FRIENDREQUEST_SEND = 0;
const FRIENDREQUEST_RECEIVED = 1;
const BEFRIENDED = 2;
const UNKNOWN = 3;

export default {
    name: "FriendshipAction",
    components: { ActionIcon },
    props: {
        showLabels: Boolean,
        user: Object,
    },
    computed: {
        theFriendStatus: {
            get: function () {
                let info = {};
                switch (this.partnerStatus(this.user.id)) {
                    case FRIENDREQUEST_SEND:
                        info.icon = "user-clock";
                        info.label = "Anfrage zurückziehen";
                        info.class = "";
                        break;
                    case FRIENDREQUEST_RECEIVED:
                        info.icon = "user-tag";
                        info.label = "Anfrage annehmen";
                        info.class = "";
                        break;
                    case BEFRIENDED:
                        info.icon = "user-minus";
                        info.label = "Partner entfernen";
                        break;
                    default:
                        info.icon = "user-plus";
                        info.label = "Anfrage senden";
                        info.class = "";
                        break;
                }

                return info;
            },
        },
        ...mapGetters("partner", {
            partnerStatus: "friendStatus",
            partnerName: "name",
        }),
    },
    methods: {
        async trigger() {
          const {data} = await axios.get("/api/user/premium")
            if (!data.isPremium) {
              this.$router.push({ name: "premium" });
            }

            const props = {
                userId: this.user.id,
                userName: this.user.nickname,
            };
            switch (this.partnerStatus(this.user.id)) {
                case FRIENDREQUEST_SEND:
                    this.$eventBus.emit("modal-requested", {
                        component: RetractFriendRequest,
                        props,
                    });
                    this.$eventBus.emit("retract_friend_request", {
                        props,
                    });
                    break;
                case FRIENDREQUEST_RECEIVED:
                    this.$eventBus.emit("modal-requested", {
                        component: ReactToFriendRequest,
                        props,
                    });
                    break;
                case BEFRIENDED:
                    this.$eventBus.emit("modal-requested", {
                        component: RemoveFriend,
                        props,
                    });
                    break;
                default:
                    this.$eventBus.emit("modal-requested", {
                        component: SendFriendRequest,
                        props,
                    });
                    break;
            }
        },
    },
};
</script>

<style scoped></style>
