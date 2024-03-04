export const getValueFromMeta = (metaHeaderName) => {
    const metaTag = document.head.querySelector(
        'meta[name="' + metaHeaderName + '"]'
    );

    if (!metaTag) {
        return { value: null, error: "meta tag not found" };
    }

    if (!metaTag.hasAttribute("content")) {
        return { value: null, error: "content attribute missing" };
    }

    let value = metaTag.getAttribute("content");

    if (metaTag.hasAttribute("type")) {
        switch (metaTag.getAttribute("type")) {
            case "string":
                value = String(metaTag.content);
                break;
            case "boolean":
                value = Boolean(metaTag.content);
                break;
            default:
                value = String(metaTag.content);
                break;
        }
    }

    return { value, error: null };
};

export const setMetaTitle = (value) => {
    const metaTitle = document.head.querySelector('title');

    if (metaTitle && metaTitle.textContent !== value) {
        metaTitle.textContent = value;
    }
}
