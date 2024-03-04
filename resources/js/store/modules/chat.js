import Repository from "../../repositories/RepositoryFactory";
import moment from "moment";
const ChatRepository = Repository.get("chat");

const state = () => ({
    activeChat: null,
    activeChatPartner: null,
    lastMessageDate: null,
    unreadCount: 0,
    unreadCountPerChat: {},
    chats: {},
    viewRef: null,
    messages: [],
});

const actions = {
    async init({ commit }) {
        const unreadsPerChat = await ChatRepository.fetchUnreadByChat();
        commit("setUnreadChatCount", unreadsPerChat);
    },
    async setActiveChatPartner({ commit }, partner) {
        commit("setActiveChartPartner", partner);
    },
    selectChat({ commit, dispatch }, payload) {
        commit("resetMessages");
        commit("setActiveChat", payload.chatId);
        commit("setLastMessageDate", payload.lastMessageDate);
        commit("setMessages", payload);
        dispatch("markAsRead", payload);
        dispatch("reduceUnreadChatCount", payload.partner.id);
    },
    reduceUnreadChatCount({ commit, state }, partnertId) {
        const unreadsPerChat = { ...state.unreadCountPerChat };
        if (unreadsPerChat.hasOwnProperty(partnertId)) {
            delete unreadsPerChat[partnertId];
            commit("setUnreadChatCount", unreadsPerChat);
        }
    },
    increaseUnreadChatCount({ commit, state }, partnertId) {
        const unreadsPerChat = incrementUnreadCount(
            state.unreadCountPerChat,
            partnertId
        );
        commit("setUnreadChatCount", unreadsPerChat);
    },
    addMessage({ commit }, message) {
        commit("addMessageToChat", message);
        commit("scrollToBottom");
    },
    sendMessage(
        { state, commit, dispatch },
        { activeChat, message, partnerId }
    ) {
        return axios
            .post("/api/chat/" + activeChat + "/send", {
                message,
            })
            .then(({ data }) => {
                this.dispatch("partner/unshift", partnerId);
                this.dispatch("matomo/messageSent");
                return {...data};
            });
    },
    newMessageIncomming({ state, commit, dispatch }, message) {
        const partnerId = message.user_id; // its reverse now
        const activePartnerId = state.activeChatPartner
            ? state.activeChatPartner.id
            : null;

        dispatch("increaseUnreadChatCount", partnerId);

        if (activePartnerId && activePartnerId === partnerId) {
            commit("addMessageToChat", { partnerId, ...message });
            commit("scrollToBottom");
        }
    },
    enter({ commit }, payload) {
        commit("setViewRef", payload.ref);
    },
    leave({ commit }) {
        commit("resetActiveChat");
        commit("resetActiveChatPartner");
        commit("resetLastMessageDate");
        commit("resetViewRef");
        commit("resetMessages");
    },
    shutdown({ commit }) {
        commit("resetUnreadCounts");
        this.dispatch("leave");
    },
    updateMessagesAsReadInDB(_, { chatId, partnerId }) {
        return ChatRepository.updateMessagesAsReadInDB(chatId, partnerId);
    },
    friendshipAccepted({ commit }, contact) {
        commit("changeFriendshipStatus", contact);
    },
    async markAsRead({ commit, state, dispatch }, payload) {
        console.log("payload", payload);
        console.log("state", state);

        const chatId = state.activeChat;
        const partnerId = state.activeChatPartner.id;
        const messages = await dispatch("updateMessagesAsReadInDB", {
            chatId,
            partnerId,
        });
        dispatch("reduceUnreadChatCount", partnerId);
        commit("markMessagesAsReadInStore", messages);
    },
};

const getters = {
    activeChat: (state) => {
        return state.activeChat;
    },
    activeChatPartner: (state) => {
        return state.activeChatPartner;
    },
    activeChatPartnerId: (state) => {
        return state.activeChatPartner?.id || null;
    },
    chats: (state) => {
        return state.chats;
    },
    lastMessageDate: (state) => {
        return state.lastMessageDate;
    },
    messages: (state) => {
        if (!state.chats.hasOwnProperty(state.activeChatPartner.id)) return [];
        return state.chats[state.activeChatPartner.id];
    },
};

const mutations = {
    setMessages(state, payload) {
        state.messages = payload.messages;
    },
    addMessageToChat(state, msg) {
        if (
            state.activeChatPartner &&
            state.activeChatPartner.id === msg.partnerId
        ) {
            state.messages.push(msg);
        }
    },
    setUnreadChatCount(state, unreadsPerChat) {
        state.unreadCountPerChat = unreadsPerChat;
        state.unreadCount = summarizeMessageCount(unreadsPerChat);
    },
    setActiveChat(state, chatId) {
        state.activeChat = chatId;
    },
    resetActiveChat(state) {
        state.activeChat = null;
    },
    resetUnreadCounts(state) {
        state.unreadCount = 0;
        state.unreadCountPerChat = {};
    },
    resetMessages(state) {
        state.messages = [];
    },
    resetActiveChatPartner(state) {
        state.activeChatPartner = null;
    },
    setActiveChartPartner(state, partner) {
        state.activeChatPartner = { ...partner };
    },
    resetLastMessageDate(state) {
        state.lastMessageDate = null;
    },
    setLastMessageDate(state, lastMessageDate) {
        state.lastMessageDate = lastMessageDate;
    },
    setViewRef(state, viewRef) {
        state.viewRef = viewRef;
    },
    resetViewRef(state) {
        state.viewRef = null;
    },
    scrollToBottom(state) {
        if (state.viewRef && state.viewRef.log)
            state.viewRef.log.scrollToBottom();
    },
    markMessagesAsReadInStore(state, messages) {
        const oldStoreMessages = [...state.messages];
        if (oldStoreMessages.length && Object.values(messages).length) {
            state.messages = oldStoreMessages.map((msg) => {
                if (!messages.hasOwnProperty(msg.id)) return msg;
                return {
                    ...msg,
                    sent_at: messages[msg.id].sent_at,
                    read_at: msg.read_at ? msg.read_at : true, // no timestamp for now
                };
            });
        }
    },
};

function summarizeMessageCount(counts = {}) {
    if (!Object.values(counts)) {
        return 0;
    }
    return Object.values(counts).reduce((a, b) => a + b, 0);
}

function incrementUnreadCount(unreadCountPerChat, partnertId) {
    const unreads = { ...unreadCountPerChat };
    if (unreads.hasOwnProperty(partnertId)) {
        unreads[partnertId]++;
    } else {
        unreads[partnertId] = 1;
    }
    return unreads;
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
