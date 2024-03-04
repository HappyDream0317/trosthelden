export const getCookie = function (name) {
    if(!window.Cookiebot|| !name) return;
    
    return window.Cookiebot.getCookie(name);
};

export const deleteCookie = function (name) {
    if(!window.Cookiebot || !name) return;
    
    window.Cookiebot.removeCookieHTTP(name); 
};

export const setCookie = function (name, value, path = '/', domain = null, secure= true) {
    if(!window.Cookiebot || !name || !value) return;
    
    const expiredate = (new window.CookieControl.DateTime).addMonths(1)
    const isSecure = "https:" === window.location.protocol && secure;
    const cookieDef = name + "=" + value + (expiredate ? ";expires=" + expiredate.toGMTString() : "") + (path ? ";path=" + path : "") + (domain ? ";domain=" + domain : "") + (isSecure ? ";secure" : ""); 
    document.cookie = cookieDef;
}

export const checkCookie = function (name) {
    if(!window.Cookiebot || !name) return;
    
    let value =  getCookie(name);
    return (value !== "" && value !== null && value !== false && value !== undefined);
}
