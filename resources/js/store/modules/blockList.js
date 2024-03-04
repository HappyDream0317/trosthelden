import Repository from "../../repositories/RepositoryFactory";

const BlockListRepository = Repository.get("blockList");

const state = () => ({
    blockList: [],
});

const actions = {
    async init({ commit }) {
        let list = await BlockListRepository.getAll();
        commit("setBlockList", list);
    },
    add({ state, commit, dispatch }, userId) {
        return new Promise((resolve, reject) => {
            BlockListRepository.add(userId)
                .then(({ data }) => {
                    if (data.status) {
                        commit("setBlockList", [...state.blockList, data.user]);
                        resolve();
                    }
                    reject();
                })
                .catch((err) => {
                    reject(err);
                });
        });
    },
    remove({ state, commit, dispatch }, userId) {
        return new Promise((resolve, reject) => {
            BlockListRepository.remove(userId)
                .then(() => {
                    const list = state.blockList.filter((u) => u.id !== userId);
                    commit("setBlockList", list);
                    resolve();
                })
                .catch((err) => {
                    reject(err);
                });
        });
    },
};

const getters = {
    blockList: (state) => {
        return state.blockList;
    },
    isBlockedUser: (state) => (id) => {
        return state.blockList.find((u) => u.user.id === id);
    },
};

const mutations = {
    setBlockList(state, list) {
        state.blockList = list;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
