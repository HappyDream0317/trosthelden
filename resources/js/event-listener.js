import Vue from './app';

class EventListener {
    // Simple event listener
    static toggleActionIcon(fct) {
        Vue.app.config.globalProperties.$eventBus.on("toggleActionIcon", fct);
    }
}

export default EventListener;
