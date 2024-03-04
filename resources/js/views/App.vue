<template>
  <router-view></router-view>
</template>
<script>
import {BEFRIENDED} from "../helpers/PartnerHelpers";
import {checkTraceableParams, updateReferrerCookie} from "../referrer";
import {mapGetters} from "vuex";

export default {
  computed: {
    ...mapGetters("currentUser", {
      isBusinessAccount: "isBusinessAccount"
    })
  },
  data() {
    return {
      interval: null,
      // hotfixInterval: null,
      newMessages: null,
      newTrostpartner: null,
      partnerAccepted: null,
      statusChanged: null,
      friendRequestChanged: null
    };
  },
  created() {
    this.handelReferrer();
    this.$eventBus.on("profile-init", ({userId}) => {
      this.partnerInit();
      this.blockListInit();
      this.chatInit();
      this.handelLastSeen(userId);
      this.subscribeToMessages(userId);
      this.subscribeToNewTrostpartner(userId);
      this.subscribeToFriendshipAccepted(userId);
      this.subscribeToPartnerStatusChanged(userId);
      this.subscribeToNewFriendshipRequest(userId);
      this.storyblokInit();
      this.b2bUserInit(userId);
    });
    this.$eventBus.on("logout", () => {
      this.cleanup();
      this.$router.push({ path: '/login'})
    });
  },
  methods: {
    partnerInit() {
      this.$store.dispatch("partner/init");
    },
    chatInit() {
      this.$store.dispatch("chat/init");
    },
    blockListInit() {
      this.$store.dispatch("blockList/init");
    },
    handelLastSeen(userId) {
      this.lastSeenInit(userId);
      document.addEventListener("visibilitychange", () => {
        if (document.hidden) {
          clearInterval(this.interval);
        } else {
          this.lastSeenInit();
          // Just to be sure all is working fine
          this.$store.dispatch("chat/init");
          this.$store.dispatch("partner/init");
        }
      });
    },
    lastSeenInit() {
      this.$store.dispatch("currentUser/setLastSeen");
      this.interval = setInterval(
          () => this.$store.dispatch("currentUser/setLastSeen"),
          60 * 1000
      );
    },
    storyblokInit() {
      this.$store.dispatch("storyblok/init");
    },
    subscribeToMessages(userId) {
      const channelName = "user_" + userId + "_chat_message";
      this.newMessages = window.Echo.channel(channelName);
      this.newMessages.listen("ChatMessageNew", (data) => {
        console.log("ChatMessageNew");
        const message = data.message;
        console.log(message);
        this.$store.dispatch("chat/newMessageIncomming", message);
        this.$store.dispatch("partner/unshift", message.user_id);
      });
    },
    subscribeToNewTrostpartner(userId) {
      const channelName = "user_" + userId + "_chat_contacts";
      this.newTrostpartner = window.Echo.channel(channelName);
      this.newTrostpartner.listen("NewChatContact", (data) => {
        console.log("NewChatContact");
        console.log(data);
        this.$store.dispatch("partner/add", {
          ...data.contact,
          friend_status: data.friend_status,
        });
      });
    },
    subscribeToFriendshipAccepted(userId) {
      const channelName = "user_" + userId + "_friendship_accepted";
      this.partnerAccepted = window.Echo.channel(channelName);
      this.partnerAccepted.listen(
          "FriendRequestConfirmation",
          (userIdAndPartnerId) => {
            console.log("FriendRequestConfirmation");
            console.log(userIdAndPartnerId);
            this.$store.dispatch("partner/statusChanged", {
              status: BEFRIENDED,
              ...userIdAndPartnerId,
            });
          }
      );
    },
    subscribeToPartnerStatusChanged(userId) {
      const channelName = "user_" + userId + "_partner_status_changed";
      this.statusChanged = window.Echo.channel(channelName);
      this.statusChanged.listen(
          "PartnerStatusChanged",
          (statusAndUserIds) => {
            console.log("PartnerStatusChanged");
            console.log(statusAndUserIds);
            this.$store.dispatch(
                "partner/statusChanged",
                statusAndUserIds
            );
          }
      );
    },
    subscribeToNewFriendshipRequest(userId) {
      const channelName = "user_" + userId + "_friend_requests_changed";
      this.friendRequestChanged = window.Echo.channel(channelName);
      this.friendRequestChanged.listen(
          "FriendRequestChanged",
          () => {
            console.log("FriendRequestChanged");
            this.$store.dispatch("partner/fetchUnreadFriendRequestCount");
          }
      );
    },
    cleanup() {
      this.$eventBus.off("profile-init");
      this.$eventBus.off("logout");
      if (this.newMessages) this.newMessages.unsubscribe();
      if (this.newTrostpartner) this.newTrostpartner.unsubscribe();
      if (this.partnerAccepted) this.partnerAccepted.unsubscribe();
      if (this.statusChanged) this.statusChanged.unsubscribe();
      if (this.friendRequestChanged) this.friendRequestChanged.unsubscribe();
      this.$store.dispatch("chat/shutdown");
    },
    handelReferrer() {
      let hasTraceable = checkTraceableParams();
      if (hasTraceable) updateReferrerCookie();
    },
    b2bUserInit(userId) {
      if(this.isBusinessAccount)
        this.$store.dispatch("b2bUser/init", { userId });
    }
  },
  beforeDestroy() {
    this.cleanup();
  },
};
</script>
<style scoped></style>
