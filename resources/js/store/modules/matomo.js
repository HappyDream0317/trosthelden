import Vue from '../../app';

const actions = {
    messageSent() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent("Chat", "message sent");
        }
    },
    partnerInvited() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent("Trostpartner", "TP inquiry sent");
        }
    },
    partnerAccepted() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent(
                "Trostpartner",
                "TP inquiry accepted"
            );
        }
    },
    partnerUnfriended() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent("Trostpartner", "TP unfriended");
        }
    },
    partnerRetracted() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent(
                "Trostpartner",
                "TP inquiry retracted"
            );
        }
    },
    partnerRefused() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent(
                "Trostpartner",
                "TP inquiry refused"
            );
        }
    },
    postPublished() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent("Gruppen", "post published");
        }
    },
    commentPublished() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent("Gruppen", "comment published");
        }
    },
    watchlistRemove() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent(
                "Trostpartner",
                "TP removed from watchlist"
            );
        }
    },
    watchlistAdd() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent(
                "Trostpartner",
                "TP added to watchlist"
            );
        }
    },
    blocklistRemove() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent(
                "Trostpartner",
                "TP removed from hidden list"
            );
        }
    },
    blocklistAdd() {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent(
                "Trostpartner",
                "TP added to hidden list"
            );
        }
    },
    guideNextStep({ commit }, name) {
        if (Vue.app.config.globalProperties.$matomo) {
            Vue.app.config.globalProperties.$matomo.trackEvent(
                "Fragebogen Progress",
                "Clicked next step button",
                name
            );
        }
    },
};

export default {
    namespaced: true,
    actions,
};
