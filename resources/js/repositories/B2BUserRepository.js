export default {
    getUser(userId) {
        return axios.get(`/api/b2b/${userId}/user`).then(({ data }) => data);
    },
    getPartner(userId, id) {
        return axios.get(`/api/b2b/${userId}/partner/${id}`).then(({ data }) => data);
    },
    getFlatrateCoupon(user_id) {
        return axios.post(`/api/b2b/coupon/has-flatrate`, { user_id }).then(({ data }) => data);
    },
};