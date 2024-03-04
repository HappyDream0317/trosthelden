import Vue from '../../app';

const state = () => ({
    isVerified: false,
    isFirstView: false,
    isFirstViewCountdownRunning: false,
    requestForbidden: false,
    currentUser: null,
    hasCompletedFrabo: null,
    wasOptedOut: null,
    canParticipate: null,
    hasSeenElements: [],
    permissions: [],
});

const getters = {
    isFirstView: (state) => {
        return state.isFirstView;
    },

    /**
     *
     * @param state
     * @return boolean
     */
    isRequestForbidden: (state) => {
        return state.requestForbidden;
    },

    /**
     * Is the current user fully logged in? Is the user loaded into memory?
     * @param state
     * @return {boolean}
     */
    isLoggedIn: (state) => {
        return !!state.currentUser;
    },

    isVerified: (state) => {
        return state.isVerified;
    },

    /**
     * Get premium status.
     */
    isPremium: ({ currentUser }) => {
        return currentUser?.is_premium || currentUser?.force_premium;
    },

    /**
     * Get the id of the current user.
     * @param state
     * @return {*|null}
     */
    getId: (state) => {
        return state.currentUser && state.currentUser.hasOwnProperty("id")
            ? parseInt(state.currentUser.id)
            : null;
    },
    /**
     * Get the current users object.
     * @param state
     * @return {null}
     */
    getObject: (state) => {
        return state.currentUser;
    },
    /**
     * Get the nickname of the current user.
     * @param state
     * @return {string|(function(): *)|{valid: boolean, name: string, rules: [string], value: null, errors: []}|default.methods.form.nickname.value|null}
     */
    getNickname: (state) => {
        return state.currentUser && state.currentUser.hasOwnProperty("nickname")
            ? state.currentUser.nickname
            : null;
    },

    hasCompletedFrabo: (state) => {
        return state.hasCompletedFrabo;
    },

    wasOptedOut: (state) => {
        return state.wasOptedOut;
    },

    canParticipate: (state) => {
        return state.canParticipate;
    },

    hasSeenElement: (state) => (elementName) => {
        return state.hasSeenElements.includes(elementName);
    },

    /**
     * Get the b2b_partner_id of the current user.
     * @param state
     * @return {*|null}
     */
    getPartnerId: (state) => {
        return state.currentUser && state.currentUser.hasOwnProperty("b2b_partner_id")
            ? parseInt(state.currentUser.b2b_partner_id)
            : null;
    },
    /**
     * Check if the current user has a business account
     * @param state
     * @return boolean
     */
    isBusinessAccount: (state) => {
        return state.currentUser && state.currentUser.hasOwnProperty("is_business_account")
            ? state.currentUser.is_business_account
            : false;
    },
    isAdmin: (state) => {
        return !!state.currentUser?.is_super_admin;
    },
    isAllowedTo: (state) => (permission) => {
        if(state.currentUser?.is_super_admin) return true;
        return state.permissions.includes(permission);
    },
    isAllowedAny: (state) => (list) => {
        if(state.currentUser?.is_super_admin) return true;
        return [...list].some(p => state.permissions.includes(p));
    },
    isAllowedAll: (state) => (list) => {
        if(state.currentUser?.is_super_admin) return true;
        return !list.some(p => !state.permissions.includes(p));
    },
};

