import {setCookie, getCookie, checkCookie, deleteCookie} from "./utils/cookie";

export const COOKIE_NAME = "CookieReferrer";

export const TRACEABLE_PARAMS = [
    "referrer",
    "referring_domain",
    "utm_content",
    "utm_campaign",
    "utm_medium",
    "utm_source",
    "utm_keyword"
];

export const checkTraceableParams = function () {
    let queryString = window.location.search;
    if (queryString !== "") {
        let urlParams = new URLSearchParams(queryString);
        for (const key of urlParams.keys()) {
            if (TRACEABLE_PARAMS.includes(key)) return true;
        }
    }
    return false;
};

export const getParams = function () {
    let queryString = window.location.search;
    if (queryString !== "") {
        let response = {};
        let urlParams = new URLSearchParams(queryString);
        TRACEABLE_PARAMS.map((value, index) => {
            if(urlParams.has(value)) response[value] = urlParams.get(value);
        });
        return response;
    }
    return null;
};

export const updateReferrerCookie = function () {
    let params = getParams();
    if(params) setCookie(COOKIE_NAME, JSON.stringify(params), 1);
};

export const checkReferrerCookie = function () {
    return checkCookie(COOKIE_NAME);
};

export const checkReferrerHeader = async function () {
    return axios.get("/api/referrer/check").then(
        ({data}) => {
            return data?.exist ?? false;
        }).catch((error) => {
            console.error(error);
            return false;
    });
};

export const getReferrerCookie = function () {
    return getCookie(COOKIE_NAME);
};

export const checkReferrer = async function () {
    let hasCookie = checkReferrerCookie();
    if(hasCookie) return hasCookie;

    let hasHeader = await checkReferrerHeader();
    if(hasHeader) return hasHeader;

    return false;
};

export const deleteReferrerCookie= () => {
    deleteCookie(COOKIE_NAME);
};