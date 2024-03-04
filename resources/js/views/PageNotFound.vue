<template>
    <div>
        <div class="header d-flex justify-content-center align-items-center">
            <a href="/">
                <img
                    src="/img/th_logo_classic_blauweiss_rgb.png"
                    alt=""
                    class="header-logo"
                />
            </a>
        </div>
        <div
            class="d-flex flex-column justify-content-center align-items-center mt-5"
        >
            <p class="color--primary">Die Seite wurde leider nicht gefunden.</p>
            <a href="/" class="btn btn-primary mt-3 p-2">Zur Startseite</a>
        </div>
    </div>
</template>

<script>
export default {
    name: "PageNotFound",
};
</script>

<style lang="scss" scoped>
.header {
    width: 100%;
    padding: 0;
    background-image: url(/img/wolken.jpg);
    background-repeat: no-repeat;
    background-size: cover;
    height: 235px;

    img {
        max-height: 140px;
    }
}

p {
    font-weight: 300;
    font-size: 24px;
}
</style>
