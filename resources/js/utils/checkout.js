import { checkCookie, getCookie } from "./cookie";

export const description = (value) => {
    let indicator = value.indexOf(",");

    if (indicator !== -1) {
        for (let i = indicator; i < indicator + 4; i++) {
            let j = i - 1;
            if (value[i] == " " && !isNaN(value[j])) {
                let start = i;
                let newSubStr = "€\r\n";
                value =
                    value.slice(0, start) + newSubStr + value.slice(start + 1);
                return value;
            }
        }
    }
    return value;
};
export const price = (value) => {
    value = (Math.round(parseFloat(value) * 100) / 100).toFixed(2);
    value = value.toString().replace(".", ",");

    return `${value}€`;
};

export const trimData = (data) => {
    let results = {};
    Object.keys(data).map((key, index) => {
        let value = data[key];
        if (typeof value === "string") value = value.trim();
        if (key === "iban") value = value.replace(/\s/g, "");
        results[key] = value;
    });
    return results;
};

export async function getPartnerCode(userId = null, couponCode = null) {
    let resultCookie = await getPartnerCodeFormCookie();
    if (resultCookie) return resultCookie;

    let resultUrl = await getPartnerCodeFormURL();
    if (resultUrl) return resultUrl;

    let resultUser = await getPartnerCodeFromUser(userId);
    if (resultUser) return resultUser;

    let resultCoupon = await getPartnerCodeFormCoupon(couponCode);
    if (resultCoupon) return resultCoupon;

    return null;
}

export async function getPartnerCodeFormCookie() {
    if (checkCookie("partner")) {
        return getCookie("partner");
    }
    return null;
}

export async function getPartnerCodeFormURL() {
    if (window.location.search !== "") {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.get("partner");
    }
    return null;
}

export async function getPartnerCodeFromUser(id) {
    if (id && !id.startsWith("coupon-")) {
        let { b2b_partner_id } = await axios.get(`/api/user/${id}/partner-code`);

        if (b2b_partner_id) {
            let { data } = await axios.get(
                `/api/b2b/partner/${b2b_partner_id}/code`
            );
            return data.code;
        }
    }
    return null;
}

export async function getPartnerCodeFormCoupon(coupon) {
    if (coupon) {
        let { data } = await axios.get(`/api/b2b/discount/${coupon}`);
        return data.b2b_partner.code;
    }
    return null;
}

export function setLocalStore(name, value) {
    if(value !== null && value !== undefined) window.localStorage.setItem(name, value);
}

export function clearLocalStore(name) {
    window.localStorage.removeItem(name)
}

export function getLocalStore(name) {
    return window.localStorage.getItem(name) === 'null' ? null : window.localStorage.getItem(name);
}
