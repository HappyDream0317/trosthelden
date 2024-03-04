import store from "../store";
import {trackMatomoDimensions} from "../matomo";
import {createRouter, createWebHistory} from 'vue-router';
import {routes} from "./routes";
import {updateCanonicalURL} from "../utils/canonicalURL";
import {
    getVerifyRoute,
    scrollBehavior,
    isGuestPage,
    updateMetaTitle,
    canAccessByRole,
    getDashboardRoute
} from './helpers';

const routerConfig = {
    history: createWebHistory(),
    scrollBehavior: scrollBehavior,
};


const router = createRouter({...routerConfig, routes});

router.beforeEach(async (to, from, next) => {
    trackMatomoDimensions();


    if (
        isGuestPage(to) &&
        to.name !== "login" &&
        to.name !== "register" &&
        to.name !== "checkout"
    ) {
        if (to.fullPath === "/") {
            return next({name: "login"});
        }

        return next();
    }

    // Error 401
    if (store.getters["currentUser/isRequestForbidden"]) {

        if (!isGuestPage(to)) return next({name: "login"});
        else return next();
    }

    if (!store.getters["currentUser/isLoggedIn"]) {

        return await store
            .dispatch("currentUser/fetch")
            .then(() => {

                if (!store.getters["currentUser/isVerified"]) {
                    const verifyRoute = getVerifyRoute(to);
                    if (verifyRoute !== null) {
                        return next({
                            path: verifyRoute,
                        });
                    }
                }

                if (!canAccessByRole(to)) {
                    let isBusinessUser = store.getters["currentUser/isBusinessAccount"];

                    if (to.name === "dashboard" && isBusinessUser) return next({name: "b2b_dashboard"});
                    if (to.name === "b2b_dashboard" && !isBusinessUser) return next({name: "dashboard"});
                    return next({name: "login"});
                }

                if (to.name === "login" || to.name === "register") {
                    let dashboardRoute = getDashboardRoute();
                    return next({path: dashboardRoute,});
                }
                return next();
            })
            .catch((e) => {

                if (!isGuestPage(to)) return next({name: "login"});
                else return next();
            });
    }

    if (!store.getters["currentUser/isVerified"]) {

        const verifyRoute = getVerifyRoute(to);
        if (verifyRoute !== null) {
            return next({
                path: verifyRoute,
            });
        }
    }

    return next();
});

router.afterEach((to, from) => {
    updateMetaTitle(to);
    updateCanonicalURL();
})

export default router;