const actions = {
    /**
     * First View
     */
    firstViewCounterInit({ commit }) {
        if (!this.state.currentUser.isFirstViewCountdownRunning) {
            commit("setFirstView", true);
            commit("setFirstViewCountdown", true);
            let countdown = 30;
            const interval = setInterval(async () => {
                countdown -= 1;
                if (countdown <= 0) {
                    commit("setFirstView", false);
                    commit("setFirstViewCountdown", false);
                    localStorage.removeItem("firstView");
                    clearInterval(interval);
                }
            }, 1000);
        }
    },

    /**
     * Update the memory with the current user.
     * @param commit
     * @return {Promise<unknown>}
     */
    setLastSeen({ commit }) {
        return new Promise((resolve, reject) => {
            axios.post("/api/profile/last_seen").catch((err) => {
                reject(err);
                Vue.app.config.globalProperties.$eventBus.emit("logout");
            });
        });
    },

    init({ commit }, data) {
        return new Promise((resolve) => {
            commit("setRequestForbidden", false);
            commit("setUser", data.user);
            commit("setVerifiedStatus", data.user.is_verified);
            commit("setFraboStatus", data.user.matching_step);
            commit("setPermissions", data.user.permissionsViaRoles);
            commit("setCanParticipate", data.user);
            commit("setHasSeenElements", data.user.has_seen);
            Vue.app.config.globalProperties.$eventBus.emit("profile-init", {
                userId: data.user.id,
            });
            resolve();
        });
    },

    /**
     * Login a user.
     * @param commit
     * @param dispatch
     * @param payload
     * @return {Promise<unknown>}
     */
    login(
        { commit, dispatch },
        payload = { email: null, password: null, force: false }
    ) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/auth/access-tokens", {
                    username: payload.email,
                    password: payload.password,
                    force: payload.force || false,
                })
                .then(async ({ data }) => {
                    await dispatch("init", data);
                    resolve(data);
                })
                .catch((err) => {
                    reject(err);
                });
        });
    },

    /**
     * Login remote a user.
     * @param commit
     * @param dispatch
     * @param payload
     * @return {Promise<unknown>}
     */
    loginRemote(
        { commit, dispatch },
        payload = { id: null, token: null }
    ) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/auth/access-tokens/remote", {
                    id: payload.id || null,
                    token: payload.token || null,
                })
                .then(async ({ data }) => {
                    await dispatch("init", data);
                    resolve();
                })
                .catch((err) => {
                    reject(err);
                });
        });
    },

    /**
     * Update the memory with the current user.
     * @param commit
     * @param dispatch
     * @return {Promise<unknown>}
     */
    fetch({ commit, dispatch }) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/profile/init")
                .then(async ({ data }) => {
                    if (data && data.user) {
                        await dispatch("init", data);
                        resolve();
                    } else {
                        reject();
                    }
                })
                .catch((err) => {
                    reject(err);
                });
        });
    },

    /**
     * Request a password forgotten email
     * @param commit
     * @param email
     * @return {Promise<unknown>}
     */
    forgotPassword({ commit }, email) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/forgot-password", {
                    email: email,
                })
                .then(({ data }) => {
                    resolve(data);
                })
                .catch((err) => {
                    console.error(err);
                    reject(err);
                });
        });
    },

    verifyUser(
        { commit, dispatch, getters },
        payload = { user_id: null, hash: null }
    ) {
        return new Promise((resolve, reject) => {
            axios
                .post(`/api/verify/${payload.user_id}/${payload.hash}`, {
                    params: new URLSearchParams(window.location.search),
                    headers: {
                        Accept: "application/json",
                    },
                })
                .then((response) => {
                    if (response.status !== 204) {
                        reject(response);
                    }
                    // if the user is logged in, then refresh the local userdata
                    if (getters.isLoggedIn) {
                        dispatch("fetch").then(() => {
                            resolve();
                        });
                    }

                    // the user isn't logged in. Just resolve
                    resolve();
                })
                .catch((err) => {
                    console.error(err);
                    reject(err);
                });
        });
    },
    /**
     * Load the localstorage into the state
     * @param commit
     * @param payload
     */
    loadRequestForbidden({ commit }, payload = false) {
        commit("setRequestForbidden", payload);
    },

    /**
     * Logout the current user.
     * @param commit
     */
    logout({ dispatch }) {
        return new Promise((resolve) => {
            axios
                .delete("/api/auth/access-tokens/revoke")
                .then(({ data }) => {
                    resolve(data);
                })
                .finally(() => {
                    dispatch("shutdown");
                });
        });
    },

    shutdown({ commit }) {
        commit("resetUserState");
        Vue.app.config.globalProperties.$eventBus.emit("logout");
    },

    addLastSeenElement({ commit }, payload) {
        commit("addLastSeenElement", payload);
    },
};

const mutations = {
    resetUserState(state) {
        state.isVerified = false;
        state.isFirstView = false;
        state.isFirstViewCountdownRunning = false;
        state.requestForbidden = false;
        state.currentUser = null;
        state.hasCompletedFrabo = null;
        state.wasOptedOut = null;
        state.canParticipate = null;
        state.hasSeenElements = [];
        state.permissions = [];
    },
    setUser(state, user) {
        state.currentUser = { ...user };
    },
    setVerifiedStatus(state, isVerified) {
        state.isVerified = isVerified;
    },
    setRequestForbidden(state, value) {
        state.requestForbidden = value;
    },
    setFirstView(state, value) {
        state.isFirstView = value;
    },
    setFirstViewCountdown(state, value) {
        state.isFirstViewCountdownRunning = value;
    },
    setFraboStatus(state, value) {
        const intVal = parseInt(value);
        state.hasCompletedFrabo = intVal === -1;
        state.wasOptedOut = intVal === -2;
    },
    setCanParticipate(state, value) {
        state.canParticipate = (state.currentUser?.is_super_admin) ||
            (state.permissions.includes('view partner') && parseInt(value.matching_step) === -1);
    },
    setHasSeenElements(state, value) {
        state.hasSeenElements = value || [];
    },
    addLastSeenElement(state, value) {
        if (!state.hasSeenElements.includes(value))
            state.hasSeenElements.push(value);
    },
    setPermissions(state, value) {
        state.permissions = value || [];
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
