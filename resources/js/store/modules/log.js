/** Hotfix Logging Approach ... */

const actions = {
    error(_, { name, data = {} }) {
        axios
            .post("api/log/error", {
                name: name,
                data: data,
            })
            .then(() => {})
            .catch(() => {});
    },
};

export default {
    namespaced: true,
    actions,
};
