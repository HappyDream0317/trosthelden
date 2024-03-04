import {createApp} from 'vue'
import App from "./views/App";

import eventBus from "./event-bus";
import store from "./store/index";
import router from "./router";
import VueMatomo from "vue-matomo";
import {StoryblokVue} from '@storyblok/vue';
import Embed from "v-video-embed";
import {matomoOptions} from "./matomo";

import VCalendar from "v-calendar";
import 'v-calendar/style.css';

import * as rules from "@vee-validate/rules";
import {localize} from '@vee-validate/i18n';
import {
    configure,
    defineRule,
    Field,
    Form,
    ErrorMessage
} from "vee-validate";

import {icons} from "./icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";


require("./bootstrap");


//import {envInit} from "./env";



/**
 * Font Awesome
 */
library.add(...icons);
/**
 * End Font Awesome
 */

/**
 * Validation
 */
Object.keys(rules)
    .filter(rule => rule !== 'default')
    .forEach(rule => defineRule(rule, rules[rule]));

defineRule("equal_passwords", (value, {target}) => {
    if (value === target)
        return "Die Passwörter müssen identisch sein!";
    else
        return true;
});

localize("de");

configure({
    classes: {
        valid: "vee-valid",
        invalid: "vee-invalid",
    },
    generateMessage: localize({
        de: {
            messages: {
                required: "Diese Angabe ist notwendig.",
                min: (_, {length}) =>
                    `Dieses Feld muss mindestens ${length} Zeichen lang sein`,
                max: (_, {length}) =>
                    `Dieses Feld darf maximal ${length} Zeichen lang sein`,
                max_value: (_, {value}) => `Der maximale Wert beträgt 120`, // FIXME: hardcoded, no time
                email: "Bitte gib eine gültige E-Mail Adresse an.",
            },
        },
        en: {
            messages: {
                required: "This field is required",
                min: "this field must have no less than {length} characters",
                max: (_, {length}) =>
                    `this field must have no more than ${length} characters`,
                max_value: (_, {value}) =>
                    `this field must have a value less than ${value}`,
                email: "this field must ahve a valid email address.",
            },
        },
    })
});

/**
 * End Validation
 */




const app = createApp(App);

store.dispatch("env/init").then(() => {
    require("./pusher-channels");

    const files = require.context("./", true, /\.vue$/i);
    files.keys()
        .map((key) =>
            app.component(key.split("/").pop().split(".")[0], files(key).default)
        );

    window.addEventListener('CookiebotOnConsentReady',  () => {
        if(window?.Cookiebot?.consent?.statistics) {
            app.use(VueMatomo, matomoOptions(router));
        }
    });

    app.component("fa-icon", FontAwesomeIcon);
    app.component("ValidationProvider", Field);
    app.component("ValidationObserver", Form);
    app.component("ValidationError", ErrorMessage);
    app.use(VCalendar);
    app.use(StoryblokVue);
    app.use(Embed);
    app.use(eventBus);
    app.use(store);
    app.use(router);
    app.mount('#app');
})

export default { app };

