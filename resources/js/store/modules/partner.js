import Repository from "../../repositories/RepositoryFactory";
import PartnerHelpers from "../../helpers/PartnerHelpers";
import {
    BEFRIENDED,
    FRIENDREQUEST_SEND,
    FRIENDREQUEST_RECEIVED,
    UNKNOWN,
} from "../../helpers/PartnerHelpers";
const PartnerRepository = Repository.get("partners");

const state = () => ({
    allPartners: undefined,
    activePartners: [],
    rejectedPartners: [],
    unreadFriendRequestCount: 0,
});

const actions = {
    async init({ commit }) {
        let countRequests = await PartnerRepository.getFriendRequestCount();
        commit("setUnreadFriendRequestCount", countRequests);

        let partners = await PartnerRepository.getAll();
        partners = PartnerHelpers.reducePartnerList(partners);
        commit("setPartnerList", partners);
    },
    unshift({ state, commit }, partnerId) {
        const list = state.allPartners.filter((p) => p.id !== partnerId);
        const partner = state.allPartners.find((p) => p.id === partnerId);
        list.unshift(partner);
        commit("setPartnerList", list);
    },
    /**
     * Adds the user to the partner list.
     *
     * @param state
     * @param commit
     * @param dispatch
     * @param partner
     */
    add({ state, commit, dispatch }, partner) {
        if (state.allPartners.find((p) => p.id === partner.id)) return;
        const list = state.allPartners;
        list.unshift(partner);
        commit("setPartnerList", list);
    },
    accept({ state, commit, dispatch }, { partnerId, message }) {
        return axios
            .put("/api/user/friend/request/accept/" + partnerId, {
                message,
            })
            .then(({ data }) => {
                if (data.success) {
                    dispatch("changeStatus", {
                        userId: partnerId,
                        friend_status: BEFRIENDED,
                    });
                    this.dispatch("chat/addMessage", {
                        partnerId,
                        ...data.message,
                    });
                    this.dispatch("matomo/partnerAccepted");
                }
                return partnerId;
            });
    },
    invite({ state, commit, dispatch }, { partnerId, message }) {
        return axios
            .post("/api/user/friend/request/send/" + partnerId, {
                message,
            })
            .then(({ data }) => {
                if (data.success) {
                    dispatch("add", data.partner);
                    dispatch("changeStatus", {
                        userId: partnerId,
                        friend_status: FRIENDREQUEST_SEND,
                    });
                    this.dispatch("chat/addMessage", {
                        partnerId,
                        ...data.message,
                    });
                    this.dispatch("matomo/partnerInvited");
                }
                return partnerId;
            });
    },
    remove({ state, commit, dispatch }, { partnerId, message }) {
        return axios
            .put("/api/user/friend/list/remove/" + partnerId, {
                message,
            })
            .then(({ data }) => {
                if (data.success) {
                    dispatch("changeStatus", {
                        userId: partnerId,
                        friend_status: UNKNOWN,
                    });
                    if (data.message) {
                        this.dispatch("chat/addMessage", {
                            partnerId,
                            ...data.message,
                        });
                    }
                    this.dispatch("matomo/partnerUnfriended");
                }
                return partnerId;
            });
    },
    retract({ state, commit, dispatch }, { partnerId, message }) {
        return axios
            .put("/api/user/friend/request/retract/" + partnerId, {
                message,
            })
            .then(({ data }) => {
                if (data.success) {
                    dispatch("changeStatus", {
                        userId: partnerId,
                        friend_status: UNKNOWN,
                    });
                    this.dispatch("chat/addMessage", {
                        partnerId,
                        ...data.message,
                    });
                    this.dispatch("matomo/partnerRetracted");
                }
                return partnerId;
            });
    },
    deny({ state, commit, dispatch }, { partnerId, message }) {
        return axios
            .put("/api/user/friend/request/deny/" + partnerId, {
                message,
            })
            .then(({ data }) => {
                if (data.success) {
                    dispatch("changeStatus", {
                        userId: partnerId,
                        friend_status: UNKNOWN,
                    });
                    this.dispatch("chat/addMessage", {
                        partnerId,
                        ...data.message,
                    });
                    this.dispatch("matomo/partnerRefused");
                }
                return partnerId;
            });
    },
    /**
     * Changes the friend status of a specific user.
     *
     * @param state
     * @param commit
     * @param userId
     * @param friend_status
     */
    changeStatus({ state, commit }, { userId, friend_status }) {
        const list = state.allPartners;
        const index = list.findIndex((p) => p.id === userId);
        if (index !== -1) {
            list[index].friend_status = friend_status;
            commit("setPartnerList", list);
        }
    },
    extract({ state, commit }, partnerId) {
        const list = state.allPartners.filter((p) => p.id !== partnerId);
        commit("setPartnerList", list);
    },
    /**
     * This should just be called when data is coming
     * in from the Websocket. More concrete: User A click e.g. "deny partner"
     * the frontend of User B has to be changed as well.
     *
     * Check the UserController you will find:
     * - retractFriendRequest
     * - denyFriendRequest
     * - removeFriend
     *
     * @param state
     * @param commit
     * @param dispatch
     * @param rootGetters
     * @param statusAndUserIds
     */
    statusChanged({ state, commit, rootGetters, dispatch }, statusAndUserIds) {
        const friendStatus = statusAndUserIds.status;
        delete statusAndUserIds.status;
        const currentUserId = rootGetters["currentUser/getId"];
        const partnerId = PartnerHelpers.getOtherUserId(
            currentUserId,
            statusAndUserIds
        );
        dispatch("changeStatus", {
            userId: partnerId,
            friend_status: friendStatus,
        });
    },
    async fetchUnreadFriendRequestCount({commit}) {
        let countRequests = await PartnerRepository.getFriendRequestCount();
        commit("setUnreadFriendRequestCount", countRequests);
    }
};

