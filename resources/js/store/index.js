import { createStore } from 'vuex'
import currentUser from "./modules/user";
import storyblok from "./modules/storyblok";
import subscription from "./modules/subscription";
import partner from "./modules/partner";
import chat from "./modules/chat";
import matomo from "./modules/matomo";
import log from "./modules/log";
import env from "./modules/env";
import blockList from "./modules/blockList";
import b2bUser from "./modules/b2bUser";



export default createStore({
    modules: {
        chat,
        currentUser,
        log,
        matomo,
        partner,
        storyblok,
        subscription,
        env,
        blockList,
        b2bUser
    },
});
