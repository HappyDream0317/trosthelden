export const updateCanonicalURL = () => {
    let {origin, pathname} = window.location;
    let url = `${origin}${pathname}`;
    let canonicalLink = document.head.querySelector(`link[rel="canonical"]`);

    if (canonicalLink.href !== url) canonicalLink.href = url;
}