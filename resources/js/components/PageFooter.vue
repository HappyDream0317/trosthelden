<template>
    <footer>
        <div class="footer-wrapper container">
            <img class="tip" src="/img/zipfel--yellow20.svg" alt="zipfel" />
            <FooterBanner v-if="!isPremium" class="only--mobile"/>
            <nav>
                <ul>
                    <li>
                        <router-link
                            :to="{ name: 'faq' }"
                            class="nav-link"
                            exact
                            >Häufige Fragen</router-link
                        >
                    </li>
                    <li>
                        <a
                            href="https://www.trosthelden.de/ueber-uns"
                            target="_blank"
                            >Über TrostHelden</a
                        >
                    </li>
                </ul>
                <ul>
                    <li>
                        <router-link
                            :to="{ name: 'customerservice' }"
                            class="nav-link"
                            exact
                            >Kundenservice</router-link
                        >
                    </li>
                    <li>
                        <router-link
                            :to="{ name: 'imprint' }"
                            class="nav-link"
                            exact
                            >Impressum</router-link
                        >
                    </li>
                    <li v-if="isPremium">
                        <router-link
                            :to="{ name: 'settings', hash: '#premium' }"
                            class="nav-link"
                            exact
                            >Mitgliedschaft kündigen</router-link
                        >
                    </li>
                </ul>
                <ul>
                    <li>
                        <router-link
                            :to="{ name: 'conditions' }"
                            class="nav-link"
                            exact
                            >AGB</router-link
                        >
                    </li>
                    <li>
                        <router-link
                            :to="{ name: 'privacy' }"
                            class="nav-link"
                            exact
                            >Datenschutz</router-link
                        >
                    </li>
                    <li>
                        <router-link
                            :to="{ name: 'revocation' }"
                            class="nav-link"
                            exact
                            >Widerrufsbelehrung</router-link
                        >
                    </li>
                    <li>
                        <button
                            type="button"
                            style="
                                line-height: 0.75rem;
                                font-size: 0.75rem;
                                display: inline-block;
                                text-transform: uppercase;
                            "
                            class="btn btn-link nav-link text-white"
                            @click="openCookieSettings"
                        >
                            Cookie Einstellungen
                        </button>
                    </li>
                </ul>
            </nav>
            <div class="social" :class="!isPremium && 'only--desktop'">
                <div class="social-wrapper">
                    <img src="/img/crest.svg" alt="logo" />
                    <ul>
                        <li class="text-right">
                            <a
                                href="https://www.youtube.com/channel/UCDHxr8Mkxe4tIV_eWf4NCeg"
                                target="_blank"
                                ><fa-icon
                                    class="icon"
                                    :icon="['fab', 'youtube']"
                                ></fa-icon
                            ></a>
                        </li>
                        <li class="text-center">
                            <a
                                href="https://www.facebook.com/TrostHelden-108679684045740/"
                                target="_blank"
                                ><fa-icon
                                    class="icon"
                                    :icon="['fab', 'facebook-square']"
                                ></fa-icon
                            ></a>
                        </li>
                        <li class="text-left">
                            <a
                                href="https://www.instagram.com/trosthelden_official/"
                                target="_blank"
                                ><fa-icon
                                    class="icon"
                                    :icon="['fab', 'instagram']"
                                ></fa-icon
                            ></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
import FooterBanner from "./premium/FooterBanner";
import { mapGetters } from "vuex";

export default {
    name: "PageFooter",
    components: {
        FooterBanner
    },
    methods: {
        openCookieSettings() {
            this.$eventBus.emit("show-cookie-banner");
        },
    },
    computed: {
        ...mapGetters("currentUser", {
            isPremium: "isPremium",
        }),
    },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";
@import "~bootstrap/scss/bootstrap-grid";

footer {
    margin-top: $header-footer-spacing;
    width: 100%;
    background-color: $brand-color-primary;
    color: $brand-color-base;

    & > .footer-wrapper {
        position: relative;
        max-width: 46rem;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        padding: 4rem 0;
    }
}
a {
    color: $brand-color-base;
}
div.social {
    flex-basis: 10.5rem;
    order: 1;
    margin-right: 4rem;

    & img {
        margin: 0 auto;
        width: 100%;
        max-width: 7.5rem;
        display: block;
        margin-top: -6%;
    }
    & ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 2rem;
    }
}
footer nav {
    order: 2;
    flex: 1;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;

    & > ul {
        height: 100%;
        margin: 0;
        padding: 0;
        line-height: 0.75rem;
        font-size: 0.75rem;

        & > li {
            list-style: none;
            padding: 0.625rem 0.625rem;
            text-transform: uppercase;

            & > .nav-link {
                padding: 0;
                margin: 0;
            }
        }
    }
}
.tip {
  position: absolute;
  top:-1px;
  width: 60px;
  transform: translateX(3.5rem);
}
@include media-breakpoint-down("sm") {
    div.social {
        flex-basis: 100%;
        order: 2;
        margin-right: 0;
        padding: 0.75rem;

        & > .social-wrapper {
            max-width: 12rem;
            margin: auto;
        }
    }
    footer nav > ul {
        height: auto;
        padding: 0 0.75rem;
    }
  .tip {
    left: 50%;
    transform: translateX(-50%);
  }
}
</style>
