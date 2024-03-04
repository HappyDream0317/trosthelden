import store from "../store";

/**
 * Add console logs that only show if you are in local environment or if you add &dbg=on as queryparameter or set
 * window.debug = true in the console
 *
 * @param args
 */
export function debugLog(...args) {

    if(
        isDebug() ||
        window.location.href.includes('docker') ||
        window.location.href.includes('localhost')
    ) {
        console.log(args)
    }
}

export function isDebug() {
    return window.location.href.includes('dbg=on') ||
        window.debug === true ||
        localStorage.getItem('dbg') === 'true'
}