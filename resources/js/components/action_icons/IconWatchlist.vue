<template>
    <button
        class="btn btn-outline-primary w-100 mt-1 mt-lg-0 mb-1 font-weight-not-bold p-2"
        @click="trigger"
    >
        <fa-icon class="icon" :icon="watchIcon"></fa-icon><slot></slot>
    </button>
</template>

<script>
import ActionIcon from "./ActionToggleIcon";
import AddToWatchList from "../groups/AddToWatchList";
import RemoveFromWatchList from "../groups/RemoveFromWatchList";
export default {
    name: "IconWatchlist",
    components: { ActionIcon },
    props: {
        userId: Number,
        userName: String,
        isWatched: {
            type: Boolean,
            default: false,
        },
    },

    data: function () {
        return {
            watchStatus: this.isWatched,
        };
    },
    mounted() {
        this.$eventBus.on("watchlist-added", (event) => {
            if (event.userId === this.userId) {
                this.watchStatus = true;
            }
        });
        this.$eventBus.on("watchlist-removed", (event) => {
            if (event.userId === this.userId) {
                this.watchStatus = false;
            }
        });
    },
    computed: {
        watchIcon() {
            if (this.watchStatus === true) {
                return "star";
            }
            return ["far", "star"];
        },
    },
    methods: {
        trigger() {
            let props = {
                userId: this.userId,
                userName: this.userName,
            };
            if (this.watchStatus === false) {
                this.$eventBus.emit("modal-requested", {
                    component: AddToWatchList,
                    props,
                });
                return;
            }
            this.$eventBus.emit("modal-requested", {
                component: RemoveFromWatchList,
                props,
            });
        },
    },
    beforeDestroy() {
        this.$eventBus.off("watchlist-added");
        this.$eventBus.off("watchlist-removed");
    },
};
</script>

<style scoped>
.btn {
    border: 2px solid transparent;
}
</style>
