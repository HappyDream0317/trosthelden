import store from "../store";
import {setMetaTitle} from "../utils/metaTagHelper";

export function getVerifyRoute(to) {
    if (
        store.getters["currentUser/isLoggedIn"] &&
        !store.getters["currentUser/isVerified"] &&
        !isForNotVerified(to) &&
        !isVerifyPage(to)
    ) {
        return "/pleaseverify";
    }

    if (store.getters["currentUser/isVerified"] && isVerifyPage(to)) {
        console.log("pushed");
        return "/login";
    }

    return null;
}

// utils
export function scrollBehavior(to, from, savedPosition) {
    if (from.name === "foreignProfile" && to.name === "matchings") {
        localStorage.setItem(
            "matchingsScrollPosition",
            JSON.stringify(savedPosition)
        );
    }
    if (from.name === "foreignProfile" && to.name !== "matchings") {
        localStorage.removeItem("viewingProfile");
        localStorage.removeItem("currentPage");
    }
    if (to.hash) {
        return window.scrollTo({
            top: document.querySelector(to.hash).offsetTop,
            behavior: 'smooth'
        })
    }

    if (savedPosition) {
        return savedPosition;
    } else {
        return { x: 0, y: 0 };
    }
}

export function isForNotVerified(to) {
    return to.matched.some((record) => record.meta.forNotVerified === true);
}

export function isGuestPage(to) {
    return to.matched.some((record) => record.meta.guest);
}

export function isVerifyPage(to) {
    return to.name === "pleaseverify";
}

export function isForBusinessAccount(to){
    return to.matched.some((record) => record.meta.forBusinessAccount);
}

export function updateMetaTitle(to) {
    let app = "Trosthelden";
    let title = [app, to.meta?.title ?? null].join(" ");
    setMetaTitle(title);
}

export function canAccessByRole(to) {
    const isAllowedTo = store.getters["currentUser/isAllowedTo"];

    let isBusinessPage = isForBusinessAccount(to);

    if(isBusinessPage && isAllowedTo('view b2b_dashboard')) {
        return  true;
    }

    if(!isBusinessPage && isAllowedTo('view standard_dashboard')) {
        return  true;
    }


    return false;
}

export function getDashboardRoute() {
    return store.getters["currentUser/isBusinessAccount"] ? '/b2b/dashboard' : '/dashboard';
}
