import Repository from "../../repositories/RepositoryFactory";

const B2BUserRepository = Repository.get("b2bUser");

const state = () => ({
    user: null,
    partner: null,
    isPartner: false,
    hasFlatrate: null,
    flatrateCoupon: null,
});

const actions = {
    async init({ commit, dispatch }, {userId}) {
        const user = await B2BUserRepository.getUser(userId);
        const isPartner = user ? !!user.b2b_partner : false;

        commit("setUser", {user});
        commit("setIsPartner", {isPartner});

        dispatch("flatrateCoupon", { userId});

        if(isPartner)
            dispatch("partner", { userId, id: user.b2b_partner.id });

    },
    async partner({ commit, dispatch }, {userId, id}) {
        const partner = await B2BUserRepository.getPartner(userId, id);
        commit("setPartner", {partner});
    },
    async flatrateCoupon({ commit, dispatch }, {userId}) {
        const {id} = await B2BUserRepository.getFlatrateCoupon(userId);
        const hasFlatrate = !!id;

        commit("setHasFlatrate", {hasFlatrate});

        if(hasFlatrate)
            commit("setFlatrateCoupon", {flatrateCoupon: id});
    }
};

const getters = {
    getUser: (state) => {
        return state.user;
    },
    getIsPartner: (state) => {
        return state.isPartner;
    },
    getPartner: (state) => {
        return state.partner;
    },
    getRedirects(state) {
        return state?.partner?.b2b_redirects || [];
    },
    getHasFlatrate: (state) => {
        return state.hasFlatrate;
    },
    getFlatrateCoupon: (state) => {
        return state.flatrateCoupon;
    }
};

const mutations = {
    setUser(state, payload) {
        state.user = payload.user;
    },
    setIsPartner(state, payload) {
        state.isPartner = payload.isPartner;
    },
    setPartner(state, payload) {
        state.partner = payload.partner;
    },
    setHasFlatrate(state, payload) {
        state.hasFlatrate = payload.hasFlatrate;
    },
    setFlatrateCoupon: (state, payload) => {
        state.flatrateCoupon = payload.flatrateCoupon;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
