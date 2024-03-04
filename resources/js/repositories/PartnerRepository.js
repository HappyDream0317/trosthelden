export default {
    getAll() {
        return axios.get(`/api/chat/partners`).then(({ data }) => data);
    },
    getFriendRequestCount() {
        return axios.get("/api/user/friend/list/invitation/new") .then(({ data }) => data.total);
    }
};
