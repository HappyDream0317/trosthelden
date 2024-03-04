/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";
import store from "./store";

const getValueFromEnv = store.getters["env/getValueFromEnv"];

window.Pusher = require("pusher-js");

window.Echo = new Echo({
    broadcaster: "pusher",
    key: getValueFromEnv("PUSHER_APP_KEY").value,
    cluster: getValueFromEnv("PUSHER_APP_CLUSTER").value,
    encrypted: true,
});

