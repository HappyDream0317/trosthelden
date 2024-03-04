import store from "./store/index";

export const matomoOptions = (router) => {

    const getValueFromEnv = store.getters["env/getValueFromEnv"];

    const {
        value: matomoEnableTracking,
        error: matomoEnableTrackingError,
    } = getValueFromEnv("MATOMO_TRACKING_ENABLED");
    if (matomoEnableTrackingError) {
        console.error(
            "Could not retrieve MATOMO_TRACKING_ENABLED: " +
            matomoEnableTrackingError
        );
    }

    const { value: matomoSiteId, error: matomoSiteIdError } = getValueFromEnv(
        "MATOMO_TRACKING_SITE_ID"
    );
    if (matomoSiteIdError) {
        console.error(
            "Could not retrieve MATOMO_TRACKING_SITE_ID: " + matomoSiteIdError
        );
    }

    return ({
        host: matomoEnableTracking ? "https://stats.trosthelden.de/" : null,
        siteId: matomoSiteId,

        // Enables automatically registering pageviews on the router
        router: router,

        // Enables link tracking on regular links. Note that this won't
        // work for routing links (ie. internal Vue router links)
        enableLinkTracking: true,

        // Require consent before sending tracking information to matomo
        requireConsent: false,

        // Whether to track the initial page view
        trackInitialView: true,

        // Run Matomo without cookies
        disableCookies: false,

        // Enable the heartbeat timer (https://developer.matomo.org/guides/tracking-javascript-guide#accurately-measure-the-time-spent-on-each-page)
        enableHeartBeatTimer: false,

        // Set the heartbeat timer interval
        heartBeatTimerInterval: 15,

        // Changes the default .js and .php endpoint's filename
        trackerFileName: "matomo",

        // Overrides the tracker endpoint entirely
        trackerUrl: undefined,

        // Overrides the tracker script path entirely
        trackerScriptUrl: undefined,

        // Whether or not to log debug information
        debug: true,

        preInitActions: [
            ["setDocumentTitle", document.domain + "/" + document.title],
            ["setDoNotTrack", true], // respect 'do not track'
        ],
    });
}

export function trackMatomoDimensions() {
    let registeredDimensionId, domainDimensionId;
    const _paq = window._paq || [];
    const subdomain = window.location.host.split(".")[1]
        ? window.location.host.split(".")[0]
        : false;

    if (subdomain === "staging-app") {
        domainDimensionId = 1;
        registeredDimensionId = 3;
    } else if (subdomain === "app") {
        domainDimensionId = 2;
        registeredDimensionId = 1;
    }

    if (subdomain && domainDimensionId) {
        //console.log("dimension: " + domainDimensionId);
        _paq.push(["setCustomDimension", domainDimensionId, subdomain]);
    }
    if (
        subdomain &&
        registeredDimensionId &&
        store.getters["currentUser/isLoggedIn"]
    ) {
        //console.log("registered: " + registeredDimensionId);
        _paq.push(["setCustomDimension", registeredDimensionId, true]);
    }
}

export function trackMatomoEvent(type, name, value = null) {
    const _paq = window._paq || [];

    let event, category, action;
    switch (type) {
        case "payment":
            event = "trackEvent";
            category = "payment";
            action = "payment-status-changed";
            break;
        case "register":
            event = "trackEvent";
            category = "register-funnel";
            action = "click on submit button";
            break;
    }

    if (value !== null) _paq.push([event, category, action, name, value]);
    else _paq.push([event, category, action, name]);
}
