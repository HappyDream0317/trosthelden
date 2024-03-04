<template>
    <div
        v-if="hasCompletedFrabo && !hasSeenElement(elementName)"
        class="dashboard-intro-widget rounded-box mb-2 p-2"
    >
        <h3>Willkommen auf deiner Startseite</h3>
        <fa-icon class="fa-times-circle" icon="times-circle" @click="onHide" />
        <youtube-player
            class="rounded-video m-0"
            src="https://www.youtube.com/embed/1jIfmDq1_fo"
        >
        </youtube-player>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import YoutubePlayer from "../YoutubePlayer";

export default {
    name: "DashboardIntroWidget",
    components: {
        YoutubePlayer,
    },
    data() {
        return {
            elementName: "dashboard-intro-04-16-21",
        };
    },
    computed: {
        ...mapGetters("currentUser", {
            hasCompletedFrabo: "hasCompletedFrabo",
            hasSeenElement: "hasSeenElement",
        }),
    },
    methods: {
        onHide() {
            axios
                .post("/api/user/has-seen", {
                    element: this.elementName,
                })
                .then(() => {
                    this.$store.dispatch(
                        "currentUser/addLastSeenElement",
                        this.elementName
                    );
                })
                .catch((err) => {
                    console.error(err);
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.dashboard-intro-widget {
    position: relative;
    h3 {
        font-size: 1rem;
    }
    .fa-times-circle {
        position: absolute;
        right: 0.75rem;
        top: 0.6rem;
        cursor: pointer;
    }
}
</style>