const getters = {
    allPartners: (state) => {
        return state.allPartners;
    },
    friendStatus: (state) => (partnerId) => {
        const partner = state.allPartners
            ? state.allPartners.find((p) => {
                  return p.id === partnerId && p.nickname !== null;
              })
            : null;
        return partner ? partner.friend_status : null;
    },
    name: (state) => (partnerId) => {
        const partner = state.allPartners
            ? state.allPartners.find((p) => {
                  return p.id === partnerId && p.nickname !== null;
              })
            : null;
        return partner ? partner.nickname : null;
    },
    activePartners: (state) => {
        return state.allPartners
            ? state.allPartners.map((p) => p.status === BEFRIENDED)
            : [];
    },
    rejectedPartners: (state) => {
        return state.allPartners.map((p) => p.status === UNKNOWN);
    },
    isActivePartner: (state) => (id) => {
        return state.allPartners
            ? state.allPartners.find((p) => {
                  return (
                      p.id === id &&
                      p.friend_status === BEFRIENDED &&
                      p.nickname !== null
                  );
              })
            : null;
    },
    isOpenPartnership: (state) => (id) => {
        return state.allPartners
            ? state.allPartners.find((p) => {
                  return (
                      p.id === id &&
                      (p.friend_status === FRIENDREQUEST_SEND ||
                          p.friend_status === FRIENDREQUEST_RECEIVED) &&
                      p.nickname !== null
                  );
              })
            : null;
    },
    isWaitingForAcceptance: (state) => (id) => {
        return state.allPartners
            ? state.allPartners.find((p) => {
                  return (
                      p.id === id &&
                      p.friend_status === FRIENDREQUEST_SEND &&
                      p.nickname !== null
                  );
              })
            : null;
    },
    isReceivingRequest: (state) => (id) => {
        return state.allPartners
            ? state.allPartners.find((p) => {
                  return (
                      p.id === id &&
                      p.friend_status === FRIENDREQUEST_RECEIVED &&
                      p.nickname !== null
                  );
              })
            : null;
    },
    isRejectedPartner: (state) => (id) => {
        return state.allPartners
            ? state.allPartners.find((p) => {
                  return (
                      p.id === id &&
                      p.friend_status === UNKNOWN &&
                      p.nickname !== null
                  );
              })
            : null;
    },
    partnerWasDeleted: (state) => (id) => {
        return state.allPartners
            ? state.allPartners.find((p) => {
                  return p.id === id && p.nickname === null;
              })
            : null;
    },
    getUnreadFriendRequestCount(state) {
        return state.unreadFriendRequestCount;
    },
};

const mutations = {
    setPartnerList(state, partners) {
        state.allPartners = partners;
    },
    setUnreadFriendRequestCount(state, unreadsFriendRequestCount) {
        state.unreadFriendRequestCount = unreadsFriendRequestCount;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
