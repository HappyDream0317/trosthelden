import Repository from "../../repositories/RepositoryFactory";

const EnvRepository = Repository.get("env");

const state = () => ({
    vars: null
});

const actions = {
    async init({ commit }) {
        const vars = await EnvRepository.getAll();
        commit("setVars", vars);
    },
};

const getters = {
    getValueFromEnv: (state)  => (key) => {
        return state.vars && state.vars.hasOwnProperty(key)
            ? {value: state.vars[key], error: null}
            : {value: null, error: "value missing"};
    },
    isInit: (state) => {
        return state.vars;
    },
};

const mutations = {
    setVars(state, vars) {
        let data = {};
        Object.keys(vars).map((key, index) => {
            let item = vars[key];
            let value = null;
            if (item?.type) {
                switch (item?.type) {
                    case "string":
                        value = String(item?.content);
                        break;
                    case "boolean":
                        value = Boolean(item?.content);
                        break;
                    default:
                        value = String(item?.content);
                        break;
                }
            }
            data[key] = value;
        });
        state.vars = { ...data };
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
