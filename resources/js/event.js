import Vue from './app';

class Event {
    static toggleActionIcon(uid) {
        Vue.app.config.globalProperties.$eventBus.emit("toggleActionIcon", uid);
    }
}

export default Event;
