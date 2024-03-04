export default {
    getAll() {
        return axios.post(`/api/env`).then(({ data }) => data);
    }
};