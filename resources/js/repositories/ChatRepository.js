export default {
    updateMessagesAsReadInDB(chatId, partnerId) {
        return axios
            .post(`api/chat/messages/${chatId}/${partnerId}/read`)
            .then(({ data }) => data)
            .catch((err) => console.error(err));
    },
    fetchUnreadByChat() {
        return axios
            .get("/api/chat/unreadByChat")
            .then(({ data }) => data)
            .catch((err) => console.error(err));
    },
    // getAll() {
    // },
    // get(id) {
    // },
    // create(payload) {
    // },
    // update(payload, id) {
    // },
    // delete(id) {
    // },
};
