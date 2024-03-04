import store from "../../store";

const state = () => ({
    token: "",
    version: "",
    destination: "",
    isInEditor: false,
});

const actions = {
    init({ commit }) {
        const getValueFromEnv = store.getters["env/getValueFromEnv"];
        commit("setToken", getValueFromEnv("STORYBLOK_TOKEN"));
        commit("setVersion", getValueFromEnv("STORYBLOK_VERSION"));
        commit("setDestination", getValueFromEnv("STORYBLOK_DESTINATION"));
    },
    setVersion({ commit }, value) {
        commit("setVersion", value);
    },
    setIsInEditor({ commit }, value) {
        commit("setIsInEditor", value);
    },
};

const getters = {
    isInEditor: (state) => {
        return state.isInEditor;
    },
    token: (state) => {
        return state.token.value;
    },
    version: (state) => {
        return state.version.value;
    },
    destination: (state) => {
        return state.destination.value;
    },
};

const mutations = {
    setIsInEditor(state, isInEditor) {
        state.isInEditor = isInEditor;
    },
    setToken(state, token) {
        state.token = token;
    },
    setVersion(state, version) {
        state.version = version;
    },
    setDestination(state, destination) {
        state.destination = destination;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
