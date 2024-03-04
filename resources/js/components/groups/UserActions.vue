<template>
  <div
      class="user-actions user-actions--2x2"
      v-if="user.is_mourner && isAllowedTo('view user_actions')"
  >
    <FriendshipAction
        v-if="!user.is_blocked"
        class="btn-friendship btn btn-primary w-100 mt-1 mt-lg-0 mb-1 font-weight-not-bold"
        :showLabels="showLabel"
        :user="user"
    >
      <span v-if="showLabel">Trauerfreunde</span>
    </FriendshipAction>
    <DenyFriendshipAction
        class="btn btn-outline-primary w-100 mt-1 mt-lg-0 mb-1 font-weight-not-bold"
        v-if="isReceivingRequest(user.id) && !user.is_blocked"
        :user-id="user.id"
    ></DenyFriendshipAction>
    <button
        class="btn btn-outline-primary w-100 mt-1 mt-lg-0 mb-1 font-weight-not-bold"
        @click="onMessageClick"
    >
      <fa-icon class="icon" icon="envelope"></fa-icon>
      <span v-if="showLabel">Nachricht</span>
    </button>
    <IconWatchlist
        :user-id="user.id"
        :user-name="user.nickname"
        :is-watched="user.is_watched"
    >
      <span v-if="showLabel">Merken</span>
    </IconWatchlist>
    <IconBlocklist
        class="btn btn-outline-primary w-100 mt-1 mt-lg-0 mb-1 font-weight-not-bold py-1 px-3"
        :user-id="user.id"
        :user-name="user.nickname"
        :is-blocked="user.is_blocked"
        :show-label="showLabel"
    />
  </div>
</template>

<script>
const FRIENDREQUEST_SEND = 0;
const FRIENDREQUEST_RECEIVED = 1;
const BEFRIENDED = 2;
const UNKNOWN = 3;

import QuickMessage from "./QuickMessage";
import FriendshipAction from "../action_icons/FriendshipAction";
import DenyFriendshipAction from "../action_icons/DenyFriendshipAction";
import IconWatchlist from "../action_icons/IconWatchlist";
import IconBlocklist from "../action_icons/IconBlocklist";
import InformationMessage from "./InformationMessage";
import {mapGetters} from "vuex";

export default {
  name: "UserActions",
  components: {
    FriendshipAction,
    IconWatchlist,
    IconBlocklist,
    DenyFriendshipAction,
  },
  props: {
    user: Object,
    showLabel: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    ...mapGetters("currentUser", {
      currentUser: "getObject",
      isAllowedTo: "isAllowedTo"
    }),
    ...mapGetters("partner", {
      isActivePartner: "isActivePartner",
      isWaitingForAcceptance: "isWaitingForAcceptance",
      isReceivingRequest: "isReceivingRequest",
    }),
  },
  methods: {
    isPremium() {
      return axios
          .get("/api/user/premium")
          .then(({data}) => data.isPremium)
          .catch((err) => {
            console.error(err);
          });
    },
    writeMessage() {
      this.$eventBus.emit("modal-requested", {
        component: QuickMessage,
        props: {
          key: this.user.id,
          userId: this.user.id,
          recipient: this.user.name,
        },
      });
    },
    async onMessageClick() {
      if (!(await this.isPremium())) {
        this.$router.push({name: "premium"});
      }
      if (
          this.isActivePartner(this.user.id) ||
          this.isWaitingForAcceptance(this.user.id)
      ) {
        this.goToChat();
      } else {
        this.showMessage();
      }
    },
    showMessage() {
      //TODO: Do the same with the vue component? faUserPlus
      const icon =
          '<svg style="margin: 0 1px" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user-plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="icon svg-inline--fa fa-user-plus fa-w-20 color--primary"><path fill="currentColor" d="M624 208h-64v-64c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v64h-64c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h64v64c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-64h64c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm-400 48c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z" class=""></path></svg>';
      this.$eventBus.emit("modal-requested", {
        component: InformationMessage,
        props: {
          title: "Nachricht senden",
          messageClass: "d-inline-block",
          message:
              "Nur Trauerfreunde können einander Nachrichten senden. Um ein Mitglied als Trauerfreund hinzuzufügen, klicke auf das " +
              icon +
              " Icon",
        },
      });
    },
    goToChat() {
      this.$router.push({
        name: "chat",
        params: {
          pre_selected_user: this.user,
        },
      });
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.user-actions {
  @media screen and (min-width: 768px) {
    display: flex;
    justify-content: space-between;
    button {
      margin-right: 1rem;
      width: auto;

      &.btn.mb-1 {
        @media screen and (max-width: 767px) {
          margin-bottom: 0.7rem !important;
        }
      }

      &:last-child {
        margin: 0;
      }
    }
  }

  .btn.mb-1 {
    @media screen and (max-width: 767px) {
      margin-bottom: 0.7rem !important;
    }
  }

  &--2x2 {
    margin-top: 1rem;
    @media screen and (min-width: 768px) {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      ::v-deep {
        button {
          width: 49%;
          margin: 0;
        }
      }
    }
  }

  &--1x3 {
    margin-top: 1rem;
    @media screen and (min-width: 768px) {
      ::v-deep {
        button {
          &:first-child {
            flex: inherit;
          }

          flex: 1;
        }
      }
    }
  }
}

.btn-primary {
  border-radius: 4px;
}

.btn-outline-primary {
  color: $brand-color-primary;
  border: 2px solid $brand-color-primary;
  border-radius: 4px;

  &:hover {
    color: #ffffff;
  }
}
</style>
