export default {
    getAll() {
        return axios.get(`/api/user/blocklist`).then(({ data }) => data);
    },
    add(userId) {
        return axios.post("/api/user/blocklist/block/" + userId);
    },
    remove(userId) {
        return axios.put("/api/user/blocklist/remove/" + userId);
    },
};
